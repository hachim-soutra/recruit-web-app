<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class LanguageController extends Controller
{
    public function index($type = 'active', Request $request = null)
    //public function index($type = 'active')
    {
        $keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data    = Language::orderBy('updated_at', 'DESC')
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
                    $q->orWhere('body', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
			
        return view('admin.language.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.language.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'body' => 'required|string|max:250',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $lang         = new Language;
        $lang->name   = $request->input('name');
        $lang->body   = $request->input('body');
        $lang->status = 1;

        if ($lang->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Added a new Language.",
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
        $lang = Language::where('id', $id)
            ->first();
        return view('admin.language.edit', compact('lang'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'body' => 'required|string|max:250',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $lang = Language::where('id', $id)
            ->first();
        $lang->name   = $request->input('name');
        $lang->body   = $request->input('body');
        $lang->status = 1;
        if ($lang->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Updated a Language.",
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
        $res = Language::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Language has been Updated",
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
        $res = Language::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Language has been Updated",
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
        $lang = Language::findOrFail($id);

        $res = $lang->delete();
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
