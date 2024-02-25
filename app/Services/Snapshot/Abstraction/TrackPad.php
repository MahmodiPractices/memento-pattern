<?php

namespace App\Services\Snapshot\Abstraction;

use App\Models\Snapshot;
use App\Services\Snapshot\Caretaker;
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

                $mementoExport = $machine->snapshots()
                    ->latest()->first()->memento;
            } else {
                $current = $machine->snapshots()
                    ->where('is_current', 1)->latest()->first();

                $mementoExport = $machine->snapshots()
                    ->where('created_at', '<', $current->created_at)
                    ->latest()->firstOrFail()->memento;
            }

            return $machine->restore($mementoExport);

        } catch (Exception $e){
            Log::error("Undo operation failed for {$machine->id} machine id : " . $e->getMessage());

            return false;
        }
    }

    /**
     * Redo changes operations
     *
     * @return bool
     */
    public function redo(): bool
    {

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
}
