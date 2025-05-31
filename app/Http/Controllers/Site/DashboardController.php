<?php

namespace App\Http\Controllers\Site;

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
use App\Models\UserSkill;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\Language;
use App\Models\Coach;
use App\Models\Candidate;
use App\Models\Qualification;
use App\Models\WorkExperience;
use App\Models\CompanyBookmark;
use App\Mail\VerifyUser;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use DB;
use App\Http\Controllers\Site\AuthController;
use Response;
use Session;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/');
    }
    public function dashboard($postid = null, $from = null)
    {
        $jobpost = (object)array();
        $coachDetail = (object)array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];
        if (Auth::user()->user_type === 'employer') {
            if (Auth::user()->valid_subscription) {
                $condition = [
                    ['status', '=', '1'],
                    ['employer_id', '=', Auth::id()],
                    ['job_status', '=', 'Published'],
                    ['subscription_id', '=', Auth::user()->valid_subscription->id]
                ];
            } else {
                $condition = [
                    ['status', '=', '1'],
                    ['employer_id', '=', Auth::id()],
                    ['job_status', '=', 'Published'],
                    ['subscription_id', '<>', null]
                ];
            }
        }
        $firstJobDetail = JobPost::with('bookmark', 'applicatons')
            ->where($condition)
            ->whereDate('job_expiry_date', '>', Carbon::now());
        if ($postid != null) {
            $condition = [['status', '=', '1'], ['id', '=', $postid]];
            if (Auth::user()->user_type === 'employer') {
                if (Auth::user()->valid_subscription) {
                    $condition = [
                        ['status', '=', '1'],
                        ['id', '=', $postid],
                        ['employer_id', '=', Auth::id()],
                        ['subscription_id', '=', Auth::user()->valid_subscription->id]
                    ];
                } else {
                    $condition = [
                        ['status', '=', '1'],
                        ['id', '=', $postid],
                        ['employer_id', '=', Auth::id()],
                        ['subscription_id', '<>', null]
                    ];
                }
            }
            $firstJobDetail = JobPost::with('bookmark', 'applicatons')
                ->where($condition)
                ->whereDate('job_expiry_date', '>', Carbon::now());
        }
        if (Auth::user()->user_type === 'candidate') {
            $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];
        }
        if (Auth::user()->user_type === 'employer') {
            if (Auth::user()->valid_subscription) {
                $condition = [
                    ['status', '=', '1'],
                    ['employer_id', '=', Auth::id()],
                    ['job_status', '=', 'Published'],
                    ['subscription_id', '=', Auth::user()->valid_subscription->id]
                ];
            } else {
                $condition = [
                    ['status', '=', '1'],
                    ['employer_id', '=', Auth::id()],
                    ['job_status', '=', 'Published'],
                    ['subscription_id', '<>', null]
                ];
            }
        }
        if (Auth::user()->user_type === 'coach') {
            $coachDetail  = Coach::with('user')->where('user_id', Auth::id())->first();
        }
        $jobpost = JobPost::with('applicatons')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now());
        $paginateQuery = $jobpost->paginate(6);
        $data = array(
            'jobPost'               => $paginateQuery,
            'firstJobDetail'        => $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$postid])->first(),
            'totalResult'           => $jobpost->count(),
            'candidateDetail'       => Candidate::where('user_id', Auth::id())->first(),
            'coachDetail'           => $coachDetail,
            'from'                  => $from,
            'postid'                => $postid,
            'validSubscription'     => Auth::user()->valid_subscription,
            'waitingSubscription'   => Auth::user()->waiting_subscription
        );
        if ((session()->get('segments') != '')) {
            $trigger = '';
            if (Auth::user()->user_type === 'candidate') {
                $sessionData = session()->get('segments');
                $trigger = $sessionData['trigger'];
            }
            if (Auth::user()->user_type === 'employer') {
                $sessionData = session()->get('segments');
                $trigger = $sessionData['trigger'];
            }
            session()->forget('segments');
            session()->put('trigger', $trigger);
            return redirect()->to($sessionData['urlSegment']);
        }
        return view('site.pages.dashboard', compact('data'));
    }

    public function commonajax(Request $request)
    {
        $inputs = $request->all();
        $response = False;
        switch ($inputs) {
            case ($inputs['queryfor'] == 'skill'):
                UserSkill::where('candidate_id', $inputs['userid'])->delete();
                for ($i = 0, $sk = $inputs['skills']; $i < count($sk); $i++) :
                    $data = array('candidate_id' => $inputs['userid'], 'skill_id' => $sk[$i]);
                    if (UserSkill::updateOrCreate($data, $data)) {
                        $response = TRUE;
                    }
                endfor;
                return $response;
                break;
            case ($inputs['queryfor'] == 'education'):
                UserEducation::where('candidate_id', $inputs['userid'])->delete();
                for ($i = 0, $edus = $inputs['edus']; $i < count($edus); $i++) :
                    $data = array('candidate_id' => $inputs['userid'], 'qualification_id' => $edus[$i]);
                    if (UserEducation::updateOrCreate($data, $data)) {
                        $response = TRUE;
                    }
                endfor;
                return $response;
                break;
            case ($inputs['queryfor'] == 'language'):
                $str_lang = '';
                if (!empty(@$inputs['langs']) > 0) :
                    for ($i = 0, $langs = @$inputs['langs']; $i < count($langs); $i++) :
                        $str_lang .= $langs[$i];
                        if ($i < count($langs) - 1) {
                            $str_lang .= ', ';
                        }
                    endfor;
                endif;
                $data = array('languages' => $str_lang);
                if (Candidate::updateOrCreate(['user_id' => $inputs['userid']], $data)) {
                    $response = TRUE;
                }
                return $response;
                break;
            default:
                return $response;
                break;
        }
        return $response;
    }

    #profile
    public function profile()
    {
        $trigger = '';
        $data = array(
            'skills' => Skill::where('status', '1')->get(),
            'qualification' => Qualification::where('status', '1')->get(),
            'userSkill' => UserSkill::with('skill')->where('candidate_id', Auth::id())->get(),
            'userEdu' => UserEducation::with('qualification')->where('candidate_id', Auth::id())->get(),
            'userAppliedJobs' => JobApply::with('jobs', 'candidate')->where('candidate_id', Auth::id())->orderBy('id', 'DESC')->paginate(6),
            'language' => Language::where('status', '1')->get(),
            'industry' => Industry::where('status', '1')->get(),
            'coachDetail' => Coach::where('user_id', Auth::id())->first(),
            'employeeDetail' => Employer::where('user_id', Auth::id())->first(),
            'candidateDetail' => Candidate::where('user_id', Auth::id())->first(),
            'candidateWorks' => WorkExperience::where('candidate_id', Auth::id())->where('status', '1')->get(),
            'country' => Country::all(),
            'city' => City::all(),
            'state' => State::all(),
        );
        if ((session()->get('segments') != '') && (Auth::user()->user_type === 'candidate')) {
            $sessionData = session()->get('segments');
            $trigger = $sessionData['trigger'];
            session()->forget('segments');
            session()->put('trigger', $trigger);
            return redirect()->to($sessionData['urlSegment']);
        }
        return view('site.pages.profile', compact('data'));
    }
    #coach | employee
    public function profileUpdate(Request $request)
    {
        $inputs = $request->all();

        $rules = array(
            'fullname'    => 'required|string|max:150',
            'mobileno'    => 'required|min:10|max:13|unique:users,mobile,' . Auth::id(),
            'emailid'     => 'required|email|unique:users,email,' . Auth::id(),
        );
        if (Auth::user()->user_type == 'employer') {
            $emp_rules = array(
                'company_details'     => 'required',
                'website_link'        => 'required',
                'company_name'        => 'required',
            );
            $rules = array_merge($rules, $emp_rules);
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors()->all());
        }
        $fname = $lname = '';
        if ($request->input('fullname') != '') {
            $str_explode = explode(' ', $request->input('fullname'));
            $fname = $str_explode[0];
            if (array_key_exists('1', $str_explode)) {
                $lname = $str_explode[1];
            }
        }
        $userDetail = User::findOrFail(Auth::user()->id);
        $user = array(
            'email' => $inputs['emailid'],
            'mobile' => $inputs['mobileno'],
            'name' => $inputs['fullname'],
            'first_name' => $fname,
            'last_name' => $lname,
            'updated_at' => Carbon::now()->toDateTimeString()
        );
        if ($userDetail->email != $request->input('email')) {
            $user['email_verified'] = 0;
            $user['email_verified_at'] = Carbon::now()->toDateTimeString();
        }
        if ($userDetail->mobile != $request->input('mobile')) {
            $user['mobile_verified'] = 0;
            $user['mobile_verified_at'] = Carbon::now()->toDateTimeString();
        }
        if (User::where('id', Auth::id())->update($user)) {
            if ($user['email_verified'] == 0) {
                Mail::to($userDetail->email)->send(new VerifyUser($userDetail));
            }
            if (Auth::user()->user_type == 'employer') {
                $filename = !$request->hasFile('company_logo') ? (($request->input('company_logo_old') == '') ? 'no_logo.jpeg' : $request->input('company_logo_old')) : 'no_logo.jpeg';
                if ($request->hasFile('company_logo')) {
                    $fullpath = public_path('uploads/company_logo');
                    $file = $request->file('company_logo');
                    $filename = uniqid(Auth::id() . '_') . "." . $file->getClientOriginalExtension();
                    if (!file_exists($fullpath)) {
                        mkdir($fullpath, 0777, true);
                    }
                    $file->move($fullpath, $filename);
                }
                $employer = array(
                    'established_in' => $request->input('established_in', null),
                    'company_logo' => $filename,
                    'country' => $request->input('country', null),
                    'state' => $request->input('state', null),
                    'city' => $request->input('city', null),
                    'zip' => $request->input('zipcode', null),
                    'company_name' => $inputs['company_name'],
                    'number_of_employees' => $request->input('number_of_employees', null),
                    'company_details' => $inputs['company_details'],
                    'phone_number' => $inputs['phone_number'],
                    'address' => $request->input('address', null),
                    'industry_id' => $request->input('industry_id', null),
                    'website_link' => $request->input('website_link', null),
                    'linkedin_link' => $request->input('linkedin_link', null),
                    'tag_line' => $request->input('tag_line', null),
                    'company_ceo' => $request->input('company_ceo', null)
                );
                Employer::updateOrCreate(['user_id' => Auth::id()], $employer);
            }
            if (Auth::user()->user_type == 'coach') {
                $filename = !$request->hasFile('coach_banner') ? (($request->input('coach_banner_old') == '') ? 'no_banner.jpg' : $request->input('coach_banner_old')) : 'no_banner.jpg';
                if ($request->hasFile('coach_banner')) {
                    $fullpath = public_path('uploads/coach_banner');
                    $file = $request->file('coach_banner');
                    $filename = uniqid(Auth::id() . '_') . "." . $file->getClientOriginalExtension();
                    if (!file_exists($fullpath)) {
                        mkdir($fullpath, 0777, true);
                    }
                    if (@$inputs['coach_banner_old'] != '' && $inputs['coach_banner_old'] != "no_banner.jpg") {
                        @unlink($fullpath . '/' . @$inputs['coach_banner_old']);
                    }
                    $file->move($fullpath, $filename);
                }
                $coach = array(
                    'country' => $request->input('country', null),
                    'state' => $request->input('state', null),
                    'city' => $request->input('city', null),
                    'zip' => $request->input('zipcode', null),
                    'address' => $request->input('address', null),
                    'total_experience_year' => $request->input('total_experience_year', null),
                    'total_experience_month' => $request->input('total_experience_month', null),
                    'university_or_institute' => $request->input('university_or_institute', null),
                    'gender' => $request->input('gender', null),
                    'about_us' => $request->input('about_us', null),
                    'coach_banner' => $filename,
                    'linkedin_link' => $request->input('coach_linkedin_link', null),
                    'facebook_link' => $request->input('coach_facebook_link', null),
                    'instagram_link' => $request->input('coach_instagram_link', null),
                    'contact_link' => $request->input('coach_contact_link', null),
                    'how_we_help' => $request->input('how_we_help', null),
                    'faq' => $request->input('faq', null),
                );
                Coach::updateOrCreate(['user_id' => Auth::id()], $coach);
            }
        }
        return redirect()->back()->withSuccess(ucfirst(Auth::user()->user_type) . " Details Updated.");
    }
    #candidate
    public function educationQualification()
    {
        $data = array(
            'skills' => Skill::where('status', '1')->get(),
            'qualification' => Qualification::where('status', '1')->get(),
            'userSkill' => UserSkill::with('skill')->where('candidate_id', Auth::id())->get(),
            'userEdu' => UserEducation::with('qualification')->where('candidate_id', Auth::id())->get(),
            'language' => Language::where('status', '1')->get(),
            'employeeDetail' => Employer::where('user_id', Auth::id())->first(),
            'industry' => Industry::where('status', '1')->get(),
            'coachDetail' => Coach::where('user_id', Auth::id())->first(),
            'candidateDetail' => Candidate::where('user_id', Auth::id())->first(),
        );
        return view('site.pages.profile_education', compact('data'));
    }
    #profile update
    public function profileUpdateCandidate(Request $request)
    {
        $languages = '';
        $inputs = $request->all();
        $validator = Validator::make($request->all(), [
            'first_name'              => 'required',
            'last_name'               => 'required',
            'email'                   => 'required',
            'address'                 => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors()->all());
        }
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
        if (gettype($request->input('languages', null)) && count($request->input('languages', '')) > 0) {
            $languages = implode(',', $request->input('languages', null));
        }
        $candidate = Candidate::updateOrCreate(['user_id' => Auth::user()->id], [
            'address'                 => $request->input('address'),
            'city'                    => $request->input('city', null),
            'state'                   => $request->input('state', null),
            'country'                 => $request->input('country', null),
            'zip'                     => $request->input('zip', null),
            'linkedin_link'           => $request->input('linkedin_link', null),
            'portfolio_link'          => $request->input('portfolio_link', null),
            'notice_period'           => $request->input('notice_period', null),
            'visa_status'             => $request->input('visa_status', null),
            'total_experience_year'   => $request->input('total_experience_year', null),
            'total_experience_month'  => $request->input('total_experience_month', null),
            'alternate_mobile_number' => $request->input('alternate_mobile_number', null),
            'highest_qualification'   => $request->input('highest_qualification', null),
            'functional_id'           => $request->input('functional_id', null),
            'university_or_institute' => $request->input('university_or_institute', null),
            'year_of_graduation'      => $request->input('year_of_graduation', null),
            'education_type'          => $request->input('education_type', null),
            'date_of_birth'           => $request->input('dob', null),
            'candidate_type'          => $request->input('candidate_type', null),
            'preferred_job_type'      => $request->input('preferred_job_type', ''),
            'gender'                  => $request->input('gender', null),
            'marital_status'          => $request->input('marital_status', null),
            'resume_title'            => $request->input('resume_title', null),
            'bio'                     => $request->input('bio', null),
            'nationality'             => $request->input('nationality', null),
            'current_salary'          => $request->input('current_salary', null),
            'expected_salary'         => $request->input('expected_salary', null),
            'salary_currency'         => $request->input('salary_currency', null),
            'languages'               => $languages,
        ]);

        $myarray = $request->input('languages');
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
        return redirect()->back()->withSuccess(ucfirst(Auth::user()->user_type) . " Details Updated.");
    }
    #work experience
    public function workExperience(Request $request)
    {
        $inputs = $request->all();
        $rules = [
            'job_role' => 'required',
            'company_name' => 'required',
            'current_work_here'     => 'required|in:0,1'
        ];
        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $validator->errors()->merge(['err' => '1']);
            return redirect()->back()->withErrors($validator->errors()->messages())->withInput();
        }
        $experience = array(
            'candidate_id' => Auth::id(),
            'job_title' => $request->input('job_role'),
            'company' => $request->input('company_name'),
            'address' => $request->input('company_address'),
            'currently_work_here' => $inputs['current_work_here'],
            'from_date' => $request->input('form_date'),
            'end_date' => $request->input('to_date', null),
            'details' => $request->input('company_detail')
        );
        if (WorkExperience::updateOrCreate([
            'id' => $inputs['exprowid'],
            'candidate_id' => Auth::id()
        ], $experience)) {
            return redirect()->back()->withSuccess(ucfirst(Auth::user()->user_type) . " Work Experience Saved.");
        } else {
            return redirect()->back()->withError("Something Went Wrong.");
        }
    }
    #edit experience
    public function workExperienceEdit(Request $request)
    {
        $inputs = $request->all();
        if (@$inputs['experienceid'] != '') {
            $exp = WorkExperience::where('id', $inputs['experienceid'])->first();
            return response()->json(['code' => 200, 'data' => $exp, 'error' => '']);
        }
        return response()->json(['code' => 500, 'data' => '', 'error' => 'Experience Id Getting Blank.']);
    }
    #dleete
    public function workExperienceDelete(Request $request)
    {
        $inputs = $request->all();
        if (@$inputs['experienceid'] != '') {
            WorkExperience::where('id', $inputs['experienceid'])->update([
                'status' => '0'
            ]);
            return response()->json(['code' => 200, 'data' => '', 'error' => '']);
        }
        return response()->json(['code' => 500, 'data' => '', 'error' => 'Experience Id Getting Blank.']);
    }
    #appliede job
    public function appliedJobDetail($appliedjobid = null)
    {
        $appliedjobid = base64_decode($appliedjobid);
        if ($appliedjobid) {
            $detail = JobApply::where('id', $appliedjobid)->with('jobs', 'candidate')->first();
            return view('site.pages.appliedjobdetail', compact('detail'));
        }
        return redirect()->back();
    }
    #upload resume
    public function uploadResume()
    {
        $segments = array(
            'urlSegment' => "profile",
            'trigger' => "upload-resume"
        );
        session()->put('segments', $segments);
        if (Auth::check()) {
            return $this->profile();
        }
        $auth = new AuthController;
        return $auth->signin();
    }

    public function coverletterUpdate(Request $request)
    {
        $rules = array(
            'cover_upload' => 'nullable|mimes:zip,pdf|max:3072',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors()->all());
        } else {
            if ($request->hasFile('cover_upload')) {
                $time      = Carbon::now();
                $file      = $request->file('cover_upload');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/cover_letter/'), $filename);
                Candidate::updateOrCreate(['user_id' => Auth::id()], ['cover_letter' => $filename]);
            }
            return redirect()->back()->withSuccess('Cover Letter Updated.');
        }
    }

    public function fileUpload(Request $request)
    {
        $path = $request->input('upload');
        $fieldname = $request->input('fieldname');
        $fullpath = $this->publicpath . '/' . $path;
        $filename = '';
        #fileupload
        $validator = Validator::make($request->all(), [
            $fieldname    => 'required|max:1024|mimes:pdf,doc,docx',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all());
        } else {
            if ($request->hasFile($fieldname)) {
                $file = $request->file($fieldname);
                $filename = uniqid(Auth::id() . '_') . "." . $file->getClientOriginalExtension();
                if (!file_exists($fullpath)) {
                    mkdir($fullpath, 0777, true);
                }
                if (@$inputs['oldfile'] != '') {
                    @unlink($fullpath . '/' . @$inputs['oldfile']);
                }
                $file->move(public_path('uploads/' . $path), $filename);
            }
            if (Candidate::updateOrCreate(['user_id' => Auth::id()], [$path => $filename])) {
                return redirect()->back()->withSuccess(ucfirst(Auth::user()->user_type) . " " . ucfirst(str_replace('_', ' ', $path)) . " Uploaded.");
            } else {
                return redirect()->back()->withError("File Upload Failed.");
            }
        }
    }
}
