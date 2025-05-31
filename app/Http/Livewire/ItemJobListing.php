<?php

namespace App\Http\Livewire;

use App\Models\JobPost;
use Livewire\Component;

class ItemJobListing extends Component
{
    public $jobPosts;

    public $firstJobDetail;

    public function mount($jobPosts, $firstJobDetail)
    {
        $this->jobPosts = $jobPosts;
        $this->firstJobDetail = $firstJobDetail;
    }

    public function render()
    {
        return view('livewire.item-job-listing');
    }

    public function selectJob($selectedJobId)
    {
        $this->firstJobDetail = JobPost::with('bookmark', 'applicatons')->find($selectedJobId);
        $this->emit('selectedJobChanged', $this->firstJobDetail);

    }
}
