<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    function list(Request $request) {
        if (Auth::check()) {
            $notification = Notification::where('receiver_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->with('sender', 'receiver')
                ->get();

            if ($notification) {
                return response()->json([
                    'status'       => true,
                    'message'      => "Success",
                    'notification' => $notification,
                ], 200);
            } else {
                return response()->json([
                    'status'     => false,
                    'message'    => "No Data Found!",
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                'data' => [
                    'status'     => false,
                    'message'    => "User Must be login",
                    "error_type" => 2,

                ],
            ], 200);
        }

    }
}
