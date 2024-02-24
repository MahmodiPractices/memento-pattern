<?php

namespace Tests\Feature\Policy;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UndoMachinePolicyTest extends TestCase
{
    private const ROUTE_NAME = 'machine.edit';

    /**
     * @return void
     */
    public function test_undo_policy_does_not_authorize_when_there_is_no_any_snapshot_for_machine_in_database()
    {
        $machine = Machine::factory()->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertDontSee('واگرد');
    }

    /**
     * @return void
     */
    public function test_undo_policy_authorize_when_there_is_a_current_snapshot_but_also_there_are_other_snapshots_before_this()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 9))->create();

        $this->travel(1)->day();

        Snapshot::factory()->current()->for($machine, 'snapshotable')->create();

        $this->travel(1)->minute();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 9))->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertSee('واگرد');
    }

    /**
     * @return void
     */
    public function test_undo_policy_does_not_authorize_when_there_is_a_current_snapshot_and_there_is_no_other_snapshots_before_this()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->current()->for($machine, 'snapshotable')->create();

        $this->travel(1)->minute();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 9))->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertDontSee('واگرد');
    }

    /**
     * @return void
     */
    public function test_undo_policy_authorize_when_there_is_no_current_snapshot_but_snapshots_table_not_empty()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 9))->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertSee('واگرد');
    }
}
