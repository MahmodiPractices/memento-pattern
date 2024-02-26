<?php

namespace Tests\Feature\Http\Web\MachineController;

use App\Models\Snapshot;
use App\Models\Machine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use WithFaker;

    /**
     * Index machine route name
     */
    private const ROUTE_NAME = 'machine.update';

    /**
     * The route that update method should redirect after successful update machine
     */
    private const REDIRECT_ROUTE = 'machine.index';

    /**
     * Asserts controller do require validation
     *
     * Asserts controller do request validation for
     * name, core, ram and storage fields
     *
     * @return void
     */
    public function test_required_validation()
    {
        $machine = Machine::factory()->create();

        $res = $this->put(route(self::ROUTE_NAME, $machine));

        $res->assertSessionHasErrors(['name', 'core', 'ram', 'storage']);
    }

    /**
     * Asserts controller do unique validation for machine name
     *
     * @return void
     */
    public function test_unique_machine_name_validation()
    {
        $machine = Machine::factory()->create();

        $other = Machine::factory()->create();

        $data = [
            'name' => $other->name,
            'core' => 2,
            'ram' => 2,
            'storage' => 20,
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertSessionHasErrorsIn('name');
    }

    /**
     * Asserts controller do is numeric validation for machine hardware fields
     *
     * @return void
     */
    public function test_is_numeric_validation()
    {
        $machine = Machine::factory()->create();

        $data = [
            'name' => 'my-machine',
            'core' => 'my-core',
            'ram' => 'my-ram',
            'storage' => 'my-storage',
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertSessionHasErrors(['core', 'ram', 'storage']);
    }

    /**
     * @return void
     */
    public function test_update_machine_does_not_throw_any_exception_when_machine_fields_sends_without_any_change()
    {
        $machine = Machine::factory()->create();

        $data = [
            'name' => $machine->name,
            'core' =>  $machine->core,
            'ram' =>  $machine->ram,
            'storage' =>  $machine->storage,
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertRedirectToRoute(self::REDIRECT_ROUTE);

        $res->assertSessionHas('alert-success');
    }

    /**
     * @return void
     */
    public function test_update_machine_avoid_remove_machine_snapshots_that_created_after_defined_as_current_snapshot_and_avoid_remove_is_current_definition_of_current_snapshot_when_there_is_no_any_change_in_fields()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(5)->create();

        $currentSnapshot = Snapshot::factory()->for($machine, 'snapshotable')->current()->create();

        $shouldNotRemoveSnapshots = Snapshot::factory()->for($machine, 'snapshotable')->count(5)->create();

        $data = [
            'name' => $machine->name,
            'core' =>  $machine->core,
            'ram' =>  $machine->ram,
            'storage' =>  $machine->storage,
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertSessionHas('alert-success');

        $this->assertDatabaseHas($currentSnapshot->getTable(), [
            'id' => $currentSnapshot->id,
            'snapshotable_id' => $machine->id,
            'is_current' => 1
        ]);

        foreach ($shouldNotRemoveSnapshots as $snapshot)
            $this->assertDatabaseHas($snapshot->getTable(), [
                'id' => $snapshot->id,
                'snapshotable_id' => $machine->id,
            ]);
    }

    /**
     * @return void
     */
    public function test_update_machine_removes_machine_snapshots_that_created_after_defined_as_current_snapshot_and_removes_is_current_definition_of_current_snapshot_when_machine_fields_changed()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(5)->create();

        $currentSnapshot = Snapshot::factory()->for($machine, 'snapshotable')->current()->create();

        $this->travel(1)->hour();

        $shouldRemoveSnapshots = Snapshot::factory()->for($machine, 'snapshotable')->count(5)->create();

        $data = [
            'name' => $this->faker->name,
            'core' =>  rand(1, 12),
            'ram' =>  rand(2, 8),
            'storage' =>  rand(10, 100),
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertSessionHas('alert-success');

        $this->assertDatabaseMissing($currentSnapshot->getTable(), [
            'id' => $currentSnapshot->id,
            'snapshotable_id' => $machine->id,
            'is_current' => 1
        ]);

        foreach ($shouldRemoveSnapshots as $snapshot)
            $this->assertDatabaseMissing($snapshot->getTable(), [
                'id' => $snapshot->id,
                'snapshotable_id' => $machine->id,
            ]);
    }

    /**
     * @return void
     */
    public function test_update_machine_makes_new_snapshot_for_machine_when_there_is_no_any_current_snapshot_and_machine_fields_changed()
    {
        $fakeSnapshotsCount = 5;

        $machine = Machine::factory()->create();

        $snapshots = Snapshot::factory()->for($machine, 'snapshotable')->count($fakeSnapshotsCount)->create();

        $data = [
            'name' => $this->faker->name,
            'core' =>  rand(2, 12),
            'ram' =>  rand(2, 8),
            'storage' =>  rand(10, 100),
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $res->assertSessionHas('alert-success');

        $fakeSnapshotsCount++;

        $this->assertDatabaseCount($snapshots[0]->getTable(), $fakeSnapshotsCount);

        foreach ($snapshots as $snapshot)
            $this->assertDatabaseHas($snapshot->getTable() , [
                'id' => $snapshot->id,
                'is_current' => $snapshot->is_current,
                'created_at' => $snapshot->created_at,
            ]);
    }

    public function test_update_machine_stores_changed_values_in_database_and_works_ok()
    {
        $machine = Machine::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'core' =>  rand(2, 12),
            'ram' =>  rand(2, 8),
            'storage' =>  rand(10, 100),
        ];

        $res = $this->put(route(self::ROUTE_NAME, $machine), $data);

        $this->assertDatabaseHas($machine->getTable(), array_merge(['id' => $machine->id], $data));
    }
}
