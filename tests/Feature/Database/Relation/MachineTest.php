<?php

namespace Tests\Feature\Database\Relation;

use App\Models\Machine;
use App\Models\Caretaker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Database\Traits\HasManyRelationTesting;
use Tests\Feature\Database\Traits\MorphManyRelationTesting;
use Tests\TestCase;

class MachineTest extends TestCase
{
    use MorphManyRelationTesting;

    /**
     * Defines model under test.
     *
     * @return Model
     */
    protected function model(): Model
    {
        return new Machine();
    }

    /**
     * Defines in morph many relation models with model under test
     *
     * @return Model|array
     */
    protected function inMorphManyRelationModel(): Model|array
    {
        return new Caretaker();
    }

    protected function inMorphManyRelationModelMethodNames(): array|null
    {
        return [
            'snapshots' => new Caretaker()
        ];
    }
}
