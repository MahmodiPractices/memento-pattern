<?php

namespace App\Models\CaretakerAbstractions;

class TrackPad
{
    /**
     * Bridge
     *
     * @param \App\Models\Caretaker $caretaker
     */
    public function __construct(
        private \App\Models\Caretaker $caretaker
    )
    {}

    /**
     * Undo changes operations
     *
     * @return bool
     */
    public function undo():bool
    {

    }

    /**
     * Redo changes operations
     *
     * @return bool
     */
    public function redo():bool
    {

    }

    /**
     * Checks that the Machine as selected snapshot
     *
     * @return bool
     */
    public function hasCurrent():bool
    {

    }
}
