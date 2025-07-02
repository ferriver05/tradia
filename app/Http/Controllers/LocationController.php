<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\City;

class LocationController extends Controller
{
    /** Devuelve JSON de estados para un country_id */
    public function states(Request $request)
    {
        $data = $request->validate([
            'country_id' => 'required|integer|exists:countries,id',
        ]);

        $states = State::where('country_id', $data['country_id'])
                    ->orderBy('name')
                    ->get(['id','name'])
                    ->map(function($s) {
                        $s->name = preg_replace('/\s*Department$/i', '', $s->name);
                        return $s;
                    });

        return response()->json($states);
    }

    /** Devuelve JSON de ciudades para un state_id */
    public function cities(Request $request)
    {
        $data = $request->validate([
            'state_id' => 'required|integer|exists:states,id',
        ]);

        $cities = City::where('state_id', $data['state_id'])
                      ->orderBy('name')
                      ->get(['id','name']);

        return response()->json($cities);
    }
}
