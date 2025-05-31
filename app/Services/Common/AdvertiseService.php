<?php

namespace App\Services\Common;

use App\Enum\Jobs\AdvertiseStatusEnum;
use App\Mail\AdvertiseAdminMail;
use App\Mail\AdvertiseFinishRegistrationMail;
use App\Mail\AdvertiseGuestMail;
use App\Mail\JobPostMail;
use App\Mail\VerifyUser;
use App\Mail\Welcome;
use App\Models\Candidate;
use App\Models\Coach;
use App\Models\Employer;
use App\Models\JobAdvertise;
use App\Models\JobPost;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdvertiseService
{
    public function paginate($queries): array
    {
        $count = 0;
        $search = false;

        $advertises = JobAdvertise::whereNotNull('job_type');

        if ($queries !== null) {
            $search = true;
            $advertises->where("company_name", "like", "%" . $queries . "%")
                ->orWhere("company_name", "like", "%" . $queries . "%")
                ->orWhere("first_name", "like", "%" . $queries . "%")
                ->orWhere("last_name", "like", "%" . $queries . "%")
                ->orWhere("email", "like", "%" . $queries . "%")
                ->orWhere("job_type", "like", "%" . $queries . "%")
                ->orWhere("phone", "like", "%" . $queries . "%");
        }

        $count = $advertises->count();
        $companies = $advertises->orderByRaw('id desc')->paginate(8);

        return array(
            'count'              => $count,
            'advertises'         => $companies,
            'search'             => $search
        );
    }

    public function update_status(JobAdvertise $advertise): JobAdvertise
    {
        $advertise->update([
            'status' =>
                $advertise->status == AdvertiseStatusEnum::UNREAD ?
                    AdvertiseStatusEnum::READ : AdvertiseStatusEnum::UNREAD
        ]);
        return $advertise;
    }

    public function send_registration(JobAdvertise $advertise): User
    {
        $checkUser = User::where('email', $advertise->email)->first();


        if ($checkUser && $checkUser->id !== null) {
            $checkUser->update([
                'verify_token' => Str::random(12) . rand(10000, 99999),
            ]);
            Mail::to($checkUser->email)->send(new AdvertiseFinishRegistrationMail($advertise, $checkUser));

            return $checkUser;
        } else {
            $user                 = new User();
            $user->name           = $advertise->first_name.' '.$advertise->last_name;
            $user->first_name     = $advertise->first_name;
            $user->last_name      = $advertise->last_name;
            $user->email          = $advertise->email;
            $user->user_type      = 'employer';
            $user->mobile         = $advertise->phone;
            $user->user_key       = 'JP' . rand(100, 999) . date("his");
            $user->password       = Hash::make('JP' . rand(100, 999) . date("his"));
            $user->remember_token = Str::random(10);
            $user->verify_token   = Str::random(12) . rand(10000, 99999);
            $user->save();
            if ($user) {
                $user->syncRoles([2]);
                $user_profile          = new Employer();
                $user_profile->user_id = $user->id;
                $user_profile->save();

                Mail::to($user->email)->send(new AdvertiseFinishRegistrationMail($advertise, $user));
            }

            return $user;
        }
    }

    public function advertiseJob($data)
    {
        $advertise = JobAdvertise::create($data);

        Mail::to(explode(",", config('app.admin_email')))->send(new AdvertiseAdminMail($advertise));
        Mail::to($advertise->email)->send(new AdvertiseGuestMail($advertise));

        return $advertise;
    }
}
