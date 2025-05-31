<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class IndustryController extends Controller
{
    public function getFunctionalArea(Request $request)
    {
        $keyword = $request->input("keyword", null);

        $data = Industry::orderBy('name', 'ASC')->where('status', 1)
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere("name", "like", "%" . $keyword . "%");
                }
            })
            ->get();
        if ($data->count() > 0) {
            foreach ($data as $e) {
                $data_array[] = ['id' => $e->name, 'text' => $e->name];
            }
            return response()->json($data_array);
        } else {
            return response()->json($data_array);
        }

    }

    public function index($type = 'active', Request $request = null)
    //public function index($type = 'active')
    {
        $keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data    = Industry::orderBy('updated_at', 'DESC')
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
			
        return view('admin.industry.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.industry.add');
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
        $ins              = new Industry;
        $ins->name        = $request->input('name');
        $ins->description = $request->input('description');
        $ins->status      = 1;

        if ($ins->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Added a new Industry.",
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
        $ins = Industry::where('id', $id)
            ->first();
        return view('admin.industry.edit', compact('ins'));
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
        $ins = Industry::where('id', $id)
            ->first();
        $ins->name        = $request->input('name');
        $ins->description = $request->input('description');
        $ins->status      = 1;
        if ($ins->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Updated a Industry.",
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
        $res = Industry::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Industry has been Updated",
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
        $res = Industry::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Industry has been Updated",
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
        $ins = Industry::findOrFail($id);

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
