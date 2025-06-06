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
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    \Illuminate\Pagination\Paginator::useBootstrap();
    \Illuminate\Support\Facades\Schema::defaultStringLength(191);

    // Prevent Eloquent queries during composer install or migration
    if (!$this->app->runningInConsole()) {
        $settings = \App\Models\Setting::where('status', '1')->first();
        $sector = \App\Models\Industry::where('status', '1')->get();
        //$newscat = \App\Models\NewsCategory::where([['status', '!=', 'D']])->where('navbar_show', '1')->get();
        $newscat = [];
        $homePageContent = \App\Models\HomePageContent::take(1)->first();
        $homePageRecruitmentType = \App\Models\HomePageRecruitmentType::orderBy('updated_at', 'DESC')->take(3)->get();

        view()->share(compact(
            'settings',
            'sector',
            'newscat',
            'homePageContent',
            'homePageRecruitmentType'
        ));
    }

    \Laravel\Cashier\Cashier::useCustomerModel(\App\Models\Cashier\User::class);
    \Laravel\Cashier\Cashier::useSubscriptionModel(\App\Models\Subscription::class);
}

}
