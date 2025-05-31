<?php
/* BUGGY */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;

class JobStatisticsController extends Controller
{
	public function __construct()
    {

    }

	public static function update_SearchTerms($keyword, $app_type = 'app') {


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
				//$IP_array=array_unique($IP_array);
				$IP_array=array_filter($IP_array);

				$affected = DB::table('job_hits')
				  ->where('id', '=', intval($data->id))->where('app_type', '=', $app_type)
				  ->update([
					'stat_count' => count($IP_array),
					'IPs' => implode(',' ,$IP_array),
					'stat_date' => date('Y-m-d H:i:s')
				]);
			}
		}

	}
}
/* EoF */
