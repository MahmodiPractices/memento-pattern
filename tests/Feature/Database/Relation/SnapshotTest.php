<?php

namespace Tests\Feature\Database\Relation;

use App\Models\Machine;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Database\Traits\BelongsToRelationTesting;
use Tests\TestCase;

class SnapshotTest extends TestCase
{
    use BelongsToRelationTesting;

    /**
     * Defines model under test.
     *
     * @return Model
     */
    protected function model(): Model
    {
        return new Snapshot();
    }

    /**
     * Defines in belongs to relation models with model under test
     *
     * @return Model|array
     */
    protected function inBelongsToRelationModel(): Model|array
    {
        return new Machine();
    }
}
