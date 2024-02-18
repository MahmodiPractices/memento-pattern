<?php

namespace App\Services;

use App\Http\Requests\StoreMachineRequest;
use App\Models\Machine;

class MachineService
{
    public function create(StoreMachineRequest $request):Machine|false
    {
        return Machine::create([
            'name' => $request->input('name'),
            'core' => $request->input('core'),
            'ram' => $request->input('ram'),
            'storage' => $request->input('storage'),
        ]);
    }
}
