<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MachinePolicy
{
    /**
     * Determine that can user undo machine changes
     */
    public function undo(?User $user, Machine $machine): bool
    {
        if(!$machine->snapshots()->count())
            return false;

        if($current = $machine->currentSnapshot())
            return (bool)$machine->snapshots()
                ->where('created_at', '<', $current->created_at)
                ->count();

        return true;
    }

    /**
     * Determine that can user redo machine changes
     */
    public function redo(?User $user, Machine $machine): bool
    {
        return true;
    }
}
