<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployerResource extends JsonResource
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

            "user_id"             => $this->user->id,
            "name"                => $this->user->name,
            "email"               => $this->user->email,
            "mobile"              => $this->user->mobile,
            "user_key"            => $this->user->user_key,
            "fpm_token"           => $this->user->fpm_token,
            "avatar"              => $this->user->avatar,
            "user_type"           => $this->user->user_type,
            "status"              => $this->user->status,
            "is_complete"         => $this->user->is_complete,
            "email_verified"      => $this->user->email_verified,
            "mobile_verified"     => $this->user->mobile_verified,

            'address'             => $this->profile->address,
            'city'                => $this->profile->city,
            'state'               => $this->profile->state,
            'country'             => $this->profile->country,
            'zip'                 => $this->profile->zip,

            'industry_id'         => $this->profile->industry_id,
            'company_ceo'         => $this->profile->company_ceo,
            'number_of_employees' => $this->profile->number_of_employees,
            'established_in'      => $this->profile->established_in,
            'fax'                 => $this->profile->fax,
            'facebook_address'    => $this->profile->facebook_address,
            'twitter'             => $this->profile->twitter,
            'ownership_type'      => $this->profile->ownership_type,
            'phone_number'        => $this->profile->phone_number,
            'company_details'     => $this->profile->company_details,
            'linkedin_link'       => $this->profile->linkedin_link,
            'website_link'        => $this->profile->website_link,

        ];

        //$output["profile"] = ["propic" => $this->profile->propic];
        //$output["roles"] = RoleResource::collection($this->user->roles);

        return $output;

    }
}
