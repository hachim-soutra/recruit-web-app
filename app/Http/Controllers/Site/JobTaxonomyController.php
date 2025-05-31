<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Carbon\Carbon;
use DB;

class JobTaxonomyController extends Controller
{

    private function sanitize_jobid($jobid)
    {
        if ($jobid != '' && $jobid != '0') {

            $job_slug = trim($jobid);
            if (!is_numeric($job_slug)) {

                $txt = trim(substr($job_slug, 0, stripos($job_slug, '-')));
                $jobid = intval($txt);
            }
        }
        return $jobid;
    }

    public function jobTaxonomyList()
    {

        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];

        $functional_area = DB::table('job_posts')->distinct()->where("functional_area", "<>", "all")->orderBy('functional_area', 'asc')->get(['functional_area']);
        $list = $functional_area;

        $job_type = DB::table('job_posts')->distinct()->orderBy('preferred_job_type', 'asc')->get(['preferred_job_type']);
        $list = $job_type;


        $list = array();
        //$job_location = DB::table('job_posts')->distinct()->orderBy('city', 'asc')->get(['city']);
        //$job_location = DB::table('job_posts')->distinct()->orderBy('job_location', 'asc')->get(['job_location']);
        $job_location = DB::table('job_posts')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now())->distinct()->orderBy('job_location', 'asc')->get(['job_location']);
        foreach ($job_location as $addr) {
            // print_r($addr);
            // echo $addr->job_location;
            // echo '<br />';

            $ll = explode(',', $addr->job_location);
            $list = array_merge($list, $ll);
        }
        $list = array_map('trim', $list);
        $list = array_map('ucwords', $list);

        $list = array_unique(array_filter($list));
        asort($list);
        $job_location = $list;

        //echo '<h5>jobTaxonomyList</h5>';
        //echo count($list); echo '<pre>'; print_r($list); echo '</pre>';

        $data = array(
            'functional_area' => $functional_area,
            'job_type' => $job_type,
            'job_location' => $job_location
        );

        return view('site.pages.jobtaxonomylist', compact('data'));
    }

    public function jobCategoryListing($term = null, $jobid = null)
    {

        /*
		if($jobid != '' && $jobid != '0'){

			$job_slug = trim($jobid);
			if( !is_numeric($job_slug)) {

				$txt = trim(substr($job_slug, 0, stripos($job_slug, '-')));
				$jobid = intval($txt);
			}

		}
		*/

        $term = trim($term);
        if ($term == '' || $term == null || empty($term)) return redirect()->route('common.job-listing');

        $jobid = $this->sanitize_jobid($jobid);
        //die('J='.$jobid);

        $term = trim(str_ireplace('-', '/', strtolower($term)));
        $terms = array_filter(explode('/', $term));
        $job_category = $term;

        $meta_title = ucwords($job_category) . " Jobs In Ireland - Recruit.ie";
        $meta_title = ucwords($job_category) . " Jobs In Ireland";
        $meta_desc = "View " . ucwords($job_category) . " jobs in Ireland in Recruit.ie's. job search. Find " . ucwords($job_category) . " jobs near you in Ireland in our national job search";

        $jobpost = (object)array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];

        $jobpost = JobPost::with('applicatons')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now());

        $jobpost->where("functional_area", "like", "%" . $job_category . "%");
        if (!empty($terms)) foreach ($terms as $sub_term)
            $jobpost->orWhere("functional_area", "like", "%" . trim($sub_term) . "%");

        $jobpost = $jobpost->orderByRaw('id desc');

        $count = $jobpost->count();
        //echo '<pre>'; print_r($jobpost); echo '</pre>'; die;

        if ($count > 0) {
            $meta_title = "{$count} " . ucwords($job_category) . " Jobs In Ireland - Recruit.ie";
            $meta_title = "{$count} " . ucwords($job_category) . " Jobs In Ireland";
            $meta_desc = "View {$count} " . ucwords($job_category) . " jobs in Ireland in Recruit.ie's. job search. Find " . ucwords($job_category) . " jobs near you in Ireland in our national job search";
            $meta_desc = "View {$count} " . ucwords($job_category) . " jobs in Ireland in Recruit.ie's job search. Find and apply for " . ucwords($job_category) . " jobs near you in Ireland in our national job search";
        }

        $paginateQuery = $jobpost->paginate(6);
        //echo '<pre>'; print_r($paginateQuery); echo '</pre>'; die;

        if ($jobid != '' && $jobid != '0') {

            $firstJobDetail = JobPost::with('bookmark', 'applicatons')
                ->where($condition)
                ->where('id', $jobid)
                ->whereDate('job_expiry_date', '>', Carbon::now());

            $firstJobDetail = $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$jobid])->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;

        } else {

            $firstJobDetail = $jobpost->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;
        }

        if ($jobid != null) {
            if ($firstJobDetail->job_title != '') $meta_title = "{$firstJobDetail->job_title} job by {$firstJobDetail->company_name}";

            $meta_desc = "{$firstJobDetail->job_title} is a {$firstJobDetail->preferred_job_type} " . ucwords($job_category) . " job by {$firstJobDetail->company_name} for candidates having" . " {$firstJobDetail->experience} of experience";

            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
        }

        session()->forget('search_job');

        $data = array(
            'term' => $job_category,
            'jobPost' => $paginateQuery,
            'jobid' => $jobid,
            'firstJobDetail' => $firstJobDetail,
            'count' => $count,
            'meta_title' => trim($meta_title),
            'meta_desc' => trim($meta_desc)
        );

        return view('site.pages.jobcategory', compact('data'));
    }

    public function jobTypeListing($term = null, $jobid = null)
    {

        $term = trim($term);
        if ($term == '' || $term == null || empty($term)) return redirect()->route('common.job-listing');

        $jobid = $this->sanitize_jobid($jobid);

        //echo $term;
        //echo $jobid;
        //die;

        $job_type = $term;

        $meta_title = ucwords($job_type) . " jobs in Ireland";
        $meta_desc = "View " . ucwords($job_type) . " jobs in Ireland and Recruit.ie's national database of " . ucwords($job_type) . " jobs in Ireland. Search and apply for " . ucwords($job_type) . " jobs in Ireland with Recruit.ie";

        $jobpost = (object)array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];

        $jobpost = JobPost::with('applicatons')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now());

        $jobpost->where("preferred_job_type", "like", "%" . $job_type . "%");

        $jobpost = $jobpost->orderByRaw('id desc');

        $count = $jobpost->count();
        //echo '<pre>'; print_r($jobpost); echo '</pre>'; die;

        if ($count > 0) {
            $meta_title = "{$count} " . ucwords($job_type) . " jobs in Ireland";
            $meta_desc = "View {$count} " . ucwords($job_type) . " jobs in Ireland and Recruit.ie's national database of " . ucwords($job_type) . " jobs in Ireland. Search and apply for " . ucwords($job_type) . " jobs in Ireland with Recruit.ie";
        }

        $paginateQuery = $jobpost->paginate(6);
        //echo '<pre>'; print_r($paginateQuery); echo '</pre>'; die;

        if ($jobid != '' && $jobid != '0') {

            $firstJobDetail = JobPost::with('bookmark', 'applicatons')
                ->where($condition)
                ->where('id', $jobid)
                ->whereDate('job_expiry_date', '>', Carbon::now());

            $firstJobDetail = $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$jobid])->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;

        } else {

            $firstJobDetail = $jobpost->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;
        }

        if ($jobid != null) {
            if ($firstJobDetail->job_title != '') $meta_title = "{$firstJobDetail->job_title} " . ucwords($job_type) . " job by {$firstJobDetail->company_name}";

            $meta_desc = "{$firstJobDetail->job_title} is a " . ucwords($job_type) . " job by {$firstJobDetail->company_name} for candidates having" . " {$firstJobDetail->experience} of experience";

            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
        }

        session()->forget('search_job');

        $data = array(
            'term' => $term,
            'jobPost' => $paginateQuery,
            'jobid' => $jobid,
            'firstJobDetail' => $firstJobDetail,
            'count' => $count,
            'meta_title' => trim($meta_title),
            'meta_desc' => trim($meta_desc)
        );

        //print_r($data);

        return view('site.pages.jobtype', compact('data'));
    }

    public function jobLocationListing($term = null, $jobid = null)
    {

        $term = trim($term);
        if ($term == '' || $term == null || empty($term)) return redirect()->route('common.job-listing');

        $jobid = $this->sanitize_jobid($jobid);

        //echo $term;
        //echo $jobid;
        //die;

        $location = $term;

        $meta_title = "Jobs in " . ucwords($location) . ", full-time, part-time";
        $meta_desc = "View full-time and part-time jobs in " . ucwords($location) . " in Recruit.ie's job search. Search and apply for jobs in " . ucwords($location) . " with Recruit.ie";

        $jobpost = (object)array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];

        $jobpost = JobPost::with('applicatons')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now());

        $jobpost->where("job_location", "like", "%" . $location . "%");
        $jobpost->orWhere("city", "like", "%" . $location . "%");
        $jobpost->orWhere("state", "like", "%" . $location . "%");
        $jobpost->orWhere("country", "like", "%" . $location . "%");

        $jobpost = $jobpost->orderByRaw('id desc');

        $count = $jobpost->count();
        //echo '<pre>'; print_r($jobpost); echo '</pre>'; die;

        if ($count > 0) {
            $meta_title = "{$count} jobs in " . ucwords($location) . ", full-time, part-time";
            $meta_desc = "View {$count} full-time and part-time jobs in " . ucwords($location) . " in Recruit.ie's job search. Search and apply for jobs in " . ucwords($location) . " with Recruit.ie";
        }


        $paginateQuery = $jobpost->paginate(6);
        //echo '<pre>'; print_r($paginateQuery); echo '</pre>'; die;

        if ($jobid != '' && $jobid != '0') {

            $firstJobDetail = JobPost::with('bookmark', 'applicatons')
                ->where($condition)
                ->where('id', $jobid)
                ->whereDate('job_expiry_date', '>', Carbon::now());

            $firstJobDetail = $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$jobid])->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;

        } else {

            $firstJobDetail = $jobpost->first();
            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
            //die;
        }

        if ($jobid != null) {
            if ($firstJobDetail->job_title != '') $meta_title = "{$firstJobDetail->job_title} job by {$firstJobDetail->company_name} in " . ucwords($location);

            $meta_desc = "{$firstJobDetail->job_title} is a {$firstJobDetail->preferred_job_type} job in " . ucwords($location) . " by {$firstJobDetail->company_name} for candidates having" . " {$firstJobDetail->experience} of experience";

            //echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
        }

        session()->forget('search_job');

        $data = array(
            'term' => $term,
            'jobPost' => $paginateQuery,
            'jobid' => $jobid,
            'firstJobDetail' => $firstJobDetail,
            'count' => $count,
            'meta_title' => trim($meta_title),
            'meta_desc' => trim($meta_desc)
        );

        return view('site.pages.joblocation', compact('data'));
    }

    public function jobLocationListing_OLD($term = null, $jobid = null)
    {

        $term = trim($term);
        if ($term == '' || $term == null || empty($term)) return redirect()->route('common.job-listing');


        $location = $term;

        $jobpost = (object)array();
        $condition = [['status', '=', '1'], ['job_status', '=', 'Published']];

        $jobpost = JobPost::with('applicatons')->where($condition)->whereDate('job_expiry_date', '>', Carbon::now());

        $jobpost->where("job_location", "like", "%" . $location . "%");
        //$jobpost->orWhere("city", "like", "%" . $location . "%");
        //$jobpost->orWhere("state", "like", "%" . $location . "%");
        //$jobpost->orWhere("country", "like", "%" . $location . "%");

        $jobpost = $jobpost->orderByRaw('id desc');

        $count = $jobpost->count();
        //echo '<pre>'; print_r($jobpost); echo '</pre>'; die;


        $paginateQuery = $jobpost->paginate(6);
        //echo '<pre>'; print_r($paginateQuery); echo '</pre>'; die;

        $firstJobDetail = $jobpost->first();

        /*
		if($jobid != '' && $jobid != '0'){

            $firstJobDetail = JobPost::with('bookmark', 'applicatons')
                            ->where($condition)
                            ->where('id', $jobid)
                            ->whereDate('job_expiry_date', '>', Carbon::now());

			//$firstJobDetail = $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$jobid])->first();

			//echo '<pre>'; print_r($firstJobDetail); echo '</pre>';
			die($jobid );
        }
		else {

			//$firstJobDetail = $jobpost->first();
			//echo '<pre>'; print_r($firstJobDetail); echo '</pre>'; die;

			//session()->forget('search_job');
		}
		*/
        /*
		if($jobid == '0'){
            session()->forget('search_job');
        }
        */

        $data = array(
            'term' => $term,
            'jobPost' => $paginateQuery,
            //'firstJobDetail' => $firstJobDetail->orderByRaw('case when id=? then -1 else 0 end, id desc', [$jobid])->first(),
            'firstJobDetail' => $firstJobDetail,
            'count' => $count
        );

        return view('site.pages.joblocation', compact('data'));

        die($term . $count);

        /*
        //$location = $request->input("job_location");


		$query = JobPost::with('bookmark', 'applicatons')->where($condition)
               // ->whereDate('job_expiry_date', '>', Carbon::now())
                ->where(function ($query) use ($keyword, $location, $functional_area) {
                    if ($location) {
                        $query->where("job_location", "like", "%" . $location . "%");

						$query->orWhere("city", "like", "%" . $location . "%");
                        $query->orWhere("state", "like", "%" . $location . "%");
                        $query->orWhere("country", "like", "%" . $location . "%");
                    }
                });


		$jobpost = $query->where("job_location", "like", "%" . str_replace("Ireland","",$location). "%");


        $jobpost = $jobpost->paginate(6);
		*/

        //$firstJobDetail = $jobpost->first();
        $data = array(
            'term' => $term,
            'jobPost' => $jobpost,
            //'firstJobDetail' => $firstJobDetail,
            'totalResult' => $jobpost->count(),
        );

        return view('site.pages.joblisting', compact('data'));
    }
}
/* EoF */
