<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $candidate    = User::where('user_type', 'candidate')->where('status', 1)->count();
            $employer     = User::where('user_type', 'employer')->where('status', 1)->count();
            $coach        = User::where('user_type', 'coach')->where('status', 1)->count();
            $draft_jobs   = JobPost::where('status', 1)->where('job_status', 'Save as Draft')->count();
            $publish_jobs = JobPost::where('status', 1)->where('job_status', 'Published')->whereDate('job_expiry_date', '>', Carbon::now())->count();
            $expire_jobs  = JobPost::where('status', 1)->where('job_status', 'Published')->whereDate('job_expiry_date', '<=', Carbon::now())->count();
            return view('admin.dashboard.index', compact('candidate', 'employer', 'coach', 'draft_jobs', 'publish_jobs', 'expire_jobs'));
        } else {
            return redirect()->route('login');
        }
    }
}
