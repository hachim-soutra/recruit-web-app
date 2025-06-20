
<?php

use App\Enum\Payments\SubscriptionStatusEnum;
use App\Helpers\MyHelper;
use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\CareerCoach\ChatsController;
use App\Http\Controllers\Site\CommonController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SubscriptionController;
use App\Jobs\Plan\AssignEmployersToPlanJob;
use App\Mail\AlertMail;
use App\Models\Alert;
use App\Models\Campaign;
use App\Models\Plan;
use App\Models\PlanPackage;
use App\Models\Slot;
use App\Models\Subscription;
use App\Models\SubscriptionSlot;
use App\Services\EmployerService;
use App\Services\Payment\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Stripe\StripeClient;

Route::group(['middleware' => 'revalidate'], function () {
    Route::get('/mail', function (EmployerService $employerService) {
        dd($employerService->getEmployersUnsubscribed()->count());

        $recipient = "hachimsoutra@gmail.com";
        $campaign = Campaign::first();
        Mail::to($recipient)->send(new AlertMail($campaign, $recipient));

        return view("emails.unsubscribe", ["email" => "test"]);
    });
    Route::get('/',  [PageController::class, 'index'])
        ->name('welcome');
    Route::get('/about-us',  [PageController::class, 'aboutUs'])
        ->name('about.us');
    Route::get('/contact-us',  [PageController::class, 'contactUs'])
        ->name('contact.us');
    Route::post('/contact-store',  [PageController::class, 'contact'])
        ->name('contact_us.store');
    Route::get('/blogs/{type}', [PageController::class, 'blogs'])
        ->name('blogs');
    Route::get('blog-details/{type}/{slug}',  [PageController::class, 'blogDetails'])
        ->name('blog-details');
    Route::get('career-coach/{skill?}',  [PageController::class, 'careerCoach'])
        ->name('career-coach');
    Route::get('testimonial',  [PageController::class, 'testimonial'])
        ->name('testimonial');
    Route::get('partner-us',  [PageController::class, 'partnerUs'])
        ->name('partner.us');
    Route::get('alerts',  [PageController::class, 'alert'])
        ->name('alert');
    Route::post('alerts',  [PageController::class, 'alertStore'])
        ->name('alert.store');

    ## 6-23
    Route::get('permanent-recruitment', 'PageController@permanentRecruitment')->name('permanent-recruitment');
    Route::get('virtual-recruitment-event', 'PageController@virtualRecruitment')->name('virtual-recruitment-event');
    Route::get('tech-careers-expo', 'PageController@techCareersExpo')->name('tech-careers-expo');
    Route::get('jobs-expo', 'PageController@jobsExpo')->name('jobs-expo');
    Route::get('career-advice/{slug?}', 'PageController@careerAdvice')->name('career-advice');

    Route::get('/term-of-use', 'PageController@termOfUse')
        ->name('term_of_use');
    Route::get('/unsubscribe/{email}', 'PageController@unsubscribe')
        ->name('unsubscribe');
    Route::post('/unsubscribe/{email}', 'PageController@unsubscribeStore')
        ->name('unsubscribe.store');
    Route::get('/privacy-policy', 'PageController@privacy')
        ->name('privacy');
    Route::get('/{id}/get-invoice', 'PageController@getInvoice')
        ->name('invoice');

    //Verify User Email by token (api)
    Route::get('/user/verify/{token}', 'VerificationController@verifyUser');
    // password (f password for API)
    Route::get('password/reset/{token}', 'PasswordController@showResetForm')
        ->name('password.reset');
    Route::post('reset-password', 'PasswordController@passwordReset')
        ->name('password-reset');
    Route::get('success', 'PasswordController@passwordSuccess')
        ->name('success');

    ### 5-26-2022  suman   #####
    Route::get('register', 'AuthController@register')->name('register');
    Route::post('registration', 'AuthController@registration')->name('registration');
    Route::get('signin/{jobappliid?}', 'AuthController@signin')->name('signin');
    Route::post('logincheck', 'AuthController@loginCheck')->name('logincheck');
    Route::get('forgot-password', 'AuthController@forgotPassword')->name('forgot-password');
    Route::post('forgot-password-mail-send', 'AuthController@forgotPasswordMailSend')->name('forgot-password-mail-send');
    Route::post('change-password-save', 'AuthController@changePasswordSave')->name('change-password-save');
    Route::get('change-password/{mail?}', 'AuthController@changePass')->name('change-password');
    Route::get('complete-registration/{token}', [AuthController::class, 'complete_registration'])->name('complete-registration');
    Route::post('registration-complete', [AuthController::class, 'registration_complete'])->name('registration-complete');
    #social login
    Route::get('social-login/{usertype?}/{provider?}', [AuthController::class, 'socialLoginRedirectToProvider'])->name('auth.social.login');
    Route::get('auth/{provider?}/callback', [AuthController::class, 'socialLoginHandleProviderCallback'])->name('auth.social.callback');
    #social login end
    Route::get('logout', 'AuthController@logout')->name('logout');
    #upload resume
    Route::get('upload-resume', 'DashboardController@uploadResume')->name('upload-resume');
    #employer #job post
    Route::get('employer-post-job', 'EmployerController@employerPostJob')->name('employer.post.job');
    #job search
    Route::match(['get', 'post'], 'seeker-findjob', 'SeekerController@seekerFindJob')->name('seeker-findjob');

    #job listing without login
    Route::get('job-listing/{id?}', [CommonController::class, 'globalJobListing'])->name('common.job-listing');

    Route::get('job-detail/{id}', [CommonController::class, 'globalJobDetail'])->name('common.job-detail');

    Route::get('company-search', [CommonController::class, 'companySearch'])->name('common.company-search');

    Route::get('company-detail/{id}', [CommonController::class, 'companyDetail'])->name('common.company-detail');

    Route::get('advertise-your-job', [CommonController::class, 'advertiseJob'])->name('common.advertise-job');

    Route::post('post-advertise-job', [CommonController::class, 'advertiseJobPost'])->name('common.advertise-job-post');

    Route::get('tag/{slug?}', 'CommonController@tagRedirect')->name('tag');        /* AND */

    //    Route::get('job-listing/{jobid?}/{industryname?}', 'CommonController@jobListing')->name('job-listing');

    Route::get('job-taxonomy-list', 'JobTaxonomyController@jobTaxonomyList')->name('job-taxonomy-list');

    Route::get('job-location/{term?}', 'JobTaxonomyController@jobLocationListing')->name('job-location');                    /* AND */
    Route::get('job-location/{term}/job/{jobid?}', 'JobTaxonomyController@jobLocationListing')->name('job-location-jobid');    /* AND */

    Route::get('job-category/{term?}', 'JobTaxonomyController@jobCategoryListing')->name('job-category');                    /* AND */
    Route::get('job-category/{term}/job/{jobid?}', 'JobTaxonomyController@jobCategoryListing')->name('job-category-jobid');    /* AND */

    Route::get('job-type/{term?}', 'JobTaxonomyController@jobTypeListing')->name('job-type');                    /* AND */
    Route::get('job-type/{term}/job/{jobid?}', 'JobTaxonomyController@jobTypeListing')->name('job-type-jobid');    /* AND */

    Route::post('job-listing-apply', 'CommonController@jobListingApply')->name('job-listing-apply');
    Route::get('bookmarked-job/{jobid?}', 'SeekerController@jobBookMarked')->name('bookmarked-job');

    Route::post('job-statistics-hits', 'JobStatisticsController@update_Hits')->name('job-statistics-hits');    /* AND : POST */

    Route::group(['middleware' => 'auth'], function () {
        Route::get('dashboard/{postid?}/{from?}', 'DashboardController@dashboard')->name('dashboard');
        Route::get('profile', 'DashboardController@profile')->name('profile');
        #profile update
        #coach | exployeer
        Route::post('profile-update', 'DashboardController@profileUpdate')->name('profile-update');
        #exployer
        Route::get('applied-job-detail/{appliedjobid?}', 'DashboardController@appliedJobDetail')->name('applied-job-detail');
        #candidate
        Route::get('education-qualification', 'DashboardController@educationQualification')->name('education-qualification');
        Route::post('profile-update-candidate', 'DashboardController@profileUpdateCandidate')->name('profile-update-candidate');
        Route::post('profile-common-ajax', 'DashboardController@commonajax')->name('profile-common-ajax');
        Route::post('profile-file-upload', 'DashboardController@fileUpload')->name('profile-file-upload');
        Route::post('profile-coverletter-update', 'DashboardController@coverletterUpdate')->name('profile-coverletter-update');
        Route::post('profile-work-experience', 'DashboardController@workExperience')->name('profile-work-experience');
        Route::post('work-experience-edit', 'DashboardController@workExperienceEdit')->name('work-experience-edit');
        Route::post('work-experience-delete', 'DashboardController@workExperienceDelete')->name('work-experience-delete');
        #Route::get('bookmarked-job/{jobid?}', 'SeekerController@jobBookMarked')->name('bookmarked-job');    8-18
        #
        Route::get('get-state-from-country/{countryid?}', 'SeekerController@getStateCountry')->name('get-state-from-country');
        Route::get('job-applicant/{jobid?}', 'EmployerController@jobApplicants')->name('job-applicant');
        #employer
        Route::middleware(['is-user-employer'])->group(function () {
            #job post
            #view
            Route::get('post-job', 'EmployerController@postJob')->name('post-job')->middleware(['subscribed']);
            Route::get('draft-job/{draftid?}', 'EmployerController@draftJob')->name('draft-job');
            Route::post('change-job-expire-date', 'EmployerController@changeJobExpireDate')->name('change-job-expire-date');
            Route::get('edit-job/{jobid?}', 'EmployerController@editJob')->name('edit-job');
            Route::post('change-job-status', 'EmployerController@changeJobStatus')->name('change-job-status');
            #create
            Route::post('job-post-save', 'EmployerController@jobCreate')->name('job-post-save')->middleware(['subscribed']);
            Route::post('job-post-update/{id}', 'EmployerController@jobUpdate')->name('employer.job_post.update');
            Route::post('jobpost-common-ajax', 'CommonController@jobpostAjax')->name('jobpost-common-ajax');
        });

        Route::middleware(['is-user-job-seeker'])->group(function () {
            #candidat
            #find job
            Route::get('find-job', 'SeekerController@findJob')->name('find-job');
            #find coach
            Route::get('find-career-coach', 'SeekerController@careerCoachFind')->name('find-career-coach');
            #applied
            #seach candidate
            Route::get('search-candidate', 'SeekerController@searchCandidate')->name('search-candidate');
            Route::post('update-resume-coverletter', 'SeekerController@updateResumeCoverletter')->name('update-resume-coverletter');
            Route::post('apply-job', 'SeekerController@applyJob')->name('apply-job');
            Route::post('save-job-carrier', 'SeekerController@saveJobCarrier')->name('save-job-carrier');
            #favourite jobs
            Route::get('favourite-job', 'SeekerController@favouriteJob')->name('favourite-job');  ## bookmarked job list ##
            #event
            Route::get('events', 'SeekerController@events')->name('events');
            Route::get('event-detail/{eventid?}', 'SeekerController@eventDetail')->name('event-detail');
            Route::prefix('job-seeker')->group(function () {
                Route::get('chats', [ChatsController::class, 'index'])->name("job-seeker.chat");
                Route::get('chats/{id}', [ChatsController::class, 'startChat'])->name("job-seeker.start-chat");
            });
        });
        #common
        #candidate | exployeer
        #file upload
        Route::post('upload-file', 'CommonController@uploadFile')->name('upload-file');
        #transaction
        Route::get('transaction', 'CommonController@transaction')->name('transaction');
        Route::get('invoice/{id}', 'CommonController@transaction_invoice')->name('invoice');
        Route::get('invoice-charge/{id}', 'CommonController@transaction_invoice_charge')->name('invoice-charge');
        #notification
        Route::get('notification', 'CommonController@notification')->name('notification');
        #settings
        Route::get('setting', 'CommonController@setting')->name('setting');
        Route::post('reset-password', 'CommonController@resetPassword')->name('reset-password');
        Route::get('delete-account', 'CommonController@deleteAccount')->name('delete-account');
        #contact support
        Route::get('contact-support', 'CommonController@contactSupport')->name('contact-support');
        Route::post('contact-query', 'CommonController@contactQuery')->name('contact-query');
        #about us
        Route::get('profile-about-us', 'CommonController@profileAboutUs')->name('profile-about-us');

        route::middleware(['is-user-employer'])->prefix('subscription')->group(function () {
            Route::get('', [SubscriptionController::class, 'index'])->name("subscription");
            Route::get('checkout/{plan_packages:stripe_plan}', [SubscriptionController::class, 'chooseSubscription'])->middleware(['not-subscribed'])->name("choose-subscription");
        });
        route::middleware(['is-user-employer'])->prefix('slot')->group(function () {
            Route::get('checkout/{id}', [SubscriptionController::class, 'chooseSlot'])->name("choose-slot");
        });
        Route::middleware(['is-user-career-coach'])->prefix('career-coachs')->group(function () {
            Route::get('chats', [ChatsController::class, 'index'])->name("career-coach.chat");
        });
        Route::post('chats', [ChatsController::class, 'get_client_message'])->name("chat.index");
        Route::get('rooms', [ChatsController::class, 'indexApi'])->name("chat.api");
        Route::get('rooms', [ChatsController::class, 'indexApi'])->name("chat.api");
        Route::post('chat', [ChatsController::class, 'store'])->name("chat.store");
        Route::post('chat-seen', [ChatsController::class, 'seen'])->name("career-coach.chat.seen");
    });

    Route::get('/{type}/accept-consent', 'CommonController@acceptConsent')
        ->name('accept-consent');
    Route::get('/{type}/reject-consent', 'CommonController@rejectConsent')
        ->name('reject-consent');

    Route::get('/stripe-sub', 'CommonController@stripeSub')
        ->name('stripe_sub_test');

    Route::get('/stripe-payement', 'CommonController@stripePayement')
        ->name('stripe_payement_test');

    Route::get('/admin-stripe-payement', 'CommonController@adminStripePayement')
        ->name('admin_stripe_payement');
    Route::get('/cancel-stripe-payement', 'CommonController@cancelStripePayement')
        ->name('cancel-payement');

    Route::get('coach-detail/{coachid?}', 'SeekerController@coachDetail')->name('coach-detail');

    Route::get('/get-country', function () {

        $employer = \App\Models\Employer::find(1);

        $location = \App\Services\Common\LocationService::getCountryFromAddress($employer->address);

        if ($employer->city == null) {
            $employer->city = $location['city'];
        }
        if ($employer->state == null) {
            $employer->state = $location['state'];
        }
        if ($employer->country == null) {
            $employer->country = $location['country'];
        }

        $employer->save();

        return true;
    });
    Route::get('/fetch-industries', 'CommonController@fetchIndustries')
        ->name('industries.index');
    Route::get('/fetch-candidates', 'CommonController@fetchCandidates')
        ->name('candidates.index');
    Route::get('/fetch-jobs', 'CommonController@fetchJobs')
        ->name('jobs.index');
});
