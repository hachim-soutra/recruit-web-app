<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CarrierJobUser;
use App\Notifications\CarrierJobListNotification;
use Carbon\Carbon;

class SendCarrierJobList extends Command
{
    protected $signature = 'carriers:send-job-list';
    protected $description = 'Send carrier job list report by user at the end of the day';

    public function handle() {

        $startDate = Carbon::yesterday()->startOfDay();
        $endDate = Carbon::yesterday()->endOfDay();

        $this->info("Sending carrier jobs for users between $startDate and $endDate");

        $userIds = CarrierJobUser::whereBetween('created_at', [$startDate, $endDate])
            ->distinct()
            ->pluck('user_id');

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            $jobs = CarrierJobUser::where('user_id', $user->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with('job')
                ->get();

            $report = [
                'agent' => $user->name,
                'total_jobs' => $jobs->count(),
                'jobs' => $jobs->map(fn($cj) => [
                    'job_id' => $cj->job_id,
                    'job_title' => optional($cj->job)->job_title ?? 'N/A',
                    'created_at' => $cj->created_at->format('Y-m-d H:i'),
                ]),
            ];

            // Step 3: Send notification
            $user->notify(new CarrierJobListNotification($report));
        }

        $this->info('Carrier job summaries sent to all users.');
    }
}
