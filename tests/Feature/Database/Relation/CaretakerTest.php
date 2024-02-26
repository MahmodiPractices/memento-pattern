<?php

namespace Tests\Feature\Database\Relation;

use App\Models\Snapshot;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Database\Traits\MorphToRelationTesting;
use Tests\TestCase;

class CaretakerTest extends TestCase
{
    /**
     * Asserts morphed to many relation with machine model is established
     *
     * @return void
     */
    public function test_morphed_to_many_relation_with_machine()
    {
        // get currentModel
        $caretaker = new Snapshot();

        $machine = new Machine();

        // get currentModel table
        $inRelationModelTableName = $machine->getTable();

        // create new instance of current model in database by a belongs instance of in relation model
        $currentModelInstance = $caretaker::factory()->for(Machine::factory() , 'snapshotable')->create();

        // assert return type of relation method
        $this->assertTrue($currentModelInstance->snapshotable instanceof $machine);

        // assert id of card`s person isset
        $this->assertTrue($currentModelInstance->snapshotable->id != null);

        // assert person exists in database
        $this->assertDatabaseHas($inRelationModelTableName , $currentModelInstance->snapshotable->toArray());
    }
}
