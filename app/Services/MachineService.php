<?php

namespace App\Services;

use App\Http\Requests\StoreMachineRequest;
use App\Models\Machine;

class MachineService
{
    /**
     * Create new machine
     *
     * @param StoreMachineRequest $request
     * @return Machine|false
     */
    public function create(StoreMachineRequest $request):Machine|false
    {
        return Machine::create([
            'name' => $request->input('name'),
            'core' => $request->input('core'),
            'ram' => $request->input('ram'),
            'storage' => $request->input('storage'),
        ]);
    }

    /**
     * Destroy machine form db
     *
     * @param Machine $machine
     * @return bool
     */
    public function delete(Machine $machine):bool
    {
        return $machine->delete();
    }
}
