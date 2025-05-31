<?php

namespace App\Http\Livewire\Campaigns;

use App\Models\Campaign;
use App\Models\Industry;
use App\Services\CampaignService;
use Livewire\Component;

class EditCampaignComponent extends Component
{

    // model attribute
    public $campaignId;
    public $object;
    public $dateEnvoi;
    public $selectedRecipients;
    public $selectedJobs;
    public $title;
    public $description;

    // select filters
    public $location;
    public $industry;
    public $industries;

    // add custom recipient
    public $email;

    public $candidates;
    public $candidatesOriginal;

    public $jobs;
    public $jobsOriginal;
    public $selectedJobsModel;

    protected $rules = [
        'object' => 'required',
        'dateEnvoi' => 'required|date_format:Y-m-d\TH:i',
        'selectedRecipients' => 'required|array|min:1',
        'selectedJobs' => 'required|array|min:1',
        'title' => 'required',
        'description' => 'required',
    ];

    public function mount(CampaignService $campaignService, Campaign $campaign)
    {
        $this->campaignId = $campaign->id;
        $this->object = $campaign->object;
        $this->dateEnvoi = $campaign->date_envoi->format("Y-m-d\TH:i");
        $this->selectedRecipients = json_decode($campaign->recipients);
        $this->selectedJobs = json_decode($campaign->jobs);
        $this->title = $campaign->title;
        $this->description = $campaign->description;

        $this->industries = Industry::where('status', '1')->orderBy("name")->get();
        $this->candidatesOriginal = $campaignService->candidates()->get();
        $this->candidates = $campaignService->candidates()->whereNotIn("email", $this->selectedRecipients)->get();

        $this->jobsOriginal = $campaignService->jobs();
        $this->jobs = $this->jobsOriginal->whereNotIn("id", $this->selectedJobs);
        $this->selectedJobsModel = $this->jobsOriginal->whereIn("id", $this->selectedJobs);
    }

    public function update(CampaignService $campaignService)
    {
        $this->validate();
        $campaignService->update($this->campaignId, $this->object, $this->dateEnvoi, $this->title, $this->description, $this->selectedRecipients, $this->selectedJobs);
        return redirect()->route("admin.campaign.index");
    }

    // Candidate methods
    public function addEmailCandidate()
    {
        $this->validate([
            "email" => "required|email"
        ]);
        array_push($this->selectedRecipients, $this->email);
        $this->email = "";
    }

    public function addCandidate($candidate)
    {
        array_push($this->selectedRecipients, $candidate["email"]);
        $this->handleRecipients();
    }

    public function removeCandidate($candidate)
    {
        $this->selectedRecipients = array_filter($this->selectedRecipients, fn ($m) => $m != $candidate);
        $this->handleRecipients();
    }

    public function handleRecipients()
    {
        $this->candidates = $this->candidatesOriginal->whereNotIn("email", $this->selectedRecipients);
    }
    // End candidate methods

    // Jobs methods
    public function selectJob($selectedJob)
    {
        array_push($this->selectedJobs, $selectedJob);
        $this->handleJob();
    }

    public function removeJob($selectedJob)
    {
        $this->selectedJobs = array_filter($this->selectedJobs, fn ($m) => $m != $selectedJob);
        $this->handleJob();
    }

    public function handleJob()
    {
        $this->selectedJobsModel = $this->jobsOriginal->whereIn("id", $this->selectedJobs);
        $this->jobs = $this->jobsOriginal->whereNotIn("id", $this->selectedJobs);
    }
    // End Jobs methods


    public function render()
    {
        return view('livewire.campaigns.edit-campaign-component');
    }

    public function changeIndustry()
    {
        $selectedJobsModel = $this->jobsOriginal;
        $selectedRecipientsModel = $this->candidatesOriginal;
        if ($this->location) {
            $selectedJobsModel = $selectedJobsModel->where("job_location", $this->location);
            $selectedRecipientsModel = $selectedRecipientsModel->whereHas("candidate.alerts", function ($q) {
                $q->where("job_location", $this->location)->orWhereNull("job_location");
            });
        }
        if ($this->industry) {
            $selectedJobsModel = $selectedJobsModel->where("functional_area", $this->industry);
            $selectedRecipientsModel = $selectedRecipientsModel->whereHas("candidate.alerts", function ($q) {
                $q->where("industry", $this->industry)->orWhereNull("industry");
            });
        }
        $this->selectedJobsModel = $selectedJobsModel;

        $this->selectedJobs = $this->selectedJobsModel->map(function ($x) {
            return $x->id;
        })->toArray();

        $this->selectedRecipients = $this->selectedRecipientsModel->map(function ($x) {
            return $x->email;
        })->toArray();

        $this->candidates = $this->candidatesOriginal->whereNotIn("email", $this->selectedRecipients);
        $this->jobs = $this->jobsOriginal->whereNotIn("id", $this->selectedJobs);
    }
}
