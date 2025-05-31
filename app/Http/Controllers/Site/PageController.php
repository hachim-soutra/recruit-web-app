<?php

namespace App\Http\Controllers\Site;

use App\Enum\AdviceStatusEnum;
use App\Enum\EventStatusEnum;
use App\Enum\NewsStatusEnum;
use App\Enum\UserSourceEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\AlertRequest;
use App\Mail\AlertMail;
use App\Mail\ContactNotifyMail;
use App\Mail\ThankyouMail;
use App\Mail\UnsubscribeMail;
use App\Models\Advice;
use App\Models\Contact;
use App\Models\Employer;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Transuction;
use App\Models\Testimonial;
use App\Models\Client;
use App\Models\Team;
use App\Models\User;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Skill;
use App\Models\UserSkill;
use App\Models\Qualification;
use App\Models\UserEducation;
use App\Models\Language;
use App\Models\Candidate;
use App\Services\Common\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\JobPost;
use App\Models\Coach;
use App\Models\Industry;
use App\Models\AboutSetting;
use App\Models\Alert;
use App\Models\LookingStaff;
use App\Models\HomePageContent;
use App\Services\UserService;
use DB;
use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class PageController extends Controller
{
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index()
    {
        $data['banner_stats']['jobs'] = JobPost::count();
        $data['news'] = News::where('status', NewsStatusEnum::SHOW_IN_HOME)->orderBy('id', 'DESC')->get();
        $data['events'] = Event::where('status', EventStatusEnum::SHOW_IN_HOME)->orderBy('id', 'DESC')->get();
        $data['advices'] = Advice::where('status', AdviceStatusEnum::SHOW_IN_HOME)->orderBy('id', 'DESC')->get();
        $data['latest_jobs'] = JobPost::where('show_in_home', '1')->orderBy('created_at', 'desc')->get();
        $data['banner_stats']['companies'] = Employer::count();
        $data['sectors'] = Industry::where('status', '1')->get('name');
        $data['roles'] = config('app.roles');
        $data['employers'] = Employer::whereHas("user", function ($q) {
            $q->where('status', 1);
        })->get('company_name');
        return view('site.pages.welcome', compact('data'));
    }

    public function unsubscribe($email)
    {
        return view('site.pages.unsubscribe', compact('email'));
    }

    public function unsubscribeStore($email, Request $request)
    {
        Mail::to(explode(",", config('app.admin_email')))->send(new UnsubscribeMail($request->motif, $email));
        return redirect()->route('welcome')->withSuccess('Your answer have been registered successfully.');
    }

    public function termOfUse()
    {
        $settings = Setting::where('id', '=', '1')
            ->where('status', '=', '1')
            ->first();

        return view('site.pages.terms_of_use', compact('settings'));
    }

    public function privacy()
    {
        $settings = Setting::where('id', '=', '1')
            ->where('status', '=', '1')
            ->first();

        return view('site.pages.privacy', compact('settings'));
    }

    public function aboutUs()
    {
        $about = AboutSetting::first();
        $team = Team::with('user')->where('status', 'A')->take(4)->orderBy('id', 'DESC')->get();
        return view('site.pages.about_us', compact('about', 'team'));
    }

    public function getInvoice($id)
    {
        $invoice       = Transuction::where('id', base64_decode($id))->with('job', 'user')->first();
        $subscriptions = null;
        // $subscriptions = Subscription::where('transaction_id', $invoice->id)->with('plan')->first();
        return view('site.pages.invoice', compact('invoice', 'subscriptions'));
    }

    public function contactUs()
    {
        return view('site.pages.contact_us');
    }

    public function contact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:50',
            'email'        => 'required|email',
            'phone_number' => 'required|numeric|digits_between:10,12',
            'subject'      => 'required|string|max:190',
            'sms_body'     => 'required|string',
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

        $info               = new Contact();
        $info->name         = $request->input('name');
        $info->email        = $request->input('email');
        $info->phone_number = $request->input('phone_number');
        $info->subject_name = $request->input('subject');
        $info->sms_body     = $request->input('sms_body');

        if ($info->save()) {
            Mail::to($info->email)->send(new ThankyouMail($info));
            $settings = Setting::where('id', '=', '1')
                ->where('status', '=', '1')
                ->first();

            Mail::to($settings->contact_email)->send(new ContactNotifyMail($info));

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Feedback has been submited successfully.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'status'  => "error",
                    'message' => 'Opps error.',
                ],
            ], 200);
        }
    }

    public function blogs($type)
    {
        $result = $this->blogService->find_all_by_type($type);
        $data = array(
            'news' => $result,
            'similartype' => '',
            'type' => $type,
        );
        return view('site.pages.news', compact('data'));
    }

    public function blogDetails($type, $slug)
    {
        if ($slug != '') {
            $result = $this->blogService->find_blog_by_type_and_slug($type, $slug);
            $data = array(
                'news'          => $result['blog'],
                'similar'       => $result['similar'],
                'meta_title'    => $result['meta_title'],
                'meta_desc'     => $result['meta_desc'],
                'type'          => $type
            );
            return view('site.pages.blog-details', compact('data'));
        }
        return redirect()->back();
    }

    public function careerCoach($skill = null)
    {
        $coach = Coach::join('users', function ($join) {
            $join->on('coaches.user_id', '=', 'users.id');
        })
            ->where('users.status', '=', 1)->get();
        if ($skill != null) {
            $coach = Coach::with('user')
                ->where('skill_details', 'LIKE', '%' . $skill . '%')
                ->whereHas('user', function ($q) {
                    $q->where('users.user_type', '=', 'coach');
                })->paginate(12);
        }
        return view('site.pages.careercoach', compact('coach'));
    }

    public function testimonial()
    {
        $data = array(
            'testimonial' => Testimonial::where([['status', '!=', 'D']])->get(),
        );
        return view('site.pages.testimonial', compact('data'));
    }

    public function partnerUs()
    {
        $conditions = [['status', '!=', 'D']];
        $data = array(
            'clients' => Client::where($conditions)->get(),
            'team' => DB::table('users as u')->select('t.*', 'u.name', 'u.avatar as image')
                ->join('teams as t', 't.user_id', '=', 'u.id')
                ->where('t.status', '!=', 'D')
                ->orderBy('id', 'ASC')->get(),
        );
        return view('site.pages.partnerus', compact('data'));
    }

    public function permanentRecruitment()
    {
        $data = LookingStaff::where('page_type', 'parmanent-recruitment')->first();
        $staff = (object)json_decode($data->content, true);
        return view('site.pages.permanentrecruitment', compact('staff', 'data'));
    }

    public function virtualRecruitment()
    {
        $data = LookingStaff::where('page_type', 'virtual-recruitment')->first();
        $staff = (object)json_decode($data->content, true);
        return view('site.pages.virtualrecruitment', compact('staff', 'data'));
    }

    public function techCareersExpo()
    {
        $data = LookingStaff::where('page_type', 'tech-careers')->first();
        $staff = (object)json_decode($data->content, true);
        return view('site.pages.techcareersexpo', compact('staff', 'data'));
    }

    public function jobsExpo()
    {
        $data = LookingStaff::where('page_type', 'jobs-expo')->first();
        $staff = (object)json_decode($data->content, true);
        return view('site.pages.jobsexpo', compact('staff', 'data'));
    }

    public function careerAdvice($slug = null)
    {
        $newscat_id = NewsCategory::where('category_slug', $slug)->first();
        $data = array(
            'news' => News::where('status', '!=', 'D')->where('news_category_id', $newscat_id->id)->orderBy('id', 'DESC')->paginate(3),
            'similartype' => '',
            'type' => 'all',
        );
        return view('site.pages.careeradvice', compact('data'));
    }

    public function alert()
    {
        $industries = Industry::where('status', '1')->orderBy("name")->get();
        return view('site.pages.alert', compact('industries'));
    }

    public function alertStore(AlertRequest $request, UserService $userService)
    {
        $data = $request->validated();
        $data["source"] = UserSourceEnum::ALERT->value;
        $data["password"] = Str::random(10);
        $data["usertype"] = "candidate";
        $user = User::where("email", $request->email)->first();
        if ($user && $user->user_type != 'candidate') {
            throw ValidationException::withMessages(['email' => 'This user exists already.']);
        }
        if (!$user) {
            $user = $userService->register($data);
        }
        $alert = new Alert();
        $alert->candidate_id = $user->candidate->id;
        $alert->industry = $request->industry;
        $alert->salary_period = $request->salary_period;
        $alert->salary_rate = $request->salary_rate;
        $alert->preferred_job_type = $request->preferred_job_type;
        $alert->job_location = $request->job_location;
        $alert->save();

        return redirect()->back()->withSuccess('Job Alerts Created Successfully.');
    }
}
