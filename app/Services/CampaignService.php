<?php

namespace App\Services;

use App\Enum\UserSourceEnum;
use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CampaignService
{
    public function __construct(protected Campaign $model)
    {
        $this->model = $model;
    }

    public function pagination()
    {
        return $this->model->query()->paginate();
    }

    public function find($id)
    {
        return $this->model->query()->findOrFail($id);
    }

    public function recipients()
    {
        return User::where("user_type", "candidate")->where("source", UserSourceEnum::ALERT->value)->with("candidate.alerts")->get();
    }

    public function jobs()
    {
        return JobPost::where('status', '1')->orderBy('id', 'DESC')->get();
    }

    public function candidates()
    {
        return User::where("user_type", "candidate")->with(["candidate.alerts"])->orderBy('id', 'desc');
    }

    public function save($data)
    {
        $campaign = new Campaign();
        $campaign->title = $data["title"];
        $campaign->object = $data["object"];
        $campaign->description = $data["description"];
        $campaign->date_envoi = $data["dateEnvoi"];
        $campaign->recipients = json_encode($data["selectedRecipients"]);
        $campaign->jobs = json_encode($data["selectedJobs"]);
        $campaign->save();

        return $campaign;
    }
    public function update($id, $data)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->title = $data["title"];
        $campaign->object = $data["object"];
        $campaign->description = $data["description"];
        $campaign->date_envoi = $data["dateEnvoi"];
        $campaign->recipients = json_encode($data["selectedRecipients"]);
        $campaign->jobs = json_encode($data["selectedJobs"]);
        $campaign->save();

        return $campaign;
    }

    /**
     * @throws \Throwable
     */
    public function delete(Campaign $campaign): bool
    {
        return $campaign->deleteOrFail();
    }
}
