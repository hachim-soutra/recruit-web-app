<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Institute;
use App\Models\Subject;
use App\Models\Tutor;
use App\Models\TutorBankDetails;
use App\Models\TutorDeclaration;
use App\Models\TutorEducation;
use App\Models\TutorEducationSubject;
use App\Models\TutorFeesInfo;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class TutorController extends Controller
{
    public function index($type = null, Request $request = null)
    {
        //$keyword = $request->input('keyword', null);
		$keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
		
        $data    = User::orderBy('updated_at', 'DESC')
            ->where(function ($q) use ($type) {
                if ($type === 'verified') {
                    $q->where(['status' => 1, 'verified' => 1]);
                }
                if ($type === 'pending') {
                    $q->where(['status' => 1, 'verified' => 0]);
                }
                if ($type === 'rejected') {
                    $q->where(['status' => 1, 'verified' => 0, 'rejected' => 1]);
                }
            })
            ->where('user_type', 'tutor')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('user_key', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->with('tutor')
            ->paginate(15);
        return view('admin.tutor.list', compact('data', 'request'));
    }

    public function showAccountDetails($id)
    {
        $user = User::where('id', $id)
            ->with('subjects')
            ->first();
        $tutor = Tutor::where('user_id', $id)
            ->first();

        return view('admin.tutor.account_details', compact('user', 'tutor'));
    }

    public function editAccountDetails($id)
    {
        $countries = Country::orderBy('name', 'ASC')
            ->get();
        $subjects = Subject::orderBy('name', 'ASC')
            ->get();
        $user = User::where('id', $id)
            ->with('subjects')
            ->first();
        $tutor = Tutor::where('user_id', $id)
            ->first();
        return view('admin.tutor.edit_account_details', compact('subjects', 'countries', 'user', 'tutor'));
    }

    public function updateAccountDetails(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users,email,' . $id,
            'mobile'                => 'nullable|unique:users,mobile,' . $id,
            'address'               => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            'country'               => 'required',
            'zip'                   => 'required',
            'bio'                   => 'required|string|max:3000',
            'native_language'       => 'required',
            'other_known_languages' => 'nullable',
            'subjects'              => 'required|array|min:1',
            'other_known_subjects'  => 'nullable',
            'teaching_philosophy'   => 'required|string|max:3000',
            'teaching_history'      => 'required|string|max:3000',
            'other_interest'        => 'required|string|max:3000',
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
            $user = User::where('id', $id)
                ->first();
            $user->name       = $request->input('name');
            $user->email      = $request->input('email');
            $user->mobile     = $request->input('mobile');
            $user->updated_at = Carbon::now()->toDateTimeString();
            $user->save();
            $subjects = $request->subjects;
            $user->subjects()->sync($subjects);
            $tutor = Tutor::where('user_id', $user->id)->first();
            if (!$tutor) {
                $tutor          = new Tutor;
                $tutor->user_id = Auth::user()->id;
            }
            $tutor->address                    = $request->input('address');
            $tutor->city                       = $request->input('city');
            $tutor->state                      = $request->input('state');
            $tutor->country                    = $request->input('country');
            $tutor->zip                        = $request->input('zip');
            $tutor->native_language            = $request->input('native_language');
            $tutor->other_known_languages      = $request->input('other_known_languages', null);
            $tutor->other_known_subjects       = $request->input('other_known_subjects', null);
            $tutor->bio                        = $request->input('bio');
            $tutor->active_bio                 = $request->input('bio');
            $tutor->teaching_philosophy        = $request->input('teaching_philosophy');
            $tutor->active_teaching_philosophy = $request->input('teaching_philosophy');
            $tutor->teaching_history           = $request->input('teaching_history');
            $tutor->active_teaching_history    = $request->input('teaching_history');
            $tutor->other_interest             = $request->input('other_interest');
            $tutor->active_other_interest      = $request->input('other_interest');
            $tutor->updated_at                 = Carbon::now()->toDateTimeString();
            $tutor->save();
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Tutor account has been updated successfully.',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while oparation.',
                ],
            ], 200);
        }
    }

    public function showAcademicHistory($id)
    {
        $user = User::where('id', $id)
            ->first();
        $edu = TutorEducation::where('user_id', $id)
            ->with('institute', 'subjects')
            ->get();
        return view('admin.tutor.academic_history', compact('user', 'edu'));
    }

    public function editAcademicHistory($user_id, $id)
    {
        $data = Institute::orderBy('updated_at', 'DESC')
            ->get();
        $user = User::where('id', $user_id)
            ->first();
        $edu = TutorEducation::where('id', $id)
            ->with('institute', 'subjects')
            ->first();
        return view('admin.tutor.edit_academic_history', compact('data', 'user', 'edu'));
    }

    public function updateAcademicHistory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_year'   => 'required|string',
            'end_year'     => 'required|string',
            'degree'       => 'required|string',
            'institute_id' => 'required|string',
            'is_featured'  => 'nullable',
            'certificate'  => 'nullable|file|max:2048',
            'subject'      => 'required|array|min:1',
            'subject.*'    => 'required|string',
            'grade'        => 'required|array|min:1',
            'grade.*'      => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $is_featured       = $request->input('is_featured', null);
        $edu               = TutorEducation::findOrFail($id);
        $edu->institute_id = $request->input('institute_id');
        $edu->start_year   = $request->input('start_year');
        $edu->end_year     = $request->input('end_year');
        $edu->degree       = $request->input('degree');
        $edu->is_featured  = ($is_featured) ? 1 : 0;
        $edu->status       = 1;
        if ($request->hasFile('certificate')) {
            if ($edu->certificate) {
                if (File::exists("uploads/users/" . $edu->path)) {
                    File::delete("uploads/users/" . $edu->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('certificate');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move('uploads/users/', $filename);
            $edu->certificate = $filename;
        }
        $edu->updated_at = Carbon::now()->toDateTimeString();
        if ($edu->save()) {
            TutorEducationSubject::where('education_id', $edu->id)->delete();
            $subject = $request->input('subject');
            $grade   = $request->input('grade');
            if (count($subject) > 0) {
                foreach ($subject as $key => $value) {
                    $edusub               = new TutorEducationSubject;
                    $edusub->education_id = $edu->id;
                    $edusub->subject      = $value;
                    $edusub->grade        = $grade[$key];
                    $edusub->save();
                }
            }
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Updated Qualification Details.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "System error please try after sometime",
                ],
            ], 200);
        }
    }

    public function updateEduStatus(Request $request)
    {
        $res = TutorEducation::where('id', $request->input('eduId'))
            ->update([
                'status' => $request->input('status'),
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Data updated",
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

    public function showBankDetails($id)
    {
        $user = User::where('id', $id)
            ->first();
        $bank = TutorBankDetails::where('user_id', $id)
            ->first();
        return view('admin.tutor.bank_details', compact('user', 'bank'));
    }

    public function editBankDetails($id)
    {
        $user = User::where('id', $id)
            ->first();
        $bank = TutorBankDetails::where('user_id', $id)
            ->first();
        return view('admin.tutor.edit_bank_details', compact('user', 'bank'));
    }

    public function updateBankDetails(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id'             => 'required',
            'account_holder_name' => 'required|string',
            'account_number'      => 'required|unique:tutor_bank_details,account_number,' . $id,
            'ifsc_code'           => 'required|string',
            'bank_name'           => 'required|string',
            'bank_branch'         => 'required|string',
            'address'             => 'required|string',
            'mobile_number'       => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user_id = $request->input('user_id');
        $bank    = TutorBankDetails::where(['id' => $id, 'user_id' => $user_id])
            ->update([
                'account_holder_name' => $request->input('account_holder_name'),
                'account_number'      => $request->input('account_number'),
                'ifsc_code'           => $request->input('ifsc_code'),
                'bank_name'           => $request->input('bank_name'),
                'bank_branch'         => $request->input('bank_branch'),
                'address'             => $request->input('address'),
                'mobile_number'       => $request->input('mobile_number'),
                'updated_at'          => Carbon::now()->toDateTimeString(),
            ]);

        if ($bank) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Bank Details Updated.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "System error please try after sometime",
                ],
            ], 200);
        }

    }

    public function showTutorProfileStatus($id)
    {
        $user = User::where('id', $id)
            ->first();
        $tutor = Tutor::where('user_id', $id)
            ->first();
        $tutor_fee = TutorFeesInfo::where('user_id', $id)
            ->first();
        return view('admin.tutor.tutor_profile_status', compact('tutor_fee', 'user', 'tutor'));
    }

    public function updateTutorProfileStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id'                  => 'required',
            'hourly_rate'              => 'required|numeric',
            'hourly_pay_bronze_tire'   => 'required|numeric',
            'hourly_pay_silver_tire'   => 'required|numeric',
            'hourly_pay_gold_tire'     => 'required|numeric',
            'hourly_pay_platinum_tire' => 'required|numeric',
            'five_hour_package'        => 'required|numeric',
            'ten_hour_package'         => 'required|numeric',
            'twentee_hour_package'     => 'required|numeric',
            'account_status'           => 'required',
            'rejecte_reason'           => 'required_if:account_status,3',
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
            $user_id        = $id;
            $account_status = $request->input('account_status');
            if ($account_status == 1) {
                User::where('id', $user_id)
                    ->update([
                        'verified'       => 0,
                        'rejected'       => 0,
                        'rejecte_reason' => '',
                        'updated_at'     => Carbon::now()->toDateTimeString(),
                    ]);
            }
            if ($account_status == 2) {
                User::where('id', $user_id)
                    ->update([
                        'verified'       => 1,
                        'rejected'       => 0,
                        'rejecte_reason' => '',
                        'updated_at'     => Carbon::now()->toDateTimeString(),
                    ]);
                $tutor = Tutor::where('user_id', $id)
                    ->first();
                Tutor::where('user_id', $id)
                    ->update([
                        'active_bio'                 => $tutor->bio,
                        'active_teaching_philosophy' => $tutor->teaching_philosophy,
                        'active_teaching_history'    => $tutor->teaching_history,
                        'active_other_interest'      => $tutor->other_interest,
                        'updated_at'                 => Carbon::now()->toDateTimeString(),
                    ]);
            }
            if ($account_status == 3) {
                User::where('id', $user_id)
                    ->update([
                        'verified'       => 0,
                        'rejected'       => 1,
                        'rejecte_reason' => $request->input('rejecte_reason'),
                        'updated_at'     => Carbon::now()->toDateTimeString(),
                    ]);
            }
            $fee = TutorFeesInfo::where('user_id', $request->input('user_id'))
                ->first();
            if (!$fee) {
                $fee = new TutorFeesInfo;
            }
            $fee->user_id                  = $request->input('user_id');
            $fee->hourly_rate              = $request->input('hourly_rate');
            $fee->hourly_pay_bronze_tire   = $request->input('hourly_pay_bronze_tire');
            $fee->hourly_pay_silver_tire   = $request->input('hourly_pay_silver_tire');
            $fee->hourly_pay_gold_tire     = $request->input('hourly_pay_gold_tire');
            $fee->hourly_pay_platinum_tire = $request->input('hourly_pay_platinum_tire');
            $fee->five_hour_package        = $request->input('five_hour_package');
            $fee->ten_hour_package         = $request->input('ten_hour_package');
            $fee->twentee_hour_package     = $request->input('twentee_hour_package');
            $fee->updated_at               = Carbon::now()->toDateTimeString();
            $fee->save();

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Tutor account has been updated successfully.',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while oparation.',
                ],
            ], 200);
        }
    }

    public function showDeclarations($id)
    {
        $user = User::where('id', $id)
            ->with('subjects')
            ->first();
        $declaration = TutorDeclaration::where('user_id', $id)
            ->first();
        return view('admin.tutor.declarations', compact('user', 'declaration'));
    }

    public function makeFetured(Request $request)
    {
        $res = User::where('id', $request->input('rowId'))
            ->update(['is_featured' => 1]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Tutor updated",
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

    public function removeFetured(Request $request)
    {
        $res = User::where('id', $request->input('rowId'))
            ->update(['is_featured' => 0]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Tutor updated",
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

    public function removeEducation(Request $request, $id)
    {
        $edu = TutorEducation::findOrFail($id);

        if ($edu->certificate) {
            if (File::exists("uploads/users/" . $edu->path)) {
                File::delete("uploads/users/" . $edu->path);
            }
        }
        $edu->delete();
        $res = TutorEducationSubject::where('education_id', $id)
            ->delete();
        if ($edu) {
            return response()->json([
                'success' => [
                    'message' => "successfully removed educational history",
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

    public function changeInterviewStatus(Request $request)
    {
        $res = User::where('id', $request->input('rowId'))
            ->update(['interviewed' => $request->input('status')]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Tutor updated",
                    'status'  => $request->input('status'),
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

}
