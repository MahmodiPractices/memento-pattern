<?php

namespace App\Models\MachineAbstractions;

use App\Factory\Memento;
use App\Models\Machine;

class Snapshot
{
    public function __construct(
        private Machine $machine
    ){}

    /**
     * Store machine situation through create new snapshot
     *
     * @return bool
     */
    public function store():bool
    {

    }

    /**
     * Restore machine situation that stored in passed memento argument
     *
     * @param Memento $snapshot
     * @return bool
     */
    public function restore(Memento $snapshot):bool
    {

    }
}
