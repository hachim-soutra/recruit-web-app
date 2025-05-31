<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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
use App\Models\JobStat;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\UserSkill;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\Language;
use App\Models\Coach;
use App\Models\Candidate;
use App\Models\Qualification;
use App\Models\WorkExperience;
use App\Models\CompanyBookmark;
use App\Models\Transuction;
use App\Models\Notification;
use App\Models\Contact;
use App\Mail\VerifyUser;
use App\Mail\ContactNotifyMail;
use App\Mail\ThankyouMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\Http\Controllers\Site\AuthController;
use Response;

use App\Models\Coupon;
use App\Services\Payment\PaymentService;
use Stripe;
use Stripe\StripeClient;
use Exception;

class EmployerController extends Controller
{
    #post job page
    public function postJob()
    {
        $data = array(
            'industries' => Industry::where('status', '1')->get(),
            'skills' => Skill::where('status', '1')->get(),
            'qualifications' => Qualification::where('status', '1')->get(),
        );
        if ((session()->get('segments') != '') && (Auth::user()->user_type === 'employer')) {
            $sessionData = session()->get('segments');
            $trigger = $sessionData['trigger'];
            session()->forget('segments');
            session()->put('trigger', $trigger);
            return redirect()->to($sessionData['urlSegment']);
        }
        return view('site.pages.employer.jobpost', compact('data'));
    }
    #draft job
    public function draftJob($draftid = null)
    {
        $jobpost = array();
        $condition = [['status', '=', '1'], ['employer_id', '=', Auth::id()], ['job_status', '=', 'Save as Draft']];
        $firstJobDetail = JobPost::where($condition)->orderBy('created_at', 'DESC')->first();
        $jobdraft = JobPost::where($condition)->orderBy('created_at', 'DESC')->paginate(6);
        if ($draftid != null) {
            $condition = [['status', '=', '1'], ['id', '=', $draftid], ['employer_id', '=', Auth::id()], ['job_status', '=', 'Save as Draft']];
            $firstJobDetail = JobPost::where($condition)->orderBy('created_at', 'DESC')->first();
        }
        $data = array(
            'jobDraft' => $jobdraft,
            'firstJobDetail' => $firstJobDetail,
        );
        return view('site.pages.employer.jobdraft', compact('data'));
    }
    #create
    public function jobCreate(Request $request, PaymentService $paymentService)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_title'          => 'required',
                'job_expiry_date'    => 'required|date|date_format:Y-m-d|after:today',
                'salary_from'        => 'nullable',
                'salary_to'          => 'nullable',
                'salary_currency'    => 'nullable',
                'salary_period'      => 'nullable',
                'hide_salary'        => 'required|in:yes,no',
                'job_mode'           => 'required|in:site,remote,hybrid',
                'job_location'       => 'nullable',
                'qualifications'     => 'required|array',
                'job_skills'         => 'required|array',
                'preferred_job_type' => 'required',
                'experience'         => 'required',
                'total_hire'         => 'required',
                'job_details'        => 'required',
                'additinal_pay'      => 'nullable|array',
                'job_status'         => 'required ',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->input())->withErrors($validator->errors()->all());
            }
            $user = auth()->user();
            if ($user->available_jobs_number <= 0) {
                return redirect()->to('subscription')->withSuccess("You have consumed the number of job posts that you have purchased.");
            }
            $post                     = new JobPost;
            $post->job_title          = $request->input('job_title');
            $post->job_location       = $request->input('job_location');
            $post->city               = $request->input('city', null);
            $post->state              = $request->input('state', null);
            $post->country            = $request->input('country', null);
            $post->zip                = $request->input('zip', null);
            $post->job_skills         = json_encode($request->input('job_skills'));
            $post->functional_area    = $request->input('industry', null);
            $post->preferred_job_type = $request->input('preferred_job_type', null);
            $post->experience         = $request->input('experience', null);
            $post->total_hire         = $request->input('total_hire', null);
            $post->job_details        = $request->input('job_details', null);
            $post->employer_id        = $user->id;
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
            $post->job_mode    = $request->input('job_mode');
            $post->additinal_pay  = json_encode($request->input('additinal_pay'));
            $post->qualifications = json_encode($request->input('qualifications'));
            $post->created_by     = $user->id;
            $post->payment_status = "Paid";
            if ($request->input('job_status') == 'Published') {
                $post->job_status = "Published";
            } else {
                $post->job_status = "Save as Draft";
            }
            if ($post->save()) {
                $employer = $user->employer;
                $job_post_data                = array();
                $job_post_data['name']        = $user->name;
                $job_post_data['job_title']   = $request->input('job_title');
                $job_post_data['expiry_date'] = $request->input('job_expiry_date');

                $job_post_data['company_name'] = !empty($employer) ? $employer->company_name : "For Your Company";

                Mail::to(explode(",", config('app.admin_email')))->send(new JobPostMail($job_post_data));
                Mail::to($user->email)->send(new JobPostMail($job_post_data));
                $paymentService->checkSubscription($user);
            }



            return redirect()->back()->withSuccess("Congratulations! The job post has been created successfully. You now have {$user->available_jobs_number} available slots.");
        } else {
            return redirect()->back()->withError("System error please try after sometime.");
        }
    }

    public function editJob($jobid)
    {
        $jobDetail = JobPost::where(['id' => $jobid, 'status' => '1'])->first();
        if (Auth::id() != $jobDetail->employer_id) {
            return redirect()->route('dashboard')->withError("System error Please do not try to update job if you're not yours.");
        }
        $data = array(
            'industries'        => Industry::where('status', '1')->get(),
            'skills'            => Skill::where('status', '1')->get(),
            'qualifications'    => Qualification::where('status', '1')->get()
        );
        return view('site.pages.employer.jobedit', compact('jobDetail', 'data'));
    }

    public function jobUpdate(Request $request, $id, PaymentService $paymentService)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'job_title'          => 'required',
                'job_expiry_date'    => 'required|date|date_format:Y-m-d|after:today',
                'salary_from'        => 'nullable',
                'salary_to'          => 'nullable',
                'salary_currency'    => 'nullable',
                'salary_period'      => 'nullable',
                'hide_salary'        => 'required|in:yes,no',
                'job_mode'           => 'required|in:site,remote,hybrid',
                'job_location'       => 'nullable',
                'qualifications'     => 'required|array',
                'job_skills'         => 'required|array',
                'preferred_job_type' => 'required',
                'experience'         => 'required',
                'total_hire'         => 'required',
                'job_details'        => 'required',
                'additinal_pay'      => 'nullable|array',
                'job_status'         => 'required',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withInput($request->input())->withErrors($validator->errors()->all());
            }
            $user = auth()->user();
            $post                     = JobPost::find($id);
            $post->job_title          = $request->input('job_title');
            $post->job_location       = $request->input('job_location');
            $post->city               = $request->input('city', null);
            $post->state              = $request->input('state', null);
            $post->country            = $request->input('country', null);
            $post->zip                = $request->input('zip', null);
            $post->job_skills         = json_encode($request->input('job_skills'));
            $post->functional_area    = $request->input('industry', null);
            $post->preferred_job_type = $request->input('preferred_job_type', null);
            $post->experience         = $request->input('experience', null);
            $post->total_hire         = $request->input('total_hire', null);
            $post->job_details        = $request->input('job_details', null);
            $post->employer_id        = $user->id;
            $post->subscription_id    = $user->valid_subscription->id;
            $post->job_expiry_date    = $request->input('job_expiry_date');
            $post->salary_from        = $request->input('salary_from');
            $post->salary_to          = $request->input('salary_to');
            $post->salary_currency    = $request->input('salary_currency');
            if ($request->input('salary_period')) {
                $post->salary_period  = $request->input('salary_period');
            } else {
                $post->salary_period  = 'Monthly';
            }
            $post->hide_salary    = $request->input('hide_salary');
            $post->job_mode    = $request->input('job_mode');
            $post->additinal_pay  = json_encode($request->input('additinal_pay'));
            $post->qualifications = json_encode($request->input('qualifications'));
            $post->created_by     = $user->id;
            $post->payment_status = "Paid";
            if ($request->input('job_status') == 'Published') {
                $post->job_status = "Published";
            } else {
                $post->job_status = "Save as Draft";
            }
            if ($post->save()) {
                $paymentService->checkSubscription($user);
            }
            return redirect()->back()->withSuccess("Job post has been updated successfully");
        } else {
            return redirect()->back()->withError("System error please try after sometime.");
        }
    }

    #change job status
    public function changeJobStatus(Request $request)
    {
        $inputs = $request->all();
        if ($inputs['jobid'] != '') {
            if ($inputs['status'] == 'Published') {

                $status = JobPost::where('id', $inputs['jobid'])->update([
                    'job_status' => $inputs['status'],
                    'updated_by' => Auth::id()
                ]);
                return response()->json(['code' => 200, 'error' => '', 'msg' => 'Status Changed to' . $inputs['status']]);
            } else {
                $status = JobPost::where('id', $inputs['jobid'])->update([
                    'job_status' => $inputs['status'],
                    'updated_by' => Auth::id()
                ]);
                return response()->json(['code' => 200, 'error' => '', 'msg' => 'Status Changed to ' . $inputs['status']]);
            }
        }
        return response()->json(['code' => 401, 'error' => 'Job Post Id Required.', 'msg' => '']);
    }
    #employee post job button
    public function employerPostJob()
    {
        $segments = array(
            'urlSegment' => "post-job",
            'trigger' => "jobPostForm"
        );
        session()->put('segments', $segments);
        if (Auth::check()) {
            return $this->postJob();
        }
        $auth = new AuthController;
        return $auth->signin();
    }

    #change job expired date
    public function changeJobExpireDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstJob_id'        => 'required',
            'job_expire_date'    => 'required|date|date_format:Y-m-d|after:today',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        } else {
            $update = JobPost::where('id', $request->input('firstJob_id'))->update([
                'job_expiry_date' => date('Y-m-d', strtotime($request->input('job_expire_date')))
            ]);
            if ($update) {
                return response()->json(['code' => 200, 'msg' => 'Job Expired Date Updated.']);
            } else {
                return response()->json(['code' => 500, 'msg' => 'System Error.Try After Some Time.']);
            }
        }
    }

    public function jobApplicants($jobid = null)
    {
        $stats = ['CLICK' => 0, 'VIEW' => 0];
        if ($jobid != '') {
            $data = JobApply::where('job_id', $jobid)->with('jobs', 'candidate', 'candidate.candidate')->paginate(15);
            $job = JobPost::where('id', $jobid)->first();
            $jobStats = JobStat::select('type', DB::raw('count(*) as total'))->where('job_id', $jobid)->groupBy('type')->pluck('total','type')->toArray();
            if (isset($jobStats['CLICK'])) {
                $stats['CLICK'] = $jobStats['CLICK'];
            }
            if (isset($jobStats['VIEW'])) {
                $stats['VIEW'] = $jobStats['VIEW'];
            }
            return view('site.pages.employer.job_applicant_list', compact('data', 'job', 'stats'));
        }
        return redirect()->back();
    }
}
