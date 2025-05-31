<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    function list(Request $request) {
        if (Auth::check()) {
            $limit  = 5;
            $offset = ((((int) $request->page_no - 1) * $limit));

            $events = Event::where('status', '=', 1)->where('is_delete', 1)->get();

            $total_events = Event::where('status', '=', 1)->where('is_delete', 1)
            ->get();

            $total = count($total_events);
            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'per_page' => $limit,
                'total'    => $total,
                'events'   => $events,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function Details(Request $request)
    {
        if (Auth::check()) {

            $data = Event::where('id', '=', $request->input('event_id'))->first();

            return response()->json([
                "status"        => true,
                "message"       => "Success",
                'event_details' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }

    }
}
