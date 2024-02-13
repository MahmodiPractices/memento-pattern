<?php

namespace App\Models;

use Database\Factories\CaretakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Caretaker extends Model
{
    use HasFactory;

    protected $table = 'snapshots';

    /**
     * Define implicit factory
     *
     * @return Factory
     */
    protected static function newFactory():Factory
    {
        return CaretakerFactory::new();
    }

    /**
     * Snapshot-able polymorph relation
     *
     * @return MorphTo
     */
    public function snapshotable():MorphTo
    {
        return $this->morphTo(name: 'snapshotable', id: 'snapshotable_id', type: 'snapshotable_type');
    }


}
