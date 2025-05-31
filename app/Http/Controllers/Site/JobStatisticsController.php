<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Skill;
use App\Library\PushNotification;
use App\Mail\JobPostMail;
use App\Mail\JobAppliedMail;
use App\Mail\JobAppliedNotifyMail;
use App\Models\Employer;
use App\Models\Bookmark;
use App\Models\Industry;
use App\Models\JobApply;
use App\Models\JobNotInterested;
use App\Models\JobPost;
use App\Models\JobReport;
use App\Models\Setting;
use App\Models\UserSkill;
use App\Models\User;
use App\Models\UserEducation;
use App\Models\Language;
use App\Models\Coach;
use App\Models\Candidate;
use App\Models\Qualification;
use App\Models\WorkExperience;
use App\Models\CompanyBookmark;
use App\Models\Transuction;
use App\Models\Notification;
use App\Models\Contact;
use App\Mail\VerifyUser;
use App\Mail\ContactNotifyMail;
use App\Mail\ThankyouMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Validator;
use DB;
use App\Http\Controllers\Site\AuthController;
use Response;
use App\Models\Coupon;
use App\Models\AboutSetting;
use App\Models\ContactSetting;
use Stripe;
use Stripe\StripeClient;
use Exception;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class JobStatisticsController extends Controller
{
	public function __construct()
    {

    }

	/*
	public function jobListingApply(Request $request){
        if($request->input('jobid') != ''){
            $jobapply = array(
                'where' => 'joblist',
                'jobid' => $request->input('jobid'),
                'for' => 'seeker',
                'loginid' => Auth::id(),
            );
            session()->put('jobapply', $jobapply);
            return response()->json(['code' => 200, 'jobapply' => $jobapply]);
        }
        return response()->json(['code' => 500]);
    }
	*/

	public static function update_SearchTerms($keyword, $app_type = 'web') {
		$keyword = trim(strtolower($keyword));
		if($keyword != '') {

			$IP=trim($_SERVER['REMOTE_ADDR']);
			$IP_array=array();

			//$data = $stats_db->get_row( "SELECT * FROM {$stats_db->prefix}hits WHERE post_id = {$postId} AND details ='{$details}' AND DATE(stat_date) = '".date('Y-m-d')."' AND type='term' AND site_id='".SITE_TERM."' LIMIT 1" );

			$data = DB::table('job_hits')->where('type', '=', 'term')->where('app_type', '=', $app_type)->where('post_id', '=', 0)->where('details', '=', $keyword)->whereDate('stat_date', '=', Carbon::now())->get();

			if( empty($data) || $data == NULL || $data->count()==0 ) {

				$IP_array[]=$IP;
				//$IP_array=array_unique($IP_array);
				$IP_array=array_filter($IP_array);

				DB::table('job_hits')->insert([
					'type' => 'term',
					'post_id' => 0,
					'details' => $keyword,
					'stat_date' => date('Y-m-d H:i:s'),
					'stat_count' => count($IP_array),
					'IPs' => implode(',' ,$IP_array),
					'app_type' => $app_type
				]);

			} else {

				$data=$data[0];

				$IP_array=explode(',', $data->IPs);
				$IP_array[]=$IP;
			


			}
		}
	}

	public function update_Hits(Request $request) {

		if( $request->input('jobid') != '' && $request->input('jobid') > 0 ) {

			echo $jobid = intval($request->input('jobid'));

			$IP=trim($_SERVER['REMOTE_ADDR']);
			$IP_array=array();

			$data = DB::table('job_hits')->where('type', '=', 'hits')->where('app_type', '=', 'web')->where('post_id', '=', $jobid)->whereDate('stat_date', '=', Carbon::now())->get();

			//print_r($data);
			//echo $data->count();

			if( empty($data) || $data == NULL || $data->count()==0 ) {

				$IP_array[]=$IP;
				//$IP_array=array_unique($IP_array);
				$IP_array=array_filter($IP_array);

				DB::table('job_hits')->insert([
					'type' => 'hits',
					'post_id' => $jobid,
					'stat_date' => date('Y-m-d H:i:s'),
					'stat_count' => count($IP_array),
					'IPs' => implode(',' ,$IP_array),
					'app_type' => 'web'
				]);

				//echo 'INSERT';
				return response()->json(['code' => 200, 'stats' => 'insert', 'jobid' => $jobid]);

			} else {

				$data=$data[0];

				$IP_array=explode(',', $data->IPs);
				$IP_array[]=$IP;
				//$IP_array=array_unique($IP_array);
				$IP_array=array_filter($IP_array);

				$affected = DB::table('job_hits')
				  ->where('id', intval($data->id))
				  ->update([
					'stat_count' => count($IP_array),
					'IPs' => implode(',' ,$IP_array),
					'stat_date' => date('Y-m-d H:i:s')
				]);

				//echo 'UPDATE';
				return response()->json(['code' => 200, 'stats' => 'update', 'jobid' => $jobid, 'id' => $data->id]);
			}
		}
		return response()->json(['code' => 500]);
		//echo '<hr />Done';
	}
}

