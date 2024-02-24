<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaretakerController extends Controller
{
    /**
     * Undo changes
     *
     * @param Machine $machine
     * @return RedirectResponse
     */
    public function undo(Machine $machine):RedirectResponse
    {

    }

    /**
     * Redo changes
     *
     * @param Machine $machine
     * @return RedirectResponse
     */
    public function redo(Machine $machine):RedirectResponse
    {

    }
}
