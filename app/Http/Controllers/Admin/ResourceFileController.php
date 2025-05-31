<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\ResourceFileService;
use Illuminate\Http\Request;
use Validator;

class ResourceFileController
{
    private $resourceFileService;

    public function __construct(ResourceFileService $resourceFileService)
    {
        $this->resourceFileService = $resourceFileService;
    }

    public function index(Request $request)
    {
        $keyword = request('keyword') ? request('keyword') : '';
        $data = $this->resourceFileService->paginate($keyword);
        return view('admin.resource.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.resource.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filename'      => 'required|max:255',
            'file'          => 'required|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx|max:4096'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status" => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ]);
        }
        $result = $this->resourceFileService->store($validator->validated());
        return response()->json([
            'data' => [
                "status"  => $result ? 'success' : 'error',
                'message' => $result ?
                             'File has been created successfully.' :
                             'Sorry a problem occurred while creating the file.',
            ],
        ]);
    }

    public function trash($id)
    {
        $result = $this->resourceFileService->delete($id);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "File has been deleted",
                ],
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not found",
                ],
            ]);
        }
    }

}
