<?php

namespace App\Services\Snapshot\Abstraction;

use App\Models\Snapshot;
use App\Services\Snapshot\Caretaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class TrackPad
{
    /**
     * Bridge
     *
     * @param Caretaker $caretaker
     */
    public function __construct(
        private Caretaker $caretaker
    ){}

    /**
     * Undo changes operations
     *
     * @return bool
     * @throws \Exception
     */
    public function undo(): bool
    {
        $machine = $this->caretaker->getMachine();

        try {
            if(!$machine->hasCurrentSnapshot()){

                $shouldSetSnapshot = $machine->snapshots()
                    ->latest()->firstOrFail();

                $this->store(); // take new snapshot before undo changes
            } else {
                $current = $machine->snapshots()
                    ->where('is_current', 1)->latest()->firstOrFail();

                $shouldSetSnapshot = $machine->snapshots()
                    ->where('created_at', '<', $current->created_at)
                    ->latest()->firstOrFail();
            }

            $shouldSetSnapshot->update([
                'is_current' => 1
            ]);

            return $machine->restore($shouldSetSnapshot->memento);

        } catch (Exception $e){
            Log::error("Undo operation failed for {$machine->id} machine id : " . $e->getMessage());

            return false;
        }
    }

    /**
     * Redo changes operations
     *
     * @return bool
     * @throws \Exception
     */
    public function redo(): bool
    {
        $machine = $this->caretaker->getMachine();

        if(!$current = $machine->currentSnapshot()){
            Log::error('Redo method called when entry machine has not any current snapshot !');

            return false;
        }

        $snapshot = $machine->snapshots()
            ->where('created_at', '>', $current->created_at)
            ->firstOrFail();

        DB::beginTransaction();

        $snapshot->update([
            'is_current' => '1'
        ]);

        $current->update([
            'is_current' => 0,
        ]);

        $res = $machine->restore($snapshot->memento);

        DB::commit();

        return $res;
    }

    /**
     * Removes all snapshots that have greater id compare current snapshot
     *
     * @return bool
     */
    public function forgetHistoryAfterCurrent(): bool
    {
        $machine = $this->caretaker->getMachine();

         $current = $machine->currentSnapshot();

         if(!$current)
             return false;


         return (bool)$machine->snapshots()
             ->where('created_at', '>', $current->created_at)
             ->where('id', '>', $current->id)
             ->delete();
    }

    /**
     * Deletes all machine snapshots from database
     *
     * @return bool
     */
    public function forgetHistory():bool
    {
        return $this->caretaker->getMachine()->snapshots()->delete();
    }

    /**
     * Sets is_current field value 0 if there is current snapshot for machine
     *
     * @return void
     */
    public function unmarkCurrentSnapshot():void
    {
        $machine = $this->caretaker->getMachine();

        $machine->snapshots()
            ->where('is_current', '!=', '0')
            ->update(['is_current' => '0']);
    }

    /**
     * Makes and save new snapshot of machine
     *
     * @return Snapshot
     * @throws \Exception
     */
    public function store():Snapshot
    {
        $this->forgetHistoryAfterCurrent();

        $this->unmarkCurrentSnapshot();

        $machine = $this->caretaker->getMachine();

        $mementoExport = $machine->store();

        return $machine->snapshots()->create([
            'memento' => $mementoExport,
        ]);
    }
}
