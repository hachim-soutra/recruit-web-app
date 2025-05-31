<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input("keyword", null);

        $data = Category::orderBy('id', 'DESC')
            ->where(function ($query) use ($keyword) {
                if ($keyword) {
                    $query->orWhere("name", "like", "%" . $keyword . "%");
                    $query->orWhere("description", "like", "%" . $keyword . "%");
                }
            })
            ->paginate(20);
        return view('admin.category.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.category.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $category              = new Category();
        $category->name        = $request->input('name');
        $category->slug        = Str::slug($request->input('name'), '-');
        $category->description = $request->input('description', null);
        $category->parent_id   = $request->input('parent_id', null);
        if ($request->hasFile('icon')) {
            $time      = Carbon::now();
            $file      = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm') . '/';
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move('uploads/category/' . $directory, $filename);
            $category->icon = $directory . $filename;
        }
        $category->created_by = Auth::user()->id;
        if ($category->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Category has been created successfully.',
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
        $category   = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:250|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $category              = Category::findOrFail($id);
        $category->name        = $request->input('name');
        $category->slug        = Str::slug($request->input('name'), '-');
        $category->description = $request->input('description', null);
        $category->parent_id   = $request->input('parent_id', null);
        if ($request->hasFile('icon')) {
            if ($category->icon) {
                if (File::exists("uploads/category/" . $category->path)) {
                    File::delete("uploads/category/" . $category->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm') . '/';
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move('uploads/category/' . $directory, $filename);
            $category->icon = $directory . $filename;
        }

        if ($category->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Category has been created successfully.',
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

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category = $category->delete();
        if ($category) {
            return response()->json([
                'success' => [
                    'message' => "Category has been Removed",
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
