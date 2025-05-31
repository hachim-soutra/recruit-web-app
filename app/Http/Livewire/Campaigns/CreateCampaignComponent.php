<?php

namespace App\Http\Livewire\Campaigns;

use App\Jobs\SendCampaignJob;
use App\Models\Industry;
use App\Models\JobPost;
use App\Models\User;
use App\Services\CampaignService;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateCampaignComponent extends Component
{

    // model attribute
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
        'dateEnvoi' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'selectedRecipients' => 'required|array|min:1',
        'selectedJobs' => 'required|array|min:1',
        'title' => 'required',
        'description' => 'required',
    ];

    public function mount(CampaignService $campaignService)
    {
        $this->selectedRecipients = [];
        $this->selectedJobs = [];

        $this->industries = Industry::where('status', '1')->orderBy("name")->get();

        $this->candidatesOriginal = $campaignService->candidates()->get();
        $this->candidates = $campaignService->candidates()->get();

        $this->jobsOriginal = $campaignService->jobs();
        $this->jobs = $campaignService->jobs();
        $this->selectedJobsModel = [];
    }

    public function save(CampaignService $campaignService)
    {
        $this->validate();
        $campaignService->save($this->object, $this->dateEnvoi, $this->title, $this->description, $this->selectedRecipients, $this->selectedJobs);
        return redirect()->route("admin.campaign.index");
    }

    public function send(CampaignService $campaignService)
    {
        $campaign = $campaignService->save($this->object, now(), $this->title, $this->description, $this->selectedRecipients, $this->selectedJobs);
        session()->flash('success', 'Campaign created successfully.');
        SendCampaignJob::dispatch($campaign);
        return redirect()->route("admin.campaign.index");
    }

    public function preview()
    {
        $jobs = $this->selectedJobsModel;
        $title = $this->title;
        $description = $this->description;
        $viewJobs = view('emails.alert', compact('jobs', 'title', 'description'))->render();
        Mail::send([], [], function ($message) use ($viewJobs) {
            $message->to(auth()->user()->email, auth()->user()->name);
            $message->subject($this->object);
            $message->setBody($viewJobs, 'text/html');
        });
        session()->flash('success', 'Campaign test sending successfully.');
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
        $this->candidates = User::where("user_type", "candidate")->whereNotIn("email", $this->selectedRecipients)->get();
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
        return view('livewire.campaigns.create-campaign-component');
    }

    public function changeIndustry()
    {
        $selectedJobsModel = JobPost::where('status', '1');
        $selectedRecipientsModel = User::where("user_type", "candidate");
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

        $this->selectedJobsModel = $selectedJobsModel->get();

        $this->selectedJobs = $this->selectedJobsModel->map(function ($x) {
            return $x->id;
        })->toArray();

        $this->selectedRecipients = $selectedRecipientsModel->get()->map(function ($x) {
            return $x->email;
        })->toArray();

        $this->candidates = $this->candidatesOriginal->whereNotIn("email", $this->selectedRecipients);
        $this->jobs = $this->jobsOriginal->whereNotIn("id", $this->selectedJobs);
    }
}
