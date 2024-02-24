<?php

namespace App\Services\Snapshot\Abstraction;

use App\Models\Snapshot;
use App\Services\Snapshot\Caretaker;

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
     */
    public function undo(): bool
    {

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
