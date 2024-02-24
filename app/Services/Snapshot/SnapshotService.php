<?php

namespace App\Services\Snapshot;

use App\Models\Machine;
use App\Services\Snapshot\Abstraction\TrackPad;


class SnapshotService
{
    /**
     * @var Machine
     */
    private Machine $machine;

    /**
     * Returns caretaker tracker abstraction
     *
     * Placed track base methods undo, redo sample in this abstraction.
     *
     * @param Machine $machine
     * @return TrackPad
     */
    public function tracker(Machine $machine):TrackPad
    {
        $this->setMachine($machine);

        if(!isset($this->singleton[TrackPad::class]))
            $this->singleton[TrackPad::class] = new TrackPad($this);

        return $this->singleton[TrackPad::class];
    }

    /**
     * @return Machine
     */
    public function getMachine(): Machine
    {
        return $this->machine;
    }

    /**
     * @param Machine $machine
     * @return void
     */
    public function setMachine(Machine $machine): void
    {
        $this->machine  = $machine;
    }
}
