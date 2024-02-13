<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Machine extends Model
{
    use HasFactory;

    /**
     * Relation method with Caretaker
     *
     * Each machine can have many snapshots while each snapshot
     * is belongs to specific machine
     *
     * @return MorphMany
     */
    public function snapshots():MorphMany
    {
        return $this->morphMany(Caretaker::class, 'snapshotable');
    }
}
