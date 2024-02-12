<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Snapshot extends Model
{
    use HasFactory;

    /**
     * Relation method with Machine
     *
     * Each snapshot is belongs to a specific machine while
     * each machine can have many snapshots
     *
     * @return BelongsTo
     */
    public function machine():BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }
}
