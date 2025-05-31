<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactNotifyMail;
use App\Mail\ThankyouMail;
use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;

class ContactController extends Controller
{
    public function contact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:50',
            'email'        => 'required|email',
            'phone_number' => 'required',
            'subject'      => 'required|string|max:190',
            'sms_body'     => 'required|string',
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

        $info               = new Contact();
        $info->name         = $request->input('name');
        $info->email        = $request->input('email');
        $info->phone_number = $request->input('phone_number');
        $info->subject_name = $request->input('subject');
        $info->sms_body     = $request->input('sms_body');

        if ($info->save()) {
            Mail::to($info->email)->send(new ThankyouMail($info));
            $settings = Setting::where('id', '=', '1')
                ->where('status', '=', '1')
                ->first();

            Mail::to($settings->contact_email)->send(new ContactNotifyMail($info));

            return response()->json([
                "status"  => true,
                "message" => "Feedback has been submitted successfully.",
            ], 200);
        } else {

            return response()->json([
                'status'  => false,
                'message' => 'Opps error.',
            ], 200);
        }

    }
}
