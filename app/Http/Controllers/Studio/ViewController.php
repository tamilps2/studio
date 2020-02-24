<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Studio;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        return view('app', [
            'scripts' => Studio::scriptVariables(),
        ]);
    }
}
