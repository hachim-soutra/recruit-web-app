<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyUser;
use App\Mail\Welcome;
use App\Models\Coach;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use App\Models\Role;
use App\Models\Transuction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use File;

class CoachController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        $keyword = '';
        if ($request == null || $request == '') {

            $request = (object)[];
            $request->keyword = $keyword = trim(request('keyword'));
        } else {
            $keyword = trim($request->input('keyword', null));
        }

        $data    = User::orderBy('updated_at', 'DESC')
            ->with('coach')
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'archived') {
                    $q->where('status', 0);
                }
            })
            ->where('user_type', 'coach')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('user_key', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        //TODO: Subscription remove to clean code
        foreach ($data as $d) :
            //     $is_active_subscriped = Subscription::where('user_id', $d->id)->where('status', 'Running')->first();
            //     if(!empty($is_active_subscriped)){
            //         $d->is_active_subscriped = true;
            //     }else{
            //         $d->is_active_subscriped = false;
            //     }
            $d->is_active_subscriped = false;
        endforeach;

        return view('admin.coach.list', compact('data', 'request'));
    }

    public function create()
    {
        $roles = Role::where(function ($q) {
            $q->where('name', '!=', 'tutor');
            $q->where('name', '!=', 'member');
        })->get();
        $countries = Country::all();
        $cities = City::all();
        $states = State::all();
        return view('admin.coach.add', compact('roles', 'countries', 'cities', 'states'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string',
            'email'         => 'required|email|unique:users,email',
            'mobile'        => 'required|numeric|digits_between:9,15|unique:users,mobile',
            'date_of_birth' => 'nullable|date',
            'zip'           => 'nullable',
            'address'       => 'nullable',
            'contact_link'  => 'nullable|url',
            'linkedin_link'  => 'nullable|url',
            'instagram_link'  => 'nullable|url',
            'facebook_link'  => 'nullable|url',
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'coach_banner'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $name_explode = explode(',', $request->input('name'));
        $first_name = $name_explode[0];
        $last_name = '';
        if (array_key_exists('1', $name_explode)) {
            $last_name = $name_explode[1];
        }
        $user             = new User();
        $user->name       = $request->input('name');
        $user->first_name       = $first_name;
        $user->last_name       = $last_name;
        $user->email      = $request->input('email');
        $user->mobile     = $request->input('mobile', null);
        $user->user_key   = 'JP' . rand(100, 999) . date("his");

        //$user->password = Hash::make('123456');
        $user_password    = trim(Str::random(9));
        $user->password   = Hash::make($user_password);

        $user->user_type    = 'coach';
        $user->email_verified  = 1;
        $user->mobile_verified = 1;
        $user->verified = 1;
        $user->is_complete = 1;
        $user->updated_at = Carbon::now()->toDateTimeString();
        if ($request->hasFile('avatar')) {

            $time      = Carbon::now();
            $file      = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/users/'), $filename);
            $user->avatar = $filename;
        }
        $user->save();
        if ($user) {
            Coach::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'date_of_birth' => $request->input('date_of_birth', null),
                'contact_link'  => $request->input('contact_link', null),
                'linkedin_link'  => $request->input('linkedin_link', null),
                'instagram_link'  => $request->input('instagram_link', null),
                'facebook_link'  => $request->input('facebook_link', null),
                'country'       => $request->input('country', null),
                'state'         => $request->input('state', null),
                'city'          => $request->input('cityname', null),
                'zip'           => $request->input('zip', null),
                'address'       => $request->input('address', null),
                'about_us'      => $request->input('about_us', null),
                'faq'           => $request->input('faq', null),
                'how_we_help'   => $request->input('help_desk', null),
                'coach_skill'   => $request->input('skill_detail', null),
                'coach_banner'   => null,
            ]);
            $coach = Coach::where('user_id', $user->id)->first();
            if ($request->hasFile('coach_banner')) {
                $time      = Carbon::now();
                $file      = $request->file('coach_banner');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/coach_banner/'), $filename);
                $coach->coach_banner = $filename;
            }
            $coach->save();

            //Mail::to($user->email)->send(new VerifyUser($user));
            //Mail::to($user->email)->send(new Welcome($user, '123456'));
            Mail::to($user->email)->send(new Welcome($user, $user_password));

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Coach has been updated successfully.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ], 200);
        }
    }

    public function show($id)
    {
        $data         = User::where('id', $id)->with('coach')->first();
        $transactions = Transuction::where('user_id', $id)->get();
        return view('admin.coach.show', compact('data', 'transactions'));
    }

    public function edit($id)
    {
        $roles = Role::where(function ($q) {
            $q->where('name', '!=', 'tutor');
            $q->where('name', '!=', 'member');
        })->get();
        $user      = User::where('id', $id)->first();
        $countries = Country::all();
        $cities = City::all();
        $states = State::all();
        return view('admin.coach.edit', compact('roles', 'user', 'countries', 'cities', 'states'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string',
            'email'         => 'required|email|unique:users,email,' . $id,
            'mobile'        => 'nullable|digits_between:9,15|unique:users,mobile,' . $id,
            'date_of_birth' => 'nullable|date',
            //  'marital_status' => 'required',
            'country'       => 'nullable',
            'state'         => 'nullable',
            'city'          => 'nullable',
            'zip'           => 'nullable',
            'address'       => 'nullable',
            'contact_link'  => 'nullable|url',
            'linkedin_link'  => 'nullable|url',
            'instagram_link'  => 'nullable|url',
            'facebook_link'  => 'nullable|url',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'coach_banner'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'password'              => 'nullable|string|min:6|confirmed',
            // 'password_confirmation' => 'sometimes|required_with:password',
            // 'roles'                 => 'required|array|min:1',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user             = User::findOrFail($id);
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->mobile     = $request->input('mobile', null);
        $user->updated_at = Carbon::now()->toDateTimeString();
        if ($request->hasFile('avatar')) {

            $time      = Carbon::now();
            $file      = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/users/'), $filename);
            $user->avatar = $filename;
        }
        $user->save();

        // $roles = $request->roles;
        // $user->syncRoles($roles);
        if ($user) {
            Coach::where('user_id', $id)
                ->update([
                    'date_of_birth' => $request->input('date_of_birth'),
                    'contact_link'  => $request->input('contact_link'),
                    'linkedin_link' => $request->input('linkedin_link'),
                    'facebook_link' => $request->input('facebook_link'),
                    'instagram_link' => $request->input('instagram_link'),
                    'country'       => $request->input('country'),
                    'state'         => $request->input('state'),
                    'city'          => $request->input('city'),
                    'zip'           => $request->input('zip'),
                    'address'       => $request->input('address'),
                    'about_us'      => $request->input('about_us', null),
                    'faq'           => $request->input('faq', null),
                    'how_we_help'   => $request->input('help_desk', null),
                    'coach_skill'   => $request->input('skill_detail', null),
                ]);
            $coach = Coach::where('user_id', $id)->first();
            if ($request->hasFile('coach_banner')) {
                if ($coach->coach_banner !== "no_banner.jpg") {
                    if (File::exists("uploads/users/" . $coach->coach_banner)) {
                        File::delete("uploads/users/" . $coach->coach_banner);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('coach_banner');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/coach_banner/'), $filename);
                $coach->coach_banner = $filename;
            }
            $coach->save();


            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Coach has been updated successfully.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ], 200);
        }
    }

    public function archive($id)
    {
        $res = User::where('id', $id)
            ->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function restore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function emailArchive($id)
    {
        $res = User::where('id', $id)
            ->update([
                'email_verified' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User email verified has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function emailRestore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'email_verified' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User email verified has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function mobileArchive($id)
    {
        $res = User::where('id', $id)
            ->update([
                'mobile_verified' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Mobile verified has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record verified not Found",
                ],
            ], 200);
        }
    }

    public function mobileRestore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'mobile_verified' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Mobile has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }
}
