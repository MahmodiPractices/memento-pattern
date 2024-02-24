<?php

namespace Tests\Feature\Http\Web\MachineController;

use App\Models\Machine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /**
     * Index machine route name
     */
    private const ROUTE_NAME = 'machine.index';

    /**
     * Asserts index view displays all of exists machines
     *
     * @return void
     */
    public function test_index_machine_view_display_all()
    {
        $machines = Machine::factory()->count(rand(1, 10))->create();

        $res = $this->get(route(self::ROUTE_NAME));

        $res->assertViewHasAll(['machines' => $machines]);
    }
}
