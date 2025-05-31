<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class CandidateController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        //$keyword = $request->input('keyword', null);
        $keyword = '';
        if ($request == null || $request == '') {

            $request = (object)[];
            $request->keyword = $keyword = trim(request('keyword'));
        } else {
            $keyword = trim($request->input('keyword', null));
        }


        $data    = User::orderBy('updated_at', 'DESC')
            ->with('candidate')
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'archived') {
                    $q->where('status', 0);
                }
            })
            ->where('user_type', 'candidate')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('user_key', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.candidate.list', compact('data', 'request'));
    }

    public function edit($id)
    {
        $roles = Role::where(function ($q) {
            $q->where('name', '!=', 'tutor');
            $q->where('name', '!=', 'member');
        })->get();
        $user      = User::where('id', $id)->with('candidate')->first();
        $countries = Country::all();
        return view('admin.candidate.edit', compact('roles', 'user', 'countries'));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string',
            'email'          => 'required|email|unique:users,email,' . $id,
            'mobile'         => 'required|digits_between:9,15|unique:users,mobile,' . $id,
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'required',
            'marital_status' => 'required',
            'country'        => 'required',
            'state'          => 'required',
            'city'           => 'required',
            'zip'            => 'required|max:9999999|integer',
            'address'        => 'required',
            'portfolio_link'        => 'nullable',

            // 'password'              => 'nullable|string|min:6|confirmed',
            // 'password_confirmation' => 'sometimes|required_with:password',
            // 'roles'                 => 'required|array|min:1',

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
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->mobile     = $request->input('mobile', null);
        $user->updated_at = Carbon::now()->toDateTimeString();
        $user->save();
        // $roles = $request->roles;
        // $user->syncRoles($roles);
        if ($user) {
            Candidate::where('user_id', $id)
                ->update([
                    'date_of_birth'  => $request->input('date_of_birth'),
                    'gender'         => $request->input('gender'),
                    'marital_status' => $request->input('marital_status'),
                    'country'        => $request->input('country'),
                    'state'          => $request->input('state'),
                    'city'           => $request->input('city'),
                    'zip'            => $request->input('zip'),
                    'address'        => $request->input('address'),
                ]);

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Candidate has been updated successfully.",
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
        $data = User::where('id', $id)->with('candidate')->first();
        return view('admin.candidate.show', compact('data'));
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
}
