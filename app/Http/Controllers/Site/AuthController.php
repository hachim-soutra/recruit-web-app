<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Mail\Welcome;
use App\Mail\ForgotPass;
use App\Services\Auth\AuthSocialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;

class AuthController extends Controller
{
    private $authSocialService;
    public function __construct(AuthSocialService $authSocialService)
    {
        $this->authSocialService = $authSocialService;
    }

    public function register()
    {
        return view('site.auth.register');
    }

    public function complete_registration($token)
    {
        $user = User::where('verify_token', $token)->first();

        return view('site.auth.complete-register', compact('user'));
    }

    public function signin($jobappliid = null)
    {
        $search_job = array();
        $joblistdata = array();
        $extra = '';
        if (!empty(session()->get('search_job'))) {
            $search_job = session()->get('search_job');
            $extra = session()->get('frompage');
            session()->forget('search_job');
        }
        $joblistdata = session()->get('jobapply');
        return view('site.auth.login', compact('search_job', 'extra', 'joblistdata', 'jobappliid'));
    }

    public function registration(Request $req)
    {
        $inputs = $req->all();
        $rules = [
            'fullname'  => 'required|min:3|max:50',
            'email_id'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'min:8|required',
        ];
        $validator = \Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }
        $fullname = $req->input('fullname');
        $first_name = $last_name = '';
        if ($fullname != '') {
            $explode = explode(' ', $fullname);
            $first_name = $explode[0];
            if (array_key_exists('1', $explode)) {
                $last_name = $explode[1];
            }
        }
        $user                 = new User();
        $user->name           = $req->input('fullname');
        $user->first_name     = $first_name;
        $user->last_name      = $last_name;
        $user->email          = $req->input('email_id');
        $user->user_type      = $req->input('usertype');
        $user->mobile         = $req->input('phone_number', null);
        $user->user_key       = 'JP' . rand(100, 999) . date("his");
        $user->password       = Hash::make($req->input('password'));
        $user->remember_token = Str::random(10);
        $user->verify_token   = Str::random(12) . rand(10000, 99999);
        $user->save();
        if ($user) {
            if ($req->input('usertype') === "employer") {
                $user->syncRoles([2]);
                $user_profile          = new Employer();
                $user_profile->user_id = $user->id;
                $user_profile->save();
            }
            if ($req->input('usertype') === "candidate") {
                $user->syncRoles([3]);
                $user_profile          = new Candidate();
                $user_profile->user_id = $user->id;
                $user_profile->save();
            }
            if ($req->input('usertype') === "coach") {
                $user->syncRoles([4]);
                $user_profile          = new Coach();
                $user_profile->user_id = $user->id;
                $user_profile->save();
            }
            Mail::to($user->email)->send(new VerifyUser($user));
            Mail::to($user->email)->send(new Welcome($user, $req->input('password')));
            Auth::login($user);
            if (Auth::check()) {
                return response()->json(['code' => 200, 'msg' => 'Registration completed successfully. Please check your registerd email for email verification']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'User Created.Login First.']);
            }
        } else {
            return response()->json(['code' => 500, 'msg' => 'OOPS! User Not Created.']);
        }
    }
    public function registration_complete(Request $req)
    {
        $inputs = $req->all();
        $rules = [
            'fullname'          => 'required|min:3|max:50',
            'email_id'          => 'required|email|unique:users,email,'.$req->get('id'),
            'password'          => 'required|min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password'  => 'min:8|required'
        ];
        $validator = \Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }
        $fullname = $req->input('fullname');
        $first_name = $last_name = '';
        if ($fullname != '') {
            $explode = explode(' ', $fullname);
            $first_name = $explode[0];
            if (array_key_exists('1', $explode)) {
                $last_name = $explode[1];
            }
        }
        $user                 = User::find($req->get('id'));
        $user->name           = $req->input('fullname');
        $user->first_name     = $first_name;
        $user->last_name      = $last_name;
        $user->email          = $req->input('email_id');
        $user->user_type      = $req->input('usertype');
        $user->password       = Hash::make($req->input('password'));
        $user->verify_token   = Str::random(12) . rand(10000, 99999);
        $user->save();
        if ($user) {
            Mail::to($user->email)->send(new VerifyUser($user));
            Mail::to($user->email)->send(new Welcome($user, $req->input('password')));
            Auth::login($user);
            if (Auth::check()) {
                return response()->json(['code' => 200, 'msg' => 'Registration completed successfully. Please check your registerd email for email verification']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'User Created.Login First.']);
            }
        } else {
            return response()->json(['code' => 500, 'msg' => 'OOPS! User Not Created.']);
        }
    }
    public function loginCheck(Request $req)
    {
        $inputs = $req->all();
        $joblistdata = array();
        $rules = [
            'email_id'     => 'required|email',
            'password'  => 'required'
        ];
        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }
        $select = array('id', 'password', 'name', 'user_key', 'email', 'mobile', 'avatar', 'user_type', 'status');
        $userCheck = User::select($select)->where('email', $req->input('email_id'))->first();
        if (empty($userCheck)) {
            return response()->json(['code' => 401, 'msg' => 'Email-ID not registered.']);
        }
        $password_check = Hash::check($req->input('password'), $userCheck->password);
        if (!$password_check) {
            return response()->json(['code' => 401, 'msg' => 'Password mismatched.']);
        } else {
            if ($userCheck->status == '1') {
                if ($userCheck->user_type == 'admin') {
                    return response()->json(['code' => 401, 'msg' => 'Admin credentials can not use in client panel.']);
                } else {
                    if ($req->input('usertype') == $userCheck->user_type) {
                        $credentials = array('email' => $req->input('email_id'), 'password' => $req->input('password'));
                        if (Auth::attempt($credentials)) {
                            if ($req->input('fpm_token', null)) {
                                $user_fpm_token = $req->input('fpm_token', null);
                            } else {
                                $user_fpm_token = "FPM_TokenNotFound";
                            }
                            User::where('id', Auth::user()->id)->update(['fpm_token' => $user_fpm_token]);
                            if (!empty(session()->get('jobapply'))) {
                                $joblistdata = session()->get('jobapply');
                            }
                            session()->forget('jobapply');
                            return response()->json(['code' => 200, 'msg' => 'User Logged in successfully.', 'data' => $joblistdata]);
                        } else {
                            session()->forget('jobapply');
                            return response()->json(['code' => 500, 'msg' => 'Auth Attempt unsuccessful.']);
                        }
                    } else {
                        session()->forget('jobapply');
                        return response()->json(['code' => 401, 'msg' => 'Login Type credentials Not Matched.']);
                    }
                }
            }
            session()->forget('jobapply');
            return response()->json(['code' => 501, 'msg' => 'Account Deactivated']);
        }
    }


    // Codes For Social Login
    public function socialLoginRedirectToProvider($usertype, $provider)
    {
        session(['login_user_type' => $usertype]);
        session(['login_user_provider' => $provider]);
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function socialLoginHandleProviderCallback(Request $request)
    {
        $user_type  = session('login_user_type');
        $provider   = session('login_user_provider');

        if(isset($request->query()['error'])) {
            return redirect('signin')->withErrors($request->query()['error_description']);
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
            $result = $this->authSocialService->handleSocialLogin($user, $user_type, $provider);
            Auth::login($result['user']);
            session()->forget('login_user_type');
            session()->forget('login_user_provider');
            if ($result['user']->user_type == 'coach') {
                return redirect()->to('profile');
            } else {
                return redirect()->to('dashboard');
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('signin')->withSuccess('Logout Successfully.');
    }

    public function forgotPassword()
    {
        return view('site.auth.forgotpassword');
    }

    public function forgotPasswordMailSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailid'      => 'required|email',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors());
        } else {
            $mailcheck = User::where('email', $request->input('emailid'))->first();
            if ($mailcheck) {
                Mail::to($mailcheck->email)->send(new ForgotPass($mailcheck));
                return redirect()->back()->withSuccess("A link has been sent to your mail-id please check.");
            } else {
                return redirect()->back()->withError("Email-ID Not Registered.");
            }
        }
    }

    public function changePass($mail = null)
    {
        $mail = base64_decode($mail);
        return view('site.auth.changepassword', compact('mail'));
    }

    public function changePasswordSave(Request $request)
    {
        $mailcheck = User::where('email', $request->input('emailid'))->first();
        $password = $mailcheck->password;
        $validator = Validator::make($request->all(), [
            'emailid'      => 'required|email',
            'password'  => ['required', 'min:8', 'required_with:confirm_password', 'same:confirm_password'],
            'confirm_password'  => 'min:8|required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors());
        } else {
            if ($mailcheck) {
                $query = User::where('email', $request->input('emailid'))->update([
                    'password' => Hash::make($request->input('password'))
                ]);
                return redirect()->route('signin')->withSuccess("Password Changed Successfully.");
            } else {
                return redirect()->back()->withError("Email-ID Not Registered.");
            }
        }
    }
}
