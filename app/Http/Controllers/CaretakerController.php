<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Services\Snapshot\Caretaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaretakerController extends Controller
{
    public function __construct(
        private Caretaker $caretaker
    )
    {}

    /**
     * Undo changes
     *
     * @param Machine $machine
     * @return RedirectResponse
     * @throws \Exception
     */
    public function undo(Machine $machine):RedirectResponse
    {
        $this->authorize('undo', $machine);

        $redirect = redirect()->back();

        return $this->caretaker
            ->tracker($machine)
            ->undo() ?
                $redirect->with('alert-success', 'عملیات واگرد انجام شد !') :
                $redirect->with('alert-danger', 'خطایی پیش آمده است !');
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
