<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentResource;
use App\Models\Subject;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', null);
        $data    = StudentResource::orderBy('updated_at', 'ASC')
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('description', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.resource.list', compact('data', 'request'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('updated_at', 'ASC')
            ->where('status', 1)
            ->get();
        return view('admin.resource.add', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|unique:student_resources,title|max:250',
            'description' => 'nullable',
            'file'        => 'required|max:2048',
            'subject'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $resource              = new StudentResource();
        $resource->title       = $request->input('title');
        $resource->subject_id  = $request->input('subject');
        $resource->slug        = Str::slug($request->input('title'), '-');
        $resource->description = $request->input('description', null);
        if ($request->hasFile('file')) {
            $time      = Carbon::now();
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move('uploads/', $filename);
            $resource->file      = $filename;
            $resource->file_type = $file->getClientMimeType();

        }
        if ($resource->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Resource has been created successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the resource.',
                ],
            ], 200);
        }
    }

    public function edit($id)
    {
        $resource = StudentResource::findOrFail($id);
        $subjects = Subject::orderBy('updated_at', 'ASC')
            ->where('status', 1)
            ->get();
        return view('admin.resource.edit', compact('resource', 'subjects'));
    }

    public function update(request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:250|unique:student_resources,title,' . $id,
            'description' => 'nullable',
            'subject'     => 'required',
            'file'        => 'nullable|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $resource              = StudentResource::findOrFail($id);
        $resource->title       = $request->input('title');
        $resource->slug        = Str::slug($request->input('title'), '-');
        $resource->subject_id  = $request->input('subject');
        $resource->description = $request->input('description', null);
        if ($request->hasFile('file')) {
            if ($resource->file) {
                if (File::exists("uploads/" . $resource->path)) {
                    File::delete("uploads/" . $resource->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move('uploads/', $filename);
            $resource->file      = $filename;
            $resource->file_type = $file->getClientMimeType();

        }
        $resource->updated_at = Carbon::now()->toDateTimeString();
        if ($resource->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Resource has been updated successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while updating the Resource.',
                ],
            ], 200);
        }
    }

    public function destroy($id)
    {
        $resource = StudentResource::findOrFail($id);
        if ($resource->file) {
            if (File::exists("uploads/" . $resource->path)) {
                File::delete("uploads/" . $resource->path);
            }
        }
        $res = $resource->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Resource " . $resource->name . " has been Removed",
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
