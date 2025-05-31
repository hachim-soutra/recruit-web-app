<?php

namespace App\Console\Commands;

use App\Models\Employer;
use App\Models\JobPost;
use App\Services\Common\LocationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCompanyCountryIfNull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:company-country-if-null';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command update or set the country column if is null for employers table';

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
        Employer::whereNull('country')->orWhereNull('state')->orWhereNull('city')->chunk(200, function ($employers) {
            foreach ($employers as $employer) {
                if ($employer->address == null) {
                    continue;
                }
                Log::info('Start change employer.', ['id' => $employer->id, 'country' => $employer->country, 'state' => $employer->state, 'city' => $employer->city ]);
                $location = $this->locationService->getCountryFromAddress($employer->address);
                if ($employer->city == null) {
                    $employer->city = $location['city'];
                }
                if ($employer->state == null) {
                    $employer->state = $location['state'];
                }
                if ($employer->country == null) {
                    $employer->country = $location['country'];
                }
                $employer->save();
                Log::info('job changed.', ['id' => $employer->id, 'country' => $employer->country, 'state' => $employer->state, 'city' => $employer->city ]);
            }
        });
        return 1;
    }
}
