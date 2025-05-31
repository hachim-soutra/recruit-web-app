<?php

namespace App\Http\Livewire\Campaigns;

use App\Models\JobPost;
use Livewire\Component;

class ShowCampaignComponent extends Component
{
    public $campaign;
    public $selectedJobsModel;

    public function mount($campaign)
    {
        $this->campaign = $campaign;
        $this->selectedJobsModel = $campaign->jobs_models;
    }

    public function render()
    {
        return view('livewire.campaigns.show-campaign-component');
    }
}
