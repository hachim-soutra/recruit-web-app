<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\User;
use App\Library\PushNotification;
use DB;

class JobPostExpiryCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobpostexpiry:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an expiry email and in account notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
        //YYYY-MM-DD
        $current_date      = Carbon::now()->format('Y-m-d');
        $license_exp_dates = JobPost::where('job_expiry_date', $current_date)
            ->where('status', 1)
            ->get();
        if ($license_exp_dates) {
            foreach ($license_exp_dates as $key => $value) {
                $data                     = [];
                $data['name']             = Auth::user()->name;
                $data['job_title']        = $value->job_title;
                $data['email']            = $value->driver->email;
                $data['job_expiry_date']  = $value->job_expiry_date;
                // $body                     = "Job Expired " . $job->job_title . ".";    8-18
                $body                     = "Job Expired " . $value->job_title . ".";
                $title                    = 'Job Expired';
                $sender_id                = 1;
                // $receiver_id              = $job->employer_id;  8-18
                $receiver_id              = $value->employer_id;
                #push notification
                $employer = User::where('id', $value->employer_id)->first();
                if ($employer->fpm_token) {

                    $param          = [];
                    $param['msg']   = "Hi ".$employer->name .", your job ".$value->job_title." is going to expire ".$current_date;
                    $param['token'] = $employer->fpm_token;

                    PushNotification::fire_notification($param);
                }
                Mail::to($value->driver->email)->send(new JobExpireMail($data));

            }

        }

		/* AND */
		$this->setExpiredJobs();
		$this->setJobsStatus();
		/* AND */

        $this->info('Expiry:Cron Cummand Run successfully!');
    }

	/* AND */

	public function setExpiredJobs() {

		$condition = [['status', '=', '1'],['job_status', '=', 'Published']];
		$jobs = DB::table('job_posts')->where($condition)->whereDate('job_expiry_date', '<', Carbon::now())->get(['id']);
		$arr = array();
		if(!empty($jobs) && count($jobs) > 0) {
			foreach ($jobs as $jb) {
				$arr[] = $jb->id;
				$affected = DB::table('job_posts')->where('id', $jb->id)->update(['status' => 0]);
			}
			mail('ayan.lakhanidev@gmail.com', 'Jobs Expiry Cron', 'List: ' . implode(',' , $arr));
			$this->info('Jobs Expiry:' . implode(',' , $arr));
		}
	}

	public function setJobsStatus() {

		$condition = [['status', '<>', 1],['job_status', '=', 'Published']];
		$jobs = DB::table('job_posts')->where($condition)->whereDate('job_expiry_date', '>=', Carbon::now())->get(['id']);
		$arr = array();
		if(!empty($jobs) && count($jobs) > 0) {
			$arr[] = $jb->id;
			$affected = DB::table('job_posts')->where('id', $jb->id)->update(['status' => 1]);
		}
		mail('ayan.lakhanidev@gmail.com', 'Jobs Status Cron', 'List: ' . implode(',' , $arr));
	}

	/* AND */
}
/* EoF */
