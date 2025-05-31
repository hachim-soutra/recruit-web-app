<?php

namespace App\Console\Commands;

use App\Enum\CampaignStatusEnum;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCampaignCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $campaigns = Campaign::where("status", CampaignStatusEnum::WAITING->value)->get();
        foreach ($campaigns as $campaign) {
            if ($campaign->date_envoi->diffInMinutes(null, false) > 0) {
                $campaign->status = CampaignStatusEnum::SENDING->value;
                $campaign->save();
                SendCampaignJob::dispatch($campaign);
            }
        }
    }
}
