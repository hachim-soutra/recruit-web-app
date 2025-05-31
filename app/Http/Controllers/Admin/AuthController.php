<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('admin.auth.login');
        }
    }

    public function login(Request $request, Guard $guard)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        } else {
            $remember_me = (!empty($request->remember)) ? true : false;
            $credentials = array(
                'email'          => $request->input('email'),
                'password'       => $request->input('password'),
                'status'         => true,
                'email_verified' => true,
            );
            if (Auth::attempt($credentials, $remember_me)) {
                $user = Auth::user();
                if ($user->hasPermission('dashboard')) {
                    return response()->json([
                        'data' => [
                            "status"  => 'success',
                            "message" => "Login success",
                        ],
                    ], 200);
                } else {
                    $guard->logout();
                    $request->session()->invalidate();
                    return response()->json([
                        'data' => [
                            "status"  => 'error',
                            "message" => "You don't have permission to access this.",
                        ],
                    ], 200);
                }

            } else {
                return response()->json([
                    'data' => [
                        "status"  => 'error',
                        "message" => "Please insert a valid credential.",
                    ],
                ], 200);
            }
        }
    }

    public function logout(Request $request, Guard $guard)
    {
        $guard->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }

    public function profileDetails()
    {
        $data = User::where('id', Auth::user()->id)->first();

        return view('admin.pages.settings', compact('data'));
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:8',
            'new_password'     => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $hashedPassword = Auth::user()->password;

        if (\Hash::check($request->current_password, $hashedPassword)) {

            if (!\Hash::check($request->new_password, $hashedPassword)) {

                $users           = User::find(Auth::user()->id);
                //$users->password = bcrypt($request->new_password);
                $users->password = Hash::make($request->new_password);
				
                User::where('id', Auth::user()->id)->update(array('password' => $users->password));

                return response()->json([
                    'data' => [
                        "status"  => "success",
                        "message" => "password updated successfully done",
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"  => "error",
                        "message" => "new password can not be the old password!",
                    ],
                ], 200);

            }

        } else {

            return response()->json([
                'data' => [
                    "status"  => false,
                    "message" => "old password doesnt matched! ",
                ],
            ], 200);
        }
    }

    public function settingsUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string',
            'email'  => 'required|email',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $id               = Auth::user()->id;
        $user             = User::findOrFail($id);
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->updated_at = Carbon::now()->toDateTimeString();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                if (File::exists("uploads/users/" . $user->path)) {
                    File::delete("uploads/users/" . $user->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm') . '/';
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/users/' . $directory), $filename);
            $user->avatar = $directory . $filename;
        }

        $res = $user->save();
        if ($res) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Profile has been updated successfully.",
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
}
