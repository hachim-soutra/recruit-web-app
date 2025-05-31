<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qualification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class QualificationController extends Controller
{
    public function index($type = 'active', Request $request = null)
    //public function index($type = 'active')
    {
        $keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data    = Qualification::orderBy('updated_at', 'DESC')
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
			
        return view('admin.qualification.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.qualification.add');
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
        $qua              = new Qualification;
        $qua->name        = $request->input('name');
        $qua->description = $request->input('description');
        $qua->status      = 1;

        if ($qua->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Added a new Qualification.",
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
        $qua = Qualification::where('id', $id)
            ->first();
        return view('admin.qualification.edit', compact('qua'));
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
        $qua = Qualification::where('id', $id)
            ->first();
        $qua->name        = $request->input('name');
        $qua->description = $request->input('description');
        $qua->status      = 1;
        if ($qua->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Qualification updated successfully.",
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
        $res = Qualification::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Qualification has been Updated",
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
        $res = Qualification::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Qualification has been Updated",
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
        $qua = Qualification::findOrFail($id);

        $res = $qua->delete();
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
