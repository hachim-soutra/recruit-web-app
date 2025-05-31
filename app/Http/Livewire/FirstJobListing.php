<?php

namespace App\Http\Livewire;

use App\Enum\Jobs\JobStatsTypeEnum;
use App\Models\JobPost;
use App\Models\JobStat;
use App\Services\Common\JobPostService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class FirstJobListing extends Component
{
    public $firstJobDetail;

    protected $listeners = [
        'selectedJobChanged'
    ];

    public function mount($firstJobDetail,)
    {
        $this->firstJobDetail = $firstJobDetail;
    }

    public function selectedJobChanged(JobPost $job)
    {
        JobStat::create([
            'job_id'        => $job->id,
            'user_id'       => Auth::id() !== null ? Auth::id() : null,
            'guest_ip'      => Request::ip(),
            'type'          => JobStatsTypeEnum::CLICK->value
        ]);
        $this->firstJobDetail = $job;
    }

    public function render()
    {
        return view('livewire.first-job-listing');
    }
}
