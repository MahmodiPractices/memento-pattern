<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Machine extends Model
{
    use HasFactory;

    /**
     * Relation method with Snapshot
     *
     * Each machine can have many snapshots while each snapshot
     * is belongs to specific machine
     *
     * @return HasMany
     */
    public function snapshots():HasMany
    {
        return $this->hasMany(Snapshot::class);
    }
}
