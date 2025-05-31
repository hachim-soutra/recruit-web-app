<?php

namespace App\Http\Controllers\Site;

use App\Helpers\MyHelper;
use App\Http\Controllers\Controller;
use App\Library\PushNotification;
use App\Mail\JobPostMail;
use App\Mail\JobAppliedMail;
use App\Mail\JobAppliedNotifyMail;
use App\Models\Balance;
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
use App\Models\Subscription;
use App\Models\User; 
use App\Models\UserEducation; 
use App\Models\Language; 
use App\Models\Coach; 
use App\Models\Candidate; 
use App\Models\Qualification; 
use App\Models\WorkExperience; 
use App\Models\CompanyBookmark;
use App\Models\Package; 
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

class EmployerController extends Controller
{
    public function subscription(){
        $data = array(
            'subscription' => Subscription::where('user_id', Auth::id())->get(),
            'packages' => Package::where('status', '1')->get(),
        );
        return view('site.pages.subscription', compact('data'));
    }

    public function transaction(){
        $data = array(
            'transactions' => Transuction::where('user_id', Auth::id())->get(),
        );
        return view('site.pages.transaction', compact('data'));
    }

    public function transactionInvoice($rowid){
        $invoice = Transuction::with('user')->where('id', $rowid)->first();
        $subscriptions = Subscription::with('plan')->where('transaction_id', $rowid)->first();
        return view('site.pages.invoice', compact('invoice', 'subscriptions'));
    }
}
