<?php

namespace App\Models;

use App\Models\CaretakerAbstractions\TrackPad;
use Database\Factories\SnapshotFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Snapshot extends Model
{
    use HasFactory;

    /**
     * Singleton property
     *
     * Uses for cache built instances and avoiding rebuild
     *
     * @var array
     */
    private static array $singleton;

    /**
     * @var string[] 
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $casts = [
        'memento'
    ];

    /**
     * Define implicit factory
     *
     * @return Factory
     */
    protected static function newFactory():Factory
    {
        return SnapshotFactory::new();
    }

    /**
     * MementoObject-able polymorph relation
     *
     * @return MorphTo
     */
    public function snapshotable():MorphTo
    {
        return $this->morphTo(name: 'snapshotable', id: 'snapshotable_id', type: 'snapshotable_type');
    }

    /**
     * Interact with the user's first name.
     */
    protected function memento(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => unserialize($value),
            set: fn (string $value) => serialize($value),
        );
    }
}
