<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Carbon\Carbon;
use App\Models\Client;
use Auth;

class ClientController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/client/');
    }

    public function index(Request $request){
        $conditions = [['status', '!=', 'D']];
        $clients = Client::where($conditions);
        $keyword = $request->input('keyword', null);
        if($keyword != ''){
            $clients = $clients->where(function($q) use($keyword){
                if($keyword){
                    $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('description', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        $clients = $clients->orderBy('id', 'DESC')->paginate(10);
        return view('admin.client.list', compact('clients', 'request'));
    }

    public function create(){
        $detail = (object)array();
        return view('admin.client.add', compact('detail'));
    }

    public function store(Request $req){
        $inputs = $req->all();
        $filename = (!$req->hasFile('imagefile')) ? @$inputs['oldfile'] : '';
        $rules = [
            'name'                  => 'required|string',
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
            $file->move(public_path('uploads/client/'), $filename);
        }
        $create = array(
            'name' => $inputs['name'], 
            'isfeatured' => $inputs['isfeatured'], 
            'image' =>  ($req->hasFile('imagefile')) ? 'client/'.$filename : $filename, 
            'description' => @$inputs['description'], 
            'created_by' => Auth::id(), 
            'updated_by' => Auth::id(), 
        );
        if(Client::updateOrCreate(['id' => @$inputs['rowid']], $create)){
            if(@$inputs['rowid'] != ''){
                $update = array(
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                );
                Client::where('id', @$inputs['rowid'])->update($update);
            }
            return redirect()->to('admin/client/list');
        }else{
            $resMsg = array('errMsg' => "Creation Failed.");
            return redirect()->back()->with($resMsg);
        }
    }

    public function changeStatus($status, $id){
        if($id != ''){
            $conditions = [['id', '=', $id]]; 
            $update = Client::where($conditions)->update(['status' => $status]);
            if(!$update){
                $resMsg = array('errMsg' => 'Error From Update Client Table.');
                return redirect()->back()->with($resMsg);
            }
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Update row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function trash($id){
        if($id != ''){
            Client::where(['id'=> $id])->update(['status' => 'D']);
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Delete row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function edit($id){
        if($id != ''){
            $detail = Client::where('id', $id)->first();
            return view('admin.client.add', compact('detail'));
        }
        return redirect()->back();
    }
}
