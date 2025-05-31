<?php

namespace App\Http\Controllers\Admin;

use Validator;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', null);
        $data    = Role::orderBy('updated_at', 'ASC')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('description', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->with('permissions')
            ->paginate(15);
        return view('admin.roles.list', compact('data', 'request'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('updated_at', 'ASC')->get();
        return view('admin.roles.add', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|string|unique:roles,display_name',
            'description'  => 'nullable',
            'permissions'  => 'required|array|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $role               = new Role();
        $role->display_name = $request->input('display_name');
        $role->name         = Str::slug($request->input('display_name'), '_');
        $role->description  = $request->input('description', null);
        $res                = $role->save();
        $role->syncPermissions($request->permissions);
        if ($res) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Role has been created successfully!",
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

    public function edit($role_id)
    {
        $role        = Role::with('permissions')->findOrFail($role_id);
        $permissions = Permission::orderBy('updated_at', 'ASC')->get();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'display_name' => 'required|string|unique:roles,display_name,' . $id,
            'description'  => 'nullable',
            'permissions'  => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $role               = Role::findOrFail($id);
        $role->display_name = $request->input('display_name');
        $role->name         = Str::slug($request->input('display_name'), '_');
        $role->description  = $request->input('description', null);
        $role->updated_at   = Carbon::now()->toDateTimeString();
        $res                = $role->save();
        $role->syncPermissions($request->permissions);
        if ($res) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Role has been updated successfully!",
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

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $res  = $role->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Role " . ucwords(str_replace('-', ' ', $role->name)) . " has been Removed",
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
