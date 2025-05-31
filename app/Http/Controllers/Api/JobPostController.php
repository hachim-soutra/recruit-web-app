<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\PushNotification;
use App\Mail\JobPostMail;
use App\Mail\JobAppliedMail;
use App\Mail\JobAppliedNotifyMail;
use App\Models\Employer;
use App\Models\Bookmark;
use App\Models\Industry;
use App\Models\JobApply;
use App\Models\JobNotInterested;
use App\Models\JobPost;
use App\Models\JobReport;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\Candidate;
use App\Models\User;
use App\Services\Payment\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class JobPostController extends Controller
{
    public function listing(Request $request)
    {
        if (Auth::check()) {
            $keyword = $request->input("keyword", null);
            $limit   = 300;
            $offset  = ((((int) $request->page_no - 1) * $limit));

            if (Auth::user()->user_type == 'employer') {
                $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay', 'status', 'job_status')->orderBy('id', 'DESC')
                    ->where('status', 1)
                    //->where('created_by', Auth::user()->id)
                    ->where('employer_id', Auth::user()->id)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where(function ($query) use ($keyword) {
                        if ($keyword) {
                            $query->orWhere("job_title", "like", "%" . $keyword . "%");
                        }
                    })
                    ->withCount('applicatons')
                    ->take($limit)
                    ->skip($offset)
                    ->get();

                $job = JobPost::orderBy('id', 'DESC')
                    ->where('created_by', Auth::user()->id)
                    ->where('status', 1)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where('employer_id', Auth::user()->id)
                    ->get();
            }

            if (Auth::user()->user_type == 'candidate') {
                $keyword           = $request->input("keyword");
                $location          = $request->input("location");
                $functional_area   = $request->input("functional_area");
                $salary_range_min  = "";
                $salary_range_max  = "";


                if ($request->input("salary_range_min") && $request->input("salary_range_max")) {
                    if (count($request->input("salary_range_min")) > 0) {
                        $salary_range_min  = min($request->input("salary_range_min"));
                    }
                    if (count($request->input("salary_range_max")) > 0) {
                        $salary_range_max  = max($request->input("salary_range_max"));
                    }
                }



                $prefared_job_type = $request->input("prefared_job_type");

                $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay')->orderBy('id', 'DESC')
                    ->where('status', 1)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where(function ($query) use ($keyword, $location, $functional_area, $salary_range_min, $salary_range_max, $prefared_job_type) {
                        if ($keyword) {
                            $query->orWhere("job_title", "like", "%" . $keyword . "%");
                        }
                        if ($location) {
                            $query->orWhere("job_location", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("state", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("country", "like", "%" . $location . "%");
                        }
                        if ($functional_area) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            $query->orWhereIn("functional_area", $functional_area);
                        }
                        if ($prefared_job_type) {
                            $query->orWhereIn('preferred_job_type', $prefared_job_type);

                            // $query->orWhere("preferred_job_type", "like", "%" . $prefared_job_type . "%");
                        }
                        if ($salary_range_min && $salary_range_max) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            // $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                            //     ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);
                            $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                                ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);
                        }
                    })
                    ->take($limit)
                    ->skip($offset)
                    ->get();

                $job = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay')->orderBy('id', 'DESC')
                    ->where('status', 1)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where(function ($query) use ($keyword, $location, $functional_area, $salary_range_min, $salary_range_max, $prefared_job_type) {
                        if ($keyword) {
                            $query->orWhere("job_title", "like", "%" . $keyword . "%");
                        }
                        if ($location) {
                            $query->orWhere("job_location", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("state", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("country", "like", "%" . $location . "%");
                        }
                        if ($functional_area) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            $query->orWhereIn("functional_area", $functional_area);
                        }
                        if ($prefared_job_type) {
                            $query->orWhereIn('preferred_job_type', $prefared_job_type);

                            // $query->orWhere("preferred_job_type", "like", "%" . $prefared_job_type . "%");
                        }
                        if ($salary_range_min && $salary_range_max) {

                            $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                                ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);
                        }
                    })
                    ->get();
            }


            // if (Auth::user()->user_type == 'candidate') {
            //     $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay', 'status', 'job_status')->orderBy('id', 'DESC')
            //         ->with('employer')
            //         ->where('status', 1)
            //         ->where('job_status', 'Published')
            //         ->whereDate('job_expiry_date', '>', Carbon::now())
            //         ->where(function ($query) use ($keyword) {
            //             if ($keyword) {
            //                 $query->orWhere("title", "like", "%" . $keyword . "%");
            //             }
            //         })
            //     // ->paginate(10);
            //         ->take($limit)
            //         ->skip($offset)
            //         ->get();
            //     $job = JobPost::orderBy('id', 'DESC')
            //         ->where('status', 1)
            //         ->where('job_status', 'Published')
            //         ->whereDate('job_expiry_date', '>', Carbon::now())
            //         ->get();

            // }

            $total = count($job);
            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'per_page' => $limit,
                'total'    => $total,
                'job_list' => $data,
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function draftListing(Request $request)
    {
        if (Auth::check()) {
            $keyword = $request->input("keyword", null);
            $limit   = 5;
            $offset  = ((((int) $request->page_no - 1) * $limit));

            $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay', 'status', 'job_status')->orderBy('id', 'DESC')
                ->where('status', 1)
                ->where('created_by', Auth::user()->id)
                ->where('employer_id', Auth::user()->id)
                ->where('job_status', 'Save as Draft')
                ->whereDate('job_expiry_date', '>', Carbon::now())
                ->where(function ($query) use ($keyword) {
                    if ($keyword) {
                        $query->orWhere("title", "like", "%" . $keyword . "%");
                    }
                })
                ->withCount('applicatons')
                ->take($limit)
                ->skip($offset)
                ->get();

            $job = JobPost::orderBy('id', 'DESC')
                ->where('created_by', Auth::user()->id)
                ->where('status', 1)
                ->where('job_status', 'Save as Draft')
                ->whereDate('job_expiry_date', '>', Carbon::now())
                ->where('employer_id', Auth::user()->id)
                ->get();

            $total = count($job);
            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'per_page' => $limit,
                'total'    => $total,
                'job_list' => $data,
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function JobCreate(Request $request, PaymentService $paymentService)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [

                'job_title'          => 'required',
                'functional_area'    => 'required_without:new_job_type',
                'new_job_type'       => 'required_without:functional_area',
                'job_expiry_date'    => 'required|date|date_format:Y-m-d|after:today',
                'salary_from'        => 'nullable',
                'salary_to'          => 'nullable',
                'salary_currency'    => 'nullable',
                'salary_period'      => 'nullable',
                'hide_salary'        => 'nullable',
                'job_location'       => 'nullable',
                'qualifications'     => 'required|array',
                'city'               => 'nullable',
                'state'              => 'nullable',
                'country'            => 'nullable',
                'zip'                => 'nullable',
                'job_skills'         => 'required|array',
                'new_job_skills'     => 'nullable|array',
                'preferred_job_type' => 'required',
                'experience'         => 'required',
                'total_hire'         => 'required',
                'job_details'        => 'required',
                'additinal_pay'      => 'nullable|array',
                'job_status'         => 'required ',
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

            if ($request->input('new_job_type')) {
                $job_type_have = Industry::where('name', '=', $request->input('new_job_type'))->count();
                if ($job_type_have == 0) {
                    $ins              = new Industry;
                    $ins->name        = $request->input('new_job_type');
                    $ins->description = $request->input('new_job_type');
                    $ins->status      = 1;
                    $ins->save();
                }
            }
            $functional_area = $request->input('functional_area') ? $request->input('functional_area') : $request->input('new_job_type');

            $skill = $request->input('new_job_skills');

            if (count($skill) > 0) {
                foreach ($skill as $key => $val) {
                    $ins              = new Skill;
                    $ins->name        = $val;
                    $ins->description = $val;
                    $ins->status      = 1;
                    $ins->save();
                }
            }

            $user = Auth::user()->with(['employer', 'subscriptions'])->first();

            $post                     = new JobPost;
            $post->job_title          = $request->input('job_title');
            $post->job_location       = $request->input('job_location');
            $post->city               = $request->input('city', null);
            $post->state              = $request->input('state', null);
            $post->country            = $request->input('country', null);
            $post->zip                = $request->input('zip', null);
            $post->job_skills         = json_encode($request->input('job_skills'));
            $post->functional_area    = $functional_area;
            $post->preferred_job_type = $request->input('preferred_job_type', null);
            $post->experience         = $request->input('experience', null);
            $post->total_hire         = $request->input('total_hire', null);
            $post->job_details        = $request->input('job_details', null);
            $post->employer_id        = $user->employer->id;
            $post->subscription_id    = $user->valid_subscription->id;
            $post->job_expiry_date    = $request->input('job_expiry_date');
            $post->salary_from     = $request->input('salary_from');
            $post->salary_to       = $request->input('salary_to');
            $post->salary_currency = $request->input('salary_currency');


            if ($request->input('salary_period')) {
                $post->salary_period = $request->input('salary_period');
            } else {
                $post->salary_period = 'Monthly';
            }

            $post->hide_salary    = $request->input('hide_salary');
            $post->additinal_pay  = json_encode($request->input('additinal_pay'));
            $post->qualifications = json_encode($request->input('qualifications'));
            $post->created_by     = $user->id;
            $post->payment_status = "Paid";

            if ($request->input('job_status') == 'Published') {
                $post->job_status = "Published";
            } else {
                $post->job_status = "Save as Draft";
            }

            // if ($free_balance > 0 && $request->input('job_status') == 'Published') {
            //     $post->job_status = "Published";
            // }
            $post->save();

            $employer = $user->employer;
            $job_post_data                = array();
            $job_post_data['name']        = $user->name;
            $job_post_data['job_title']   = $request->input('job_title');
            $job_post_data['expiry_date'] = $request->input('job_expiry_date');

            $job_post_data['company_name'] = $employer->company_name ? $employer->company_name : "For Your Company";

            Mail::to(explode(",", config('app.admin_email')))->send(new JobPostMail($job_post_data));
            Mail::to($user->email)->send(new JobPostMail($job_post_data));
            $paymentService->checkSubscription($user);
            return response()->json([
                "status"  => true,
                "message" => "Job post has been created successfully",

            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function publishJob(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id'     => 'required',
                'job_status' => 'required',
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
            $data = JobPost::where('id', $request->input('job_id'))
                ->update([
                    'job_status' => $request->input('job_status'),
                ]);

            return response()->json([
                "status"  => true,
                "message" => "Job has been updated successfully.",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime!",
                "error_type" => 2,
            ], 200);
        }
    }

    public function update(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id'          => 'required',
                'job_title'       => 'required',
                'job_expiry_date' => 'required',
                'salary_from'     => 'nullable',
                'salary_to'       => 'nullable',
                'salary_currency' => 'nullable',
                'salary_period'   => 'nullable',
                'hide_salary'     => 'nullable',
                'job_title'       => 'required',
                'job_location'    => 'nullable',
                'qualifications'  => 'required|array',
                'city'            => 'nullable',
                'state'           => 'nullable',
                'country'         => 'nullable',
                'zip'             => 'nullable',
                'job_skills'      => 'required|array',
                'new_job_skills'  => 'nullable|array',
                'functional_area' => 'required_without:new_job_type',
                'new_job_type'    => 'required_without:functional_area',
                'preferred_job_type' => 'required',
                'experience'      => 'required',
                'total_hire'      => 'required',
                'job_details'     => 'required',
                'additinal_pay'   => 'nullable|array',
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

            if ($request->input('new_job_type')) {
                $job_type_have = Industry::where('name', '=', $request->input('new_job_type'))->count();
                if ($job_type_have == 0) {
                    $ins              = new Industry;
                    $ins->name        = $request->input('new_job_type');
                    $ins->description = $request->input('new_job_type');
                    $ins->status      = 1;
                    $ins->save();
                }
            }
            $functional_area = $request->input('functional_area') ? $request->input('functional_area') : $ins->id;

            $skill = $request->input('new_job_skills');

            if (count($skill) > 0) {
                foreach ($skill as $key => $value) {
                    $ins              = new Skill;
                    $ins->name        = $value;
                    $ins->description = $value;
                    $ins->status      = 1;
                    $ins->save;
                }
            }

            $post                     = JobPost::where('id', $request->input('job_id'))->first();
            $post->job_title          = $request->input('job_title');
            $post->job_location       = $request->input('job_location');
            $post->city               = $request->input('city', null);
            $post->state              = $request->input('state', null);
            $post->country            = $request->input('country', null);
            $post->zip                = $request->input('zip', null);
            $post->job_skills         = json_encode($request->input('job_skills'));
            $post->functional_area    = $functional_area;
            $post->preferred_job_type = $request->input('preferred_job_type', null);
            $post->experience         = $request->input('experience', null);
            $post->total_hire         = $request->input('total_hire', null);
            $post->job_details        = $request->input('job_details', null);
            $post->employer_id        = Auth::user()->id;
            $post->job_expiry_date    = $request->input('job_expiry_date');
            if ($request->input('hide_salary') == 'no') {
                $post->salary_from     = $request->input('salary_from');
                $post->salary_to       = $request->input('salary_to');
                $post->salary_currency = $request->input('salary_currency');
            } else {
                $post->salary_from     = null;
                $post->salary_to       = null;
                $post->salary_currency = null;
            }

            if ($request->input('salary_period')) {
                $post->salary_period = $request->input('salary_period');
            } else {
                $post->salary_period = 'Monthly';
            }

            $post->hide_salary    = $request->input('hide_salary');
            $post->additinal_pay  = json_encode($request->input('additinal_pay'));
            $post->qualifications = json_encode($request->input('qualifications'));
            $post->updated_by     = Auth::user()->id;

            $post->save();

            return response()->json([
                "status"  => true,
                "message" => "Job has been updated successfully.",
            ], 200);
        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function details(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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

                if (Auth::user()->user_type == 'candidate') {
                    $is_bookmarked = Bookmark::where(['job_id' => $request->input('job_id'), 'candidate_id' => Auth::user()->id])->first();

                    $is_applied = JobApply::where(['job_id' => $request->input('job_id'), 'candidate_id' => Auth::user()->id])
                        ->where('status', '!=', 'Rejected')
                        ->first();
                    $job_reported = JobReport::where(['job_id' => $request->input('job_id'), 'candidate_id' => Auth::user()->id])
                        ->first();
                    $not_interested_job = JobNotInterested::where(['job_id' => $request->input('job_id'), 'candidate_id' => Auth::user()->id])
                        ->first();
                }

                $jobs = JobPost::where('id', $request->input('job_id'))
                    ->where('status', 1)
                    ->first();

                $data                       = [];
                $data['id']                 = $jobs->id;
                $data['employer_id']        = $jobs->employer_id;
                $data['company_name']       = $jobs->company_name;
                $data['company_logo']       = $jobs->company_logo;
                $data['job_expiry_date']    = $jobs->job_expiry_date;
                $data['salary_from']        = $jobs->salary_from;
                $data['salary_to']          = $jobs->salary_to;
                $data['salary_currency']    = $jobs->salary_currency;
                $data['salary_period']      = $jobs->salary_period;
                $data['hide_salary']        = $jobs->hide_salary;
                $data['job_title']          = $jobs->job_title;
                $data['job_location']       = $jobs->job_location;
                $data['qualifications']     = $jobs->qualifications;
                $data['city']               = $jobs->city;
                $data['state']              = $jobs->state;
                $data['country']            = $jobs->country;
                $data['zip']                = $jobs->zip;
                $data['job_skills']         = $jobs->job_skills;
                $data['functional_area']    = $jobs->functional_area;
                $data['preferred_job_type'] = $jobs->preferred_job_type;
                $data['experience']         = $jobs->experience;
                $data['total_hire']         = $jobs->total_hire;
                $data['job_details']        = $jobs->job_details;
                $data['status']             = $jobs->status;
                $data['additinal_pay']      = $jobs->additinal_pay;
                $data['job_post_date']      = Carbon::parse($jobs->created_at)->format('M d');
                Auth::user()->user_type;
                if (Auth::user()->user_type == 'candidate') {
                    if ($is_bookmarked) {
                        $data['is_bookmarked'] = 1;
                    } else {
                        $data['is_bookmarked'] = 0;
                    }
                    if ($is_applied) {
                        $data['is_applied'] = 1;
                    } else {
                        $data['is_applied'] = 0;
                    }
                    if ($job_reported) {
                        $data['job_reported'] = 1;
                    } else {
                        $data['job_reported'] = 0;
                    }
                    if ($not_interested_job) {
                        $data['not_interested_job'] = 1;
                    } else {
                        $data['not_interested_job'] = 0;
                    }
                }

                if ($jobs) {
                    return response()->json([
                        "status" => true,
                        'jobs'   => $data,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function remove(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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

                $post = JobPost::where('id', $request->input('job_id'))->first();
                if ($post) {
                    $post->status     = 0;
                    $post->updated_by = Auth::user()->id;
                    $post->save();
                    return response()->json([
                        "status"  => true,
                        'message' => "Job post has been deleted",
                    ], 200);
                } else {
                    return response()->json([
                        "status"     => false,
                        "message"    => "System error, please try after sometime",
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

    public function applyJob(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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

                $apply               = new JobApply;
                $apply->candidate_id = Auth::user()->id;
                $apply->job_id       = $request->input('job_id');
                $apply->save();

                $job      = JobPost::where('id', $request->input('job_id'))->first();
                $employer = User::where('id', $job->employer_id)->first();

                $data = array();
                $e_data = array();

                if ($apply) {
                    $data['user_name'] = Auth::user()->first_name . " " . Auth::user()->last_name;
                    $data['job_title'] = $job->job_title;

                    $e_data['candidate_name'] = ucfirst(Auth::user()->first_name) . " " . Auth::user()->last_name;
                    $e_data['user_name'] = $employer->first_name . " " . $employer->last_name;
                    $e_data['job_title'] = $job->job_title;

                    $e_candidate = Candidate::where('user_id', Auth::id())->first();
                    $e_data['candidate_cv'] = $e_candidate->resume;
                    $e_data['candidate_cl'] = $e_candidate->cover_letter;
                    try {
                        Mail::to(Auth::user()->email)->send(new JobAppliedMail($data));
                    } catch (\Throwable $th) {
                        return response()->json([
                            "status"  => true,
                            'message' => "The Post Applied Successfully.\nBut The mail server could not deliver mail to " . Auth::user()->email . ". The account or domain may not exist"
                        ], 200);
                    }
                    // Mail to relavent employer
                    try {
                        Mail::to(explode(",", config('app.admin_email')))->send(new JobAppliedNotifyMail($e_data));
                        Mail::to($employer->email)->send(new JobAppliedNotifyMail($e_data));
                    } catch (\Throwable $th) {
                        return response()->json([
                            "status"  => true,
                            'message' => "The Post Applied Successfully.\nBut The mail server could not deliver mail to " . $employer->email . ". The account or domain may not exist",
                        ], 200);
                    }

                    $title       = "Applied Job";
                    $body        = Auth::user()->name . " Applied  for the job." . $job->job_title . ".";
                    $sender_id   = Auth::user()->id;
                    $receiver_id = $job->employer_id;

                    if ($employer->fpm_token) {

                        $param          = [];
                        $param['msg']   = $employer->name . " applied for job " . $job->job_title;
                        $param['token'] = $employer->fpm_token;

                        PushNotification::fire_notification($param);
                    }

                    return response()->json([
                        "status"  => true,
                        'message' => "Congratulations " . Auth::user()->name . "! You have successfully applied for the job " . $job->job_title . ".",
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function appliedJobList(Request $request)
    {
        if (Auth::check()) {
            $limit  = 5;
            $offset = ((((int) $request->page_no - 1) * $limit));

            $apply_jobs_id = JobApply::orderBy('id', 'DESC')->where('candidate_id', Auth::user()->id)
                ->pluck('job_id');
            //  ->toArray();

            $apply_jobs = JobPost::whereIn('id', $apply_jobs_id)->orderBy('created_at', 'DESC')
                // ->take($limit)
                // ->skip($offset)
                ->get();

            $total_apply_jobs = JobPost::whereIn('id', $apply_jobs_id)
                ->get();

            $total = count($total_apply_jobs);

            if ($apply_jobs) {
                return response()->json([
                    "status"     => true,
                    "message"    => "Success.",
                    "apply_jobs" => $apply_jobs,
                    "per_page"   => $limit,
                    "total"      => $total,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function jobBookMarked(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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

                $exist = Bookmark::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->first();

                if ($exist) {
                    $booked = Bookmark::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->delete();
                    return response()->json([
                        "status"  => true,
                        'message' => "Job removed successfully from favorite list.",
                    ], 200);
                } else {
                    $booked               = new Bookmark;
                    $booked->candidate_id = Auth::user()->id;
                    $booked->job_id       = $request->input('job_id');
                    $booked->save();

                    return response()->json([
                        "status"  => true,
                        'message' => "This job added successfully as favorite.",
                    ], 200);
                }

                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
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

    public function bookmarkJobList(Request $request)
    {
        if (Auth::check()) {
            $limit  = 5;
            $offset = ((((int) $request->page_no - 1) * $limit));

            $bookmark_jobs = Bookmark::where('candidate_id', Auth::user()->id)->orderBy('id', 'DESC')
                ->with('jobs')
                ->whereHas('jobs', function ($q) {
                    $q->where('id', '>', '0');
                })
                ->take($limit)
                ->skip($offset)
                ->get();
            $bookmarks = Bookmark::where('candidate_id', Auth::user()->id)
                ->get();

            $total = count($bookmarks);

            if ($bookmark_jobs) {
                return response()->json([
                    "status"        => true,
                    "message"       => "Success.",
                    "bookmark_jobs" => $bookmark_jobs,
                    "per_page"      => $limit,
                    "total"         => $total,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function appliedCandidateListByJob(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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
                $limit  = 5;
                $offset = ((((int) $request->page_no - 1) * $limit));

                $candidates = JobApply::where('job_id', $request->input('job_id'))
                    ->where('status', '!=', 'Rejected')->orderBy('id', 'DESC')
                    ->with('jobs', 'candidate')
                    ->take($limit)
                    ->skip($offset)
                    ->get();
                $c_count = JobApply::where('job_id', $request->input('job_id'))
                    ->where('status', '!=', 'Rejected')
                    ->get();

                $total = count($c_count);

                if ($candidates) {
                    return response()->json([
                        "status"     => true,
                        "message"    => "Success.",
                        "candidates" => $candidates,
                        "per_page"   => $limit,
                        "total"      => $total,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function changeStatus(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'job_id'       => 'required',
                'candidate_id' => 'required',
                'status'       => 'required',
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

                $candidates = JobApply::where(['job_id' => $request->input('job_id'), 'candidate_id' => $request->input('candidate_id')])->update(['status' => $request->input('status')]);

                if ($request->input('status') == 'Accepted') {
                    $message = "Shortlisted.";
                }
                if ($request->input('status') == 'Rejected') {
                    $message = 'Removed.';
                } else {
                    $message = "Success.";
                }
                $job = JobPost::where('id', $request->input('job_id'))->first();

                $candidate = User::where('id', $request->input('candidate_id'))->first();

                if ($candidate->fpm_token) {

                    $param          = [];
                    $param['msg']   = $candidate->name . " your applied is " . $request->input('status') . " for job " . $job->job_title;
                    $param['token'] = $candidate->fpm_token;

                    PushNotification::fire_notification($param);
                }

                if ($candidates) {
                    return response()->json([
                        "status"  => true,
                        "message" => $message,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function shortListedList(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'job_id' => 'required',
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
                $limit  = 5;
                $offset = ((((int) $request->page_no - 1) * $limit));

                $candidates = JobApply::where(['job_id' => $request->input('job_id'), 'status' => 'Accepted'])
                    ->with('jobs', 'candidate')->orderBy('id', 'DESC')
                    ->take($limit)
                    ->skip($offset)
                    ->get();
                $c_count = JobApply::where(['job_id' => $request->input('job_id'), 'status' => 'Accepted'])
                    ->get();

                $total = count($c_count);

                if ($candidates) {
                    return response()->json([
                        "status"     => true,
                        "message"    => "Success.",
                        "candidates" => $candidates,
                        "per_page"   => $limit,
                        "total"      => $total,
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
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function reportJob(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id'     => 'required',
                'report_for' => 'nullable',
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

                $exist = JobReport::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->first();

                if ($exist) {
                    $job_report = JobReport::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->delete();
                    return response()->json([
                        "status"  => true,
                        'message' => "Not Reported.",
                    ], 200);
                } else {
                    $job_report               = new JobReport;
                    $job_report->candidate_id = Auth::user()->id;
                    $job_report->job_id       = $request->input('job_id');
                    $job_report->report_for   = $request->input('report_for');
                    $job_report->save();

                    return response()->json([
                        "status"  => true,
                        'message' => "Reported.",
                    ], 200);
                }

                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
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

    public function notInterestJob(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_id'           => 'required',
                'not_interest_for' => 'nullable',
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

                $exist = JobNotInterested::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->first();

                if ($exist) {
                    $job_not_interested = JobNotInterested::where(['candidate_id' => Auth::user()->id, 'job_id' => $request->input('job_id')])->delete();
                    return response()->json([
                        "status"  => true,
                        'message' => "Interested.",
                    ], 200);
                } else {
                    $job_not_interested                   = new JobNotInterested;
                    $job_not_interested->candidate_id     = Auth::user()->id;
                    $job_not_interested->job_id           = $request->input('job_id');
                    $job_not_interested->not_interest_for = $request->input('not_interest_for');
                    $job_not_interested->save();

                    return response()->json([
                        "status"  => true,
                        'message' => "Not Interested.",
                    ], 200);
                }

                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
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
}
