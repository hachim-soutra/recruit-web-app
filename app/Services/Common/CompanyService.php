<?php

namespace App\Services\Common;

use App\Mail\AdvertiseAdminMail;
use App\Mail\AdvertiseGuestMail;
use App\Mail\JobPostMail;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\JobAdvertise;
use App\Models\JobPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CompanyService
{
    public function paginate($queries): array
    {
        $count = 0;
        $search = false;
        $country = $_COOKIE['country'] ?? null;

        $companies = Employer::with('user')->whereRelation('user', 'status', '1');

        if ($country && $country !== "world") {
            $country = $country == 'uk' ? 'United Kingdom' : $country;
            $companies->where('country', 'LIKE', "%{$country}%");
        }

        if ($queries['company_name'] != null && $queries['first_letter'] != null) {
            $search = true;
            $companies->where("company_name", "like", "%" . $queries['company_name'] . "%")
                ->orWhere('company_name', 'LIKE', $queries['first_letter'] . '%');
        }
        elseif ($queries['company_name'] != null) {
            $search = true;
            $companies->where("company_name", "like", "%" . $queries['company_name'] . "%");
        }
        elseif ($queries['first_letter'] != null) {
            $search = true;
            $companies->where('company_name', 'LIKE', $queries['first_letter'] . '%');
        }

        $count = $companies->count();
        $companies = $companies->orderByRaw('company_name asc')->paginate(8);

        return array(
            'count'             => $count,
            'companies'         => $companies,
            'search'            => $search
        );
    }

    public function companyDetail($id): array
    {
        $meta_title= "Recruit Company";
        $meta_desc= "Recruit Company";
        $employer = Employer::with('user')->find($id);

        if ($employer) {
            $meta_title = "Recruit Company | {$employer->company_name}";
            $description = nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $employer->company_details));
            $meta_desc = "{$description} is {$employer->company_name}, Based in {$employer->address}";
        }

        return array(
            'meta_title'    => $meta_title,
            'meta_desc'     => $meta_desc,
            'company'       => $employer,
            'jobs'          => JobPost::where(['status' => '1', 'job_status' => 'Published', 'employer_id' => $employer->user_id])
                               ->whereDate('job_expiry_date', '>', Carbon::now())->orderBy('id', 'desc')->take(3)->get()
        );
    }

    public function advertiseJob($data)
    {
        $advertise = JobAdvertise::create($data);

        Mail::to(explode(",", config('app.admin_email')))->send(new AdvertiseAdminMail($advertise));
        Mail::to($advertise->email)->send(new AdvertiseGuestMail($advertise));

        return $advertise;
    }
}
