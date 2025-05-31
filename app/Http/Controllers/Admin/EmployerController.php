<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Http\Controllers\Controller;
use App\Mail\Welcome;
use App\Models\Country;
use App\Models\Employer;
use App\Models\JobPost;
use App\Models\JobApply;
use App\Models\JobStat;
use App\Models\PlanPackage;
use App\Models\Subscription;
use App\Models\Transuction;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;
use Validator;

class EmployerController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        $keyword = '';
        if ($request == null || $request == '') {

            $request = (object)[];
            $request->keyword = $keyword = trim(request('keyword'));
        } else {
            $keyword = trim($request->input('keyword', null));
        }

        $data    = User::orderBy('updated_at', 'DESC')
            ->with(['employer'])
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'archived') {
                    $q->where('status', 0);
                }
            })
            ->where('user_type', 'employer')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('user_key', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.employer.list', compact('data', 'request'));
    }

    public function create(Request $request)
    {
        $countries = Country::all();

        return view('admin.employer.add', compact('countries'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => 'required|email|unique:users,email',
            'mobile'          => 'nullable|unique:users,mobile',
            'phone_number'    => 'nullable|unique:users,mobile',
            'established_in'  => 'nullable|date',
            'zip'             => 'nullable',
            'address'         => 'required',
            'company_name'    => 'required',
            'linkedin_link'   => 'nullable|url',
            'website_link'    => 'required|url',
            'tag_line'        => 'nullable',
            'company_ceo'     => 'nullable',
            'company_details' => 'required',
            'company_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        try {
            DB::beginTransaction();
            $user                    = new User();
            $user->name              = $request->input('first_name') . " " . $request->input('last_name');
            $user->first_name        = $request->input('first_name');
            $user->last_name         = $request->input('last_name');
            $user->user_type         = "employer";
            $user->mobile            = $request->input('phone_number');
            $user->user_key          = 'JP' . rand(100, 999) . date("his");

            //$user->password          = 'password';
            $user_password           = trim(Str::random(9));
            $user->password          = Hash::make($user_password);

            $user->verify_token      = Str::random(12) . rand(10000, 99999);
            $user->email             = $request->input('email');
            $user->remember_token    = Str::random(10);
            $user->verified          = 1;
            $user->email_verified    = 1;
            $user->mobile_verified   = 1;
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            if ($request->hasFile('avatar')) {

                $time      = Carbon::now();
                $file      = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/users/'), $filename);
                $user->avatar = $filename;
            }

            $user->save();
            $user->syncRoles([2]);

            //Mail::to($user->email)->send(new Welcome($user, 'password'));
            Mail::to($user->email)->send(new Welcome($user, $user_password));

            $user_profile                  = new Employer();
            $user_profile->user_id         = $user->id;
            $user_profile->address         = $request->input('address');
            $user_profile->city            = $request->input('city');
            $user_profile->state           = $request->input('state');
            $user_profile->country         = $request->input('country');
            $user_profile->zip             = $request->input('zip');
            $user_profile->company_ceo     = $request->input('company_ceo');
            $user_profile->phone_number    = $request->input('phone_number');
            $user_profile->company_details = $request->input('company_details');
            $user_profile->linkedin_link   = $request->input('linkedin_link');
            $user_profile->website_link    = $request->input('website_link');
            $user_profile->company_name    = $request->input('company_name');
            $user_profile->tag_line        = $request->input('tag_line');
            $user_profile->established_in  = $request->input('established_in');
            if ($request->hasFile('company_logo')) {

                $time      = Carbon::now();
                $file      = $request->file('company_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/company_logo/'), $filename);
                $user_profile->company_logo = $filename;
            }

            $user_profile->save();

            DB::commit();

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Employer has been added successfully.",
                ],
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                    "message2" => $th->getMessage()
                ],
            ], 200);
        }
    }

    public function edit($id)
    {
        $employer = User::where('id', $id)->first();
        $profile  = Employer::where('user_id', '=', $id)->first();

        return view('admin.employer.edit', compact('employer', 'profile'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'first_name'      => 'required|string',
            'last_name'       => 'required|string',
            'email'           => 'required|email|unique:users,email,' . $id,
            'mobile'          => 'nullable|unique:users,mobile,' . $id,
            'established_in'  => 'nullable|date',
            'zip'             => 'nullable',
            'address'         => 'required',
            'company_name'    => 'required',
            'linkedin_link'   => 'nullable|url',
            'website_link'    => 'required|url',
            'tag_line'        => 'nullable',
            'company_ceo'     => 'nullable',
            'phone_number'    => 'nullable|unique:users,mobile,' . $id,
            'company_details' => 'required',
            'company_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user             = User::findOrFail($id);
        $user->name       = $request->input('first_name') . " " . $request->input('last_name');
        $user->first_name = $request->input('first_name');
        $user->last_name  = $request->input('last_name');
        $user->email      = $request->input('email');
        $user->mobile     = $request->input('mobile', null);
        $user->updated_at = Carbon::now()->toDateTimeString();
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                if (File::exists("uploads/users/" . $user->path)) {
                    File::delete("uploads/users/" . $user->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/users/'), $filename);
            $user->avatar = $filename;
        }

        $user->save();
        // $roles = $request->roles;
        // $user->syncRoles($roles);

        if ($user) {
            $company_logo = "";
            if ($request->hasFile('company_logo')) {

                $time      = Carbon::now();
                $file      = $request->file('company_logo');
                $extension = $file->getClientOriginalExtension();
                $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
                $file->move(public_path('uploads/company_logo/'), $filename);
                $company_logo = $filename;
            }

            Employer::where('user_id', $id)
                ->update([
                    'established_in'  => $request->input('established_in'),
                    'address'         => $request->input('address'),
                    'zip'             => $request->input('zip'),
                    'company_ceo'     => $request->input('company_ceo'),
                    'phone_number'    => $request->input('phone_number'),
                    'company_details' => $request->input('company_details'),
                    'linkedin_link'   => $request->input('linkedin_link'),
                    'website_link'    => $request->input('website_link'),
                    'company_name'    => $request->input('company_name'),
                    'tag_line'        => $request->input('tag_line'),
                    'company_logo'    => $company_logo,
                ]);

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Employer has been updated successfully.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ], 200);
        }
    }

    public function show($id)
    {
        $data         = User::where('id', $id)->with('employer')->first();
        $transactions = Transuction::where('user_id', $id)->with('job')->get();

        return view('admin.employer.show', compact('data', 'transactions'));
    }

    public function archive($id)
    {
        $res = User::where('id', $id)
            ->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User has been Updated",
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

    public function restore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User has been Updated",
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

    public function emailArchive($id)
    {
        $res = User::where('id', $id)
            ->update([
                'email_verified' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User email verified has been Updated",
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

    public function emailRestore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'email_verified' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "User email verified has been Updated",
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

    public function mobileArchive($id)
    {
        $res = User::where('id', $id)
            ->update([
                'mobile_verified' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Mobile verified has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record verified not Found",
                ],
            ], 200);
        }
    }

    public function mobileRestore($id)
    {
        $res = User::where('id', $id)
            ->update([
                'mobile_verified' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Mobile has been Updated",
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

    public function employersJob(Request $request, $id)
    {
        $keyword = $request->input('keyword', null);

        $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type', 'additinal_pay', 'status', 'job_status', 'created_at')->orderBy('id', 'DESC')
            ->with('employer')
            ->where('status', 1)
            // ->where('created_by', $id)
            ->where('employer_id', $id)
            // ->where('job_status', 'Published')
            //  ->whereDate('job_expiry_date', '>', Carbon::now())
            ->where(function ($query) use ($keyword) {
                if ($keyword) {
                    $query->orWhere("job_title", "like", "%" . $keyword . "%");
                }
            })
            ->withCount('applicatons')
            ->paginate(15);

        $user = User::where('id', $id)->first();
        // ->get();
        return view('admin.employer.job_list', compact('data', 'request', 'user'));
    }

    public function employersJobApplicant(Request $request, $id)
    {
        $stats = ['CLICK' => 0, 'VIEW' => 0];
        $data = JobApply::where('job_id', $id)
            ->with('jobs', 'candidate', 'candidate.candidate')
            ->paginate(15);
        $job = JobPost::where('id', $id)->first();
        $jobStats = JobStat::select('type', DB::raw('count(*) as total'))->where('job_id', $id)->groupBy('type')->pluck('total', 'type')->toArray();
        if (isset($jobStats['CLICK'])) {
            $stats['CLICK'] = $jobStats['CLICK'];
        }
        if (isset($jobStats['VIEW'])) {
            $stats['VIEW'] = $jobStats['VIEW'];
        }
        return view('admin.employer.job_applicant_list', compact('data', 'request', 'job', 'stats'));
    }

    public function freeSUbscription($id)
    {
        $user = User::where('id', $id)->with('employer')->first();
        if ($user->valid_subscription) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "This Employer already have a subscription.",
                ],
            ]);
        }
        $planPackage = PlanPackage::where('price', 0)->first();
        $subscription = Subscription::create([
            "user_id" => $user->id,
            "subscription_id" => null,
            "plan_package_id" => $planPackage->id,
            "stripe_id" => null,
            "estimated_end_date" => now()->addMonths($planPackage->number_of_month),
            "reel_end_date" => now()->addMonths($planPackage->number_of_month),
            "status" => !$user?->valid_subscription?->balanceIsValid() ? SubscriptionStatusEnum::IN_USE->value : SubscriptionStatusEnum::PURCHASED->value
        ]);
        if ($subscription->id) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Free Plan has been provided successfully.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ]);
        }
    }
}
