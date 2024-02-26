<?php

namespace Tests\Feature\Http\Web\MachineController;

use App\Models\Machine;
use App\Models\Snapshot;
use App\Services\Machine\MachineService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /**
     * Create machine route name
     */
    private const ROUTE_NAME = 'machine.destroy';

    /**
     * The machines table name
     */
    private const MACHINE_TB_NAME = 'machines';

    /**
     * Asserts delete method controller removes message from db and its snapshots
     *
     * @return void
     */
    public function test_machine_deletes_from_database_and_its_snapshots()
    {
        $machine = Machine::factory()->create();

        $snapshotCount = rand(1, 10);

        $snapshots = Snapshot::factory()->for($machine, 'snapshotable')->count($snapshotCount)->create();

        $res = $this->delete(route(self::ROUTE_NAME, $machine));

        $res->assertRedirect();

        $this->assertDatabaseMissing(self::MACHINE_TB_NAME, $machine->getAttributes());

        foreach ($snapshots as $snapshot)
            $this->assertDatabaseMissing($snapshots[0]->getTable(), [
                'id' => $snapshot->id,
                'memento' => $snapshot->memento
            ]);
    }

    /**
     * Asserts delete method controller pass success message to view after deleting
     *
     * @return void
     */
    public function test_delete_machine_controller_method_pass_alert_success_message_into_view()
    {
        $machine = Machine::factory()->create();

        $res = $this->delete(route(self::ROUTE_NAME, $machine));

        $res->assertRedirect();

        $res->assertSessionHas('alert-success');
    }

    /**
     * Asserts delete method controller pass error message to view when deleting was not success
     *
     * @return void
     * @throws Exception
     */
    public function test_delete_machine_controller_method_pass_alert_error_message_into_view()
    {
        $service = $this->createMock(MachineService::class);

        $service
            ->expects($this->once())
            ->method('delete')
            ->willReturn(false);

        $this->app->instance(MachineService::class, $service);

        $machine = Machine::factory()->create();

        $res = $this->delete(route(self::ROUTE_NAME, $machine));

        $res->assertRedirect();

        $res->assertSessionHas('alert-danger');
    }
}
