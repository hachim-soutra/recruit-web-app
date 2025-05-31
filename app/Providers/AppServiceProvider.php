<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use App\Models\Industry;
use App\Models\NewsCategory;
use App\Models\HomePageContent;
use App\Models\HomePageRecruitmentType;
use Illuminate\Support\Facades\URL;
use App\Models\Cashier\User;
use App\Models\Subscription;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        //send data to view
        $settings = Setting::where('status', '1')->first();
        $sector = Industry::where('status', '1')->get();
        //$newscat = NewsCategory::where([['status', '!=', 'D']])->where('navbar_show', '1')->get();
        $newscat = array();
        $homePageContent = HomePageContent::take(1)->first();
        $homePageRecruitmentType = HomePageRecruitmentType::orderBy('updated_at', 'DESC')->take(3)->get();
        view()->share(compact('settings', 'sector', 'newscat', 'homePageContent', 'homePageRecruitmentType'));
        Cashier::useCustomerModel(User::class);
        Cashier::useSubscriptionModel(Subscription::class);
    }
}
