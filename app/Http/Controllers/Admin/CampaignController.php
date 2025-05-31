<?php

namespace App\Http\Controllers\Admin;

use App\Enum\CampaignStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignRequest;
use App\Jobs\SendCampaignJob;
use App\Models\Alert;
use App\Models\Campaign;
use App\Models\Industry;
use App\Models\JobPost;
use App\Models\User;
use App\Services\CampaignService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CampaignController extends Controller
{
    public function __construct(private CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index(Request $request)
    {
        $data = $this->campaignService->pagination();
        return view('admin.campaign.index', compact('data', 'request'));
    }

    public function alerts(Request $request)
    {
        $keyword = $request->input("keyword", null);
        $data = Alert::with("candidate.user")->where(function ($query) use ($keyword) {
            if ($keyword) {
                $query->orWhere("industry", "like", "%" . $keyword . "%");
                $query->orWhere("salary_period", "like", "%" . $keyword . "%");
                $query->orWhere("salary_rate", "like", "%" . $keyword . "%");
                $query->orWhere("preferred_job_type", "like", "%" . $keyword . "%");
                $query->orWhere("job_location", "like", "%" . $keyword . "%");
            }
        })
            ->paginate();
        return view('admin.campaign.alert', compact('data', 'request'));
    }

    public function resend($id)
    {
        $campaign = $this->campaignService->find($id);
        $campaign->status = CampaignStatusEnum::SENDING->value;
        $campaign->save();
        SendCampaignJob::dispatch($campaign);
        return redirect()->back()->withSuccess('Campaign created successfully.');
    }

    public function show(int $id)
    {
        $campaign = $this->campaignService->find($id);
        return view('admin.campaign.show', compact('campaign'));
    }

    public function info(int $id)
    {
        $campaign = $this->campaignService->find($id);
        return response()->json([
            'data' => [
                "jobs" => json_decode($campaign->jobs),
                "recipients" => json_decode($campaign->recipients)
            ]
        ]);
    }

    public function create()
    {
        return view('admin.campaign.create');
    }

    public function edit(int $id)
    {
        $campaign = $this->campaignService->find($id);
        return view('admin.campaign.edit', compact('campaign'));
    }

    public function store(CampaignRequest $request, CampaignService $campaignService)
    {
        $campaignService->save($request->validated());
        return response()->json([
            'data' => [
                'message' => "Campaign has been created.",
                'url' => route("admin.campaign.index"),
            ],
        ]);
    }

    public function filter(Request $request)
    {
        $selectedJobsModel = JobPost::where('status', '1');
        $selectedRecipientsModel = User::where("user_type", "candidate");
        if ($request->location) {
            $selectedJobsModel = $selectedJobsModel->where("job_location", $request->location);
            $selectedRecipientsModel = $selectedRecipientsModel->whereHas("candidate.alerts", function ($q) use ($request) {
                $q->where("job_location", $request->location)->orWhereNull("job_location");
            });
        }
        if ($request->industry) {
            $selectedJobsModel = $selectedJobsModel->where("functional_area", $request->industry);
            $selectedRecipientsModel = $selectedRecipientsModel->whereHas("candidate.alerts", function ($q) use ($request) {
                $q->where("industry", $request->industry)->orWhereNull("industry");
            });
        }
        return response()->json([
            'data' => [
                "jobs" => $selectedJobsModel->pluck("id")->toArray(),
                "recipients" => $selectedRecipientsModel->pluck("email")->toArray()
            ]
        ]);
    }

    public function update(CampaignRequest $request, CampaignService $campaignService, $id)
    {
        $campaignService->update($id, $request->validated());
        return response()->json([
            'data' => [
                'message' => "Campaign has been updated.",
                'url' => route("admin.campaign.index"),
            ],
        ]);
    }

    public function preview(Request $request, CampaignService $campaignService)
    {
        $request->validate([
            'object' => 'required',
            'selectedRecipients' => 'required|array|min:1',
            'selectedJobs' => 'required|array|min:1',
            'title' => 'required',
            'description' => 'required',
        ]);
        $jobs = JobPost::whereIn("id", $request->selectedJobs)->get();
        $title = $request->title;
        $email = auth()->user()->email;
        $description = $request->description;
        $viewJobs = view('emails.alert', compact('jobs', 'title', 'description', 'email'))->render();
        Mail::send([], [], function ($message) use ($viewJobs, $request) {
            $message->to(auth()->user()->email, auth()->user()->name);
            $message->subject($request->object);
            $message->setBody($viewJobs, 'text/html');
        });
        return response()->json([
            'data' => [
                'message' => "Campaign test has been sent to " . auth()->user()->email,
            ],
        ]);
    }

    public function delete(Campaign $campaign)
    {
        $result = $this->campaignService->delete($campaign);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Campaign has been deleted",
                ],
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not found",
                ],
            ]);
        }
    }
}
