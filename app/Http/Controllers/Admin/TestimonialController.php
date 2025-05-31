<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Validator;
use Carbon\Carbon;
use Auth;

class TestimonialController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/testimonials/');
    }

    public function index(Request $request){
        $conditions = [['status', '!=', 'D']];
        $testimonials = Testimonial::where($conditions);
        $keyword = $request->input('keyword', null);
        if($keyword != ''){
            $testimonials = $testimonials->where(function($q) use($keyword){
                if($keyword){
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('subject', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('designation', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        $testimonials = $testimonials->orderBy('id', 'DESC')->paginate(10);
        return view('admin.testimonial.list', compact('testimonials', 'request'));
    }

    public function create(){
        $detail = (object)array();
        return view('admin.testimonial.add', compact('detail'));
    }

    public function store(Request $req){
        $inputs = $req->all();
        $filename = (!$req->hasFile('imagefile')) ? @$inputs['oldfile'] : '';
        $rules = [
            'name'                  => 'required|string',
            'quote'                 => 'required|max:3000',
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
                @unlink($this->publicpath.'/'.@$inputs['oldfile']);
            }
            $file->move(public_path('uploads/testimonials/'), $filename);
        }
        $create = array(
            'name' => $inputs['name'], 
            'subject' => $inputs['quote'], 
            'image' =>  ($req->hasFile('imagefile')) ? 'testimonials/'.$filename : $filename, 
            'email' => @$inputs['emailid'], 
            'designation' => @$inputs['designation'], 
            'rating' => @$inputs['rating'], 
            'created_by' => Auth::id(), 
            'updated_by' => Auth::id(), 
        );
        if(Testimonial::updateOrCreate(['id' => @$inputs['rowid']], $create)){
            if(@$inputs['rowid'] != ''){
                $update = array(
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                );
                Testimonial::where('id', @$inputs['rowid'])->update($update);
            }
            return redirect()->to('admin/testimonial/list');
        }else{
            $resMsg = array('errMsg' => "Creation Failed.");
            return redirect()->back()->with($resMsg);
        }
    }

    public function changeStatus($status, $id){
        if($id != ''){
            $conditions = [['id', '=', $id]]; 
            $update = Testimonial::where($conditions)->update(['status' => $status]);
            if(!$update){
                $resMsg = array('errMsg' => 'Error From Update Testimonial Table.');
                return redirect()->back()->with($resMsg);
            }
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Update row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function trash($id = ''){
        if($id != ''){
            Testimonial::where(['id'=> $id])->update(['status' => 'D']);
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Delete row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function edit($id){
        if($id != ''){
            $detail = Testimonial::where('id', $id)->first();
            return view('admin.testimonial.add', compact('detail'));
        }
        return redirect()->back();
    }

    public function view($id){
        if($id != ''){
            $detail = Testimonial::where('id', $id)->first();
            return view('admin.testimonial.view', compact('detail'));
        }
        return redirect()->back();
    }
}
