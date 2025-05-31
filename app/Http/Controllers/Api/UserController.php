<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Setting;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\Language;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use Mail;
use App\Mail\VerifyUser;

class UserController extends Controller
{


    public function GetVersion(Request $request)
    {
        if (Auth::check()) {
            $settings = Setting::where('id', '=', '1')->where('status', '=', '1')->first();
            return response()->json([
                "status"  => true,
                "user_settings"  => $settings,
                "message" => "The account has been deleted successfully.",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime.",
                "error_type" => 2,
            ], 200);
        }
    }


    public function myProfile()
    {
        if (Auth::check()) {

            if (Auth::user()->user_type == "candidate") {
                $profile = Candidate::where('user_id', '=', Auth::user()->id)->first();
                $user    = User::where('id', Auth::user()->id)
                    //->with('skills', 'qualifications')
                    ->first();
                //  return "hello world";
                $uskill    = UserSkill::where('candidate_id', Auth::user()->id)->with('skill')->get();

                $skills    = [];
                $skill_ids = [];

                if (count($uskill) > 0) {
                    foreach ($uskill as $key => $value) {
                        // return  $value->skill->id;
                        if ($value->skill) {
                            $skill_ids[] = $value->skill->id;
                            $skills[]    = $value->skill->name;
                        }
                    }
                }

                $u_qua             = UserEducation::where('candidate_id', Auth::user()->id)->with('qualification')->get();
                $qualifications    = [];
                $qualification_ids = [];
                if (count($u_qua) > 0) {
                    foreach ($u_qua as $key => $value) {
                        $qualification_ids[] = $value->qualification->id;
                        $qualifications[]    = $value->qualification->name;
                    }
                }

                $experience = WorkExperience::where('candidate_id', Auth::user()->id)->get();

                return response()->json([
                    "status"            => true,
                    'message'           => "Candidate Details",
                    'experience'        => $experience,
                    'skills'            => $skills,
                    'skill_ids'         => $skill_ids,
                    'qualifications'    => $qualifications,
                    'qualification_ids' => $qualification_ids,
                    "user"              => [

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
                        'portfolio_link'          => $profile->portfolio_link,
                        'notice_period'           => $profile->notice_period,
                        'visa_status'             => $profile->visa_status,
                        'bio'                     => $profile->bio,
                        'languages'               => $profile->languages,
                        'functional_id'           => intval($profile->functional_id),
                        'cover_letter'            => $profile->cover_letter,


                    ],

                ], 200);

                //  return new CandidateResource($data);

            }

            if (Auth::user()->user_type == "coach") {
                $profile = Coach::where('user_id', '=', Auth::user()->id)->first();
                $user    = Auth::user();
                $coach_banner = NULL;
                if (basename($profile->coach_banner) != 'no_banner.jpg') {
                    $coach_banner = $profile->coach_banner;
                } else {
                    $coach_banner = asset('/uploads/coach_banner/bannernei.jpg');
                }
                return response()->json([
                    "status"  => true,
                    'message' => "Coach Details",
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
                        'facebook_link'           => $profile->facebook_link,
                        'instagram_link'           => $profile->instagram_link,
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
                        'contact_link'            => $profile->contact_link,
                        'bio'                     => $profile->bio,
                        'about_us'                => $profile->about_us,
                        'faq'                     => $profile->faq,
                        'how_we_help'             => $profile->how_we_help,
                        'coach_banner'            => $coach_banner,
                        'coach_skill'             => $profile->coach_skill,
                        'skill_details'           => $profile->skill_details,
                        'one_month_free_status'   => 0,
                        'subscription_status'     => 1,

                    ],

                ], 200);
                //return new CoachResource($data);

            }

            if (Auth::user()->user_type == "employer") {
                $profile = Employer::where('user_id', '=', Auth::user()->id)->first();
                $user    = Auth::user();
                return response()->json([
                    "status"  => true,
                    'message' => "Employer Details",
                    "user"    => [
                        "user_id"               => $user->id,
                        "name"                  => $user->name,
                        "first_name"            => $user->first_name,
                        "last_name"             => $user->last_name,
                        "email"                 => $user->email,
                        "mobile"                => $user->mobile,
                        "user_key"              => $user->user_key,
                        "fpm_token"             => $user->fpm_token,
                        "avatar"                => $user->avatar,
                        "user_type"             => $user->user_type,
                        "status"                => $user->status,
                        "is_complete"           => $user->is_complete,
                        "email_verified"        => $user->email_verified,
                        "mobile_verified"       => $user->mobile_verified,

                        'address'               => $profile->address,
                        'city'                  => $profile->city,
                        'state'                 => $profile->state,
                        'country'               => $profile->country,
                        'zip'                   => $profile->zip,

                        'industry_id'           => $profile->industry_id,
                        'company_ceo'           => $profile->company_ceo,
                        'number_of_employees'   => $profile->number_of_employees,
                        'established_in'        => $profile->established_in,
                        'fax'                   => $profile->fax,
                        'facebook_address'      => $profile->facebook_address,
                        'twitter'               => $profile->twitter,
                        'ownership_type'        => $profile->ownership_type,
                        'phone_number'          => $profile->phone_number,
                        'company_details'       => $profile->company_details,
                        'linkedin_link'         => $profile->linkedin_link,
                        'website_link'          => $profile->website_link,
                        'company_name'          => $profile->company_name,
                        'tag_line'              => $profile->tag_line,
                        'company_logo'          => $profile->company_logo,
                        'one_month_free_status' => 0,
                        'subscription_status'   => 0,
                        'job_post_balance'      => 0,

                    ],

                ], 200);
                //return new EmployerResource($data);

            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error; please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function getProfile(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',

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

            $user = User::where('id', $request->input('user_id'))->first();

            if ($user) {
                if ($user->user_type == "candidate") {
                    $profile = Candidate::where('user_id', '=', $user->id)->first();

                    $uskill    = UserSkill::where('candidate_id', $request->input('user_id'))->with('skill')->get();
                    $skills    = [];
                    $skill_ids = [];

                    if (count($uskill) > 0) {
                        foreach ($uskill as $key => $value) {
                            if ($value->skill) {
                                $skill_ids[] = $value->skill->id;
                                $skills[]    = $value->skill->name;
                            }
                        }
                    }

                    $u_qua             = UserEducation::where('candidate_id', $request->input('user_id'))->with('qualification')->get();
                    $qualifications    = [];
                    $qualification_ids = [];
                    if (count($u_qua) > 0) {
                        foreach ($u_qua as $key => $value) {
                            $qualification_ids[] = $value->qualification->id;
                            $qualifications[]    = $value->qualification->name;
                        }
                    }

                    $experience           = WorkExperience::where('candidate_id', $request->input('user_id'))->get();
                    $functional_area_name = '';
                    if ($profile->functional_id) {
                        $functional_area      = Industry::where('id', $profile->functional_id)->first();
                        $functional_area_name = $functional_area->name;
                    }

                    return response()->json([
                        "status"            => true,
                        'message'           => "Candidate Details",
                        'experience'        => $experience,
                        'skills'            => $skills,
                        'skill_ids'         => $skill_ids,
                        'qualifications'    => $qualifications,
                        'qualification_ids' => $qualification_ids,
                        "user"              => [

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
                            'portfolio_link'          => $profile->portfolio_link,
                            'notice_period'           => $profile->notice_period,
                            'visa_status'             => $profile->visa_status,
                            'bio'                     => $profile->bio,
                            'languages'               => $profile->languages,
                            'functional_id'           => $profile->functional_id,
                            'functional_area_name'    => $functional_area_name,
                            'cover_letter'            => $profile->cover_letter,

                        ],

                    ], 200);

                    //  return new CandidateResource($data);

                }

                if ($user->user_type == "coach") {
                    $profile = Coach::where('user_id', '=', $user->id)->first();

                    return response()->json([
                        "status"  => true,
                        'message' => "Coach Details",
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
                            'facebook_link'           => $profile->facebook_link,
                            'instagram_link'           => $profile->instagram_link,
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
                            'coach_skill'             => $profile->coach_skill,
                            'skill_details'           => $profile->skill_details,

                        ],

                    ], 200);
                    //return new CoachResource($data);

                }

                if ($user->user_type == "employer") {
                    $profile = Employer::where('user_id', '=', $user->id)->first();
                    return response()->json([
                        "status"  => true,
                        'message' => "Employer Details",
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
                            'company_logo'        => $profile->company_logo,
                            'company_name'        => $profile->company_name,
                            'tag_line'            => $profile->tag_line,

                        ],

                    ], 200);
                    //return new EmployerResource($data);

                }
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "Invalid user id",
                    "error_type" => 2,
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error; please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    /*public function profileUpdate(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'first_name'              => 'required',
                'last_name'               => 'required',
                'email'                   => 'required',
                'mobile'                  => 'required',
                'address'                 => 'required',
                'city'                    => 'nullable',
                'state'                   => 'nullable',
                'country'                 => 'nullable',
                'zip'                     => 'nullable',

                'total_experience_year'   => 'nullable',
                'total_experience_month'  => 'nullable',
                'alternate_mobile_number' => 'nullable',
                'highest_qualification'   => 'nullable',
                'functional_id'           => 'nullable',
                'specialization'          => 'nullable',
                'university_or_institute' => 'nullable',
                'year_of_graduation'      => 'nullable',
                'education_type'          => 'nullable',
                'date_of_birth'           => 'nullable',
                'candidate_type'          => 'nullable',
                'preferred_job_type'      => 'nullable',
                'gender'                  => 'nullable',
                'marital_status'          => 'nullable',
                'nationality'             => 'nullable',
                'current_salary'          => 'nullable',
                'expected_salary'         => 'nullable',
                'salary_currency'         => 'nullable',
                'resume_title'            => 'nullable',
                'linkedin_link'           => 'nullable',
                'portfolio_link'          => 'nullable',
                'notice_period'           => 'nullable',
                'visa_status'             => 'nullable',
                'bio'                     => 'nullable',
                'languages'               => 'nullable',
                'skill_details'           => 'nullable',
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

            } else {
                $user             = User::findOrFail(Auth::user()->id);
                $user->first_name = $request->input('first_name');
                $user->last_name  = $request->input('last_name');
                $user->name       = $request->input('first_name') . " " . $request->input('last_name');
                if ($user->email != $request->input('email')) {
                    $user->email_verified = 0;
                }
                if ($user->mobile != $request->input('mobile')) {
                    $user->mobile_verified = 0;
                }
                $user->email  = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user->save();
                if ($user->email_verified == 0) {
                    Mail::to($user->email)->send(new VerifyUser($user));
                }

                $candidate = Candidate::where('user_id', Auth::user()->id)->update([
                    'address'                 => $request->input('address'),
                    'city'                    => $request->input('city'),
                    'state'                   => $request->input('state'),
                    'country'                 => $request->input('country'),
                    'zip'                     => $request->input('zip'),
                    'linkedin_link'           => $request->input('linkedin_link'),
                    'portfolio_link'          => $request->input('portfolio_link'),
                    'notice_period'           => $request->input('notice_period'),
                    'visa_status'             => $request->input('visa_status'),
                    'total_experience_year'   => $request->input('total_experience_year'),
                    'total_experience_month'  => $request->input('total_experience_month'),
                    'alternate_mobile_number' => $request->input('alternate_mobile_number'),
                    'highest_qualification'   => $request->input('highest_qualification'),
                    'functional_id'           => $request->input('functional_id'),
                    'specialization'          => $request->input('specialization'),
                    'university_or_institute' => $request->input('university_or_institute'),
                    'year_of_graduation'      => $request->input('year_of_graduation'),
                    'education_type'          => $request->input('education_type'),
                    'date_of_birth'           => $request->input('date_of_birth'),
                    'candidate_type'          => $request->input('candidate_type'),
                    'preferred_job_type'      => $request->input('preferred_job_type'),
                    'gender'                  => $request->input('gender'),
                    'marital_status'          => $request->input('marital_status'),
                    'resume_title'            => $request->input('resume_title'),
                    'bio'                     => $request->input('bio'),
                    'nationality'             => $request->input('nationality'),
                    'current_salary'          => $request->input('current_salary'),
                    'expected_salary'         => $request->input('expected_salary'),
                    'salary_currency'         => $request->input('salary_currency'),
                    'languages'               => $request->input('languages'),
                ]);

                $lang    = $request->input('languages');
                $myarray = explode(',', $lang);

                if (count($myarray) > 0) {
                    foreach ($myarray as $key => $value) {
                        $lang_have = Language::where('name', '=', $value)->count();
                        if ($lang_have == 0) {
                            $lang         = new Language;
                            $lang->name   = $value;
                            $lang->body   = $value;
                            $lang->status = 1;
                            $lang->save();

                        }

                    }

                }
                $user = User::where('id', Auth::user()->id)->first();

                $profile = Candidate::where('user_id', Auth::user()->id)->first();
                return response()->json([
                    "status"  => true,
                    "message" => "Successfully updated your profile.",
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
                        'portfolio_link'          => $profile->portfolio_link,
                        'notice_period'           => $profile->notice_period,
                        'visa_status'             => $profile->visa_status,
                        'bio'                     => $profile->bio,
                        'languages'               => $profile->languages,
                        'functional_id'           => $profile->functional_id,
                        'cover_letter'            => $profile->cover_letter,

                    ],
                ], 200);

            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function fileUpdate(Request $request)
    {
        $user_id   = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'type'         => 'required',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'coach_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'resume'       => 'nullable|mimes:zip,pdf|max:3072',
            'cover_letter' => 'nullable|mimes:zip,pdf|max:3072',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        if ($request->input('type') == 'avatar') {
            $user = User::findOrFail($user_id);

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
                $file->move(public_path('uploads/users/'), $filename);
                $user->avatar = $filename;
            }
            if ($user->save()) {
                $user = User::where('id', Auth::id())
                    ->first();
                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }

        }

        if ($request->input('type') == 'logo') {
            $user = User::where('id', Auth::id())
                ->first();

            $empleyer = Employer::where('user_id', $user_id)
                ->first();

            if ($request->hasFile('company_logo')) {
                if ($empleyer->company_logo) {
                    if (File::exists("uploads/company_logo/" . $empleyer->path)) {
                        File::delete("uploads/company_logo/" . $empleyer->path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('company_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/company_logo/'), $filename);
                $empleyer->company_logo = $filename;
            }
            if ($empleyer->save()) {
                $user = User::where('id', Auth::id())
                    ->first();
                $empleyer = Employer::where('user_id', $user_id)
                    ->first();

                $data = [];

                $data["user_id"]         = $user->id;
                $data["name"]            = $user->name;
                $data["email"]           = $user->email;
                $data["mobile"]          = $user->mobile;
                $data["user_key"]        = $user->user_key;
                $data["fpm_token"]       = $user->fpm_token;
                $data["avatar"]          = $user->avatar;
                $data["user_type"]       = $user->user_type;
                $data["status"]          = $user->status;
                $data["is_complete"]     = $user->is_complete;
                $data["email_verified"]  = $user->email_verified;
                $data["mobile_verified"] = $user->mobile_verified;

                $data['address'] = $empleyer->address;
                $data['city']    = $empleyer->city;
                $data['state']   = $empleyer->state;
                $data['country'] = $empleyer->country;
                $data['zip']     = $empleyer->zip;

                $data['industry_id']         = $empleyer->industry_id;
                $data['company_ceo']         = $empleyer->company_ceo;
                $data['number_of_employees'] = $empleyer->number_of_employees;
                $data['established_in']      = $empleyer->established_in;
                $data['fax']                 = $empleyer->fax;
                $data['facebook_address']    = $empleyer->facebook_address;
                $data['twitter']             = $empleyer->twitter;
                $data['ownership_type']      = $empleyer->ownership_type;
                $data['phone_number']        = $empleyer->phone_number;
                $data['company_details']     = $empleyer->company_details;
                $data['linkedin_link']       = $empleyer->linkedin_link;
                $data['website_link']        = $empleyer->website_link;
                $data['company_logo']        = $empleyer->company_logo;
                $data['company_name']        = $empleyer->company_name;
                $data['tag_line']            = $empleyer->tag_line;

                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Company logo has been updated.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }

        }

        if ($request->input('type') == 'coach_banner') {
            $user = User::where('id', Auth::id())
                ->first();
            $coach = Coach::where('user_id', $user_id)->first();
            if ($request->hasFile('coach_banner')) {
                if ($coach->coach_banner) {
                    if (File::exists("uploads/coach_banner/" . $coach->coach_banner_path)) {
                        File::delete("uploads/coach_banner/" . $coach->coach_banner_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('coach_banner');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/coach_banner/'), $filename);
                $coach->coach_banner = $filename;
            }

            if ($coach->save()) {
                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        // "user"    => $coach,

                    ],
                ], 200);

            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }
        }

        if ($request->input('type') == 'resume' || $request->input('type') == 'cover_letter') {

            $user = Candidate::where('user_id', $user_id)->first();

            if ($request->hasFile('resume')) {
                if ($user->avatar) {
                    if (File::exists("uploads/resume/" . $user->resume_path)) {
                        File::delete("uploads/resume/" . $user->resume_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('resume');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/resume/'), $filename);
                $user->resume = $filename;
            }

            if ($request->hasFile('cover_letter')) {
                if ($user->cover_letter) {
                    if (File::exists("uploads/cover_letter/" . $user->cover_letter_path)) {
                        File::delete("uploads/cover_letter/" . $user->cover_letter_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('cover_letter');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/cover_letter/'), $filename);
                $user->cover_letter = $filename;
            }

            if ($user->save()) {

                $user = User::leftJoin('candidates', function ($join) {
                    $join->on('users.id', '=', 'candidates.user_id');
                })
                    ->first();

                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }

        }

    }*/
    public function profileUpdate(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'first_name'              => 'required',
                'last_name'               => 'required',
                'email'                   => 'required',
                'mobile'                  => 'required',
                'address'                 => 'required',
                'city'                    => 'nullable',
                'state'                   => 'nullable',
                'country'                 => 'nullable',
                'zip'                     => 'nullable',

                'total_experience_year'   => 'nullable',
                'total_experience_month'  => 'nullable',
                'alternate_mobile_number' => 'nullable',
                'highest_qualification'   => 'nullable',
                'functional_id'           => 'nullable',
                'specialization'          => 'nullable',
                'university_or_institute' => 'nullable',
                'year_of_graduation'      => 'nullable',
                'education_type'          => 'nullable',
                'date_of_birth'           => 'nullable',
                'candidate_type'          => 'nullable',
                'preferred_job_type'      => 'nullable',
                'gender'                  => 'nullable',
                'marital_status'          => 'nullable',
                'nationality'             => 'nullable',
                'current_salary'          => 'nullable',
                'expected_salary'         => 'nullable',
                'salary_currency'         => 'nullable',
                'resume_title'            => 'nullable',
                'linkedin_link'           => 'nullable',
                'portfolio_link'          => 'nullable',
                'notice_period'           => 'nullable',
                'visa_status'             => 'nullable',
                'bio'                     => 'nullable',
                'languages'               => 'nullable',
                'skill_details'           => 'nullable',
                #6-27-suman
                'cover_letter'            => 'nullable',  //cover letter as a txt
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
            } else {
                $user             = User::findOrFail(Auth::user()->id);
                $user->first_name = $request->input('first_name');
                $user->last_name  = $request->input('last_name');
                $user->name       = $request->input('first_name') . " " . $request->input('last_name');
                if ($user->email != $request->input('email')) {
                    $user->email_verified = 0;
                }
                if ($user->mobile != $request->input('mobile')) {
                    $user->mobile_verified = 0;
                }
                $user->email  = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user->save();
                if ($user->email_verified == 0) {
                    Mail::to($user->email)->send(new VerifyUser($user));
                }

                $candidate = Candidate::where('user_id', Auth::user()->id)->update([
                    'address'                 => $request->input('address'),
                    'city'                    => $request->input('city'),
                    'state'                   => $request->input('state'),
                    'country'                 => $request->input('country'),
                    'zip'                     => $request->input('zip'),
                    'linkedin_link'           => $request->input('linkedin_link'),
                    'portfolio_link'          => $request->input('portfolio_link'),
                    'notice_period'           => $request->input('notice_period'),
                    'visa_status'             => $request->input('visa_status'),
                    'total_experience_year'   => $request->input('total_experience_year'),
                    'total_experience_month'  => $request->input('total_experience_month'),
                    'alternate_mobile_number' => $request->input('alternate_mobile_number'),
                    'highest_qualification'   => $request->input('highest_qualification'),
                    'functional_id'           => $request->input('functional_id'),
                    'specialization'          => $request->input('specialization'),
                    'university_or_institute' => $request->input('university_or_institute'),
                    'year_of_graduation'      => $request->input('year_of_graduation'),
                    'education_type'          => $request->input('education_type'),
                    'date_of_birth'           => $request->input('date_of_birth'),
                    'candidate_type'          => $request->input('candidate_type'),
                    'preferred_job_type'      => $request->input('preferred_job_type'),
                    'gender'                  => $request->input('gender'),
                    'marital_status'          => $request->input('marital_status'),
                    'resume_title'            => $request->input('resume_title'),
                    'bio'                     => $request->input('bio'),
                    'nationality'             => $request->input('nationality'),
                    'current_salary'          => $request->input('current_salary'),
                    'expected_salary'         => $request->input('expected_salary'),
                    'salary_currency'         => $request->input('salary_currency'),
                    'languages'               => $request->input('languages'),
                    'cover_letter'            => $request->input('cover_letter', null)
                ]);

                $lang    = $request->input('languages');
                $myarray = explode(',', $lang);

                if (count($myarray) > 0) {
                    foreach ($myarray as $key => $value) {
                        $lang_have = Language::where('name', '=', $value)->count();
                        if ($lang_have == 0) {
                            $lang         = new Language;
                            $lang->name   = $value;
                            $lang->body   = $value;
                            $lang->status = 1;
                            $lang->save();
                        }
                    }
                }
                $user = User::where('id', Auth::user()->id)->first();

                $profile = Candidate::where('user_id', Auth::user()->id)->first();
                return response()->json([
                    "status"  => true,
                    "message" => "Successfully updated your profile.",
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
                        'portfolio_link'          => $profile->portfolio_link,
                        'notice_period'           => $profile->notice_period,
                        'visa_status'             => $profile->visa_status,
                        'bio'                     => $profile->bio,
                        'languages'               => $profile->languages,
                        'functional_id'           => $profile->functional_id,
                        'cover_letter'            => $profile->cover_letter,

                    ],
                ], 200);
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error; please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function fileUpdate(Request $request)
    {
        $user_id   = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'type'                  => 'required',
            'avatar'                => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'company_logo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'coach_banner'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'resume'                => 'nullable|mimes:zip,pdf,doc,docx|max:3072',
            'cover_letter'          => 'nullable|mimes:zip,pdf,doc,docx|max:3072',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        if ($request->input('type') == 'avatar') {
            $user = User::findOrFail($user_id);

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
                $file->move(public_path('uploads/users/'), $filename);
                $user->avatar = $filename;
            }
            if ($user->save()) {
                $user = User::where('id', Auth::id())
                    ->first();
                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error; please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }
        }

        if ($request->input('type') == 'logo') {
            $user = User::where('id', Auth::id())
                ->first();

            $empleyer = Employer::where('user_id', $user_id)
                ->first();

            if ($request->hasFile('company_logo')) {
                if ($empleyer->company_logo) {
                    if (File::exists("uploads/company_logo/" . $empleyer->path)) {
                        File::delete("uploads/company_logo/" . $empleyer->path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('company_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/company_logo/'), $filename);
                $empleyer->company_logo = $filename;
            }
            if ($empleyer->save()) {
                $user = User::where('id', Auth::id())
                    ->first();
                $empleyer = Employer::where('user_id', $user_id)
                    ->first();

                $data = [];

                $data["user_id"]         = $user->id;
                $data["name"]            = $user->name;
                $data["email"]           = $user->email;
                $data["mobile"]          = $user->mobile;
                $data["user_key"]        = $user->user_key;
                $data["fpm_token"]       = $user->fpm_token;
                $data["avatar"]          = $user->avatar;
                $data["user_type"]       = $user->user_type;
                $data["status"]          = $user->status;
                $data["is_complete"]     = $user->is_complete;
                $data["email_verified"]  = $user->email_verified;
                $data["mobile_verified"] = $user->mobile_verified;

                $data['address'] = $empleyer->address;
                $data['city']    = $empleyer->city;
                $data['state']   = $empleyer->state;
                $data['country'] = $empleyer->country;
                $data['zip']     = $empleyer->zip;

                $data['industry_id']         = $empleyer->industry_id;
                $data['company_ceo']         = $empleyer->company_ceo;
                $data['number_of_employees'] = $empleyer->number_of_employees;
                $data['established_in']      = $empleyer->established_in;
                $data['fax']                 = $empleyer->fax;
                $data['facebook_address']    = $empleyer->facebook_address;
                $data['twitter']             = $empleyer->twitter;
                $data['ownership_type']      = $empleyer->ownership_type;
                $data['phone_number']        = $empleyer->phone_number;
                $data['company_details']     = $empleyer->company_details;
                $data['linkedin_link']       = $empleyer->linkedin_link;
                $data['website_link']        = $empleyer->website_link;
                $data['company_logo']        = $empleyer->company_logo;
                $data['company_name']        = $empleyer->company_name;
                $data['tag_line']            = $empleyer->tag_line;

                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Company logo has been updated.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error; please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }
        }

        if ($request->input('type') == 'coach_banner') {
            $user = User::where('id', Auth::id())
                ->first();
            $coach = Coach::where('user_id', $user_id)->first();
            if ($request->hasFile('coach_banner')) {
                if ($coach->coach_banner) {
                    if (File::exists("uploads/coach_banner/" . $coach->coach_banner_path)) {
                        File::delete("uploads/coach_banner/" . $coach->coach_banner_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('coach_banner');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/coach_banner/'), $filename);
                $coach->coach_banner = $filename;
            }

            if ($coach->save()) {
                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        // "user"    => $coach,

                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error; please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }
        }

        if ($request->input('type') == 'resume' || $request->input('type') == 'cover_letter') {

            $user = Candidate::where('user_id', $user_id)->first();

            if ($request->hasFile('resume')) {
                if ($user->avatar) {
                    if (File::exists("uploads/resume/" . $user->resume_path)) {
                        File::delete("uploads/resume/" . $user->resume_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('resume');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/resume/'), $filename);
                $user->resume = $filename;
            }

            if ($request->hasFile('cover_letter')) {
                if ($user->cover_letter) {
                    if (File::exists("uploads/cover_letter/" . $user->cover_letter_path)) {
                        File::delete("uploads/cover_letter/" . $user->cover_letter_path);
                    }
                }
                $time      = Carbon::now();
                $file      = $request->file('cover_letter');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/cover_letter/'), $filename);
                $user->cover_letter = $filename;
            }

            if ($user->save()) {

                $user = User::leftJoin('candidates', function ($join) {
                    $join->on('users.id', '=', 'candidates.user_id');
                })
                    ->first();

                return response()->json([
                    'data' => [
                        "status"  => true,
                        "message" => "Your Profile Picture has been updated. Save other details seperately.",
                        "user"    => $user,
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status"     => false,
                        "message"    => "System error; please try after sometime",
                        "error_type" => 2,
                    ],
                ], 200);
            }
        }
    }
}
