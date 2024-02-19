<?php

namespace App\Models\MachineAbstractions;

use App\Models\Machine;

class Snapshot
{
    public function __construct(
        private Machine $machine
    ){}

    /**
     * Undo changes operations
     *
     * @return bool
     */
    public function undo():bool
    {

    }

    /**
     * Redo changes operations
     *
     * @return bool
     */
    public function redo():bool
    {

    }

    /**
     * Checks that the Machine as selected snapshot
     *
     * @return bool
     */
    public function hasCurrent():bool
    {

    }

    /**
     * Store machine situation through create new snapshot
     *
     * @return bool
     */
    public function store():bool
    {

    }
}
