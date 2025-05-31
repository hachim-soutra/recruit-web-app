<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{

    public function getCountry(Request $request)
    {
        $data = Country::orderBy('name', 'ASC')
            ->get();
        if ($data) {
            return response()->json([
                "status"    => true,
                'countries' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "System error, please try after sometime.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function getState(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'country_id' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"     => false,
                "message"    => 'validation error',
                "error"      => $error,
                "error_type" => 1,
            ], 200);

        }
        $country_id = $request->input('country_id');
        $data       = State::orderBy('name', 'ASC')
            ->where('country_id', $country_id)
            ->get();

        if ($data) {
            return response()->json([
                "status" => true,
                'states' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went wrong.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function getCity(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'state_id' => 'required',

        ]);

        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"     => false,
                "message"    => 'validation error',
                "error"      => $error,
                "error_type" => 1,
            ], 200);

        }
        $state_id = $request->input('state_id');

        $data = City::orderBy('name', 'ASC')
            ->where('state_id', $state_id)
            ->get();

        if ($data) {
            return response()->json([
                "status" => true,
                'cities' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went wrong.",
                "error_type" => 2,
            ], 200);
        }

    }
}
