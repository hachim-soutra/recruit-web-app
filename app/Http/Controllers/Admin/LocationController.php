<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getCountry(Request $request)
    {
        $data = Country::orderBy('name', 'ASC')
            ->get();
        return response()->json($data);
    }

    public function getState($country_id, Request $request)
    {
        if (!$country_id) {
            return response()->json([]);
        }
        $data = State::orderBy('name', 'ASC')
            ->where('country_id', $country_id)
            ->get();
        return response()->json($data);
    }

    public function getCity($state_id, Request $request)
    {
        if (!$state_id) {
            return response()->json([]);
        }
        $data = City::orderBy('name', 'ASC')
            ->where('state_id', $state_id)
            ->get();
        return response()->json($data);
    }
}
