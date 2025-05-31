<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Models\Coach;
use App\Models\CoachBookmark;
use App\Models\Setting;
use App\Models\Transuction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stripe;
use Validator;

class CoachController extends Controller
{
    public function coachList(Request $request)
    {
        if (Auth::check()) {
            $limit  = 200;
            $offset = ((((int) 0) * $limit));

            $user = Coach::join('users', function ($join) {
                $join->on('coaches.user_id', '=', 'users.id');
            })
                ->where('users.status', '=', 1)
                // ->where('users.verified', '=', 1)
                // ->where('users.is_complete', '=', 1)
                //->where('users.email_verified', '=', 1)
                //->where('users.mobile_verified', '=', 1)
                ->take($limit)
                ->skip($offset)
                ->get();

            $total_coach = Coach::join('users', function ($join) {
                $join->on('coaches.user_id', '=', 'users.id');
            })
                ->where('users.status', '=', 1)
                // ->where('users.verified', '=', 1)
                // ->where('users.is_complete', '=', 1)
                //->where('users.email_verified', '=', 1)
                //->where('users.mobile_verified', '=', 1)
                ->get();

            $total = count($total_coach);
            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'per_page' => $limit,
                'total'    => $total,
                'coaches'  => $user,
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function coachDetails(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'coach_id' => 'required',
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

                $coach = Coach::join('users', function ($join) {
                    $join->on('coaches.user_id', '=', 'users.id');
                })
                    ->where('users.id', $request->input('coach_id'))
                    ->first();

                $bookmarks = CoachBookmark::where(['candidate_id' => Auth::user()->id, 'coach_id' => $request->input('coach_id')])->first();

                if ($bookmarks) {
                    $is_bookmark = 1;
                } else {
                    $is_bookmark = 0;
                }

                $data                            = [];
                $data['id']                      = $coach->user_id;
                $data['address']                 = $coach->address;
                $data['city']                    = $coach->city;
                $data['state']                   = $coach->state;
                $data['country']                 = $coach->country;
                $data['zip']                     = $coach->zip;
                $data['total_experience_year']   = $coach->total_experience_year;
                $data['total_experience_month']  = $coach->total_experience_month;
                $data['alternate_mobile_number'] = $coach->alternate_mobile_number;
                $data['highest_qualification']   = $coach->highest_qualification;
                $data['specialization']          = $coach->specialization;
                $data['university_or_institute'] = $coach->university_or_institute;
                $data['year_of_graduation']      = $coach->year_of_graduation;
                $data['education_type']          = $coach->education_type;
                $data['date_of_birth']           = $coach->date_of_birth;
                $data['coach_type']              = $coach->coach_type;
                $data['preferred_job_type']      = $coach->preferred_job_type;
                $data['gender']                  = $coach->gender;
                $data['marital_status']          = $coach->marital_status;
                $data['resume']                  = $coach->resume;
                $data['resume_title']            = $coach->resume_title;
                $data['linkedin_link']           = $coach->linkedin_link;
                $data['contact_link']            = $coach->contact_link;
                $data['bio']                     = $coach->bio;
                $data['teaching_history']        = $coach->teaching_history;
                $data['active_teaching_history'] = $coach->active_teaching_history;
                $data['job_post_date']           = Carbon::parse($coach->created_at)->format('M d');
                $data['about_us']                = $coach->about_us;
                $data['faq']                     = $coach->faq;
                $data['how_we_help']             = $coach->how_we_help;
                $data['coach_skill']             = $coach->coach_skill;
                $data['skill_details']           = $coach->skill_details;
                $data['name']                    = $coach->name;
                $data['user_key']                = $coach->user_key;
                $data['email']                   = $coach->email;
                $data['mobile']                  = $coach->mobile;
                $data['avatar']                  = $coach->avatar;
                $data['coach_banner']            = $coach->coach_banner;
                $data['is_bookmark']             = $is_bookmark;

                return response()->json([
                    "status"         => true,
                    "message"        => "Success",
                    'coache_details' => $data,
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

    public function bookmarkCoach(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'coach_id' => 'required',
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

                $exist = CoachBookmark::where(['candidate_id' => Auth::user()->id, 'coach_id' => $request->input('coach_id')])->first();

                if ($exist) {
                    $booked = CoachBookmark::where(['candidate_id' => Auth::user()->id, 'coach_id' => $request->input('coach_id')])->delete();
                } else {
                    $booked               = new CoachBookmark;
                    $booked->candidate_id = Auth::user()->id;
                    $booked->coach_id     = $request->input('coach_id');
                    $booked->save();
                }

                if ($booked) {
                    return response()->json([
                        "status"  => true,
                        'message' => "Success.",
                    ], 200);
                } else {
                    return response()->json([
                        "status"     => false,
                        'message'    => "Something went to wrong.",
                        "error_type" => 2,
                    ], 200);
                }
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function bookmarkCoachList(Request $request)
    {
        if (Auth::check()) {

            $limit  = 5;
            $offset = ((((int) $request->page_no - 1) * $limit));

            $bookmark_coaches = CoachBookmark::where('candidate_id', Auth::user()->id)->orderBy('id', 'DESC')
                ->with('coaches')
                ->take($limit)
                ->skip($offset)
                ->get();
            $bookmarks = CoachBookmark::where('candidate_id', Auth::user()->id)
                ->get();

            $total = count($bookmarks);

            if ($bookmark_coaches) {
                return response()->json([
                    "status"           => true,
                    "message"          => "Success.",
                    "bookmark_coaches" => $bookmark_coaches,
                    "per_page"         => $limit,
                    "total"            => $total,
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
                "message"    => "System error please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function profileUpdate(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'first_name'              => 'required',
                'last_name'               => 'required',
                'email'                   => 'required',
                'mobile'                  => 'required|unique:users,mobile,' . Auth::user()->id,
                'address'                 => 'nullable',
                'city'                    => 'nullable',
                'state'                   => 'nullable',
                'country'                 => 'nullable',
                'zip'                     => 'nullable',

                'linkedin_link'           => 'nullable',
                'contact_link'            => 'required',
                'total_experience_year'   => 'nullable',
                'total_experience_month'  => 'nullable',
                'alternate_mobile_number' => 'required|unique:users,alternate_mobile_number,' . Auth::user()->id,
                'highest_qualification'   => 'nullable',
                'specialization'          => 'nullable',
                'university_or_institute' => 'nullable',
                'year_of_graduation'      => 'nullable',
                'education_type'          => 'nullable',
                'date_of_birth'           => 'nullable',
                'coach_type'              => 'nullable',
                'preferred_job_type'      => 'nullable',
                'gender'                  => 'nullable',
                'marital_status'          => 'nullable',
                'resume_title'            => 'nullable',
                'bio'                     => 'nullable',
                'teaching_history'        => 'nullable',
                'active_teaching_history' => 'nullable',
                'about_us'                => 'nullable',
                'faq'                     => 'nullable',
                'how_we_help'             => 'nullable',
                'coach_skill'             => 'nullable',
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
                $user->name       = $request->input('first_name') . " " . $request->input('last_name');
                $user->first_name = $request->input('first_name');
                $user->last_name  = $request->input('last_name');
                if ($user->email != $request->input('email')) {
                    $user->email_verified = 0;
                }
                if ($user->mobile != $request->input('mobile')) {
                    $user->mobile_verified = 0;
                }
                $user->email       = $request->input('email');
                $user->mobile      = $request->input('mobile');
                $user->is_complete = 1;
                $user->save();
                if ($user->email_verified == 0) {
                    Mail::to($user->email)->send(new VerifyUser($user));
                }

                $coach = Coach::where('user_id', Auth::user()->id)->update([
                    'address'                 => $request->input('address'),
                    'city'                    => $request->input('city'),
                    'state'                   => $request->input('state'),
                    'country'                 => $request->input('country'),
                    'zip'                     => $request->input('zip'),
                    'linkedin_link'           => $request->input('linkedin_link'),
                    'contact_link'            => $request->input('contact_link'),

                    'total_experience_year'   => $request->input('total_experience_year'),
                    'total_experience_month'  => $request->input('total_experience_month'),
                    'alternate_mobile_number' => $request->input('alternate_mobile_number'),
                    'highest_qualification'   => $request->input('highest_qualification'),
                    'specialization'          => $request->input('specialization'),
                    'university_or_institute' => $request->input('university_or_institute'),
                    'year_of_graduation'      => $request->input('year_of_graduation'),
                    'education_type'          => $request->input('education_type'),
                    'date_of_birth'           => $request->input('date_of_birth'),
                    'coach_type'              => $request->input('coach_type'),
                    'preferred_job_type'      => $request->input('preferred_job_type'),
                    'gender'                  => $request->input('gender'),
                    'marital_status'          => $request->input('marital_status'),
                    'resume_title'            => $request->input('resume_title'),
                    'bio'                     => $request->input('bio'),
                    'teaching_history'        => $request->input('teaching_history'),
                    'active_teaching_history' => $request->input('active_teaching_history'),
                    'about_us'                => $request->input('about_us'),
                    'how_we_help'             => $request->input('how_we_help'),
                    'faq'                     => $request->input('faq'),
                    'coach_skill'             => $request->input('coach_skill'),
                    'skill_details'           => $request->input('skill_details'),
                    'coach_type'              => "Experience",
                    'preferred_job_type'      => "Full time",
                    'gender'                  => 'Male',
                    "marital_status"          => 'Married',
                ]);
                $user = User::where('id', Auth::user()->id)->first();

                $coach = Coach::where('user_id', Auth::user()->id)->first();
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

                        'address'                 => $coach->address,
                        'city'                    => $coach->city,
                        'state'                   => $coach->state,
                        'country'                 => $coach->country,
                        'zip'                     => $coach->zip,

                        'total_experience_year'   => $coach->total_experience_year,
                        'total_experience_month'  => $coach->total_experience_month,
                        'alternate_mobile_number' => $coach->alternate_mobile_number,
                        'highest_qualification'   => $coach->highest_qualification,
                        'specialization'          => $coach->specialization,
                        'university_or_institute' => $coach->university_or_institute,
                        'year_of_graduation'      => $coach->year_of_graduation,
                        'education_type'          => $coach->education_type,
                        'date_of_birth'           => $coach->date_of_birth,
                        'coach_type'              => $coach->coach_type,
                        'preferred_job_type'      => $coach->preferred_job_type,
                        'gender'                  => $coach->gender,
                        'marital_status'          => $coach->marital_status,
                        'resume'                  => $coach->resume,
                        'resume_title'            => $coach->resume_title,
                        'linkedin_link'           => $coach->linkedin_link,
                        'contact_link'            => $coach->contact_link,
                        'bio'                     => $coach->bio,
                        'about_us'                => $coach->about_us,
                        'faq'                     => $coach->faq,
                        'how_we_help'             => $coach->how_we_help,
                        'coach_banner'            => $coach->coach_banner,
                        'coach_skill'             => $coach->coach_skill,
                        'skill_details'           => $coach->skill_details,

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
}
