<?php

namespace App\Http\Controllers\Admin;

use App\Enum\NewsStatusEnum;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Validator;
use Carbon\Carbon;
use Auth;
use Image;

class NewsController extends Controller
{
    private $publicpath;
    public function __construct()
    {
        $this->publicpath = public_path('uploads/news/');
    }

    public function index(Request $request){
        $news = News::with('category');
        $keyword = $request->input('keyword', null);
        if($keyword != ''){
            $news = $news->where(function($q) use($keyword){
                if($keyword){
                    $q->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('newsdetail', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('slug', 'LIKE', '%' . $keyword . '%');
                }
            });
        }
        $news = $news->orderBy('id', 'DESC')->paginate(10);
        return view('admin.news.list', compact('news', 'request'));
    }

    public function create(){
        $detail = (object)array();
        $newstype = NewsCategory::where([['status', '!=', NewsStatusEnum::REJECTED]])->get();
        return view('admin.news.add', compact('detail', 'newstype'));
    }

    public function store(Request $req){
        $inputs = $req->all();
        $filename = str_replace(config('app.url')."/uploads/", "", ((!$req->hasFile('imagefile')) ? @$inputs['oldfile'] : ''));

        $rules = [
            'newscategory'           => 'required',
            'title'                  => 'required|string',
            'detailnews'             => 'required',
            'status'                 => ['required', new Enum(NewsStatusEnum::class)],
            'expiredate'             => 'required'
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
            $file->move(public_path('uploads/news/'), $filename);
        }
        $create = array(
            'news_category_id'  => @$inputs['newscategory'],
            'title'             => $inputs['title'],
            'slug'              => Str::slug($inputs['title'], '-'),
            'image'             => ($req->hasFile('imagefile')) ? 'news/'.$filename : $filename,
            'newsdetail'        => @$inputs['detailnews'],
            'expire_date'       => Carbon::parse(@$inputs['expiredate'])->toDateTimeString(),
            'status'            => $inputs['status'],
            'created_by'        => Auth::id(),
            'updated_by'        => Auth::id()
        );
        if(News::updateOrCreate(['id' => @$inputs['rowid']], $create)){
            if(@$inputs['rowid'] != ''){
                $update = array(
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                );
                News::where('id', @$inputs['rowid'])->update($update);
            }
            return redirect()->to('admin/news/list');
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
            $detail = News::where('id', $id)->first();
            $newstype = NewsCategory::where([['status', '!=', NewsStatusEnum::REJECTED]])->get();
            return view('admin.news.add', compact('detail', 'newstype'));
        }
        return redirect()->back();
    }

    public function view($id){
        if($id != ''){
            $detail = News::where('id', $id)->first();
            $newstype = NewsCategory::where([['status', '!=', NewsStatusEnum::REJECTED]])->get();
            return view('admin.news.view', compact('detail', 'newstype'));
        }
        return redirect()->back();
    }

    #news category
    public function category(){
        $categories = NewsCategory::where([['status', '!=', NewsStatusEnum::REJECTED]])->get();
        $category = (object)array();
        $type = 'list';
        return view('admin.news.category', compact('categories', 'category', 'type'));
    }

    public function addCategory(Request $req){
        $rules = [
            'categoryname'           => 'required|string',
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $query = NewsCategory::updateOrCreate(['id' => @$req->rowid], [
                'category_name' => $req->input('categoryname'),
                'category_slug' => Str::slug($req->input('categoryname'))
            ]);
            if($query){
                return redirect()->to('admin/news/category');
            }else{
                return redirect()->back()->withError("Something Went Wrong.");
            }
        }
    }

    public function categoryEdit($slug = null){
        $categories = NewsCategory::where([['status', '!=', NewsStatusEnum::REJECTED]])->get();
        $category = $categories->where('category_slug', $slug)->first();
        $type = 'edit';
        return view('admin.news.category', compact('categories', 'category', 'type'));
    }

    public function categoryTrash($slug = null){
        NewsCategory::where('category_slug', $slug)->update([
            'status' => NewsStatusEnum::REJECTED
        ]);
        return redirect()->back();
    }

    public function categoryChangeStatus($slug = null, $status = null){
        NewsCategory::where('category_slug', $slug)->update([
            'status' => $status
        ]);
        return redirect()->back();
    }
}
