<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Transuction;
use App\Models\Subscription;
use App\Models\Balance;
use App\Models\Employer;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DateTime;
use Session;

class MyHelper
{

    public static function saveNotifiaction($title, $body, $sender_id, $receiver_id)
    {
        // $user = User::where('id', $profile_id)->first();

        // if ($title == 'Job Applied') {
        //     $u_id             = Auth::user()->id;
        //     $cocktitle        = Auth::user()->username;
        //     $body             = "Veiw your profile.";
        //     $notification_for = $profile_id;
        //     $users            = User::select('user_key')->where('id', '=', $u_id)->first();
        //     $url              = url('/') . '/profile/' . $users->user_key;
        // }

        Notification::create([
            "sender_id" => $sender_id,
            "receiver_id" => $receiver_id,
            "title" => $title,
            "url" => '',
            "body" => $body,
        ]);
    }

    public static function checkIfUserIsEmployer()
    {
        if (Auth::user() !== null && Auth::user()->user_type == "employer") {
            return true;
        }
        return false;
    }

    public static function checkIfHasActiveSubscription()
    {
        $subscription = Subscription::with('plan')->where([['user_id', '=', Auth::id()], ['status', '=', 'Running']])->first();
        if (isset($subscription) && !empty($subscription)) {
            return true;
        }
        return false;
    }

    public static function getNotification()
    {
        if (Auth::check()) {
            return Notification::where('notification_for', Auth::user()->id)
                ->orderBy('id', 'DESC')
                ->with('user')
                ->limit(20)
                ->get();
        } else {
            return false;
        }
    }

    public static function getUnread()
    {
        if (Auth::check()) {
            return Notification::where('notification_for', Auth::user()->id)
                ->where('is_read', 0)
                ->count();
        } else {
            return false;
        }
    }

    public static function notificationCount()
    {
        if (Auth::check()) {
            return Notification::where('notification_for', Auth::user()->id)
                ->where('is_read', 0)
                ->count();
        } else {
            return false;
        }
    }

    //Get Coupon Code, Generate
    public static function getCoupon()
    {
        if (Auth::check()) {
            $chars = "8017829054SHIBANKARJANACDEFGLMOPQUWXYZ";
            $coupon = "";
            for ($i = 0; $i < 10; $i++) {
                $coupon .= $chars[mt_rand(0, strlen($chars) - 1)];
            }
            return $coupon;
        } else {
            return false;
        }
    }

    // Check Employer Current months job post Limit
    public static function getEmployerCurrentMonthBalance()
    {
        if (Auth::check()) {
            $data = Balance::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')
                ->first();
            if ($data) {
                return $data->current_month_balance;
            } else {
                return 0; // Employer don't have a subscription
            }
        } else {
            return 0;
        }
    }

    // Check Employer Current months job post Limit
    public static function getSelectedEmployerCurrentMonthBalance($employer_id)
    {
        $data = Balance::where('user_id', $employer_id)->orderBy('id', 'DESC')
            ->first();
        if ($data) {
            return $data->current_month_balance;
        } else {
            return 0; // Employer don't have a subscription
        }
    }

    //Update Current month job post balance for CRON Job
    public static function updateEmployerCurrentMonthBalance()
    {
        if (Auth::check()) {
            $data = Subscription::where('user_id', Auth::user()->id)
                ->where('status', 'Running')
                ->with('plan')
                ->first();
            if ($data) {
                $balance = Balance::where('user_id', Auth::user()->id)
                    ->update([
                        'current_month_balance' => $data->plan->number_of_job_post
                    ]);
                return $balance->current_month_balance;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Check One year subscription ability status for coach
    public static function checkCoachSubscription()
    {
        if (Auth::check()) {
            $data = Transuction::where('user_id', Auth::user()->id)
                ->where('created_at', '>', Carbon::now()->subDays(365))
                ->first();
            if ($data) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //Check One month Fre Subsecription or Job Post avibility status
    public static function checkFreeAccess()
    {
        if (Auth::check()) {
            $setting = Setting::where('id', 1)->first();
            if ($setting->new_join_one_month_free == 'Both' || $setting->new_join_one_month_free == 'Coach' || $setting->new_join_one_month_free == 'Employer') {
                $user = User::where('id', Auth::user()->id)
                    ->where('created_at', '>', Carbon::now()->subDays(30))
                    ->first();
                if ($user) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //Check One month Fre Subsecription or Job Post avibility status
    public static function checkEmployerFreeAccess($employer_id)
    {
        $setting = Setting::where('id', 1)->first();
        if ($setting->new_join_one_month_free == 'Both' || $setting->new_join_one_month_free == 'Coach' || $setting->new_join_one_month_free == 'Employer') {
            $user = User::where('id', $employer_id)
                ->where('created_at', '>', Carbon::now()->subDays(30))
                ->first();
            if ($user) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //Check user Subsecription has or not
    public static function checkSubscriptionStatus()
    {
        if (Auth::check()) {
            $data = Subscription::where('user_id', Auth::user()->id)
                ->where('status', 'Running')
                ->first();
            if ($data) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //career coach
    public static function careerCoach()
    {
        $coachSkills = array(
            'Get career advice',
            'Develop your Interview Skills',
            'CV Optimisation',
            'Executive Coaching',
            'Interpersonal Feedback',
            'Management Skills',
        );
        return $coachSkills;
    }


    //benefits or additinal_pay
    public static function benefits()
    {

        if (Auth::check()) {
            return $items = [
                [
                    'id' => 1,
                    'name' => 'Bonus',
                ],
                [
                    'id' => 2,
                    'name' => 'Overtime',
                ],
                [
                    'id' => 3,
                    'name' => 'Commission',
                ],
                [
                    'id' => 4,
                    'name' => 'Medical coverage',
                ],
                [
                    'id' => 5,
                    'name' => 'Dental insurance',
                ],
                [
                    'id' => 6,
                    'name' => 'Vision insurance',
                ],
                [
                    'id' => 7,
                    'name' => 'Life insurance policies',
                ],
                [
                    'id' => 8,
                    'name' => 'Prescription and pharmacy benefits',
                ],
                [
                    'id' => 9,
                    'name' => 'Specialist services',
                ],
                [
                    'id' => 10,
                    'name' => 'Mental health coverage',
                ],
                [
                    'id' => 11,
                    'name' => 'Retirement planning',
                ],
                [
                    'id' => 12,
                    'name' => 'Paid time off',
                ],
                [
                    'id' => 13,
                    'name' => 'Paid vacation time',
                ],
                [
                    'id' => 14,
                    'name' => 'Paid sick leave',
                ],
                [
                    'id' => 15,
                    'name' => 'Extended leave',
                ],
                [
                    'id' => 16,
                    'name' => 'Family leave',
                ],
                [
                    'id' => 17,
                    'name' => 'Disability benefits',
                ],
                [
                    'id' => 18,
                    'name' => "Workers' compensation",
                ],
                [
                    'id' => 19,
                    'name' => 'Living stipends',
                ],
                [
                    'id' => 20,
                    'name' => 'Student loan repayments',
                ],
                [
                    'id' => 21,
                    'name' => 'College grants and scholarships',
                ],
                [
                    'id' => 22,
                    'name' => " Paid training and development",
                ],
                [
                    'id' => 23,
                    'name' => 'Continuing education',
                ],

                [
                    'id' => 24,
                    'name' => 'Travel and spending expenses',
                ],
                [
                    'id' => 25,
                    'name' => 'Company equipment',
                ],
                [
                    'id' => 26,
                    'name' => 'Company transportation',
                ],
                [
                    'id' => 27,
                    'name' => 'Remote work flexibility',
                ],
                [
                    'id' => 28,
                    'name' => 'Investment opportunities',
                ],

            ];

            // json_encode($items,true);


        } else {
            return 0;
        }
    }

    public static function flashMsg($type, $message)
    {
        Session::put($type, $message);
    }

    public static function userRegistrationDate()
    {
        if (Auth::check()) {
            $query = User::where('id', Auth::id())->first();
            $regdate = $query->created_at;
            return date('F jS, Y', strtotime('+1 month', strtotime($regdate)));
        } else {
            return 0;
        }
    }

    public static function nextNewalDate()
    {
        if (Auth::check()) {
            $data = Subscription::where('user_id', Auth::user()->id)
                ->where('status', 'Running')
                ->first();
            if ($data->plan_interval == 'year') {
                if ($data) {
                    return date('F jS, Y', strtotime('+1 year', strtotime($data->created_at)));
                } else {
                    return 0;
                }
            } else {
                if ($data) {
                    return date('F jS, Y', strtotime('+1 month', strtotime($data->created_at)));
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public static function generateTokenFromId($id)
    {
        $employer = Employer::find($id);
        return $employer?->user?->createOrGetStripeCustomer();
    }
}
