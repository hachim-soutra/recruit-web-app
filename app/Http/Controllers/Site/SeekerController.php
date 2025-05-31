<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Library\PushNotification;
use App\Models\Industry;
use App\Models\JobPost;
use App\Mail\JobPostMail;
use App\Mail\JobAppliedMail;
use App\Mail\JobAppliedNotifyMail;
use App\Models\Employer;
use App\Models\Bookmark;
use App\Models\JobApply;
use App\Models\JobNotInterested;
use App\Models\JobReport;
use App\Models\User;
use App\Models\Event;
use App\Models\CoachBookmark;
use App\Models\Coach;
use App\Models\State;
use App\Models\Candidate;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Validator;
use File;

class SeekerController extends Controller
{
    public function findJob()
    {
        $data = array(
            'industries' => Industry::where('status', '1')->get()
        );
        return view('site.pages.seeker.findjob', compact('data'));
    }

    public function seekerFindJob(Request $request)
    {
        if (!Auth::id()) {
            session()->put('search_job', $request->all());
            session()->put('frompage', $request->input('frompage'));
            if ($request->input('frompage') == 'welcome') {
                return redirect()->route('common.job-listing');
            } else {
                return redirect()->route('signin');
            }
        }
        #with login#
        $jobpost = array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];
        $keyword           = $request->input("job_title");
        $location          = $request->input("job_location");
        $functional_area   = $request->only("sector");

        $query = JobPost::with('bookmark', 'applicatons')->where($condition)
            ->whereDate('job_expiry_date', '>', Carbon::now());

        if ($keyword != null || $keyword != '') {
            $query->where("job_title", "like", "%" . $keyword . "%");
        }

        if ($location != null || $location != '') {
            $query->where("job_location", "like", "%" . str_replace("Ireland", "", $location) . "%");
        }

        if (isset($functional_area['sector']) && ($functional_area['sector'] != 'Select Sector..') && ($functional_area['sector'] != 'All')) {
            $query->where("functional_area", "like", "%" . $functional_area['sector'] . "%");
        }
        $jobpost = $query->orderBy("created_at", "desc")->paginate(6);

        $firstJobDetail = $jobpost->first();
        $data = array(
            'validSubscription' => auth()->user()?->validSubscription,
            'jobPost'           => $jobpost,
            'firstJobDetail'    => $firstJobDetail,
            'totalResult'       => $jobpost->count()
        );
        return view('site.pages.dashboard', compact('data', 'request'));
    }

    public function favouriteJob()
    {
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];
        $query = Bookmark::where('candidate_id', Auth::id())->orderBy('id', 'DESC')
            ->with('jobs')
            ->whereHas('jobs', function ($q) use ($condition) {
                $q->where($condition);
            });
        $data = array(
            'bookmarks' => $query->paginate(6),
            'totalResult' => $query->count(),
        );
        return view('site.pages.seeker.favouritejob', compact('data'));
    }

    public function events()
    {
        $data = array(
            'events' => Event::where('status', '=', 1)->where('is_delete', '1')->paginate(6),
        );
        return view('site.pages.seeker.event', compact('data'));
    }

    public function eventDetail($eventid)
    {
        $condition = [['is_delete', '=', '1'], ['id', '=', $eventid]];
        $event = Event::where($condition)->first();
        return view('site.pages.seeker.eventdetail', compact('event'));
    }

    public function jobBookMarked($jobid)
    {
        if (Auth::id() != '') {
            if ($jobid) {
                $exist = Bookmark::where(['candidate_id' => Auth::user()->id, 'job_id' => $jobid])->first();
                if ($exist) {
                    $booked = Bookmark::where(['candidate_id' => Auth::user()->id, 'job_id' => $jobid])->delete();
                    return redirect()->back()->withSuccess("Job removed successfully from favourite list.");
                } else {
                    $booked               = new Bookmark;
                    $booked->candidate_id = Auth::user()->id;
                    $booked->job_id       = $jobid;
                    $booked->save();
                    return redirect()->back()->withSuccess("This job added successfully as favourite.");
                }
            }
            return redirect()->back()->withErrors("Select A Job First.");
        } else {
            return redirect()->back()->withError("You Need To Login First.");
        }
    }

    public function searchCandidate()
    {
        $uploaded_resume = 0;
        $uploaded_cl = 0;
        $candidate = Candidate::where('user_id', Auth::id())->first();
        if (!empty($candidate) && ($candidate->resume != '')) {
            $uploaded_resume = 1;
        }
        if (!empty($candidate) && ($candidate->cover_letter != '')) {
            $uploaded_cl = 1;
        }
        return response()->json(['code' => 200, 'uploaded_resume' => $uploaded_resume, 'uploaded_cl' => $uploaded_cl]);
    }

    public function applyJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_id'            => 'required|exists:job_posts,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }

        $applicant = Candidate::where('user_id', Auth::id())->first();
        $applicantCV = $applicant->resume;

        if ($applicantCV == null) {
            return response()->json(['code' => 403, 'msg' => "Please add a CV to apply to jobs."]);
        }

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
            // Mail to Candidate
            Mail::to(Auth::user()->email)->send(new JobAppliedMail($data));
            // Mail to relavent employer
            Mail::to($employer->email)->send(new JobAppliedNotifyMail($e_data));
            // Mail to relavent admins
            Mail::to(explode(",", config('app.admin_email')))->send(new JobAppliedNotifyMail($e_data));

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
            $message = "Congratulations " . Auth::user()->name . "! You have successfully applied for the job " . $job->job_title . ".";
            return response()->json(['code' => 200, 'msg' => $message]);
        } else {
            return response()->json(['code' => 500, 'msg' => "Something went to wrong."]);
        }
    }

    public function careerCoachFind()
    {

        $coach = Coach::join('users', function ($join) {
            $join->on('coaches.user_id', '=', 'users.id');
        })
            ->where('users.status', '=', 1)->get();
        return view('site.pages.seeker.careercoach', compact('coach'));
    }

    public function coachDetail($coachid)
    {
        $meta_title = 'Recruit Coach';
        $meta_desc = 'Recruit Coach';
        if ($coachid != '') {
            $coach = User::with(['coach', 'chatRooms'])->find($coachid);
            $chats = auth()->user() ? $coach->chatRooms->first() : null;

            if ($coach) {
                $meta_title = "Recruit Coach | {$coach->name}";
                $meta_desc = "{$coach->name} is a {$coach->coach->about_us}";
            }

            return view('site.pages.seeker.coachdetail', compact('coach', 'chats', 'meta_title', 'meta_desc'));
        }
        return redirect()->back();
    }

    public function updateResumeCoverletter(Request $request)
    {
        $user_id   = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'resume'        => 'exclude_unless:cover_letter,null|mimes:zip,pdf,doc,docx|max:3072',
            'cover_letter'  => 'exclude_unless:resume,null|mimes:zip,pdf,doc,docx|max:3072',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        } else {
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
                if ($user->cover_letter_path) {
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
                return response()->json([
                    'code'      => 200,
                    'msg'       => "Your Resume && Cover Letter has been updated.",
                    'data'      => $user
                ]);
            } else {
                return response()->json(['code' => 500, 'msg' => "System error please try after sometime"]);
            }
        }
    }

    public function getStateCountry($countryid = null)
    {
        if ($countryid != null) {
            $states = State::where('country_id', $countryid)->get();
            return response()->json(['code' => 200, 'data' => $states]);
        }
    }
}
