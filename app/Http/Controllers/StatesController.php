<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    static function get_states_municipalities()
    {
        return response()->json([
            'states' => State::where('country_id', 142)->with('cities')->get(),
        ]);
    }
}
