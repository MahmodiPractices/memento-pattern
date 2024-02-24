<?php

namespace App\Models;

use App\Factory\MementoObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Machine extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $guarded = ['id'];


    /**
     * Relation method with Snapshot
     *
     * Each machine can have many snapshots while each snapshot
     * is belongs to specific machine
     *
     * @return MorphMany
     */
    public function snapshots():MorphMany
    {
        return $this->morphMany(Snapshot::class, 'snapshotable');
    }

    /**
     * Store machine situation through create new snapshot
     *
     * @return bool
     * @throws \Exception
     */
    public function store():bool
    {
        $memento = new MementoObject();

        foreach ($this->attributes as $key => $value)
            $memento->set($key, $value);

        return (bool)$this->snapshots()->create([
            'memento' => $memento->export(),
        ]);
    }

    /**
     * Restore machine situation that stored in passed memento argument
     *
     * @param MementoObject $snapshot
     * @return bool
     */
    public function restore(MementoObject $snapshot):bool
    {

    }

    /**
     * Checks that the Machine have selected snapshot
     *
     * @return bool
     */
    public function hasCurrentSnapshot(): bool
    {
        return $this->snapshots()->where('is_current', '1')->exists();
    }

    /**
     * Returns snapshot that defined as current
     *
     * @return Snapshot|null
     */
    public function currentSnapshot():Snapshot|null
    {
        return $this->snapshots()->where('is_current', 1)->first();
    }
}
