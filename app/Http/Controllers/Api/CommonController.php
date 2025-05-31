<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Models\Language;
use App\Models\Qualification;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Transuction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    public function getBenefits(Request $request)
    {
        if (Auth::check()) {
            $data = array("Overtime");
            return json_encode($data);
            $data = null;
            if ($data) {
                return response()->json([
                    "status" => true,
                    'benefits' => $data,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function getSkills(Request $request)
    {
        if (Auth::check()) {
            $data = Skill::orderBy('name', 'ASC')
                ->where('status', 1)
                ->get();
            if ($data) {
                return response()->json([
                    "status" => true,
                    'skills' => $data,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function getLanguage(Request $request)
    {
        if (Auth::check()) {
            $data = Language::orderBy('name', 'ASC')
                ->where('status', 1)
                ->get();
            if ($data) {
                return response()->json([
                    "status"   => true,
                    'language' => $data,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function getFunctionalArea(Request $request)
    {
        if (Auth::check()) {
            $data = Industry::orderBy('name', 'ASC')
                ->where('status', 1)
                ->get();
            if ($data) {
                return response()->json([
                    "status"     => true,
                    'industries' => $data,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function getQualifications(Request $request)
    {
        if (Auth::check()) {
            $data = Qualification::orderBy('name', 'ASC')
                ->where('status', 1)
                ->get();
            $benefits = null;

            if ($data) {
                return response()->json([
                    "status"         => true,
                    'qualifications' => $data,
                    'benefits' => $benefits,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function about(Request $request)
    {
        $data = Setting::where('id', 1)
            ->first();
        if ($data) {
            return response()->json([
                "status"   => true,
                'about_us' => $data->about_us,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went to wrong.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function help(Request $request)
    {
        $data = Setting::where('id', 1)
            ->first();
        if ($data) {
            return response()->json([
                "status" => true,
                'help'   => $data->help,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went to wrong.",
                "error_type" => 2,
            ], 200);
        }

    }
    public function termsConditions(Request $request)
    {
        $data = Setting::where('id', 1)
            ->first();
        if ($data) {
            return response()->json([
                "status" => true,
                'terms_conditions'   => $data->term_of_use,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went to wrong.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function package(Request $request)
    {
        $data = Setting::where('id', 1)
            ->first();
        if ($data) {
            return response()->json([
                "status"                 => true,
                'currency'               => $data->currency,
                'yearly_price_for_coach' => $data->yearly_price_for_coach,
                'payment_gateway'        => $data->payment_gateway,
                'secret_key'             => $data->secret_key,
                'published_key'          => $data->published_key,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                'message'    => "Something went to wrong.",
                "error_type" => 2,
            ], 200);
        }

    }

    public function getTransactionHistory(Request $request)
    {
        if (Auth::check()) {

            $data = Transuction::where('user_id', Auth::user()->id)->with('job')->get();

            if ($data) {

                return response()->json([
                    "status"           => true,
                    "transuction_list" => $data,
                    "message"          => "Transuction list",

                ], 200);
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "No Data Found!",
                    "error_type" => 2,

                ], 200);

            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime!",
                "error_type" => 2,
            ], 200);
        }
    }

}
