<?php

namespace Tests\Feature\Policy;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RedoMachinePolicyTest extends TestCase
{
    private const ROUTE_NAME = 'machine.edit';

    /**
     * @return void
     */
    public function test_redo_policy_does_not_authorize_when_there_is_no_any_snapshot_for_machine_in_database()
    {
        $machine = Machine::factory()->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertDontSee('پیشگرد');
    }

    /**
     * @return void
     */
    public function test_redo_policy_does_not_authorize_when_there_are_some_snapshots_in_the_table_but_there_is_no_any_current_snapshot()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertDontSee('پیشگرد');
    }

    /**
     * @return void
     */
    public function test_redo_policy_does_not_authorize_when_there_are_some_snapshots_in_the_table_and_there_is_a_current_snapshot_but_there_is_no_any_snapshot_after_current()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();

        $this->travel(1)->hour();

        Snapshot::factory()->for($machine, 'snapshotable')->current()->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertDontSee('پیشگرد');
    }

    /**
     * @return void
     */
    public function test_redo_policy_authorize_when_there_are_some_snapshots_in_the_table_and_there_is_a_current_snapshot_and_there_is_some_snapshots_after_current()
    {
        $machine = Machine::factory()->create();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();

        $this->travel(1)->hour();

        Snapshot::factory()->for($machine, 'snapshotable')->current()->create();

        $this->travel(1)->hour();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();

        $res = $this->get(route(self::ROUTE_NAME, $machine));

        $res->assertSee('پیشگرد');
    }
}
