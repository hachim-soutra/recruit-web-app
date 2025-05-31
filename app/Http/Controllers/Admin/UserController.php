<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        //$keyword = $request->input('keyword', null);
		$keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data    = User::orderBy('updated_at', 'DESC')
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'archived') {
                    $q->where('status', 0);
                }
            })
            ->where('user_type', 'user')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('user_key', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.users.list', compact('data', 'request'));
    }

    public function create()
    {
        $roles = Role::where(function ($q) {
            $q->where('name', '!=', 'tutor');
            $q->where('name', '!=', 'member');
        })->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users,email',
            'mobile'                => 'nullable|unique:users,mobile',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'sometimes|required_with:password',
            'roles'                 => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user                    = new User();
        $user->name              = $request->input('name');
        $user->user_key          = 'kec' . date("Ymdhis") . rand(10, 999);
        $user->email             = $request->input('email');
        $user->mobile            = $request->input('mobile', null);
        
		//$user->password        = bcrypt($request->input('password'));
		$user_password           = trim($request->input('password'));
		$user->password          = Hash::make($user_password);
		
        $user->remember_token    = Str::random(10);
        $user->verified          = 1;
        $user->email_verified    = 1;
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
        $roles = $request->roles;
        $user->syncRoles($roles);
        if ($user) {
			
			Mail::to($user->email)->send(new Welcome($user, $user_password));
			
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Admin has been created successfully.",
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

    public function edit($id)
    {
        $roles = Role::where(function ($q) {
            $q->where('name', '!=', 'tutor');
            $q->where('name', '!=', 'member');
        })->get();
        $user = User::where('id', $id)->first();
        return view('admin.users.edit', compact('roles', 'user'));

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users,email,' . $id,
            'mobile'                => 'nullable|unique:users,mobile,' . $id,
            'password'              => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'sometimes|required_with:password',
            'roles'                 => 'required|array|min:1',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $user         = User::findOrFail($id);
        $user->name   = $request->input('name');
        $user->email  = $request->input('email');
        $user->mobile = $request->input('mobile', null);
        if ($request->input('password')) {
            //$user->password = bcrypt($request->input('password'));
            $user->password = Hash::make($request->input('password'));
        }
        $user->updated_at = Carbon::now()->toDateTimeString();
        $user->save();
        $roles = $request->roles;
        $user->syncRoles($roles);
        if ($user) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Admin has been updated successfully.",
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
        $user = User::where('id', $id)->with('roles')->first();
        return view('admin.users.show', compact('user'));
    }

    public function archive($id)
    {
        $res = User::where('id', $id)
            ->update([
                'status' => 0,
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
}
