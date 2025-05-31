<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Mail\Welcome;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use stdClass;
use Validator;

class AuthController extends Controller
{
    public function linkedinToken(Request $request)
    {

        // $endpoint = "https: //www.linkedin.com/oauth/v2/accessToken";
        // $client   = new \GuzzleHttp\Client();

        // $response = $client->request('POST', $endpoint, ['query' => [
        //     'grant_type'    => "client_credentials",
        //     'client_id'     => "78up4a4gatbkgm",
        //     'client_secret' => "773M6d9rlZtAO04W",
        // ]]);

        // $statusCode     = $response->getStatusCode();
        // return $content = $response->getBody();

        // or when your server returns json
        // $content = json_decode($response->getBody(), true);

        $newFormat = array(
            "grant_type"    => "client_credentials",
            "client_id"     => "86nairewghkj5a",
            "client_secret" => "siLVTZka0xXMuZnz",
        );

        $array_to_json = json_encode($newFormat);
        $curl          = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://www.linkedin.com/oauth/v2/accessToken',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => "$array_to_json",
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Cache-Control: no-cache',
                'Content-Type: x-www-form-urlencoded',

            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_mobile_no' => 'required',
            'password'           => 'required',
            'login_type'         => 'required',
            'fpm_token'          => 'nullable|string',
        ]);

        if (filter_var($request->input('email_or_mobile_no'), FILTER_VALIDATE_EMAIL)) {
            $login_type = "email";
        } else {
            $login_type = "mobile";
        }
        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"     => false,
                "message"    => 'validation error',
                "error"      => $error,
                "error_type" => 1,
            ], 200);

        } else {
            $credentials = array(
                $login_type => $request->input('email_or_mobile_no'),
                'password'  => $request->input('password'),
                'status'    => true,
            );
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                if (Auth::user()->mobile_verified == 0) {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Please verify your mobile number.",
                        "mobile_no"  => Auth::user()->mobile,
                        "error_type" => 2,
                    ], 200);
                }
                if (Auth::user()->email_verified == 0) {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Please verify your email.",
                        "error_type" => 2,
                    ], 200);
                }
                if (Auth::user()->status == 0) {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Your not verified please contact your admin.",
                        "error_type" => 2,
                    ], 200);
                }
                if (Auth::user()->email_verified == 1 && Auth::user()->mobile_verified == 1 && Auth::user()->status == 1) {
                    $_token       = $user->createToken('Shibsankar')->accessToken;
                    $data         = new stdClass;
                    $data->_token = $_token;
                    $data->user   = $user;

                    if ($user->user_type != $request->input('login_type')) {
                        return response()->json([
                            "status"     => false,
                            "message"    => "Please insert a valid credential. You are not a " . $request->input('login_type'),
                            "error_type" => 2,
                        ], 200);

                    }
                    $user = Auth::user();
                    if ($request->input('fpm_token')) {
                        $user->fpm_token = $request->input('fpm_token');

                    } else {
                        $user->fpm_token = "FPM_TokenNotFound";

                    }

                    $user->save();

                    if ($user->user_type == "candidate") {

                        $profile       = Candidate::where('user_id', '=', $user->id)->first();
                        $data->profile = $profile;

                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $data->_token,
                            "user"    => [

                                "user_id"                 => $user->id,
                                "first_name"              => $user->last_name,
                                "last_name"               => $user->last_name,
                                "name"                    => $user->name,
                                "email"                   => $user->email,
                                "mobile"                  => $user->mobile,
                                "user_key"                => $user->user_key,
                                "fpm_token"               => $user->fpm_token,
                                "avatar"                  => $user->avatar,
                                "user_type"               => $user->user_type,
                                "status"                  => $user->status,
                                "is_complete"             => $user->is_complete,
                                "email_verified"          => $user->email_verified,
                                "mobile_verified"         => $user->mobile_verified,

                                'address'                 => $profile->address,
                                'city'                    => $profile->city,
                                'state'                   => $profile->state,
                                'country'                 => $profile->country,
                                'zip'                     => $profile->zip,
                                'total_experience_year'   => $profile->total_experience_year,
                                'total_experience_month'  => $profile->total_experience_month,
                                'alternate_mobile_number' => $profile->alternate_mobile_number,
                                'highest_qualification'   => $profile->highest_qualification,
                                'specialization'          => $profile->specialization,
                                'university_or_institute' => $profile->university_or_institute,
                                'year_of_graduation'      => $profile->year_of_graduation,
                                'education_type'          => $profile->education_type,
                                'date_of_birth'           => $profile->date_of_birth,
                                'candidate_type'          => $profile->candidate_type,
                                'preferred_job_type'      => $profile->preferred_job_type,
                                'gender'                  => $profile->gender,
                                'marital_status'          => $profile->marital_status,
                                'resume'                  => $profile->resume_path,
                                'nationality'             => $profile->nationality,
                                'current_salary'          => $profile->current_salary,
                                'expected_salary'         => $profile->expected_salary,
                                'salary_currency'         => $profile->salary_currency,
                                'resume_title'            => $profile->resume_title,
                                'linkedin_link'           => $profile->linkedin_link,
                                'bio'                     => $profile->bio,
                                'languages'               => $profile->languages,
                                'cover_letter'            => $profile->cover_letter_path,



                            ],

                        ], 200);

                        //  return new CandidateResource($data);

                    }
                    if ($user->user_type == "coach") {
                        $profile       = Coach::where('user_id', '=', $user->id)->first();
                        $data->profile = $profile;
                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $data->_token,
                            "user"    => [
                                "user_id"                 => $user->id,
                                "name"                    => $user->name,
                                "email"                   => $user->email,
                                "mobile"                  => $user->mobile,
                                "user_key"                => $user->user_key,
                                "fpm_token"               => $user->fpm_token,
                                "avatar"                  => $user->avatar,
                                "user_type"               => $user->user_type,
                                "status"                  => $user->status,
                                "is_complete"             => $user->is_complete,
                                "email_verified"          => $user->email_verified,
                                "mobile_verified"         => $user->mobile_verified,

                                'address'                 => $profile->address,
                                'city'                    => $profile->city,
                                'state'                   => $profile->state,
                                'country'                 => $profile->country,
                                'zip'                     => $profile->zip,

                                'total_experience_year'   => $profile->total_experience_year,
                                'total_experience_month'  => $profile->total_experience_month,
                                'alternate_mobile_number' => $profile->alternate_mobile_number,
                                'highest_qualification'   => $profile->highest_qualification,
                                'specialization'          => $profile->specialization,
                                'university_or_institute' => $profile->university_or_institute,
                                'year_of_graduation'      => $profile->year_of_graduation,
                                'education_type'          => $profile->education_type,
                                'date_of_birth'           => $profile->date_of_birth,
                                'coach_type'              => $profile->coach_type,
                                'preferred_job_type'      => $profile->preferred_job_type,
                                'gender'                  => $profile->gender,
                                'marital_status'          => $profile->marital_status,
                                'resume'                  => $profile->resume,
                                'resume_title'            => $profile->resume_title,
                                'linkedin_link'           => $profile->linkedin_link,
                                'bio'                     => $profile->bio,
                                'about_us'                => $profile->about_us,
                                'faq'                     => $profile->faq,
                                'how_we_help'             => $profile->how_we_help,
                                'coach_banner'            => $profile->coach_banner,
                                'one_month_free_status'   => 0,
                                'subscription_status'     => 0,
                            ],

                        ], 200);
                        //return new CoachResource($data);

                    }
                    if ($user->user_type == "employer") {
                        $profile       = Employer::where('user_id', '=', $user->id)->first();
                        $data->profile = $profile;
                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $data->_token,
                            "user"    => [
                                "user_id"             => $user->id,
                                "name"                => $user->name,
                                "email"               => $user->email,
                                "mobile"              => $user->mobile,
                                "user_key"            => $user->user_key,
                                "fpm_token"           => $user->fpm_token,
                                "avatar"              => $user->avatar,
                                "user_type"           => $user->user_type,
                                "status"              => $user->status,
                                "is_complete"         => $user->is_complete,
                                "email_verified"      => $user->email_verified,
                                "mobile_verified"     => $user->mobile_verified,

                                'address'             => $profile->address,
                                'city'                => $profile->city,
                                'state'               => $profile->state,
                                'country'             => $profile->country,
                                'zip'                 => $profile->zip,

                                'industry_id'         => $profile->industry_id,
                                'company_ceo'         => $profile->company_ceo,
                                'number_of_employees' => $profile->number_of_employees,
                                'established_in'      => $profile->established_in,
                                'fax'                 => $profile->fax,
                                'facebook_address'    => $profile->facebook_address,
                                'twitter'             => $profile->twitter,
                                'ownership_type'      => $profile->ownership_type,
                                'phone_number'        => $profile->phone_number,
                                'company_name'        => $profile->company_name,
                                'company_logo'        => $profile->company_logo,
                                'company_details'     => $profile->company_details,
                                'linkedin_link'       => $profile->linkedin_link,
                                'website_link'        => $profile->website_link,
                                'one_month_free_status'   => 0,
                                'subscription_status' => 0,
                                'job_post_balance'    => 0,

                            ],

                        ], 200);
                        //return new EmployerResource($data);

                    }

                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Please verify your account.",
                        "error_type" => 2,
                    ], 200);

                }

            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "Please insert a valid credential.",
                    "error_type" => 2,
                ], 200);
            }
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'   => 'required|string|max:150',
            'last_name'    => 'required|string|max:150',
            'email'        => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric|digits_between:10,14|unique:users,mobile',
            'password'     => 'required|string|min:6|max:50',
            'user_type'    => 'required|string',
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
        $user                 = new User();
        $user->name           = $request->input('first_name') . " " . $request->input('last_name');
        $user->first_name     = $request->input('first_name');
        $user->last_name      = $request->input('last_name');
        $user->email          = $request->input('email');
        $user->user_type      = $request->input('user_type');
        $user->mobile         = $request->input('phone_number');
        $user->user_key       = 'JP' . rand(100, 999) . date("his");
        $user->password       = bcrypt($request->input('password'));
        $user->remember_token = Str::random(10);
        $user->verify_token   = Str::random(12) . rand(10000, 99999);
        $user->save();
        if ($user) {

            if ($request->input('user_type') === "employer") {
                $user->syncRoles([2]);
                $user_profile          = new Employer();
                $user_profile->user_id = $user->id;
                $user_profile->save();
            }
            if ($request->input('user_type') === "candidate") {
                $user->syncRoles([3]);
                $user_profile          = new Candidate();
                $user_profile->user_id = $user->id;
                $user_profile->save();
            }
            if ($request->input('user_type') === "coach") {
                $user->syncRoles([4]);
                $user_profile          = new Coach();
                $user_profile->user_id = $user->id;
                $user_profile->save();

            }

            Mail::to($user->email)->send(new VerifyUser($user));
            Mail::to($user->email)->send(new Welcome($user, $request->input('password')));

            return response()->json([
                "status"    => true,
                "mobile_no" => $request->input('phone_number'),
                "message"   => "Registration completed successfully. Please check your registerd email for email verification",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "Registration Failed.",
                "error_type" => 2,
            ], 200);
        }
    }

    public function emailExist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
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

        $email = User::where('email', '=', $request->input('email'))->first();
        if ($email === null) {
            return response()->json([
                "status"     => false,
                "message"    => "Email Does not Exist.",
                "error_type" => 2,
            ], 200);

        } else {
            return response()->json([
                "status"  => true,
                "message" => "Email Allready Exist.",
            ], 200);

        }
    }

    public function mobileExist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => 'required|numeric|digits_between:10,14',
        ]);
        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"  => false,
                "message" => 'validation error',
                "error"   => $error,
            ], 200);

        }
        $user = User::where('mobile', '=', $request->input('mobile'))->first();
        if ($user === null) {
            return response()->json([
                "status"     => false,
                "message"    => "Mobile Number Does not Exist.",
                "error_type" => 2,
            ], 200);

        } else {
            return response()->json([
                "status"  => true,
                "message" => "Mobile Nomber Allready Exist.",
            ], 200);
        }

    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();

        }
        return response()->json([
            "status"  => true,
            "message" => "Successfully Logged out",
        ], 200);
    }

    public function socialMediaLinkedinLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|min:1',
            'last_name'      => 'required|min:1',
            'email'          => 'required|email',
            'provider_id'    => 'nullable',
            'provider'       => 'nullable',
            'provider_token' => 'nullable',
            'fpm_token'      => 'nullable',
            'user_type'      => 'required|string',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"  => false,
                "message" => 'validation error',
                "error"   => $error,
            ], 200);

        } else {
            $userdata = User::where(['email' => $request->email, 'user_type' => $request->user_type])->first();
            // ->orWhere(['provider_id' => $request->provider_id])->first();
            if ($userdata) {
                $user = User::where(['email' => $request->email, 'user_type' => $request->user_type])->first();
                //   ->orWhere(['provider_id' => $request->provider_id])->first();
                $user->fpm_token = $request->fpm_token;
                if ($request->hasFile('avatar')) {
                    if ($user->avatar) {
                        if (File::exists("uploads/users/" . $user->path)) {
                            File::delete("uploads/users/" . $user->path);
                        }
                    }
                    $time      = Carbon::now();
                    $file      = $request->file('avatar');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                    $img       = Image::make($file->getRealPath());
                    $img->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save('uploads/users/' . $filename);
                    $user->avatar = $filename;
                }
                $user->save();

                if ($user->user_type == "candidate") {
                    $profile = Candidate::where('user_id', '=', $user->id)->first();

                    return response()->json([
                        "status"  => true,
                        "message" => "You Have Successfully Logged in",
                        "_token"  => $user->createToken('Shibsankar')->accessToken,
                        "user"    => [

                            "user_id"                 => $user->id,
                            "name"                    => $user->name,
                            "first_name"              => $user->first_name,
                            "last_name"               => $user->last_name,
                            "email"                   => $user->email,
                            "mobile"                  => $user->mobile,
                            "user_key"                => $user->user_key,
                            "fpm_token"               => $user->fpm_token,
                            "avatar"                  => $user->avatar,
                            "user_type"               => $user->user_type,
                            "status"                  => $user->status,
                            "is_complete"             => $user->is_complete,
                            "email_verified"          => $user->email_verified,
                            "mobile_verified"         => $user->mobile_verified,

                            'address'                 => $profile->address,
                            'city'                    => $profile->city,
                            'state'                   => $profile->state,
                            'country'                 => $profile->country,
                            'zip'                     => $profile->zip,
                            'total_experience_year'   => $profile->total_experience_year,
                            'total_experience_month'  => $profile->total_experience_month,
                            'alternate_mobile_number' => $profile->alternate_mobile_number,
                            'highest_qualification'   => $profile->highest_qualification,
                            'specialization'          => $profile->specialization,
                            'university_or_institute' => $profile->university_or_institute,
                            'year_of_graduation'      => $profile->year_of_graduation,
                            'education_type'          => $profile->education_type,
                            'date_of_birth'           => $profile->date_of_birth,
                            'candidate_type'          => $profile->candidate_type,
                            'preferred_job_type'      => $profile->preferred_job_type,
                            'gender'                  => $profile->gender,
                            'marital_status'          => $profile->marital_status,
                            'resume'                  => $profile->resume,
                            'nationality'             => $profile->nationality,
                            'current_salary'          => $profile->current_salary,
                            'expected_salary'         => $profile->expected_salary,
                            'salary_currency'         => $profile->salary_currency,
                            'resume_title'            => $profile->resume_title,
                            'linkedin_link'           => $profile->linkedin_link,
                            'bio'                     => $profile->bio,
                            'languages'               => $profile->languages,

                        ],

                    ], 200);

                    //  return new CandidateResource($data);

                }
                if ($user->user_type == "coach") {
                    $profile = Coach::where('user_id', '=', $user->id)->first();

                    return response()->json([
                        "status"  => true,
                        "message" => "You Have Successfully Logged in",
                        "_token"  => $user->createToken('Shibsankar')->accessToken,
                        "user"    => [
                            "user_id"                 => $user->id,
                            "name"                    => $user->name,
                            "email"                   => $user->email,
                            "mobile"                  => $user->mobile,
                            "user_key"                => $user->user_key,
                            "fpm_token"               => $user->fpm_token,
                            "avatar"                  => $user->avatar,
                            "user_type"               => $user->user_type,
                            "status"                  => $user->status,
                            "is_complete"             => $user->is_complete,
                            "email_verified"          => $user->email_verified,
                            "mobile_verified"         => $user->mobile_verified,

                            'address'                 => $profile->address,
                            'city'                    => $profile->city,
                            'state'                   => $profile->state,
                            'country'                 => $profile->country,
                            'zip'                     => $profile->zip,

                            'total_experience_year'   => $profile->total_experience_year,
                            'total_experience_month'  => $profile->total_experience_month,
                            'alternate_mobile_number' => $profile->alternate_mobile_number,
                            'highest_qualification'   => $profile->highest_qualification,
                            'specialization'          => $profile->specialization,
                            'university_or_institute' => $profile->university_or_institute,
                            'year_of_graduation'      => $profile->year_of_graduation,
                            'education_type'          => $profile->education_type,
                            'date_of_birth'           => $profile->date_of_birth,
                            'coach_type'              => $profile->coach_type,
                            'preferred_job_type'      => $profile->preferred_job_type,
                            'gender'                  => $profile->gender,
                            'marital_status'          => $profile->marital_status,
                            'resume'                  => $profile->resume,
                            'resume_title'            => $profile->resume_title,
                            'linkedin_link'           => $profile->linkedin_link,
                            'bio'                     => $profile->bio,

                        ],

                    ], 200);
                    //return new CoachResource($data);

                }
                if ($user->user_type == "employer") {
                    $profile = Employer::where('user_id', '=', $user->id)->first();

                    return response()->json([
                        "status"  => true,
                        "message" => "You Have Successfully Logged in",
                        "_token"  => $user->createToken('Shibsankar')->accessToken,
                        "user"    => [
                            "user_id"             => $user->id,
                            "name"                => $user->name,
                            "email"               => $user->email,
                            "mobile"              => $user->mobile,
                            "user_key"            => $user->user_key,
                            "fpm_token"           => $user->fpm_token,
                            "avatar"              => $user->avatar,
                            "user_type"           => $user->user_type,
                            "status"              => $user->status,
                            "is_complete"         => $user->is_complete,
                            "email_verified"      => $user->email_verified,
                            "mobile_verified"     => $user->mobile_verified,
                            'address'             => $profile->address,
                            'city'                => $profile->city,
                            'state'               => $profile->state,
                            'country'             => $profile->country,
                            'zip'                 => $profile->zip,
                            'industry_id'         => $profile->industry_id,
                            'company_ceo'         => $profile->company_ceo,
                            'number_of_employees' => $profile->number_of_employees,
                            'established_in'      => $profile->established_in,
                            'fax'                 => $profile->fax,
                            'facebook_address'    => $profile->facebook_address,
                            'twitter'             => $profile->twitter,
                            'ownership_type'      => $profile->ownership_type,
                            'phone_number'        => $profile->phone_number,
                            'company_details'     => $profile->company_details,
                            'linkedin_link'       => $profile->linkedin_link,
                            'website_link'        => $profile->website_link,

                        ],

                    ], 200);
                    //return new EmployerResource($data);

                }

            } else {

                $userdata = User::where('email', $request->email)->first();
                if ($userdata) {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Registration Failed. You allredy registred as a " . $userdata->user_type . ".",
                        "error_type" => 2,
                    ], 200);
                }

                $user             = new User();
                $user->name       = $request->input('first_name') . " " . $request->input('last_name');
                $user->first_name = $request->input('first_name');
                $user->last_name  = $request->input('last_name');
                $user->email      = $request->input('email');
                $user->user_type  = $request->input('user_type');
                // $user->mobile         = $request->input('phone_number');
                $user->user_key       = 'JP' . rand(100, 999) . date("his");
                $user->password       = bcrypt($request->input('password'));
                $user->remember_token = Str::random(10);
                $user->verify_token   = Str::random(12) . rand(10000, 99999);
                $user->email_verified = 1;
                $user->provider       = 'Linkedin';
                $user->fpm_token      = $request->input('fpm_token');

                if ($request->hasFile('avatar')) {
                    if ($user->avatar) {
                        if (File::exists("uploads/users/" . $user->path)) {
                            File::delete("uploads/users/" . $user->path);
                        }
                    }
                    $time      = Carbon::now();
                    $file      = $request->file('avatar');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                    $img       = Image::make($file->getRealPath());
                    $img->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save('uploads/users/' . $filename);
                    $user->avatar = $filename;
                }

                $user->save();
                if ($user) {

                    if ($request->input('user_type') === "employer") {
                        $user->syncRoles([2]);
                        $user_profile          = new Employer();
                        $user_profile->user_id = $user->id;
                        $user_profile->save();
                    }
                    if ($request->input('user_type') === "candidate") {
                        $user->syncRoles([3]);
                        $user_profile          = new Candidate();
                        $user_profile->user_id = $user->id;
                        $user_profile->save();
                    }
                    if ($request->input('user_type') === "coach") {
                        $user->syncRoles([4]);
                        $user_profile          = new Coach();
                        $user_profile->user_id = $user->id;
                        $user_profile->save();

                    }

                    if ($user->user_type == "candidate") {
                        $profile = Candidate::where('user_id', '=', $user->id)->first();

                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $user->createToken('Shibsankar')->accessToken,
                            "user"    => [

                                "user_id"                 => $user->id,
                                "name"                    => $user->name,
                                "first_name"              => $user->first_name,
                                "last_name"               => $user->last_name,
                                "email"                   => $user->email,
                                "mobile"                  => $user->mobile,
                                "user_key"                => $user->user_key,
                                "fpm_token"               => $user->fpm_token,
                                "avatar"                  => $user->avatar,
                                "user_type"               => $user->user_type,
                                "status"                  => $user->status,
                                "is_complete"             => $user->is_complete,
                                "email_verified"          => $user->email_verified,
                                "mobile_verified"         => $user->mobile_verified,

                                'address'                 => $profile->address,
                                'city'                    => $profile->city,
                                'state'                   => $profile->state,
                                'country'                 => $profile->country,
                                'zip'                     => $profile->zip,
                                'total_experience_year'   => $profile->total_experience_year,
                                'total_experience_month'  => $profile->total_experience_month,
                                'alternate_mobile_number' => $profile->alternate_mobile_number,
                                'highest_qualification'   => $profile->highest_qualification,
                                'specialization'          => $profile->specialization,
                                'university_or_institute' => $profile->university_or_institute,
                                'year_of_graduation'      => $profile->year_of_graduation,
                                'education_type'          => $profile->education_type,
                                'date_of_birth'           => $profile->date_of_birth,
                                'candidate_type'          => $profile->candidate_type,
                                'preferred_job_type'      => $profile->preferred_job_type,
                                'gender'                  => $profile->gender,
                                'marital_status'          => $profile->marital_status,
                                'resume'                  => $profile->resume,
                                'nationality'             => $profile->nationality,
                                'current_salary'          => $profile->current_salary,
                                'expected_salary'         => $profile->expected_salary,
                                'salary_currency'         => $profile->salary_currency,
                                'resume_title'            => $profile->resume_title,
                                'linkedin_link'           => $profile->linkedin_link,
                                'bio'                     => $profile->bio,
                                'languages'               => $profile->languages,

                            ],

                        ], 200);

                        //  return new CandidateResource($data);

                    }

                    if ($user->user_type == "coach") {
                        $profile = Coach::where('user_id', '=', $user->id)->first();
                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $user->createToken('Shibsankar')->accessToken,
                            "user"    => [
                                "user_id"                 => $user->id,
                                "name"                    => $user->name,
                                "first_name"              => $user->first_name,
                                "last_name"               => $user->last_name,
                                "email"                   => $user->email,
                                "mobile"                  => $user->mobile,
                                "user_key"                => $user->user_key,
                                "fpm_token"               => $user->fpm_token,
                                "avatar"                  => $user->avatar,
                                "user_type"               => $user->user_type,
                                "status"                  => $user->status,
                                "is_complete"             => $user->is_complete,
                                "email_verified"          => $user->email_verified,
                                "mobile_verified"         => $user->mobile_verified,

                                'address'                 => $profile->address,
                                'city'                    => $profile->city,
                                'state'                   => $profile->state,
                                'country'                 => $profile->country,
                                'zip'                     => $profile->zip,

                                'total_experience_year'   => $profile->total_experience_year,
                                'total_experience_month'  => $profile->total_experience_month,
                                'alternate_mobile_number' => $profile->alternate_mobile_number,
                                'highest_qualification'   => $profile->highest_qualification,
                                'specialization'          => $profile->specialization,
                                'university_or_institute' => $profile->university_or_institute,
                                'year_of_graduation'      => $profile->year_of_graduation,
                                'education_type'          => $profile->education_type,
                                'date_of_birth'           => $profile->date_of_birth,
                                'coach_type'              => $profile->coach_type,
                                'preferred_job_type'      => $profile->preferred_job_type,
                                'gender'                  => $profile->gender,
                                'marital_status'          => $profile->marital_status,
                                'resume'                  => $profile->resume,
                                'resume_title'            => $profile->resume_title,
                                'linkedin_link'           => $profile->linkedin_link,
                                'bio'                     => $profile->bio,

                            ],

                        ], 200);
                        //return new CoachResource($data);

                    }

                    if ($user->user_type == "employer") {
                        $profile = Employer::where('user_id', '=', $user->id)->first();

                        return response()->json([
                            "status"  => true,
                            "message" => "You Have Successfully Logged in",
                            "_token"  => $user->createToken('Shibsankar')->accessToken,
                            "user"    => [
                                "user_id"             => $user->id,
                                "name"                => $user->name,
                                "first_name"          => $user->first_name,
                                "last_name"           => $user->last_name,
                                "email"               => $user->email,
                                "mobile"              => $user->mobile,
                                "user_key"            => $user->user_key,
                                "fpm_token"           => $user->fpm_token,
                                "avatar"              => $user->avatar,
                                "user_type"           => $user->user_type,
                                "status"              => $user->status,
                                "is_complete"         => $user->is_complete,
                                "email_verified"      => $user->email_verified,
                                "mobile_verified"     => $user->mobile_verified,

                                'address'             => $profile->address,
                                'city'                => $profile->city,
                                'state'               => $profile->state,
                                'country'             => $profile->country,
                                'zip'                 => $profile->zip,

                                'industry_id'         => $profile->industry_id,
                                'company_ceo'         => $profile->company_ceo,
                                'number_of_employees' => $profile->number_of_employees,
                                'established_in'      => $profile->established_in,
                                'fax'                 => $profile->fax,
                                'facebook_address'    => $profile->facebook_address,
                                'twitter'             => $profile->twitter,
                                'ownership_type'      => $profile->ownership_type,
                                'phone_number'        => $profile->phone_number,
                                'company_details'     => $profile->company_details,
                                'linkedin_link'       => $profile->linkedin_link,
                                'website_link'        => $profile->website_link,

                            ],

                        ], 200);
                        //return new EmployerResource($data);

                    }

                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "Registration Failed.",
                        "error_type" => 2,
                    ], 200);
                }

            }
        }

    }

    public function linkedinAccessToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = implode(',', $validator->errors()->all());
            $error  = explode(',', $errors);
            return response()->json([
                "status"  => false,
                "message" => 'validation error',
                "error"   => $error,
            ], 200);

        } else {
            $user = User::where('id', Auth::user()->id)->first();

            if ($user) {

                $user->access_token = $request->access_token;

                if ($user->save()) {
                    return response()->json([
                        "status"  => true,
                        "message" => "Successfully Synced",

                    ], 200);
                }

            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "Registration Failed.",
                    "error_type" => 2,
                ], 200);
            }

        }

    }
}
