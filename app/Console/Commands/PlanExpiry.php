<?php

namespace App\Console\Commands;


use App\Models\Coach;
use App\Models\Transuction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PlanExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'planexpiry:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Plan Expiry CRON Job';

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
        $current_date = Carbon::now()->format('Y-m-d');
        $after_date   = Carbon::now()->subDays(2)->format('Y-m-d');
        $before_date  = Carbon::now()->addDays(2)->format('Y-m-d');

        $exp_dates = Transuction::where('expiry_date', '=', $current_date)->with('user')
            ->where('pay_by', 'coach')
            ->get();
        if ($exp_dates) {
            foreach ($exp_dates as $key => $value) {
                $data                = [];
                $data['id']          = $value->id;
                $data['name']        = $value->user->name;
                $data['email']       = $value->user->email;
                $data['expiry_date'] = $value->expiry_date;
                $title               = "Coach Subscription Plan Expired";
                $body                = $value->user->name . " your current plan expired. Please renew your paln.";
                $sender_id           = 1;
                $receiver_id         = $value->user_id;


            }

        }
        $after_exp_dates = Transuction::where('expiry_date', '=', $after_date)->with('user')
            ->where('pay_by', 'coach')
            ->get();

        if ($after_exp_dates) {
            foreach ($after_exp_dates as $key => $value) {
                $data                = [];
                $data['id']          = $value->id;
                $data['name']        = $value->user->name;
                $data['email']       = $value->user->email;
                $data['expiry_date'] = $value->expiry_date;
                $title               = "Coach Subscription Plan Expired";
                $body                = $value->user->name . " your current plan expired. Please renew your paln.";
                $sender_id           = 1;
                $receiver_id         = $value->user_id;

                Mail::to($value->user->email)->send(new AfterPlanExpireMail($data));

            }

        }
        $before_exp_dates = Transuction::where('expiry_date', '=', $before_date)->with('user')
            ->where('pay_by', 'coach')
            ->get();

        if ($before_exp_dates) {
            foreach ($before_exp_dates as $key => $value) {
                $data                = [];
                $data['id']          = $value->id;
                $data['name']        = $value->user->name;
                $data['email']       = $value->user->email;
                $data['expiry_date'] = $value->expiry_date;
                $title               = "Coach Subscription Plan Expired";
                $body                = $value->user->name . " your current plan expired. Please renew your paln.";
                $sender_id           = 1;
                $receiver_id         = $value->user_id;

                Mail::to($value->user->email)->send(new BeforePlanExpireMail($data));

            }

        }

        $this->info('Planexpiry:Cron Cummand Run successfully!');
    }
}
