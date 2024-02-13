<?php

namespace Tests\Feature\Database\Relation;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Database\Traits\HasManyRelationTesting;
use Tests\TestCase;

class MachineTest extends TestCase
{
    use HasManyRelationTesting;

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
     * Defines in has many relation models with model under test
     *
     * @return Model|array
     */
    protected function inHasManyRelationModel(): Model|array
    {
        return new Snapshot();
    }
}
