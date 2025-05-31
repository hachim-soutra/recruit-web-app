<?php

namespace App\Console\Commands;

use App\Models\JobPost;
use App\Services\Common\LocationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateJobCountryIfNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:country-if-null';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update or set the country column if is null';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $locationService;
    public function __construct(LocationService $locationService)
    {
        parent::__construct();
        $this->locationService = $locationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        JobPost::whereNull('country')->orWhereNull('state')->orWhereNull('city')->chunk(200, function ($jobs) {
            foreach ($jobs as $job) {
                if ($job->job_location == null) {
                    continue;
                }
                Log::info('Start change job.', ['id' => $job->id, 'country' => $job->country, 'state' => $job->state, 'city' => $job->city ]);
                $location = $this->locationService->getCountryFromAddress($job->job_location);
                if ($job->city == null) {
                    $job->city = $location['city'];
                }
                if ($job->state == null) {
                    $job->state = $location['state'];
                }
                if ($job->country == null) {
                    $job->country = $location['country'];
                }
                if ($job->zip == null) {
                    $job->zip = $location['zip_code'];
                }

                $job->save();
                Log::info('job changed.', ['id' => $job->id, 'country' => $job->country, 'state' => $job->state, 'city' => $job->city ]);
            }
        });
        return 1;
    }
}
