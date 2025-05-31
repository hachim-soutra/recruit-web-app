<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Api\JobStatisticsController;

class SearchController extends Controller
{
	public function findJob(Request $request)
    {
        if (Auth::check()) {
            $keyword           = $request->input("keyword");
            $location          = $request->input("location");
            $functional_area   = $request->input("functional_area");
            $salary_range_min  = ""; 
            $salary_range_max  = "";
            if(count($request->input("salary_range_min"))>0){
                $salary_range_min  = min($request->input("salary_range_min"));
            }if(count($request->input("salary_range_max"))>0){
                $salary_range_max  = max($request->input("salary_range_max"));
            }
            $prefared_job_type = $request->input("prefared_job_type");

            if (Auth::user()->user_type == 'candidate') {
				
				\DB::enableQueryLog();
				
                $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type','functional_area', 'additinal_pay')->orderBy('id', 'ASC')
                    ->where('status', 1)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where(function ($query) use ($keyword, $location, $functional_area, $salary_range_min, $salary_range_max, $prefared_job_type) {
                        if ($keyword != '') {
                            
							//$query->orWhere("job_title", "like", "%" . $keyword . "%");
							//$query->where("job_title", "like", "%" . $keyword . "%");
							
							$keyword_str = str_ireplace(array(',' , ' ' , '.'), ',', $keyword);
							$keyword_arr = array_unique(array_filter(array_map('trim', explode(',', $keyword_str))));
							
							$query->where(function ($query) use($keyword_arr) {
								for ($i = 0; $i < count($keyword_arr); $i++){
									//$query->orwhere('job_title', 'like',  '%' . $keyword_arr[$i] .'%');
									$query->where('job_title', 'like',  '%' . $keyword_arr[$i] .'%');
								}
							});
                        }
						
						//mail('ayan.lakhanidev@gmail.com','loc', 'LOC='.$location);
						
                        if ($location != '') {
							/*	 // ORIGINAL
                            $query->orWhere("job_location", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("state", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("country", "like", "%" . $location . "%");
							*/
							
							/* AND */
							
							$location_str = str_ireplace(array(',' , '.'), ',', $location);
							$location_arr = array_unique(array_filter(array_map('trim', explode(',', $location_str))));
							
							//echo '<pre>'; print_r($location_arr); echo '</pre>'; 
							
							//die($location_arr[0]);
							
							//$query->where("job_location", "like", "%" .str_replace("Ireland","",$location) . "%")->orwhere("job_location", "like", "%" . $location_arr[0] . "%");	// DOESN'T WORK
							
							$query->where("job_location", "like", "%" . $location_arr[0] . "%");	// TEST
                        }
						
						/* Sanitization */
						/*
						if(is_array($functional_area))
							$functional_area = array_filter($functional_area);
						else
							$functional_area = trim($functional_area);
						*/
						
						/* Sanitization */
						$functional_area = (is_array($functional_area))?array_unique(array_filter(array_map('trim',$functional_area))):trim($functional_area);
                        if (!empty($functional_area)) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            // $query->orWhereIn("functional_area", $functional_area ); /* ORIGINAL */
							
							$query->whereIn("functional_area", $functional_area );	/* AND */
                        }
						
						/* Sanitization */
						$prefared_job_type = (is_array($prefared_job_type))?array_unique(array_filter(array_map('trim',$prefared_job_type))):trim($prefared_job_type);
                        if (!empty($prefared_job_type)) {
                            // $query->orWhere("preferred_job_type", "like", "%" . $prefared_job_type . "%");
							// $query->orWhereIn('preferred_job_type', $prefared_job_type);	/* ORIGINAL */
							$query->whereIn('preferred_job_type', $prefared_job_type);	/* AND */
							
                        }
						
                        if ($salary_range_min > 0 && $salary_range_max > 0 && $salary_range_max >= $salary_range_min) {
                            // $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                            //     ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);
                            $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                                ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);

                        }
                    })
                    ->get();
                    //->toSql();
				
				//$sql = str_replace_array('?', $query->getBindings(), $query->toSql());
				//mail('ayan.lakhanidev@gmail.com','SQL', 'SQL='.$sql);
				
				mail('ayan.lakhanidev@gmail.com','App SQL', "|".count($data)."|".print_r(\DB::getQueryLog(),true));
            }
			
			//$data = array();	// DEBUG CODE

            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'job_list' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }
	
    public function findJob_OLD(Request $request)
    {
        if (Auth::check()) {
            $keyword           = $request->input("keyword");
            $location          = $request->input("location");
            $functional_area   = $request->input("functional_area");
            $salary_range_min  = ""; 
            $salary_range_max  = "";
            if(count($request->input("salary_range_min"))>0){
                $salary_range_min  = min($request->input("salary_range_min"));
            }if(count($request->input("salary_range_max"))>0){
                $salary_range_max  = max($request->input("salary_range_max"));
            }
            $prefared_job_type = $request->input("prefared_job_type");

            if (Auth::user()->user_type == 'candidate') {
                $data = JobPost::select('id', 'employer_id', 'job_expiry_date', 'salary_from', 'salary_to', 'salary_currency', 'salary_period', 'hide_salary', 'job_title', 'job_location', 'city', 'state', 'country', 'zip', 'preferred_job_type','functional_area', 'additinal_pay')->orderBy('id', 'DESC')
                    ->where('status', 1)
                    ->where('job_status', 'Published')
                    ->whereDate('job_expiry_date', '>', Carbon::now())
                    ->where(function ($query) use ($keyword, $location, $functional_area, $salary_range_min, $salary_range_max, $prefared_job_type) {
                        if ($keyword) {
                            $query->orWhere("job_title", "like", "%" . $keyword . "%");
                        }
                        if ($location) {
                            $query->orWhere("job_location", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("state", "like", "%" . $location . "%");
                            $query->orWhere("city", "like", "%" . $location . "%");
                            $query->orWhere("country", "like", "%" . $location . "%");
                        }
                        if ($functional_area) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            $query->orWhereIn("functional_area", $functional_area );
                        }
                        if ($prefared_job_type) {
                            $query->orWhereIn('preferred_job_type', $prefared_job_type);

                            // $query->orWhere("preferred_job_type", "like", "%" . $prefared_job_type . "%");
                        }
                        if ($salary_range_min && $salary_range_max) {
                            // $query->orWhere("functional_area", "like", "%" . $functional_area . "%");
                            // $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                            //     ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);
                            $query->whereBetween('salary_from', [$salary_range_min, $salary_range_max])
                                ->orWhereBetween('salary_to', [$salary_range_min, $salary_range_max]);

                        }

                    })
                    ->get();

            }
			
            return response()->json([
                "status"   => true,
                "message"  => "Success",
                'job_list' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function findCandidate(Request $request)
    {
        if (Auth::check()) {
            $keyword = $request->input("keyword");
            $data    = Candidate::whereHas('user', function ($q) {
                $q->where([
                    "status"      => 1,
                    "verified"    => 1,
                    "is_complete" => 1,
                ]);
            })->where(function ($query) use ($keyword) {
                if ($keyword) {

                    $query->whereHas('jobrole', function ($q) use ($keyword) {
                        $q->where("name", "like", "%" . $keyword . "%");
                    });
                }
            })
                ->with('user', 'jobrole')->orderBy('id', 'DESC')
                ->get();

            return response()->json([
                "status"    => true,
                "message"   => "Success",
                'candidate' => $data,
            ], 200);

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }
}
