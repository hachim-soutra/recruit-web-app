<?php

namespace App\Http\Controllers\Admin;

use App\Enum\AdviceStatusEnum;
use App\Enum\NewsStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Advice;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Validator;
use Carbon\Carbon;
use Auth;
use Image;

class AdviceController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/advice/');
    }

    public function index(Request $request){
        $advices = Advice::whereNotNull('id');
        $keyword = $request->input('keyword', null);
        if($keyword != ''){
            $advices = $advices->where(function($q) use($keyword){
                if($keyword){
                    $q->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('details', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('slug', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        $advices = $advices->orderBy('id', 'DESC')->paginate(10);
        return view('admin.advice.list', compact('advices', 'request'));
    }

    public function create(){
        $detail = (object)array();
        return view('admin.advice.add', compact('detail'));
    }

    public function store(Request $req){
        $inputs = $req->all();
        $filename = str_replace(config('app.url')."/uploads/", "", ((!$req->hasFile('imagefile')) ? @$inputs['oldfile'] : ''));
        $rules = [
            'title'                  => 'required|string',
            'details'             => 'required',
            'status'                 => ['required', new Enum(AdviceStatusEnum::class)],
        ];
        if(@$inputs['oldfile'] == '' || $req->hasFile('imagefile')){
            $extra = ['imagefile'    => 'required|max:10000|mimes:jpeg,jpg,png,webp'];
            $rules = array_merge($rules, $extra);
        }
        $validator = Validator::make($inputs, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }
        #fileupload
        if($req->hasFile('imagefile')){
            $file = $req->file('imagefile');
            $filename = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension();
            if(!file_exists($this->publicpath)){
                mkdir($this->publicpath, 0777, true);
            }
            if(@$inputs['oldfile'] != ''){
                $segments = explode('/', $inputs['oldfile']);
                @unlink($this->publicpath.'/'.end($segments));
            }
            $img = Image::make($file->getRealPath());
            $img->resize(348, 213, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $file->move(public_path('uploads/advice/'), $filename);
        }
        $create = array(
            'title'             => $inputs['title'],
            'slug'              => Str::slug($inputs['title'], '-'),
            'image'             => ($req->hasFile('imagefile')) ? 'advice/'.$filename : $filename,
            'details'           => @$inputs['details'],
            'status'            => $inputs['status'],
            'created_by'        => Auth::id(),
            'updated_by'        => Auth::id()
        );
        if(Advice::updateOrCreate(['id' => @$inputs['rowid']], $create)){
            if(@$inputs['rowid'] != ''){
                $update = array(
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                );
                Advice::where('id', @$inputs['rowid'])->update($update);
            }
            return redirect()->to('admin/advice/list');
        }else{
            $resMsg = array('errMsg' => "Creation Failed.");
            return redirect()->back()->with($resMsg);
        }
    }

    public function changeStatus($status, $id){
        if($id != ''){
            $conditions = [['id', '=', $id]];
            $update = News::where($conditions)->update(['status' => $status]);
            if(!$update){
                $resMsg = array('errMsg' => 'Error From Update News Table.');
                return redirect()->back()->with($resMsg);
            }
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Update row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function trash($id){
        if($id != ''){
            News::where(['id'=> $id])->update(['status' => NewsStatusEnum::REJECTED]);
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Delete row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function edit($id){
        if($id != ''){
            $detail = Advice::where('id', $id)->first();
            return view('admin.advice.add', compact('detail'));
        }
        return redirect()->back();
    }

    public function view($id){
        if($id != ''){
            $detail = News::where('id', $id)->first();
            return view('admin.advice.view', compact('detail'));
        }
        return redirect()->back();
    }
}
