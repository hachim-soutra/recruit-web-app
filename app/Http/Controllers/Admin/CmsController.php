<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ContactSetting;
use App\Models\AboutSetting;
use App\Models\HomePageContent; 
use App\Models\HomePageRecruitmentType; 
use App\Models\LookingStaff;
use Validator;
use Carbon\Carbon;
use Auth;

class CmsController extends Controller
{
    public function aboutUs($id = null){
        $abouts = AboutSetting::get();
        $singleabout = (object)array();
        $type = 'add';
        if($id != ''){
            $singleabout = AboutSetting::where('id', $id)->first();
            $type = 'edit';
        }
        return view('admin.cms.about', compact('abouts', 'singleabout', 'type'));
    }

    public function aboutUsSave(Request $req){
        $filename = $req['rowid'] != '' ? (!$req->hasFile('aboutfile')) ? @$req['oldfile'] : '' : '';
        $rules = [
            'heading'           => 'required',
            'detail'           => 'required|min:5',
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->with(['type' => 'Validation_failed'])->withErrors($validator->errors()->messages());
        }else{
            #fileupload
            if($req->hasFile('aboutfile')){
                $file = $req->file('aboutfile');
                $filename = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists('uploads/cms/about/')){
                    mkdir('uploads/cms/about/', 0777, true);
                }
                $file->move(public_path('uploads/cms/about/'), $filename);
            }
            $query = AboutSetting::updateOrCreate(['id' => $req['rowid']],[
                'heading' => $req->input('heading'),
                'detail' => $req->input('detail'),
                'aboutus_image' => $filename,
            ]);
            if($query){
                return redirect()->to('admin/cms/about-us')->withSuccess('');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }

    public function contactUs($id = null){
        $contacts = ContactSetting::get();
        $singlecontact = (object)array();
        $type = 'add';
        if($id != ''){
            $singlecontact = ContactSetting::where('id', $id)->first();
            $type = 'edit';
        }
        return view('admin.cms.contact', compact('contacts', 'singlecontact', 'type'));
    }

    public function contactUsSave(Request $req){
        $filename = $req['rowid'] != '' ? (!$req->hasFile('contactfile')) ? @$req['oldfile'] : '' : '';
        $rules = [
            'heading'           => 'required',
            'detail'           => 'required|min:5',
            'contactlocation'   => 'required'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            #fileupload
            if($req->hasFile('contactfile')){
                $file = $req->file('contactfile');
                $filename = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists('uploads/cms/contact/')){
                    mkdir('uploads/cms/contact/', 0777, true);
                }
                $file->move(public_path('uploads/cms/contact/'), $filename);
            }
            $query = ContactSetting::updateOrCreate(['id' => $req['rowid']],[
                'heading' => $req->input('heading'),
                'detail' => $req->input('detail'),
                'contactlocation' => $req->input('contactlocation'),
                'contactus_image' => $filename,
            ]);
            if($query){
                return redirect()->to('admin/cms/contact-us');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }

    public function homeContent(){
        $home = HomePageContent::first();
        $type = HomePageRecruitmentType::take(3)->orderBy('updated_at', 'DESC')->get();
        return view('admin.cms.homecontent', compact('home', 'type'));
    }

    public function footerContent(){
        return view('admin.cms.footercontent');
    }

    public function homeBannerSave(Request $req){
        $filename = $req['rowid'] != '' ? (!$req->hasFile('banner_file')) ? "" : '' : @$req['banner_file_old'];
        $rules = [
            'banner_heading'            => 'required',
            'banner_file'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'error' => $validator->errors()->messages(), 'msg' => '', 'for' => 'home_banner']);
        }else{
            #fileupload
            if($req->hasFile('banner_file')){
                $file = $req->file('banner_file');
                $filename = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists('uploads/cms/home/')){
                    mkdir('uploads/cms/home/', 0777, true);
                }
                $file->move(public_path('uploads/cms/home/'), $filename);
            }
            $home = HomePageContent::first();
            HomePageContent::updateOrCreate(['id' => @$home->id], [
                'banner_heading' => $req->input('banner_heading'), 
                'banner_file' => $filename
            ]);
            return response()->json(['code' => 200, 'error'=> '', 'for' => 'home_banner', 'msg' => 'Home Banner Content Saved.']);
        }
    }

    public function homeRecruitmentContent(Request $req){
        $rules = [
            'recruitment_heading'            => 'required',
            'recruitment_description'        => 'required|min:5'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'error' => $validator->errors()->messages(), 'msg' => '', 'for' => 'home_recruitment_content']);
        }else{
            $home = HomePageContent::first();
            HomePageContent::updateOrCreate(['id' => @$home->id], [
                'recruitment_content_heading' => $req->input('recruitment_heading'), 
                'recruitment_content_description' => $req->input('recruitment_description'), 
            ]);
            return response()->json(['code' => 200, 'error'=> '', 'for' => 'home_recruitment_content', 'msg' => 'Home Recruitment Content Saved.']);
        }
    }

    public function homeCounting(Request $req){
        $rules = [
            'glorious_years'            => 'required',
            'jobs_filled'               => 'required',
            'job_vacancies'             => 'required'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'error' => $validator->errors()->messages(), 'msg' => '', 'for' => 'home_counting']);
        }else{
            $home = HomePageContent::first();
            HomePageContent::updateOrCreate(['id' => @$home->id], [
                'glorious_years' => $req->input('glorious_years'), 
                'job_filled' => $req->input('jobs_filled'), 
                'job_vacancy' => $req->input('job_vacancies'), 
            ]);
            return response()->json(['code' => 200, 'error'=> '', 'for' => 'home_counting', 'msg' => 'Home Recruitment Counting Saved.']);
        }
    }

    public function homeRecruitmentType(Request $req){
        $filename = $req['edit_rowid'] != '' ? (!$req->hasFile('recruitment_type_picture')) ? basename(@$req['recruitment_type_picture_old']) : '' : '';
        $rules = [
            'recruitment_type'            => 'required',
            'recruitment_type_picture'    => 'required_without:edit_rowid|nullable|image|mimes:jpeg,png,jpg,gif,svg,webp'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 403, 'error' => $validator->errors()->messages(), 'msg' => '', 'for' => 'home_recruitment_type']);
        }else{
            #fileupload
            if($req->hasFile('recruitment_type_picture')){
                $file = $req->file('recruitment_type_picture');
                $filename = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists('uploads/cms/home/')){
                    mkdir('uploads/cms/home/', 0777, true);
                }
                $unlink = asset('uploads/cms/home/').'/'.@$req['recruitment_type_picture_old'];
                @unlink($unlink);
                $file->move(public_path('uploads/cms/home/'), $filename);
            }
            $type = HomePageRecruitmentType::updateOrCreate(['id' => @$req->input('edit_rowid')], [
                'recruitment_type' => $req->input('recruitment_type'), 
                'recruitment_type_file' => $filename, 
            ]);
            return response()->json(['code' => 200, 'error'=> '', 'for' => 'home_recruitment_type', 'msg' => 'Home Recruitment Type Content Saved.']);
        }
    }

    public function homeRecruitmentTypeDelete($typeid){
        HomePageRecruitmentType::where('id', $typeid)->delete();
        return redirect()->back();
    }

    public function permanentRecruitment(){
        $staff = LookingStaff::where('page_type', 'parmanent-recruitment')->first();
        $data = (object)json_decode($staff->content, true);
        return view('admin.cms.permanentrecruitment', compact('staff', 'data'));
    }

    public function permanentRecruitmentSave(Request $req){      
        $rules = [
            'banner_heading' => 'required',
            'short_description' => 'required',
            'content_title' => 'nullable|string',
            'page_content' => 'nullable|string|max:3000',
            'process_name' => 'array',
            'process_name.*' => 'string',
            'proccess_des' => 'required_with:result_image|nullable|array',
            'proccess_des.*' => 'string',
            'banner_file' => 'required_without:old_banner_file|image',
            'content_file' => 'required_with:content_title|required_without:old_content_file|nullable|image',
            'result_image' => 'required_with:proccess_des|required_without:old_result_image|nullable|array',
            'result_image.*' => 'image',
            'gallery' => 'nullable|required_without:old_gallery|array',
            'gallery.*' => 'image'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('banner_file') ? '' : '') : $req->input('old_banner_file');
            $content_file = @$req->input('rowid') == '' ? (!$req->hasFile('content_file') ? '' : '') : $req->input('old_content_file');
            $result_image_old = @$req->input('rowid') == '' ? (!$req->hasFile('result_image') ? '' : '') : $req->input('old_result_image');
            $result_image = array();
            $gallery_old = @$req->input('rowid') == '' ? (!$req->hasFile('gallery') ? '' : '') : $req->input('old_gallery');
            $gallery = array();
            #banner_file upload
            if($req->hasFile('banner_file')){
                $file = $req->file('banner_file');
                $banner_file = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $banner_file);
            }            
            #content_file upload
            if($req->hasFile('content_file')){
                $file = $req->file('content_file');
                $content_file = uniqid(Auth::id() . '_').".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $content_file);
            }
            #result_image upload
            if($req->hasFile('result_image')){
                for($i = 0;$i < count($req->file('result_image'));$i++){                
                    $file = $req->file('result_image')[$i];
                    $result_image[$i] = uniqid(Auth::id() . '_').'_result_'.".".$file->getClientOriginalExtension(); 
                    if(!file_exists(public_path('uploads/staff/'))){
                        mkdir(public_path('uploads/staff/'), 0777, true);
                    }
                    $file->move(public_path('uploads/staff/'), $result_image[$i]);
                }
            }else{
                $result_image_old = $req->input('old_result_image');
                $exp = explode('|', $result_image_old);
                for($k = 0;$k < count($exp);$k++){
                    $result_image[$k] = $exp[$k];
                }                
            }              
            #gallery upload
            if($req->hasFile('gallery')){
                for($j = 0;$j < count($req->file('gallery'));$j++){                
                    $file = $req->file('gallery')[$j];
                    $gallery[$j] = uniqid(Auth::id() . '_').'_gallery_'.".".$file->getClientOriginalExtension(); 
                    if(!file_exists(public_path('uploads/staff/'))){
                        mkdir(public_path('uploads/staff/'), 0777, true);
                    }
                    $file->move(public_path('uploads/staff/'), $gallery[$j]);
                }
            }else{
                $gallery_old = $req->input('old_gallery');
                $exp = explode('|', $gallery_old);
                for($k = 0;$k < count($exp);$k++){
                    $gallery[$k] = $exp[$k];
                }                
            }             
            $process_name = implode('|', $req->input('process_name'));
            $proccess_des = implode('|', $req->input('proccess_des'));  #result description#
            $result_image_str = implode('|', $result_image); 
            $gallery_str = implode('|', $gallery); 
            $data = array(
                'banner_heading' => $req->input('banner_heading'),
                'short_description' => $req->input('short_description'),
                'content_title' => $req->input('content_title'),
                'page_content' => $req->input('page_content'),
                'process_name' => $process_name,                
                'banner_file' => $banner_file,
                'content_file' => $content_file,
                'result_image' => $result_image_str,
                'proccess_des' => $proccess_des,
                'gallery' => $gallery_str
            );
            $content = json_encode($data, true);
            $save = array(
                'page_type' => Str::slug($req->input('page_name')),
                'content' => $content
            );
            $staff = LookingStaff::updateOrCreate(['id' => ''], $save);
            if($staff){
                return redirect()->back()->withSuccess('Staff Tech Carrers Saved Successfully.');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }

    public function virtualRecruitment(){
        $staff = LookingStaff::where('page_type', 'virtual-recruitment')->first();
        $data = (object)json_decode($staff->content, true);
        return view('admin.cms.virtualrecruitment', compact('staff','data'));
    }

    public function virtualRecruitmentSave(Request $req){
        $rules = [
            'banner_heading' => 'required',
            'short_description' => 'required',
            'page_title' => 'required',
            'left_content' => 'required',
            'right_content' => 'required',
            'middle_banner_heading' => 'nullable|string',
            'middle_short_description' => 'nullable|string|max:1500',
            'widget_heading' => 'nullable|array',
            'widget_heading.*' => 'string',
            'widget_descriptiuon' => 'required_with:Widget_heading|array',
            'widget_descriptiuon.*' => 'string',
            'event_link' => 'nullable|url',
            'fb_link' => 'nullable',
            'twitter_link' => 'nullable',
            'linkedin_link' => 'nullable',
            'bottom_banner_heading' => 'nullable',
            'banner_file' => 'required_without:old_banner_file|nullable|image',
            'left_file' => 'required_without:old_left_file|nullable|image',
            'right_file' => 'required_without:old_right_file|nullable|image',
            'middle_banner_file' => 'required_without:old_middle_banner_file|nullable|image',
            'bottom_banner_file' => 'required_without:old_bottom_banner_file|nullable|image'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('banner_file') ? '' : '') : $req->input('old_banner_file');
            $left_file = @$req->input('rowid') == '' ? (!$req->hasFile('left_file') ? '' : '') : $req->input('old_left_file');
            $right_file = @$req->input('rowid') == '' ? (!$req->hasFile('right_file') ? '' : '') : $req->input('old_right_file');
            $middle_banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('middle_banner_file') ? '' : '') : $req->input('old_middle_banner_file');
            $bottom_banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('bottom_banner_file') ? '' : '') : $req->input('old_bottom_banner_file');
            #banner_file upload
            if($req->hasFile('banner_file')){
                $file = $req->file('banner_file');
                $banner_file = uniqid(Auth::id() . '_').'_banner_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $banner_file);
            } 
            #left_file upload
            if($req->hasFile('left_file')){
                $file = $req->file('left_file');
                $left_file = uniqid(Auth::id() . '_').'_left_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $left_file);
            }  
            #right_file upload
            if($req->hasFile('right_file')){
                $file = $req->file('right_file');
                $right_file = uniqid(Auth::id() . '_').'_right_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $right_file);
            }  
            #middle_banner_file upload
            if($req->hasFile('middle_banner_file')){
                $file = $req->file('middle_banner_file');
                $middle_banner_file = uniqid(Auth::id() . '_').'_middle_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $middle_banner_file);
            }  
            #bottom_banner_file upload
            if($req->hasFile('bottom_banner_file')){
                $file = $req->file('bottom_banner_file');
                $bottom_banner_file = uniqid(Auth::id() . '_').'_bottom_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $bottom_banner_file);
            }   
            $widget_heading = implode('|', $req->input('widget_heading'));
            $widget_descriptiuon = implode('|', $req->input('widget_descriptiuon'));  #Widget description#
            $data = array(
                'banner_heading' => $req->input('banner_heading'),
                'short_description' => $req->input('short_description'),
                'page_title' => $req->input('page_title'),
                'left_content' => $req->input('left_content'),
                'right_content' => $req->input('right_content'),
                'middle_banner_heading' => $req->input('middle_banner_heading'),
                'middle_short_description' => $req->input('middle_short_description'),
                'widget_heading' => $widget_heading,
                'widget_descriptiuon' => $widget_descriptiuon,
                'event_link' => $req->input('event_link', null),
                'fb_link' => $req->input('fb_link', null),
                'twitter_link' => $req->input('twitter_link', null),
                'linkedin_link' => $req->input('linkedin_link', null),
                'bottom_banner_heading' => $req->input('bottom_banner_heading', null),
                'banner_file' => $banner_file,
                'left_file' => $left_file,
                'right_file' => $right_file,
                'middle_banner_file' => $middle_banner_file,
                'bottom_banner_file' => $bottom_banner_file,
            );
            $content = json_encode($data, true);
            $save = array(
                'page_type' => Str::slug($req->input('page_name')),
                'content' => $content
            );
            $staff = LookingStaff::updateOrCreate(['id' => @$req->input('rowid')], $save);
            if($staff){
                return redirect()->back()->withSuccess('Staff Virtual Recruitment Saved Successfully.');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }

    public function techCareers(){
        $staff = LookingStaff::where('page_type', 'tech-careers')->first();
        $data = (object)json_decode($staff->content, true);
        return view('admin.cms.techcareers', compact('staff', 'data'));
    }

    public function techCareersSave(Request $req){        
        $rules = [
            'banner_heading' => 'required',
            'left_content' => 'required',
            'right_content' => 'required',
            'candidate_count' => 'required',
            'employer_count' => 'required',
            'roles_count' => 'required',
            'process_icon' => 'required|array',
            'process_icon.*' => 'string',
            'process_name' => 'required|required_with:process_icon|array',
            'process_name.*' => 'string',
            'process_detail' => 'required_with:process_icon|required|array',
            'proccess_des.*' => 'string',
            'left_file' => 'required_without:old_left_file|image',
            'right_file' => 'required_without:old_right_file|image',
            'banner_file' => 'required_without:old_banner_file|image',
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('banner_file') ? '' : '') : $req->input('old_banner_file');
            $left_file = @$req->input('rowid') == '' ? (!$req->hasFile('left_file') ? '' : '') : $req->input('old_left_file');
            $right_file = @$req->input('rowid') == '' ? (!$req->hasFile('right_file') ? '' : '') : $req->input('old_right_file');
            #banner_file upload
            if($req->hasFile('banner_file')){
                $file = $req->file('banner_file');
                $banner_file = uniqid(Auth::id() . '_').'_banner_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $banner_file);
            } 
            #left_file upload
            if($req->hasFile('left_file')){
                $file = $req->file('left_file');
                $left_file = uniqid(Auth::id() . '_').'_left_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $left_file);
            }  
            #right_file upload
            if($req->hasFile('right_file')){
                $file = $req->file('right_file');
                $right_file = uniqid(Auth::id() . '_').'_right_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $right_file);
            }
            $process_icon = implode('|', $req->input('process_icon'));
            $process_name = implode('|', $req->input('process_name'));  #Widget description#
            $process_detail = implode('|', $req->input('process_detail'));  
            $data = array(
                'banner_heading' => $req->input('banner_heading'),
                'banner_file' => $banner_file,
                'left_file' => $left_file,
                'left_content' => $req->input('left_content'),
                'right_content' => $req->input('right_content'),
                'right_file' => $right_file,
                'candidate_count' => $req->input('candidate_count'),
                'employer_count' => $req->input('employer_count'),
                'roles_count' => $req->input('roles_count'),
                'process_icon' => $process_icon,
                'process_name' => $process_name,
                'process_detail' => $process_detail
            );
            $content = json_encode($data, true);
            $save = array(
                'page_type' => Str::slug($req->input('page_name')),
                'content' => $content
            );
            $staff = LookingStaff::updateOrCreate(['id' => @$req->input('rowid')], $save);
            if($staff){
                return redirect()->back()->withSuccess('Staff Tech Carrers Saved Successfully.');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }

    public function jobsExpo(){
        $staff = LookingStaff::where('page_type', 'jobs-expo')->first();
        $data = (object)json_decode($staff->content, true);
        return view('admin.cms.jobsexpo', compact('staff', 'data'));
    }

    public function jobsExpoSave(Request $req){
        $rules = [
            'banner_heading' => 'required',
            'left_content' => 'required',
            'right_content' => 'required',
            'candidate_count' => 'required',
            'employer_count' => 'required',
            'roles_count' => 'required',
            'process_icon' => 'required|array',
            'process_icon.*' => 'string',
            'process_name' => 'required|required_with:process_icon|array',
            'process_name.*' => 'string',
            'process_detail' => 'required_with:process_icon|required|array',
            'proccess_des.*' => 'string',
            'left_file' => 'required_without:old_left_file|image',
            'right_file' => 'required_without:old_right_file|image',
            'banner_file' => 'required_without:old_banner_file|image',
            'gallery' => 'nullable|required_without:old_gallery|array',
            'gallery.*' => 'image'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $banner_file = @$req->input('rowid') == '' ? (!$req->hasFile('banner_file') ? '' : '') : $req->input('old_banner_file');
            $left_file = @$req->input('rowid') == '' ? (!$req->hasFile('left_file') ? '' : '') : $req->input('old_left_file');
            $right_file = @$req->input('rowid') == '' ? (!$req->hasFile('right_file') ? '' : '') : $req->input('old_right_file');
            $gallery_old = @$req->input('rowid') == '' ? (!$req->hasFile('gallery') ? '' : '') : $req->input('old_gallery');
            $gallery = array();
            #banner_file upload
            if($req->hasFile('banner_file')){
                $file = $req->file('banner_file');
                $banner_file = uniqid(Auth::id() . '_').'_banner_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $banner_file);
            } 
            #left_file upload
            if($req->hasFile('left_file')){
                $file = $req->file('left_file');
                $left_file = uniqid(Auth::id() . '_').'_left_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $left_file);
            }  
            #right_file upload
            if($req->hasFile('right_file')){
                $file = $req->file('right_file');
                $right_file = uniqid(Auth::id() . '_').'_right_'.".".$file->getClientOriginalExtension(); 
                if(!file_exists(public_path('uploads/staff/'))){
                    mkdir(public_path('uploads/staff/'), 0777, true);
                }
                $file->move(public_path('uploads/staff/'), $right_file);
            }
            #gallery upload
            if($req->hasFile('gallery')){
                for($j = 0;$j < count($req->file('gallery'));$j++){                
                    $file = $req->file('gallery')[$j];
                    $gallery[$j] = uniqid(Auth::id() . '_').'_gallery_'.".".$file->getClientOriginalExtension(); 
                    if(!file_exists(public_path('uploads/staff/'))){
                        mkdir(public_path('uploads/staff/'), 0777, true);
                    }
                    $file->move(public_path('uploads/staff/'), $gallery[$j]);
                }
            }else{
                $gallery_old = $req->input('old_gallery');
                $exp = explode('|', $gallery_old);
                for($k = 0;$k < count($exp);$k++){
                    $gallery[$k] = $exp[$k];
                }                
            }  
            $process_icon = implode('|', $req->input('process_icon'));
            $process_name = implode('|', $req->input('process_name'));  #Widget description#
            $process_detail = implode('|', $req->input('process_detail')); 
            $gallery_str = implode('|', $gallery); 
            $data = array(
                'banner_heading' => $req->input('banner_heading'),
                'left_content' => $req->input('left_content'),
                'right_content' => $req->input('right_content'),
                'candidate_count' => $req->input('candidate_count'),
                'employer_count' => $req->input('employer_count'),
                'roles_count' => $req->input('roles_count'),
                'process_icon' => $process_icon,
                'process_name' => $process_name,
                'process_detail' => $process_detail,
                'banner_file' => $banner_file,
                'left_file' => $left_file,
                'right_file' => $right_file,
                'gallery_short_desc' => $req->input('gallery_short_desc'),
                'gallery' => $gallery_str
            );
            $content = json_encode($data, true);
            $save = array(
                'page_type' => Str::slug($req->input('page_name')),
                'content' => $content
            );
            $staff = LookingStaff::updateOrCreate(['id' => @$req->input('rowid')], $save);
            if($staff){
                return redirect()->back()->withSuccess('Staff Jobs Expo Saved Successfully.');
            }else{
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }
}
