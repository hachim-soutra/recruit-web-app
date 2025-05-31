<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CandidateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $output["status"] = true;

        $output["message"] = "You Have Successfully Logged in";

        $output["_token"] = $this->_token;

        $output["user"] = [

            "user_id"                 => $this->user->id,
            "name"                    => $this->user->name,
            "email"                   => $this->user->email,
            "mobile"                  => $this->user->mobile,
            "user_key"                => $this->user->user_key,
            "fpm_token"               => $this->user->fpm_token,
            "avatar"                  => $this->user->avatar,
            "user_type"               => $this->user->user_type,
            "status"                  => $this->user->status,
            "is_complete"             => $this->user->is_complete,
            "email_verified"          => $this->user->email_verified,
            "mobile_verified"         => $this->user->mobile_verified,

            'address'                 => $this->profile->address,
            'city'                    => $this->profile->city,
            'state'                   => $this->profile->state,
            'country'                 => $this->profile->country,
            'zip'                     => $this->profile->zip,
            'total_experience_year'   => $this->profile->total_experience_year,
            'total_experience_month'  => $this->profile->total_experience_month,
            'alternate_mobile_number' => $this->profile->alternate_mobile_number,
            'highest_qualification'   => $this->profile->highest_qualification,
            'specialization'          => $this->profile->specialization,
            'university_or_institute' => $this->profile->university_or_institute,
            'year_of_graduation'      => $this->profile->year_of_graduation,
            'education_type'          => $this->profile->education_type,
            'date_of_birth'           => $this->profile->date_of_birth,
            'candidate_type'          => $this->profile->candidate_type,
            'preferred_job_type'      => $this->profile->preferred_job_type,
            'gender'                  => $this->profile->gender,
            'marital_status'          => $this->profile->marital_status,
            'resume'                  => $this->profile->resume,
            'nationality'             => $this->profile->nationality,
            'current_salary'          => $this->profile->current_salary,
            'expected_salary'         => $this->profile->expected_salary,
            'salary_currency'         => $this->profile->salary_currency,
            'resume_title'            => $this->profile->resume_title,
            'linkedin_link'           => $this->profile->linkedin_link,
            'bio'                     => $this->profile->bio,
            'languages'               => $this->profile->languages,

        ];

        //$output["profile"] = ["propic" => $this->profile->propic];
        //$output["roles"] = RoleResource::collection($this->user->roles);

        return $output;

    }
}
