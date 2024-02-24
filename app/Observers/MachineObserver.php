<?php

namespace App\Observers;

use App\Models\Machine;
use App\Services\Machine\MachineService;
use App\Services\Snapshot\Caretaker;

class MachineObserver
{
    public function __construct(
        private MachineService $machineService,
        private Caretaker      $snapshotService,
    ){}

    /**
     * Handle the Machine "created" event.
     */
    public function created(Machine $machine): void
    {
        //
    }

    /**
     * Handle the Machine "updated" event.
     */
    public function updated(Machine $machine): void
    {
        if(empty($machine->snapshots))
            return;

        if($machine->hasCurrentSnapshot()){

            $this->snapshotService->tracker($machine)->forgetHistoryAfterCurrent();

            $this->snapshotService->tracker($machine)->unmarkCurrentSnapshot();
        }

        $machine->store();
    }

    /**
     * Handle the Machine "deleted" event.
     */
    public function deleted(Machine $machine): void
    {
        //
    }

    /**
     * Handle the Machine "restored" event.
     */
    public function restored(Machine $machine): void
    {
        //
    }

    /**
     * Handle the Machine "force deleted" event.
     */
    public function forceDeleted(Machine $machine): void
    {
        //
    }
}
