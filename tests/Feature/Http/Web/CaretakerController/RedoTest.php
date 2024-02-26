<?php

namespace Tests\Feature\Http\Web\CaretakerController;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class RedoTest extends TestCase
{
    use HasMockedMementoObject;

    /**
     * Redo route name
     */
    private const ROUTE_NAME = 'caretaker.redo';

    /**
     * @return void
     * @throws Exception
     */
    public function test_sets_first_available_snapshot_after_current_snapshot_values_to_the_machine()
    {
        $machine = Machine::factory()->create();

        $currentSnapshot = Snapshot::factory()->for($machine, 'snapshotable')->current()->create();

        $this->travel(1)->minute();

        $shouldBeCurrentSnapshot = Snapshot::factory()->for($machine, 'snapshotable')->create();

        $this->travel(1)->minute();

        Snapshot::factory()->for($machine, 'snapshotable')->count(rand(1, 10))->create();

        $machineAfterRedo = Machine::factory()->make();

        $this->injectMementoObjectMockToContainer($machineAfterRedo);

        $res = $this->post(route(self::ROUTE_NAME, $machine));

        $res->assertRedirect();

        $currentSnapshot->is_current = 0;

        $this->assertDatabaseMissing($currentSnapshot->getTable(), $currentSnapshot->getAttributes());

        $shouldBeCurrentSnapshot->is_current = 1;

        $this->assertDatabaseHas($shouldBeCurrentSnapshot->getTable(), $shouldBeCurrentSnapshot->getAttributes());

        $this->assertDatabaseHas($machine->getTable(), $machineAfterRedo->getAttributes());
    }
}
