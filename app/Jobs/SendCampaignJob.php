<?php

namespace App\Jobs;

use App\Enum\CampaignStatusEnum;
use App\Mail\AlertMail;
use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (json_decode($this->campaign->recipients) as $recipient) {
            Mail::to($recipient)->queue(new AlertMail($this->campaign, $recipient));
        }
        $this->campaign->status = CampaignStatusEnum::SENT->value;
        $this->campaign->date_envoi = now();
        $this->campaign->save();
    }
}
