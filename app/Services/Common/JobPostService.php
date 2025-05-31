<?php

namespace App\Services\Common;

use App\Enum\Jobs\JobStatsTypeEnum;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Industry;
use App\Models\JobPost;
use App\Models\JobStat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobPostService
{
    public function paginate($queries, $id): array
    {
        $meta_title = "";
        $meta_desc = "";
        $country = $_COOKIE['country'] ?? null;
        $jobPosts = JobPost::with(['company'])
            ->withCount('applicatons')
            ->when(auth()?->user()?->user_type === "candidate", fn ($q) => $q->with(['applicatons']))
            ->where([['status', '=', '1'], ['job_status', '=', 'Published']])
            ->whereDate('job_expiry_date', '>', Carbon::now());

        // if country not null and not equal world
        if ($country && $country !== "world") {
            $country = $country == 'uk' ? 'United Kingdom' : $country;
            $jobPosts->where('country', 'LIKE', "%{$country}%");
        }
        if ($queries['keyword']) {
            $keyword = $queries['keyword'];
            $jobPosts->where(function ($query) use ($keyword) {
                $query->where('job_title', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('company', function ($q) use ($keyword) {
                        $q->where('company_name', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }
        if ($queries['roles']) {
            $jobPosts->whereIn('preferred_job_type', $queries['roles']);
        }
        // if ($queries['locations']) {
        //     $jobPosts->whereIn('job_location', $queries['locations']);
        // }

        if ($queries['job_location'] && $queries['job_location'] != '') {
            $location_str = str_ireplace(array(',', '.'), ',', $queries['job_location']);
            $location_arr = array_unique(array_filter(array_map('trim', explode(',', $location_str))));
            $jobPosts->where("job_location", "like", "%" . $location_arr[0] . "%");
        }

        if ($queries['employers']) {
            $jobPosts->whereHas('employer.employer', function ($q) use ($queries) {
                $q->whereIn('company_name', $queries['employers']);
            });
        }
        if ($queries['sectors']) {
            $jobPosts->whereIn('functional_area', $queries['sectors']);
        }

        if ($queries['sort'] !== null) {
            if ($queries['sort'] == 'relevant') {
                $jobPosts->orderBy('applicatons_count', 'desc');
            } elseif ($queries['sort'] == 'latest') {
                $jobPosts->orderByRaw('id desc');
            } elseif ($queries['sort'] == 'soon') {
                $jobPosts->orderByRaw('job_expiry_date desc');
            }
        } else {
            $jobPosts->orderByRaw('id desc');
        }
        $response['allJobPost'] = $jobPosts->get();
        if ($id != null) {
            $firstJobDetail = JobPost::find($id);
        } else {
            $firstJobDetail = $jobPosts->first();
        }
        if ($firstJobDetail) {
            $firstJobDetail = $firstJobDetail->loadMissing(['bookmark', 'applicatons', 'company']);
            $meta_title = "{$firstJobDetail->job_title} job, at {$firstJobDetail->company_name}";
            $meta_desc = "{$firstJobDetail->job_title} is a {$firstJobDetail->preferred_job_type} job by {$firstJobDetail->company_name} in {$firstJobDetail->job_location} for candidates having" . " {$firstJobDetail->experience} of experience";
        }
        $response['firstJobDetail'] = $firstJobDetail;
        $stats['jobs'] = JobPost::count();
        $stats['companies'] = Employer::count();
        $response['banner_stats'] = $stats;
        $response['count'] = $jobPosts->count();
        $response['meta_title'] = trim($meta_title);
        $response['meta_desc'] = trim($meta_desc);
        $response['jobPost'] = $jobPosts->latest()->paginate(12);
        $response['candidateDetail'] = auth()?->user()?->candidate;
        // $response['locations'] = $response['allJobPost']->groupBy('job_location');
        // $response['employers'] = $response['allJobPost']->groupBy('employer.employer.company_name');
        // $response['roles'] = $response['allJobPost']->groupBy('preferred_job_type');
        // $response['sectors'] = $response['allJobPost']->groupBy('functional_area');

        $response['sectors'] = Industry::where('status', '1')->get('name');
        $response['roles'] = config('app.roles');
        $response['employers'] = Employer::whereHas("user", function ($q) {
            $q->where('status', 1);
        })->get('company_name');

        return $response;
    }

    public function jobDetail($slug)
    {

        $splitSlug = explode("-", $slug);

        $id = str_replace('job', '', $splitSlug[count($splitSlug) - 1]);

        $candidate = null;
        $jobPost = null;

        $jobPost = JobPost::with('bookmark', 'applicatons')->find($id);

        if ($jobPost) {
            $meta_title = "{$jobPost->job_title} job, at {$jobPost->company_name}";
            $meta_desc = "{$jobPost->job_title} is a {$jobPost->preferred_job_type} job by {$jobPost->company_name} in {$jobPost->job_location} for candidates having" . " {$jobPost->experience} of experience";
        }

        if (Auth::id() != null) {
            $candidate = Candidate::where('user_id', Auth::id())->first();
        }
        return array(
            'jobPost'               => $jobPost,
            'candidateDetail'       => $candidate,
            'meta_title' => trim($meta_title),
            'meta_desc' => trim($meta_desc),
        );
    }

    public function handleJobClickedStats($slug, $clientIP)
    {
        $splitSlug = explode("-", $slug);
        $id = str_replace('job', '', $splitSlug[count($splitSlug) - 1]);
        JobStat::create([
            'job_id'        => $id,
            'user_id'       => Auth::id() !== null ? Auth::id() : null,
            'guest_ip'      => $clientIP,
            'type'          => JobStatsTypeEnum::VIEW->value
        ]);
    }

    public function handleJobViewedStats($id, $clientIP)
    {
        JobStat::create([
            'job_id'        => $id,
            'user_id'       => Auth::id() !== null ? Auth::id() : null,
            'guest_ip'      => $clientIP,
            'type'          => JobStatsTypeEnum::VIEW->value
        ]);
    }
}
