<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\Candidate;
use App\Models\WorkExperience;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use Carbon\Carbon;
use File;

class CandidateController extends Controller
{
    public function addEditExperience(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'job_title'           => 'required|string|max:250',
                'company'             => 'required|string|max:250',
                'address'             => 'nullable|string|max:255',
                'details'             => 'required|string',
                'currently_work_here' => 'nullable',
                'from_date'           => 'required|string',
                'end_date'            => 'nullable|string',

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

            if ($request->input('experience_id')) {

                $work_experience                      = WorkExperience::findOrFail($request->input('experience_id'));
                $work_experience->candidate_id        = Auth::user()->id;
                $work_experience->job_title           = $request->input('job_title');
                $work_experience->company             = $request->input('company');
                $work_experience->address             = $request->input('address');
                $work_experience->currently_work_here = $request->input('currently_work_here');
                $work_experience->from_date           = $request->input('from_date');
                $work_experience->end_date            = $request->input('end_date');
                $work_experience->details             = $request->input('details');

                $work_experience->save();
                if ($work_experience) {

                    return response()->json([
                        "status"  => true,
                        "message" => "Successfully update your experience.",
                    ], 200);
                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "System error, please try after sometime.",
                        "error_type" => 2,
                    ], 200);
                }

            } else {
                $work_experience                      = new WorkExperience();
                $work_experience->candidate_id        = Auth::user()->id;
                $work_experience->job_title           = $request->input('job_title');
                $work_experience->company             = $request->input('company');
                $work_experience->address             = $request->input('address');
                $work_experience->currently_work_here = $request->input('currently_work_here');
                $work_experience->from_date           = $request->input('from_date');
                $work_experience->end_date            = $request->input('end_date');
                $work_experience->details             = $request->input('details');

                $work_experience->save();
                if ($work_experience) {

                    return response()->json([
                        "status"  => true,
                        "message" => "Successfully add your experience.",
                    ], 200);
                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "System error, please try after sometime.",
                        "error_type" => 2,
                    ], 200);
                }

            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function removeExperience(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'experience_id' => 'required',

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
            $experience = WorkExperience::findOrFail($request->input('experience_id'));

            $work_experience = $experience->delete();

            if ($work_experience) {

                return response()->json([
                    "status"  => true,
                    "message" => "Record has been Removed",
                ], 200);
            } else {
                return response()->json([
                    "status"     => false,
                    "message"    => "System error, please try after sometime.",
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function skillUpdate(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'skill_id' => 'required|array',
                'skill'    => 'nullable|array',
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

            if ($request->input('skill')) {
                $skills = $request->input('skill');

                foreach ($skills as $key => $val) {
                    $ins              = new Skill;
                    $ins->name        = $val;
                    $ins->description = $val;
                    $ins->status      = 1;
                    $ins->save();

                    UserSkill::insert(
                        ['candidate_id' => Auth::user()->id, 'skill_id' => $ins->id]
                    );

                }

            }

            $candidate_skill = UserSkill::where('candidate_id', Auth::user()->id)->delete();

            $skills_id_arrays = $request->input('skill_id');

            if (count($skills_id_arrays) > 0) {
                foreach ($skills_id_arrays as $key => $value) {
                    UserSkill::insert(
                        ['candidate_id' => Auth::user()->id, 'skill_id' => $value]
                    );

                }

            }

            return response()->json([
                "status"  => true,
                "message" => "Skill update successfully done.",
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function qualificationUpdate(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'qualification_id' => 'required|array',
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

            $candidate_edu = UserEducation::where('candidate_id', Auth::user()->id)->delete();

            $edu_id_arrays = $request->input('qualification_id');

            if (count($edu_id_arrays) > 0) {
                foreach ($edu_id_arrays as $key => $value) {
                    UserEducation::insert(
                        ['candidate_id' => Auth::user()->id, 'qualification_id' => $value]
                    );

                }

            }
            return response()->json([
                "status"  => true,
                "message" => "Qualification update successfully done.",
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function updateResumeCoverLetter(Request $request){
        if (Auth::check()) {
            $user_id   = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'resume'            => 'required|mimes:zip,pdf,doc,docx|max:3072',
                'cover_letter'      => 'nullable|mimes:zip,pdf,doc,docx|max:3072',
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
            }else{
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
                    if ($user->avatar) {
                        if (File::exists("uploads/cover_letter/" . $user->resume_path)) {
                            File::delete("uploads/cover_letter/" . $user->resume_path);
                        }
                    }
                    $time      = Carbon::now();
                    $file      = $request->file('cover_letter');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                    $file->move(public_path('uploads/cover_letter/'), $filename);
                    $user->cover_letter = $filename;
                }
                if($user->save()){
                    $user = User::leftJoin('candidates', function ($join) {
                        $join->on('users.id', '=', 'candidates.user_id');
                    })->first();
                    return response()->json([
                        'data' => [
                            "status"  => true,
                            "message" => "Your Profile has been updated.",
                            "user"    => $user,
                        ],
                    ], 200);
                }else{
                    return response()->json([
                        'data' => [
                            "status"     => false,
                            "message"    => "System error, please try after sometime",
                            "error_type" => 2,
                        ],
                    ], 200);
                }
            }
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function updateCoverLetter(Request $request){
        if (Auth::check()) {
            $user_id   = Auth::user()->id;
            $validator = Validator::make($request->all(), [
                'cover_letter' => 'required'
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
            }else{
                $user = Candidate::where('user_id', $user_id)->first();
                $user->cover_letter = $request->input('cover_letter');
                if($user->save()){
                    $user = User::leftJoin('candidates', function ($join) {
                        $join->on('users.id', '=', 'candidates.user_id');
                    })->first();
                    return response()->json([
                        'data' => [
                            "status"  => true,
                            "message" => "Your Cover Letter has been updated.",
                            "user"    => $user,
                        ],
                    ], 200);
                }else{
                    return response()->json([
                        'data' => [
                            "status"     => false,
                            "message"    => "System error, please try after sometime",
                            "error_type" => 2,
                        ],
                    ], 200);
                }
            }
        }else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }
}
