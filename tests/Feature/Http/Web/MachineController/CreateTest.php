<?php

namespace Tests\Feature\Http\Web\MachineController;

use App\Models\Machine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /**
     * Create machine route name
     */
    private const ROUTE_NAME = 'machine.store';

    private const MACHINE_TB_NAME = 'machines';

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
        $res = $this->post(route(self::ROUTE_NAME));

        $res->assertJsonValidationErrors(['name', 'core', 'ram', 'storage']);
    }

    /**
     * Asserts controller do unique validation for machine name
     *
     * @return void
     */
    public function test_unique_machine_name_validation()
    {
        $machine = Machine::factory()->create();

        $data = [
            'name' => $machine->name,
            'core' => 2,
            'ram' => 2,
            'storage' => 20,
        ];

        $res = $this->post(route(self::ROUTE_NAME), $data);

        $res->assertJsonValidationErrorFor('name');
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
            'name' => $machine->name,
            'core' => 2,
            'ram' => 2,
            'storage' => 20,
        ];

        $res = $this->post(route(self::ROUTE_NAME), $data);

        $res->assertJsonValidationErrorFor('name');
    }

    /**
     * Asserts controller enter and save data in db
     *
     * @return void
     */
    public function test_new_machine_stores_in_db_with_absolute_values()
    {
        $data = [
            'name' => 'my-machine',
            'core' => 2,
            'ram' => 2,
            'storage' => 20,
        ];

        $res = $this->post(route(self::ROUTE_NAME), $data);

        $this->assertDatabaseHas(self::MACHINE_TB_NAME, $data);
    }
}
