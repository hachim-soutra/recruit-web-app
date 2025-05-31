<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Employer;
use App\Models\JobPost;
use App\Models\JobApply;

use Validator;
use Carbon\Carbon;
use Auth;
use DB;

class StatisticsController extends Controller
{

	public function __construct() {
		

    }
	
	
	private function get_EmployerDetails($id) {
		if($id > 0) {
			$data = DB::table('employers')->where('id', '=', $id);
			return $data;
		}
		else return null;
	}
	
	private function get_Employers() {

		/*
		$list = User::orderBy('updated_at', 'DESC')
            ->with('employer')
            ->where('user_type', 'employer');
		*/

		/*
		$list = DB::table('employers')->whereNotNull('company_name')->orderBy('company_name', 'asc')->get();
		*/

		$list = DB::table('users')->leftJoin('employers', function ($join) {
            $join->on('users.id', '=', 'employers.user_id')
                ->where('user_type', '=', 'employer');
        })->whereNotNull('company_name')->orderBy('name', 'asc')
		->orderBy('name', 'asc')->get();

		//echo count($list);
		//print_r($list); die;

		return $list;
	}

	private function get_Providers() {

		/*
		$list = User::orderBy('updated_at', 'DESC')
            ->with('employer')
            ->where('user_type', 'employer');
		*/

		/*
		$list = DB::table('employers')->whereNotNull('company_name')->orderBy('company_name', 'asc')->get();
		*/

		$list = DB::table('users')->leftJoin('employers', function ($join) {
            $join->on('users.id', '=', 'employers.user_id')
                ->where('user_type', '=', 'employer');
        })->whereNotNull('company_name')->orderBy('company_name', 'asc')
		->orderBy('company_name', 'asc')->get();

		//echo count($list);
		//print_r($list); die;

		return $list;
	}

	private function get_Jobs($employer_id) {

		$list = DB::table('job_posts')->where('employer_id', '=', $employer_id)->orderBy('job_title', 'asc')->get();

		//echo count($list);
		//print_r($list); die;

		return $list;
	}

	private function get_Applications($job_id) {

		$list = DB::table('job_applies')->where('job_id', '=', $job_id)->get();
		return count($list);
	}

	public function Report1($type = 'display', Request $request = null) {

		$employer_id = intval(request('employer_id'));
		$employers = $this->get_Employers();
		$providers = $this->get_Providers();

		$jobs = $this->get_Jobs($employer_id);

		$data = array();
		foreach($jobs as $j) {

			//$d = array();
			$d = (object)[];
			$d->job_id = $j->id;
			$d->job_title = $j->job_title;
			$d->employer_id = $j->employer_id;
			$d->total_apply = intval($this->get_Applications($d->job_id));

			//print_r($d);

			$data[] = $d; 
		}

		return view('admin.statistics.report1', compact('employer_id', 'employers', 'providers', 'data', 'request'));
	}

	public function Recent1(Request $request) {
		
		$data = array();
		$appdate = trim($request->input('appdate'));
		$keyword = trim($request->input('keyword'));
		
		/*
		SELECT * FROM `job_applies`, `candidates`, `users`, `job_posts`, `employers` WHERE
		`candidates`.id=`job_applies`.`candidate_id`
		AND
		`candidates`.`user_id`=`users`.`id`
		AND
		`job_posts`.`id`=`job_applies`.`job_id`
		AND
		`employers`.industry_id=`job_posts`.`employer_id`
		
		select `job_applies`.`created_at` as `created_at`, `job_posts`.`id` as `job_id`, `job_posts`.`job_title`, `employers`.`company_name` as `company_name`, `c`.`name` as `candidate_name`, `c`.`email` as `candidate_email`, `c`.`mobile` as `candidate_mobile`, `c`.`id` as `candidate_id` from `job_applies` inner join `candidates` on `candidates`.`id` = `job_applies`.`candidate_id` inner join `users` as `c` on `candidates`.`user_id` = `c`.`id` inner join `job_posts` on `job_posts`.`id` = `job_applies`.`job_id` inner join `employers` on `employers`.`user_id` = `job_posts`.`employer_id` order by `job_applies`.`id` desc
		*/
		
		$job_applies = DB::table('job_applies')
					->join('candidates', 'candidates.user_id', '=', 'job_applies.candidate_id')
					->join('users AS c', 'candidates.user_id', '=', 'c.id')
					->join('job_posts', 'job_posts.id', '=', 'job_applies.job_id')
					->join('employers', 'employers.user_id', '=', 'job_posts.employer_id')
					//->whereDate('job_applies.created_at', '=', $appdate)
					->orderBy('job_applies.id', 'desc')
					->select(
						'job_applies.created_at AS created_at',
						'job_posts.id AS job_id',
						'job_posts.job_title',
						'employers.company_name AS company_name',
						'c.name AS candidate_name',
						'c.email AS candidate_email',
						'c.mobile AS candidate_mobile',
						'c.id AS candidate_id'
					);
		
		if($appdate !='') $job_applies->whereDate('job_applies.created_at', '=', $appdate);
		
		if($keyword !='') $job_applies->where('job_posts.job_title', 'LIKE', "%{$keyword}%")
										->orWhere('employers.company_name', 'LIKE', "%{$keyword}%")
										->orWhere('c.name', 'LIKE', "%{$keyword}%")
										->orWhere('c.email', 'LIKE', "%{$keyword}%")
										->orWhere('c.mobile', 'LIKE', "%{$keyword}%");
		
		$data = $job_applies->get();
					//->toSql();

		//print_r($data);die;
		
		foreach($data as &$d) {
			
			//$d->company_details = $this->get_EmployerDetails($d->employer_id);
		}
		
		
		return view('admin.statistics.recent1', compact('appdate', 'keyword', 'data', 'request'));
	}

	public function SearchKeywords1(Request $request) {
		
		//////////////////
		$row_count = 25;
		//////////////////

		$data = array();
		
		
		
		$paged = min(1,intval($request->input('page',1)));
		
		$f_site=trim($request->input('f_site','web'));
		$f_site_cond=" AND app_type LIKE '{$f_site}%' ";
		
		$f_year = min(2023, intval($request->input('f_year',2023)));
		$f_year_cond = " AND YEAR(stat_date)={$f_year} ";
		
		$match_type = trim($request->input('match_type','exact'));
		$keyword = trim($request->input('keyword',''));
		$keyword_cond='';
		if($keyword !='') {
			
			if($match_type=='exact') {
				$keyword_cond=" AND details LIKE '{$keyword}' ";
			} elseif($match_type=='begins') {	
				$keyword_cond=" AND details LIKE '{$keyword}%' ";
			} else {
				$keyword_cond=" AND details LIKE '%{$keyword}%' ";
			}
		}
		
		$SQL = "SELECT SQL_CALC_FOUND_ROWS
			keywords, 
			SUM(jan_total) AS jan_total,
			SUM(feb_total) AS feb_total,
			SUM(mar_total) AS mar_total,
			SUM(apr_total) AS apr_total,
			SUM(may_total) AS may_total,
			SUM(jun_total) AS jun_total,
			SUM(jul_total) AS jul_total,
			SUM(aug_total) AS aug_total,
			SUM(sep_total) AS sep_total,
			SUM(oct_total) AS oct_total,
			SUM(nov_total) AS nov_total,
			SUM(dec_total) AS dec_total,
			SUM(total) total
		FROM (
			SELECT
				details keywords,
				SUM(case when month(stat_date)=1 then stat_count end) As jan_total,
				SUM(case when month(stat_date)=2 then stat_count end) As feb_total,
				SUM(case when month(stat_date)=3 then stat_count end) As mar_total,
				SUM(case when month(stat_date)=4 then stat_count end) As apr_total,
				SUM(case when month(stat_date)=5 then stat_count end) As may_total,
				SUM(case when month(stat_date)=6 then stat_count end) As jun_total,
				SUM(case when month(stat_date)=7 then stat_count end) As jul_total,
				SUM(case when month(stat_date)=8 then stat_count end) As aug_total,
				SUM(case when month(stat_date)=9 then stat_count end) As sep_total,
				SUM(case when month(stat_date)=10 then stat_count end) As oct_total,
				SUM(case when month(stat_date)=11 then stat_count end) As nov_total,
				SUM(case when month(stat_date)=12 then stat_count end) As dec_total,
				SUM(stat_count) total

			FROM job_hits
			
				WHERE (`type`='term' OR `type`='keyword')
				{$keyword_cond} {$f_year_cond} {$f_site_cond}

				GROUP BY YEAR(stat_date), MONTH(stat_date), TRIM(details)

			) AS t
			
			GROUP BY keywords
			
			ORDER BY total DESC, keywords

		LIMIT ".(($paged-1)*$row_count).",{$row_count}";
		
		DB::statement("SET SQL_MODE=''");
		//$data = DB::select('select * from users where id = ?', [1]);
		$data = DB::select(DB::raw($SQL));
		
		return view('admin.statistics.searchkeywords1', compact('SQL', 'keyword', 'data', 'request', 'paged'));
	}

}

/* EoF */