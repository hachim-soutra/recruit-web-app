<?php

namespace App\Http\Controllers\Site;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\Common\AdvertiseService;
use App\Services\Common\CompanyService;
use App\Services\Common\ConsentService;
use App\Services\Common\JobPostService;
use App\Services\Payment\PaymentService;
use Error;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Models\JobPost;
use App\Models\JobReport;
use App\Models\Setting;

use App\Models\User;
use App\Models\Transuction;
use App\Models\Notification;
use App\Models\Contact;
use App\Mail\VerifyUser;
use App\Mail\ContactNotifyMail;
use App\Mail\ThankyouMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\Http\Controllers\Site\JobStatisticsController;
use Response;
use App\Models\Coupon;
use App\Models\AboutSetting;
use App\Models\ContactSetting;
use App\Models\Industry;
use App\Models\PlanPackage;
use App\Models\Slot;
use App\Models\Subscription;
use App\Models\SubscriptionSlot;
use App\Services\CampaignService;
use App\Services\Payment\StripeService;
use Stripe;
use Stripe\StripeClient;
use Exception;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cookie;

class CommonController extends Controller
{
    private $publicpath;

    private $paymentService;
    private $jobPostService;
    private $companyService;
    private $advertiseService;
    private $consentService;

    public function __construct(
        PaymentService $paymentService,
        JobPostService $jobPostService,
        CompanyService $companyService,
        AdvertiseService $advertiseService,
        ConsentService $consentService,
    ) {
        $this->publicpath = public_path('uploads/');
        $this->paymentService = $paymentService;
        $this->jobPostService = $jobPostService;
        $this->companyService = $companyService;
        $this->advertiseService = $advertiseService;
        $this->consentService = $consentService;
    }

    public function jobpostAjax(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'description' => 'required|string|max:250',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'error' => $validator->errors()]);
        }
        switch (true) {
            case ($inputs['type'] == 'skill'):
                $ins = new Skill;
                $ins->name = $request->input('name');
                $ins->description = $request->input('description');
                $ins->status = 1;
                $ins->save();
                break;

            default:
                # code...
                break;
        }
        return response()->json(['code' => 200, 'error' => '']);
    }

    public function notification()
    {
        $data = array(
            'notification' => Notification::where('receiver_id', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->whereHas('sender')
                ->whereHas('receiver')
                ->with('sender', 'receiver')->paginate(8)
        );
        return view('site.pages.common.notification', compact('data'));
    }

    public function setting()
    {
        return view('site.pages.common.passwordsetting');
    }

    public function deleteAccount()
    {
        if (Auth::check()) {
            $data = User::where('id', '=', Auth::user()->id)->first();
            User::where('id', Auth::user()->id)
                ->update([
                    'user_key' => Str::random(5) . "d0000" . $data->user_key,
                    'email' => Str::random(5) . "d0000" . $data->email,
                    'mobile' => Str::random(5) . "0000" . $data->mobile,
                    'verified' => 0,
                    'email_verified' => 0,
                    'mobile_verified' => 0,
                    'is_complete' => 0,
                    'status' => 0,
                ]);
            return redirect()->route('logout')->withSuccess('Account Deleted.');
        } else {
            return redirect()->back()->withErrors("System error please try after sometime.");
        }
    }

    public function resetPassword(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Old Password didn\'t match');
                    }
                },
            ],
            'new_password' => 'required|min:8|required_with:confirm_password|same:confirm_password|different:old_password',
            'confirm_password' => 'min:8|required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors());
        }
        if (User::where('id', Auth::id())->update([
            'password' => Hash::make($inputs['new_password']),
            'updated_at' => Carbon::now()->toDateTimeString()
        ])) {
            return redirect()->back()->withSuccess("Password Reset Successful.");
        }
        return redirect()->back()->withErrors("System Error.Try Again Later..");
    }

    public function contactSupport()
    {
        $contact = ContactSetting::first();
        $setting = Setting::first();
        return view('site.pages.common.contactsupport', compact('contact', 'setting'));
    }

    public function contactQuery(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:190',
            'sms_body' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->input())->withErrors($validator->errors());
        }
        $info = new Contact();
        $info->name = Auth::user()->name;
        $info->email = Auth::user()->email;
        $info->phone_number = Auth::user()->mobile;
        $info->subject_name = $request->input('subject');
        $info->sms_body = trim($request->input('sms_body'));
        if ($info->save()) {
            Mail::to($info->email)->send(new ThankyouMail($info));
            $settings = Setting::where('id', '=', '1')
                ->where('status', '=', '1')
                ->first();
            Mail::to($settings->contact_email)->send(new ContactNotifyMail($info));
            return redirect()->back()->withSuccess("Feedback has been submitted successfully.");
        } else {
            return redirect()->back()->withErrors('Opps error.');
        }
    }

    public function profileAboutUs()
    {
        $about = AboutSetting::get();
        return view('site.pages.common.profileaboutus', compact('about'));
    }

    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }
        $user_type = ucfirst(Auth::user()->user_type);
        $coupon = Coupon::where('code', $request->input('coupon_code'))
            ->where('coupon_for', $user_type)
            ->where('status', 1)
            ->first();
        if ($coupon) {
            return response()->json(['code' => 200, 'msg' => "Coupon applied successfully."]);
        }
        return response()->json(['code' => 500, 'msg' => "Invalid Coupon."]);
    }

    #transaction
    public function transaction()
    {
        $subscriptions = $this->paymentService->find_invoices_by_user(Auth::id());
        return view('site.pages.transaction', compact('subscriptions',));
    }

    public function transaction_invoice(string $id)
    {
        $invoice = $this->paymentService->download_invoice($id);
        return redirect()->away($invoice->invoice_pdf);
    }

    public function transaction_invoice_charge(string $id)
    {
        $invoice = $this->paymentService->download_invoice_charge($id);
        return redirect()->away($invoice);
    }

    #file upload
    public function uploadFile(Request $request)
    {
        #fileupload
        $path = 'users/';
        $fullpath = $this->publicpath . '/' . $path;
        if ($request->hasFile('upload_image')) {
            $file = $request->file('upload_image');
            $filename = uniqid(Auth::id() . '_') . "." . $file->getClientOriginalExtension();
            if (!file_exists($fullpath)) {
                mkdir($fullpath, 0777, true);
            }
            if (file_exists($fullpath . '/' . $filename)) {
                @unlink($fullpath . '/' . @$filename);
            }
            $file->move(public_path('uploads/' . $path), $filename);
            User::where('id', Auth::id())->update(['avatar' => $filename]);
            return redirect()->back()->withSuccess(ucfirst(Auth::user()->user_type) . " file Uploaded.");
        }
    }

    public function globalJobListing(Request $request, $id = null)
    {
        $queries = [
            'page'               => $request->get('page'),
            'sort'               => $request->get('sort'),
            'keyword'            => $request->get('keyword'),
            'job_location'       => $request->get('job_location'),
            'start_latitude'     => $request->get('start_latitude'),
            'start_longitude'    => $request->get('start_longitude'),
            'sector'             => $request->get('sector'),
            'roles'              => $request->get('roles'),
            'locations'          => $request->get('locations'),
            'employers'          => $request->get('employers'),
            'sectors'            => $request->get('sectors'),
        ];

        $data = $this->jobPostService->paginate($queries, $id);
        session()->flashInput($request->input());
        return view('site.pages.common.job-listing', compact('data'));
    }

    public function globalJobDetail($id, Request $request)
    {
        $data = $this->jobPostService->jobDetail($id);
        $this->jobPostService->handleJobClickedStats($id, $request->getClientIp());
        return view('site.pages.common.job-detail', compact('data'));
    }

    public function companySearch(Request $request)
    {
        $queries = [
            'page'               => $request->get('page'),
            'company_name'       => $request->get('company'),
            'first_letter'       => $request->get('first_letter')
        ];
        $data = $this->companyService->paginate($queries);
        session()->flashInput($request->input());
        return view('site.pages.common.company-search', compact('data'));
    }

    public function companyDetail($id)
    {
        $data = $this->companyService->companyDetail($id);
        return view('site.pages.common.company-detail', compact('data'));
    }

    public function advertiseJob()
    {
        return view('site.pages.common.advertise-now');
    }

    public function advertiseJobPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_type'          => 'required|in:single,multiple',
            'company_name'      => 'required|string',
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'email'             => 'required|email:rfc,dns',
            'phone'             => 'required|regex:/^0\d*$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }

        $advertise = $this->advertiseService->advertiseJob($validator->validated());

        if ($advertise != null) {
            return response()->json([
                'code'      => 200,
                'msg'       => "Advertise job form submitted successfully.",
                'data'      => $advertise
            ]);
        } else {
            return response()->json(['code' => 500, 'msg' => "System error please try after sometime"]);
        }
    }

    public function update_SearchTerms_OLD($keyword)
    {
        $keyword = trim(strtolower($keyword));
        if ($keyword != '') {

            $IP = trim($_SERVER['REMOTE_ADDR']);
            $IP_array = array();

            //$data = $stats_db->get_row( "SELECT * FROM {$stats_db->prefix}hits WHERE post_id = {$postId} AND details ='{$details}' AND DATE(stat_date) = '".date('Y-m-d')."' AND type='term' AND site_id='".SITE_TERM."' LIMIT 1" );

            $data = DB::table('job_hits')->where('type', '=', 'term')->where('app_type', '=', 'web')->where('post_id', '=', 0)->where('details', '=', $keyword)->whereDate('stat_date', '=', Carbon::now())->get();

            if (empty($data) || $data == NULL || $data->count() == 0) {

                $IP_array[] = $IP;
                //$IP_array=array_unique($IP_array);
                $IP_array = array_filter($IP_array);

                DB::table('job_hits')->insert([
                    'type' => 'term',
                    'post_id' => 0,
                    'details' => $keyword,
                    'stat_date' => date('Y-m-d H:i:s'),
                    'stat_count' => count($IP_array),
                    'IPs' => implode(',', $IP_array),
                    'app_type' => 'web'
                ]);

                //echo 'INSERT';

            } else {

                $data = $data[0];

                $IP_array = explode(',', $data->IPs);
                $IP_array[] = $IP;
                //$IP_array=array_unique($IP_array);
                $IP_array = array_filter($IP_array);

                $affected = DB::table('job_hits')
                    ->where('id', intval($data->id))
                    ->update([
                        'stat_count' => count($IP_array),
                        'IPs' => implode(',', $IP_array),
                        'stat_date' => date('Y-m-d H:i:s')
                    ]);

                //echo 'UPDATE';
            }
        }
    }

    public function jobListingApply(Request $request)
    {
        if ($request->input('jobid') != '') {
            $jobapply = array(
                'where' => 'joblist',
                'jobid' => $request->input('jobid'),
                'for' => 'seeker',
                'loginid' => Auth::id(),
            );
            session()->put('jobapply', $jobapply);
            return response()->json(['code' => 200, 'jobapply' => $jobapply]);
        }
        return response()->json(['code' => 500]);
    }

    public function tagRedirect($slug = '')
    {
        $slug = trim($slug);
        //die($slug);

        if ($slug != '')
            return redirect()->away("https://www.recruit.ie/careers/tag/{$slug}/");

        else abort(404);
    }

    public function acceptConsent($type, Request $request)
    {
        if ($type == 'job') {
            Cookie::queue('jobConsent', true, 60 * 24 * 365);
            return redirect()->route('alert');
//            $this->consentService->handleJobConsent(['email' => $request->get('consent_email')]);
        } else {
            Cookie::queue('consent', true, 60 * 24 * 365);
        }

        return redirect()->route('welcome');
    }

    public function rejectConsent($type)
    {
        if ($type == 'job') {
            Cookie::queue('jobConsent', false, 60 * 24 * 365);
        } else {
            Cookie::queue('consent', false, 60 * 24 * 365);
        }
        return redirect()->back();
    }

    public function stripeSub()
    {
        $user = auth()->user();
        $session = $user->stripe()->checkout->sessions->retrieve(request()->query('session_id'));
        $subscription = $user->stripe()->subscriptions->retrieve($session["subscription"]);
        $planPackage = PlanPackage::where("stripe_plan", $subscription["plan"]["id"])->first();
        $subscriptionModel = new Subscription();
        $subscriptionModel->user_id = $user->id;
        $subscriptionModel->plan_package_id = $planPackage->id;
        $subscriptionModel->status = !$user?->valid_subscription?->balanceIsValid() ? SubscriptionStatusEnum::IN_USE->value : SubscriptionStatusEnum::PURCHASED->value;
        $subscriptionModel->start_date = now();
        $subscriptionModel->estimated_end_date = now()->addMonths($planPackage->number_of_month);
        $subscriptionModel->reel_end_date = now()->addMonths($planPackage->number_of_month);
        $subscriptionModel->subscription_id = $subscription["id"];
        $subscriptionModel->name = $subscription["plan"]["object"];
        $subscriptionModel->stripe_id = $subscription["default_payment_method"];
        $subscriptionModel->stripe_status = $subscription["plan"]["active"] ? 'active' : '';
        $subscriptionModel->stripe_price = $subscription["plan"]["id"];
        $subscriptionModel->quantity = $subscription["plan"]["interval_count"];

        $subscriptionModel->save();
        return redirect()->route("subscription")->with('success', 'Thanks for payement!');
    }
    public function stripePayement()
    {
        $user = auth()->user();
        $session = $user->stripe()->checkout->sessions->retrieve(request()->query('session_id'));
        $paymentIntent = $user->stripe()->paymentIntents->retrieve($session["payment_intent"]);
        $slot = Slot::first();
        $subscription = $user->subscriptions()
            ->where("status", SubscriptionStatusEnum::IN_USE->value)
            ->firstOrFail();
        $subscriptionSlot = new SubscriptionSlot();
        $subscriptionSlot->slot_id = $slot->id;
        $subscriptionSlot->subscription_id = $subscription->id;
        $subscriptionSlot->payment_method_token = $paymentIntent['payment_method'];
        $subscriptionSlot->charge_token = $paymentIntent['latest_charge'];
        $subscriptionSlot->save();
        return redirect()->route("subscription")->with('success', 'Thanks for payement!');
    }

    public function adminStripePayement(StripeService $stripeService)
    {
        return $stripeService->create_subscription(request()->query('session_id'));
    }

    public function cancelStripePayement()
    {
        return redirect()->route("subscription");
    }

    public function fetchIndustries()
    {
        $data = Industry::where('status', '1')->orderBy("name")->get();
        return response()->json([
            "data" => $data
        ]);
    }

    public function fetchCandidates(CampaignService $campaignService)
    {
        $data = $campaignService->candidates()->get();
        return response()->json([
            "data" => $data
        ]);
    }
    public function fetchJobs(CampaignService $campaignService)
    {
        $data = $campaignService->jobs();
        return response()->json([
            "data" => $data
        ]);
    }
}
