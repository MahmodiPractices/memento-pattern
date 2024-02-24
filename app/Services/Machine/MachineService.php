<?php

namespace App\Services\Machine;

use App\Http\Requests\StoreMachineRequest;
use App\Http\Requests\UpdateMachineRequest;
use App\Models\Machine;
use App\Models\MachineAbstractions\Memento;
use App\Services\Snapshot\Caretaker;
use Illuminate\Contracts\Container\BindingResolutionException;

class MachineService
{

    /**
     * Singleton property
     *
     * Uses for cache built instances and avoiding rebuild
     *
     * @var array
     */
    private array $singleton;

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
     * @throws BindingResolutionException
     */
    public function delete(Machine $machine):bool
    {
        $caretaker = app()->make(Caretaker::class);

        $caretaker->tracker($machine)->forgetHistory();

        return $machine->delete();
    }

    /**
     * Update machine
     *
     * @param Machine $machine
     * @param UpdateMachineRequest $request
     * @return bool
     */
    public function update(Machine $machine, UpdateMachineRequest $request):bool
    {
        return $machine->update($request->except($machine->getGuarded()));
    }

}
