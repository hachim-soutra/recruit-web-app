<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Validator;

class SkillController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        //$keyword = $request->input('keyword', null);
		$keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data    = Skill::orderBy('updated_at', 'DESC')
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'pending') {
                    $q->where('status', 0);
                }
            })
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('description', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.skills.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.skills.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:250',
            'description' => 'required|string|max:250',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $ins              = new Skill;
        $ins->name        = $request->input('name');
        $ins->description = $request->input('description');
        $ins->status      = 1;

        if ($ins->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Added a new Skill.",
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

    public function edit($id)
    {
        $skill = Skill::where('id', $id)
            ->first();
        return view('admin.skills.edit', compact('skill'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:250',
            'description' => 'required|string|max:250',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $ins = Skill::where('id', $id)
            ->first();
        $ins->name        = $request->input('name');
        $ins->description = $request->input('description');
        $ins->status      = 1;
        if ($ins->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Added a new Skill.",
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

    public function archive($id)
    {
        $res = Skill::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Skill has been Updated",
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
        $res = Skill::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Skill has been Updated",
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

    public function destroy($id)
    {
        $ins = Skill::findOrFail($id);

        $res = $ins->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Record has been Removed",
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
