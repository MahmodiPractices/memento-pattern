<?php

namespace Tests\Feature\Http\Web\CaretakerController;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UndoTest extends TestCase
{
    /**
     * Undo route name
     */
    private const ROUTE_NAME = 'caretaker.undo';

    /**
     * Since that undo operation needs non-empty snapshot table,
     * this method is in charge to fill the table
     *
     * @param Machine $machine
     * @return Collection
     */
    private function seedSnapshots(Machine $machine):Collection
    {
        return Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();
    }

    /**
     * @return void
     */
    public function test_makes_new_snapshot_when_there_is_no_any_current_snapshot_in_the_table()
    {
        $machine = Machine::factory()->create();

        $snapshots = $this->seedSnapshots($machine);

        $snapshotsCount = count($snapshots);

        $this->assertDatabaseCount($snapshots[0]->getTable(), $snapshotsCount);

        $res = $this->post(route(self::ROUTE_NAME, $machine));

        $res->assertOk();

        $snapshotsCount = $snapshotsCount + 1;

        $this->assertDatabaseCount($snapshots[0]->getTable(), ($snapshotsCount));
    }

    /**
     * @return void
     */
    public function test_does_not_make_new_snapshot_when_there_is_a_current_snapshot_in_the_table()
    {
        $machine = Machine::factory()->create();

        $snapshotsCount = count($this->seedSnapshots($machine));

        $this->travel(1)->hour();

        Snapshot::factory()->for($machine)->current()->create();

        $this->travel(1)->hour();

        $snapshotsCount += count($this->seedSnapshots($machine));

        $this->assertDatabaseCount('snapshots', $snapshotsCount);

        $res = $this->post(route(self::ROUTE_NAME, $machine));

        $res->assertOk();

        $this->assertDatabaseCount('snapshots', $snapshotsCount);
    }

    /**
     * @return void
     */
    public function test_set_first_snapshot_before_current_and_make_that_current()
    {
        $machine = Machine::factory()->create();

        $shouldSet = Snapshot::factory()->for($machine)->create();

        $this->travel(1)->hour();

        Snapshot::factory()->for($machine)->current()->create();

        $this->travel(1)->hour();

        $this->seedSnapshots($machine);

        $res = $this->post(route(self::ROUTE_NAME, $machine));

        $res->assertOk();

        $updatedMachine = Machine::find($machine->id);

        $this->assertNotSame($machine->values(), $updatedMachine->values());

        $shouldSet = Snapshot::find($shouldSet->id);

        $this->assertTrue($shouldSet->is_current);
    }

    /**
     * @return void
     */
    public function test_makes_snapshot_when_there_is_no_any_current_snapshot_and_then_set_last_snapshot_before_new_values_to_machine()
    {
        $machine = Machine::factory()->create();

        $snapshotsCount = count($this->seedSnapshots($machine));

        $res = $this->post(route(self::ROUTE_NAME, $machine));

        $res->assertOk();

        $snapshotsCount += 1;

        $this->assertDatabaseCount('snapshots', $snapshotsCount);

        $updatedMachine = Machine::find($machine->id);

        $this->assertNotSame($machine->values(), $updatedMachine->values());
    }
}
