<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Industry;
use App\Models\JobApply;
use App\Models\JobPost;
use App\Models\Qualification;
use App\Models\Skill;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Admin\JobPostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

class JobPostController extends Controller
{

    private $jobPostService;

    public function __construct(JobPostService $jobPostService)
    {
        $this->jobPostService = $jobPostService;
    }

    public function index(Request $request)
    {
        $keyword = $request->input("keyword", null);
        $data    = JobPost::where('status', '1')->orderBy('id', 'DESC')
            ->with('employer')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere("job_title", "like", "%" . $keyword . "%");
                    $q->orWhere("created_at", "like", "%" . $keyword . "%");
                    $q->orWhere('job_status', '=', $keyword);

                    $q->orWhereHas('employer', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');

                    });
                }
            })

            ->paginate(20);
        return view('admin.job_post.list', compact('data', 'request'));
    }

    public function indexWelcome(Request $request)
    {
        $keyword = $request->input("keyword", null);
        $data    = JobPost::where('show_in_home', '1')->orderBy('id', 'DESC')
            ->with('employer')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere("job_title", "like", "%" . $keyword . "%");
                    $q->orWhere("created_at", "like", "%" . $keyword . "%");
                    $q->orWhere('job_status', '=', $keyword);

                    $q->orWhereHas('employer', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');

                    });
                }
            })

            ->paginate(20);
        return view('admin.job_post.welcome', compact('data', 'request'));
    }

    public function listonly($type = null, $keyword = null){
        if($type != null){
            $data    = JobPost::where('status', '1')->where('job_status', $type)->orderBy('id', 'DESC')
                ->with('employer')
                ->where(function ($q) use ($keyword) {
                    if ($keyword) {
                        $q->orWhere("job_title", "like", "%" . $keyword . "%");
                        $q->orWhere("created_at", "like", "%" . $keyword . "%");

                        $q->orWhereHas('employer', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', '%' . $keyword . '%');

                        });
                    }
                })->paginate(20);
            return view('admin.job_post.listonly', compact('data', 'type'));
        }
        return redirect()->route('admin.job-post.list');
    }

    public function create()
    {
        $countries      = Country::all();
        $skills         = Skill::all();
        $functionals    = Industry::all();
        $qualifications = Qualification::all();
        $employers      = User::where('status', 1)->where('user_type', 'employer')->get();

        return view('admin.job_post.add', compact('countries', 'employers', 'skills', 'functionals', 'qualifications'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_title'          => 'required|max:190',
            'job_location'       => 'required',
            'city'               => 'nullable',
            'state'              => 'nullable',
            'country'            => 'nullable',
            'zip'                => 'nullable',
            'job_skills'         => 'required|array|min:1',
            'functional_area'    => 'required',
            'preferred_job_type' => 'required',
            'experience'         => 'required',
            'total_hire'         => 'required',
            'job_details'        => 'required',
            'employer'           => 'required',
            'job_expiry_date'    => 'required|date|date_format:Y-m-d|after:today',
            'salary_from'        => 'required_if:hide_salary,no',
            'salary_to'          => 'required_if:hide_salary,no',
            'salary_currency'    => 'required_if:hide_salary,no',
            'salary_period'      => 'required',
            'hide_salary'        => 'required',
            'qualifications'     => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $subscription = Subscription::where([
            'user_id'       => $request->input('employer'),
            'status'        => SubscriptionStatusEnum::IN_USE
        ])->first();

        if (!$subscription) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry the employer not have a valid subscription.',
                ],
            ], 200);
        }

        $post                     = new JobPost;
        $post->job_title          = $request->input('job_title');
        $post->job_location       = $request->input('job_location');
        $post->city               = $request->input('city', null);
        $post->state              = $request->input('state', null);
        $post->country            = $request->input('country', null);
        $post->zip                = $request->input('zip', null);
        $post->job_skills         = json_encode($request->input('job_skills'));
        $post->functional_area    = $request->input('functional_area', null);
        $post->preferred_job_type = $request->input('preferred_job_type', null);
        $post->experience         = $request->input('experience', null);
        $post->total_hire         = $request->input('total_hire', null);
        $post->job_details        = $request->input('job_details', null);
        $post->employer_id        = $request->input('employer');
        $post->subscription_id    = $subscription->id;
        $post->job_expiry_date    = $request->input('job_expiry_date', Carbon::now()->addMonths(6));	/* AND */
        if ($request->input('hide_salary') == 'no') {
            $post->salary_from     = $request->input('salary_from');
            $post->salary_to       = $request->input('salary_to');
            $post->salary_currency = $request->input('salary_currency', '€');
        } else {
            $post->salary_from     = null;
            $post->salary_to       = null;
            $post->salary_currency = $request->input('salary_currency', '€');
        }

        $post->salary_period  = $request->input('salary_period');
        $post->hide_salary    = $request->input('hide_salary');
        $post->qualifications = json_encode($request->input('qualifications'));
        $post->payment_status = "Paid";
        $post->job_status     = "Published";
        $post->created_by     = Auth::user()->id;

        if ($post->save()) {

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Job post has been created successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the job post.',
                ],
            ], 200);
        }

    }

    public function show($id)
    {
        $job = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'qualifications', 'city', 'state', 'country', 'zip', 'job_skills', 'functional_area', 'preferred_job_type', 'experience', 'total_hire', 'job_details', 'status', 'created_by', 'updated_by', )->where('id', $id)
            ->with('employer')
            ->first();
        $skill = json_decode($job->job_skills, true);

        $qualification = json_decode($job->qualifications, true);

        return view('admin.job_post.show', compact('job', 'skill', 'qualification'));
    }

    public function edit($id)
    {
        $countries      = Country::all();
        $skills         = Skill::all();
        $functionals    = Industry::all();
        $qualifications = Qualification::all();

        $employers = User::where('status', 1)->where('user_type', 'employer')->get();
        $job       = JobPost::where('id', $id)->first();

        return view('admin.job_post.edit', compact('job', 'countries', 'skills', 'functionals', 'employers', 'qualifications'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'job_title'          => 'required|max:190',
            'job_location'       => 'required',
            'city'               => 'nullable',
            'state'              => 'nullable',
            'country'            => 'nullable',
            'zip'                => 'nullable',
            'job_skills'         => 'required|array|min:1',
            'functional_area'    => 'required',
            'preferred_job_type' => 'required',
            'experience'         => 'required',
            'total_hire'         => 'required',
            'job_details'        => 'required',
            'employer'           => 'required',
            'job_expiry_date'    => 'required|date|date_format:Y-m-d|after:today',
            'salary_from'        => 'required_if:hide_salary,no',
            'salary_to'          => 'required_if:hide_salary,no',
            'salary_currency'    => 'required_if:hide_salary,no',
            'salary_period'      => 'required',
            'hide_salary'        => 'required',
            'qualifications'     => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $subscription = Subscription::where([
            'user_id'       => $request->input('employer'),
            'status'        => SubscriptionStatusEnum::IN_USE
        ])->first();

        if (!$subscription) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry the employer not have a valid subscription.',
                ],
            ], 200);
        }

        $post                     = JobPost::where('id', $id)->first();

        $post->job_title          = $request->input('job_title');
        $post->job_location       = $request->input('job_location');
        $post->city               = $request->input('city', null);
        $post->state              = $request->input('state', null);
        $post->country            = $request->input('country', null);
        $post->zip                = $request->input('zip', null);
        $post->job_skills         = json_encode($request->input('job_skills'));
        $post->functional_area    = $request->input('functional_area', null);
        $post->preferred_job_type = $request->input('preferred_job_type', null);
        $post->experience         = $request->input('experience', null);
        $post->total_hire         = $request->input('total_hire', null);
        $post->job_details        = $request->input('job_details', null);
        $post->employer_id        = $request->input('employer');
        $post->subscription_id    = $subscription->id;

        $post->job_expiry_date    = $request->input('job_expiry_date', Carbon::now()->addMonths(6));			/* AND */
		if( $post->job_status == 'Published' && $post->job_expiry_date > Carbon::now() ) $post->status = 1;		/* AND */

        if ($request->input('hide_salary') == 'no') {
            $post->salary_from     = $request->input('salary_from');
            $post->salary_to       = $request->input('salary_to');
            $post->salary_currency = $request->input('salary_currency', '€');
        } else {
            $post->salary_from     = null;
            $post->salary_to       = null;
            $post->salary_currency = $request->input('salary_currency', '€');
        }

        $post->salary_period  = $request->input('salary_period');
        $post->hide_salary    = $request->input('hide_salary');
        $post->qualifications = json_encode($request->input('qualifications'));

        if ($post->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Job post has been updated successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the job post.',
                ],
            ], 200);
        }
    }

    public function destroy($id)
    {
        $post = JobPost::where('id', $id)->first();
        if ($post) {
            $post->status     = 0;
            $post->updated_by = Auth::user()->id;
            $post->save();
            return response()->json([
                'success' => [
                    'message' => "Job post has been removed",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Job post Not Found",
                ],
            ], 200);
        }
    }

    public function status(Request $request)
    {
        $post = JobPost::where('id', $request->input('id'))->first();
        if ($post) {
            $post->status     = $request->input('status');
            $post->updated_by = Auth::user()->id;
            $post->save();
            return response()->json([
                'success' => [
                    'message' => "Job post has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Job post Not Found",
                ],
            ], 200);
        }
    }

    public function import(Request $request)
    {
        $countries      = Country::all();
        $skills         = Skill::all();
        $functionals    = Industry::all();
        $qualifications = Qualification::all();
        $employers      = User::where('status', 1)->where('user_type', 'employer')->get();

        return view('admin.job_post.import', compact('employers'));
    }

    public function importJob(Request $request)
    {
        //return "Hello";
        $validator = Validator::make($request->all(), [
            'import_file' => 'max:10240|required|mimes:csv,txt',
            'employer'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        if ($request->hasFile('import_file')) {
            $path   = $request->file('import_file')->getRealPath();
            $header = null;
            $data   = array();
            if (($handle = fopen($path, 'r')) !== false) {
                while (($row = fgetcsv($handle, 1000)) !== false) {
                    if (!$header) {
                        $header = $row;
                    } else {
                        $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }
            // return $data;
            foreach ($data as $value) {
                $skills         = explode(',', $value['job_skills']);
                $qualifications = explode(',', $value['qualifications']);

                foreach ($skills as $i) {
                    $user = Skill::firstOrCreate(
                        ['name' => $i],
                        ['description' => $i]
                    );

                    // $user = Skill::where('name', $i)->firstOr(function ($i) {

                    //     Skill::create([
                    //         'description' => $i,
                    //         'name'        => $i,
                    //     ]);
                    // });
                }

                foreach ($qualifications as $e) {
                    $user = Qualification::firstOrCreate(
                        ['name' => $e],
                        ['description' => $e]
                    );
                }

				$job_expiry_date = trim($value['job_expiry_date']);
				if($job_expiry_date == '') $job_expiry_date = Carbon::now()->addMonths(6);	/* AND */

                $post                     = new JobPost;
                $post->job_title          = $value['job_title'];
                $post->job_location       = $value['job_location'];
                $post->city               = $value['city'];
                $post->state              = $value['state'];
                $post->country            = $value['country'];
                $post->zip                = $value['zip'];
                $post->job_skills         = json_encode(explode(',', $value['job_skills']));
                $post->functional_area    = $value['functional_area'];
                $post->preferred_job_type = $value['preferred_job_type'];
                $post->experience         = $value['experience'];
                $post->total_hire         = $value['total_hire'];
                $post->job_details        = $value['job_details'];
                $post->employer_id        = $request->input('employer');
                $post->job_expiry_date    = $job_expiry_date;
                $post->salary_from        = $value['salary_from'];
                $post->salary_to          = $value['salary_to'];
                $post->salary_currency    = "€";
                $post->salary_period      = $value['salary_period'];
                $post->hide_salary        = $value['hide_salary'];
                $post->qualifications     = json_encode(explode(',', $value['qualifications']));
                $post->payment_status     = "Paid";
                $post->job_status         = "Published";
                $post->created_by         = Auth::user()->id;
                $post->save();

            }

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Job post has been created successfully.',
                ],
            ], 200);

        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the job post.',
                ],
            ], 200);
        }

    }

    public function changeJobStatus(Request $request)
    {
        $res = JobPost::where('id', $request->id)
            ->update([
                'job_status' => $request->status,
                'updated_by' => Auth::id()
            ]);
        $message = $request->status;
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Job has been " . $message,
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function applicants(Request $request, $job_id)
    {
        $data = JobApply::where('job_id', $job_id)
        ->with('jobs', 'candidate', 'candidate.candidate')
        ->paginate(15);

        $job = JobPost::where('id', $job_id)->first();

        return view('admin.job_post.job_applicant_list', compact('data', 'request', 'job'));
    }

    public function expirejobs(){
        $data = JobPost::whereDate('job_expiry_date', '<', Carbon::now())->paginate(10);
        return view('admin.job_post.expire', compact('data'));
    }

    public function expireJobUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'rowid'        => 'required',
            'job_expire_date'    => 'required|date|date_format:Y-m-d|after:today',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'msg' => $validator->errors()->messages()]);
        }else{
            $post_detail = JobPost::with('employer')->where('id', $request->input('rowid'))->first();
            $employer_id = $post_detail->employer_id;
            $update = JobPost::where('id', $request->input('rowid'))->update([
                'job_status' => 'Published',
                'status' => 1, /* AND */
                'job_expiry_date' => date('Y-m-d', strtotime($request->input('job_expire_date'))),
                'updated_by' => Auth::id()
            ]);
            if($update){
                return response()->json(['code' => 200, 'error' => '', 'msg' => 'Expired job update and published.']);
            }else{
                return response()->json(['code' => 401, 'error' => '', 'msg' => 'Something Went Wrong.']);
            }
        }
    }

    public function showHomeJobUpdate($id){
        $update = $this->jobPostService->handleShowJobInHome($id);
        if($update){
            return response()->json(['code' => 200, 'error' => '', 'msg' => 'Show job in home update and published.']);
        }else{
            return response()->json(['code' => 401, 'error' => '', 'msg' => 'Something Went Wrong.']);
        }
    }
}
/* EoF */
