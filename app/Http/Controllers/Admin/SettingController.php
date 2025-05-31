<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Validator;

class SettingController extends Controller
{
    public function show(Request $request)
    {
        $settings = Setting::where('id', '=', '1')
            ->where('status', '=', '1')
            ->first();
        return view('admin.settings.index', compact('settings'));
    }    

    public function edit(Request $request){
        $settings = Setting::where('id', '=', '1')
            ->where('status', '=', '1')
            ->first();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        // return $request->all();
        $validator = Validator::make($request->all(), [
            'app_version'               => 'required',
            'mobile_no'               => 'required',
            'alt_mobaile_no'          => 'nullable',
            'address_one'              => 'required',
            'address_two'             => 'nullable',
            'currency'               => 'required',
            'new_join_one_month_free' => 'required',
            'payment_gateway'        => 'required',
            'secret_key'             => 'required',
            'published_key'          => 'required',
            'contact_email'          => 'required|email',
            'about_us'               => 'required',
            'help'                   => 'required',
            'privacy_policy'         => 'required',
            'term_of_use'            => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $stting                         = Setting::findOrFail(1);
        $stting->app_version = $request->input('app_version');
        $stting->new_join_one_month_free = $request->input('new_join_one_month_free');
        $stting->mobile_no               = $request->input('mobile_no');
        $stting->alt_mobaile_no          = $request->input('alt_mobaile_no');
        $stting->addres_one             = $request->input('address_one');
        $stting->address_two             = $request->input('address_two');
        $stting->currency               = $request->input('currency');
        $stting->about_us        = $request->input('about_us');
        $stting->help            = $request->input('help');
        $stting->term_of_use     = $request->input('term_of_use');
        $stting->privacy_policy  = $request->input('privacy_policy');
        $stting->payment_gateway = $request->input('payment_gateway');
        $stting->secret_key      = $request->input('secret_key');
        $stting->published_key   = $request->input('published_key');
        $stting->contact_email   = $request->input('contact_email');
        $stting->facebook_link   = $request->input('facebook_link');
        $stting->twitter_link   = $request->input('twitter_link');
        $stting->instagram_link   = $request->input('instagram_link');
        $stting->copyright_content   = $request->input('copyright_content');
        $stting                  = $stting->save();

        if ($stting) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Site Setting Updated successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while updateing the settings.',
                ],
            ], 200);
        }

    }
}
