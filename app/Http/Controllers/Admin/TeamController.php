<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Team;
use App\Models\User;
use Validator;
use Carbon\Carbon;
use Auth;
use DB;

class TeamController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/team/');
    }

    public function index(Request $request){
        $conditions = [['t.status', '!=', 'D']];
        $teams = DB::table('users as u')->select(['t.*', 't.id as teamid', 't.status as teamstatus', 'u.*', 'u.id as userid'])
                ->join('teams as t', 't.user_id', '=', 'u.id')
                ->where($conditions);
        $keyword = $request->input('keyword', null);
        if($keyword != ''){
            $teams = $teams->where(function($q) use($keyword){
                if($keyword){
                    $q->orWhere('t.designation', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('u.name', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('u.email', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('u.mobile', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        $teams = $teams->orderBy('t.id', 'DESC')->paginate(10);
        return view('admin.team.list', compact('teams', 'request'));
    }

    public function create(){
        $detail = (object)array();
        return view('admin.team.add', compact('detail'));
    }

    public function store(Request $req){
        $inputs = $req->all();
        $filename = (!$req->hasFile('imagefile')) ? @$inputs['oldfile'] : '';
        $rules = [
            'name'                  => 'required|string',
            'designation'           => 'required|string',
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
            $file->move(public_path('uploads/team/'), $filename);
        }
        $name = @$inputs['name'];
        $fname = $lname = '';
        if($name !== ''){
            $name = explode(' ', $name);
            $fname = $name[0];
            if(array_key_exists('1', $name)){
                $lname = $name[1];
            }
        }
        $user = array(
            'first_name' => $fname,
            'last_name' => $lname,
            'name' => @$inputs['name'],
            'user_key' => 'JP' . rand(100, 999) . date("his"),
            'email' => $inputs['email'],
            'mobile' => $inputs['phone'],
            'password' => Hash::make('password'),
            'avatar' => ($req->hasFile('imagefile')) ? 'team/'.$filename : $filename,
            'user_type' => 'team'
        );
        $create = User::updateOrCreate(['id' => @$inputs['userid']], $user);
        if($create){
            $team = array(
                'user_id' => $create->id, 
                'designation' => $inputs['designation'], 
                'fblink' => $inputs['fbid'], 
                'twlink' => $inputs['twid'],  
                'created_by' => Auth::id(), 
                'updated_by' => Auth::id(), 
            );
            Team::updateOrCreate(['id' => @$inputs['rowid']], $team);
            if(@$inputs['rowid'] != ''){
                $update = array(
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                );
                Team::where('id', @$inputs['rowid'])->update($update);
            }
            return redirect()->to('admin/team/list');
        }else{
            $resMsg = array('errMsg' => "Creation Failed.");
            return redirect()->back()->with($resMsg);
        }  
    }

    public function changeStatus($status, $id){
        if($id != ''){
            $conditions = [['id', '=', $id]];
            $userDetail = User::where($conditions)->first();
            $update = Team::where('user_id', $userDetail->id)->update(['status' => $status]);
            if(!$update){
                $resMsg = array('errMsg' => 'Error From Update Team Table.');
                return redirect()->back()->with($resMsg);
            }
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Update row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function trash($id){
        if($id != ''){
            $conditions = [['id', '=', $id]];
            $userDetail = User::where($conditions)->first();
            Team::where(['user_id'=> $userDetail->id])->update(['status' => 'D']);
            return redirect()->back();
        }
        $resMsg = array('errMsg' => 'Delete row id getting blank.');
        return redirect()->back()->with($resMsg);
    }

    public function edit($id){
        if($id != ''){
            $select = array('u.name', 'u.email', 'u.mobile as phone', 'u.avatar as image', 't.fblink as fbid', 't.twlink as twid', 't.designation', 'u.id as userid', 't.id as teamid');
            $detail = DB::table('users as u')->select($select)
                    ->join('teams as t', 't.user_id', '=', 'u.id')
                    ->where('u.id', $id)->first();
            return view('admin.team.add', compact('detail'));
        }
        return redirect()->back();
    }
}
