<?php

use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\AdvertiseController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ResourceFileController;
use App\Http\Controllers\Admin\SlotController;
use App\Http\Controllers\Admin\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('login', 'AuthController@index')
    ->name('login');
Route::post('login', 'AuthController@login')
    ->name('do.login');

//Verify User Email by token
// Route::get('/user/verify/{token}', 'VerificationController@verifyUser');

// Route::get('password/reset/{token}', 'PasswordController@showResetForm')
//     ->name('password.reset');
// Route::post('reset-password', 'PasswordController@passwordReset')
//     ->name('password-reset');
// Route::get('success', 'PasswordController@passwordSuccess')
//     ->name('success');

Route::middleware(['role:admin', 'auth'])->group(function () {
    Route::get('dashboard', 'DashboardController@index')
        ->name('dashboard');
    Route::post('logout', 'AuthController@logout')
        ->name('logout');

    Route::get('/profile-details', 'AuthController@profileDetails')
        ->name('profile.details');
    Route::patch('/update-setting', 'AuthController@settingsUpdate')->name('settings.update');
    Route::post('change/password', 'AuthController@changePassword')->name('change.password');

    Route::prefix('user')->name('user.')->middleware(['permission:user_*'])->group(function () {
        Route::get('/list/{type?}', 'UserController@index')
            ->middleware(['permission:user_read'])
            ->name('list');
        Route::get('/create', 'UserController@create')
            ->middleware(['permission:user_create'])
            ->name('create');
        Route::post('/store', 'UserController@store')
            ->middleware(['permission:user_create'])
            ->name('store');
        Route::get('/show/{id}', 'UserController@show')
            ->middleware(['permission:user_read'])
            ->name('show');
        Route::get('/{id}/edit', 'UserController@edit')
            ->middleware(['permission:user_update'])
            ->name('edit');
        Route::patch('/{id}/update', 'UserController@update')
            ->middleware(['permission:user_update'])
            ->name('update');
        Route::put('/archive/{id}', 'UserController@archive')
            ->middleware(['permission:user_delete'])
            ->name('archive');
        Route::put('/restore/{id}', 'UserController@restore')
            ->middleware(['permission:user_delete'])
            ->name('restore');
    });

    Route::prefix('role')->name('role.')->middleware(['permission:role_*'])->group(function () {
        Route::get('/list', 'RoleController@index')
            ->middleware(['permission:role_read'])
            ->name('list');
        Route::get('/create', 'RoleController@create')
            ->middleware(['permission:role_create'])
            ->name('create');
        Route::post('/store', 'RoleController@store')
            ->middleware(['permission:role_create'])
            ->name('store');
        Route::get('/{id}/edit', 'RoleController@edit')
            ->middleware(['permission:role_update'])
            ->name('edit');
        Route::patch('/{id}/update', 'RoleController@update')
            ->middleware(['permission:role_update'])
            ->name('update');
        Route::delete('/destroy/{id}', 'RoleController@destroy')
            ->middleware(['permission:role_delete'])
            ->name('destroy');
    });

    Route::prefix('permission')->name('permission.')->middleware(['permission:role_*'])->group(function () {
        Route::get('/list', 'PermisssionController@index')
            ->middleware(['permission:permission_read'])
            ->name('list');
        Route::get('/create', 'PermisssionController@create')
            ->middleware(['permission:permission_create'])
            ->name('create');
        Route::post('/store', 'PermisssionController@store')
            ->middleware(['permission:permission_create'])
            ->name('store');
        Route::get('/{id}/edit', 'PermisssionController@edit')
            ->middleware(['permission:permission_update'])
            ->name('edit');
        Route::patch('/{id}/update', 'PermisssionController@update')
            ->middleware(['permission:permission_update'])
            ->name('update');
        Route::delete('/destroy/{id}', 'PermisssionController@destroy')
            ->middleware(['permission:permission_delete'])
            ->name('destroy');
    });

    Route::prefix('coach')->name('coach.')->group(function () {
        Route::get('/list/{type?}', 'CoachController@index')
            ->name('list');
        Route::get('/create', 'CoachController@create')
            ->name('create');
        Route::post('/store', 'CoachController@store')
            ->name('store');
        Route::get('/show/{id}', 'CoachController@show')
            ->name('show');
        Route::get('/{id}/edit', 'CoachController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'CoachController@update')
            ->name('update');
        Route::put('/archive/{id}', 'CoachController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'CoachController@restore')
            ->name('restore');
        Route::put('/email-archive/{id}', 'CoachController@emailArchive')
            ->name('email_archive');
        Route::put('/email-restore/{id}', 'CoachController@emailRestore')
            ->name('email_restore');
        Route::put('/mobile-archive/{id}', 'CoachController@mobileArchive')
            ->name('mobile_archive');
        Route::put('/mobile-restore/{id}', 'CoachController@mobileRestore')
            ->name('mobile_restore');
    });

    /* AND */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/recent1', 'StatisticsController@Recent1')
            ->name('recent1');
        Route::get('/report1/{type?}', 'StatisticsController@Report1')
            ->name('report1');
        Route::get('/search-keywords-report-1', 'StatisticsController@SearchKeywords1')
            ->name('search_keywords_report_1');
    });
    /* AND */

    Route::prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/list/{type?}', 'CandidateController@index')
            ->name('list');
        // Route::get('/create', 'CandidateController@create')
        //     ->middleware(['permission:user_create'])
        //     ->name('create');
        // Route::post('/store', 'CandidateController@store')
        //     ->middleware(['permission:user_create'])
        //     ->name('store');
        Route::get('/show/{id}', 'CandidateController@show')
            ->name('show');
        Route::get('/{id}/edit', 'CandidateController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'CandidateController@update')
            ->name('update');
        Route::put('/archive/{id}', 'CandidateController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'CandidateController@restore')
            ->name('restore');
        Route::put('/email-archive/{id}', 'CandidateController@emailArchive')
            ->name('email_archive');
        Route::put('/email-restore/{id}', 'CandidateController@emailRestore')
            ->name('email_restore');
        Route::put('/mobile-archive/{id}', 'CandidateController@mobileArchive')
            ->name('mobile_archive');
        Route::put('/mobile-restore/{id}', 'CandidateController@mobileRestore')
            ->name('mobile_restore');
    });

    Route::prefix('employer')->name('employer.')->group(function () {
        Route::get('/list/{type?}', 'EmployerController@index')
            ->name('list');
        Route::get('/show/{id}', 'EmployerController@show')
            ->name('show');
        Route::get('/create', 'EmployerController@create')
            ->name('create');
        Route::post('/store', 'EmployerController@store')
            ->name('store');
        Route::get('/{id}/edit', 'EmployerController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'EmployerController@update')
            ->name('update');
        Route::put('/archive/{id}', 'EmployerController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'EmployerController@restore')
            ->name('restore');
        Route::put('/email-archive/{id}', 'EmployerController@emailArchive')
            ->name('email_archive');
        Route::put('/email-restore/{id}', 'EmployerController@emailRestore')
            ->name('email_restore');
        Route::put('/mobile-archive/{id}', 'EmployerController@mobileArchive')
            ->name('mobile_archive');
        Route::put('/mobile-restore/{id}', 'EmployerController@mobileRestore')
            ->name('mobile_restore');
        Route::get('/{id}/jobs', 'EmployerController@employersJob')
            ->name('jobs');

        Route::get('/{id}/job-applicants', 'EmployerController@employersJobApplicant')
            ->name('job.applicant');
        Route::post('/{id}/free-subscription', 'EmployerController@freeSUbscription')
            ->name('job.free_subscription');
    });

    Route::prefix('coupon')->name('coupon.')->group(function () {
        Route::get('/list', 'CouponController@index')
            ->name('list');
        Route::get('/create', 'CouponController@create')
            ->name('create');
        Route::post('/store', 'CouponController@store')
            ->name('store');
        Route::get('/{id}/edit', 'CouponController@edit')
            ->name('edit');
        Route::get('/{id}/show', 'CouponController@show')
            ->name('show');
        Route::patch('/{id}/update', 'CouponController@update')
            ->name('update');
        Route::delete('/destroy/{id}', 'CouponController@destroy')
            ->name('destroy');
        Route::get('/{id}/share', 'CouponController@share')
            ->name('share');
        Route::post('/{id}/share-coupon', 'CouponController@shareCoupon')
            ->name('share_to_user');
        Route::get('/{id}/shared-users', 'CouponController@shareList')
            ->name('shared_users');
    });

    Route::prefix('job-post')->name('job.')->group(function () {
        Route::get('/list', 'JobPostController@index')
            ->name('list');
        Route::get('/welcome-list', 'JobPostController@indexWelcome')
            ->name('welcome-list');
        Route::get('/listonly/{type?}', 'JobPostController@listonly')
            ->name('listonly');
        Route::get('/create', 'JobPostController@create')
            ->name('create');
        Route::post('/store', 'JobPostController@store')
            ->name('store');
        Route::get('/show/{id}', 'JobPostController@show')
            ->name('show');
        Route::get('/applicants/{id}', 'JobPostController@applicants')
            ->name('applicants');
        Route::get('/{id}/edit', 'JobPostController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'JobPostController@update')
            ->name('update');
        Route::delete('/destroy/{id}', 'JobPostController@destroy')
            ->name('destroy');
        Route::get('/import-job-csv', 'JobPostController@import')
            ->name('import');
        Route::post('/import-job-csv-store', 'JobPostController@importJob')
            ->name('import.store');
        Route::put('/change-status', 'JobPostController@changeJobStatus')
            ->name('change_status');
        Route::get('/expire-job', 'JobPostController@expirejobs')
            ->name('expire');
        Route::post('/expire-job-update', 'JobPostController@expireJobUpdate')
            ->name('expire-job-update');
        Route::post('/{id}/show-home-job-update', 'JobPostController@showHomeJobUpdate')
            ->name('show-home-job-update');
    });

    Route::prefix('qualification')->name('qualification.')->group(function () {
        Route::get('/list', 'QualificationController@index')
            ->name('list');
        Route::get('/create', 'QualificationController@create')
            ->name('create');
        Route::post('/store', 'QualificationController@store')
            ->name('store');
        Route::get('/{id}/edit', 'QualificationController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'QualificationController@update')
            ->name('update');
        Route::delete('/destroy/{id}', 'QualificationController@destroy')
            ->name('destroy');
    });

    Route::prefix('industry')->name('industry.')->group(function () {
        Route::get('/list/{type?}', 'IndustryController@index')
            ->name('list');
        Route::get('/create', 'IndustryController@create')
            ->name('create');
        Route::post('/store', 'IndustryController@store')
            ->name('store');
        Route::get('/{id}/edit', 'IndustryController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'IndustryController@update')
            ->name('update');
        Route::put('/archive/{id}', 'IndustryController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'IndustryController@restore')
            ->name('restore');
        Route::delete('/destroy/{id}', 'IndustryController@destroy')
            ->name('destroy');
    });

    Route::prefix('language')->name('language.')->group(function () {
        Route::get('/list/{type?}', 'LanguageController@index')
            ->name('list');
        Route::get('/create', 'LanguageController@create')
            ->name('create');
        Route::post('/store', 'LanguageController@store')
            ->name('store');
        Route::get('/{id}/edit', 'LanguageController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'LanguageController@update')
            ->name('update');
        Route::put('/archive/{id}', 'LanguageController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'LanguageController@restore')
            ->name('restore');
        Route::delete('/destroy/{id}', 'LanguageController@destroy')
            ->name('destroy');
    });

    Route::prefix('skill')->name('skill.')->group(function () {
        Route::get('/list/{type?}', 'SkillController@index')
            ->name('list');
        Route::get('/create', 'SkillController@create')
            ->name('create');
        Route::post('/store', 'SkillController@store')
            ->name('store');
        Route::get('/{id}/edit', 'SkillController@edit')
            ->name('edit');
        Route::patch('/{id}/update', 'SkillController@update')
            ->name('update');
        Route::put('/archive/{id}', 'SkillController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'SkillController@restore')
            ->name('restore');
        Route::delete('/destroy/{id}', 'SkillController@destroy')
            ->name('destroy');
    });

    Route::prefix('event')->name('event.')->group(function () {
        Route::get('/list/{type?}', 'EventController@index')
            ->name('list');
        Route::get('/create', 'EventController@create')
            ->name('create');
        Route::post('/store', 'EventController@store')
            ->name('store');
        Route::get('/{id}/edit', 'EventController@edit')
            ->name('edit');
        Route::get('/show/{id}', 'EventController@show')
            ->name('show');
        Route::patch('/{id}/update', 'EventController@update')
            ->name('update');
        Route::put('/archive/{id}', 'EventController@archive')
            ->name('archive');
        Route::put('/restore/{id}', 'EventController@restore')
            ->name('restore');
        Route::delete('/destroy/{id}', 'EventController@destroy')
            ->name('destroy');
    });

    #testimonial  5/23   suman
    Route::prefix('testimonial')->name('testimonial.')->group(function () {
        Route::get('/list', 'TestimonialController@index')->name('list');
        Route::get('/create', 'TestimonialController@create')->name('create');
        Route::get('/change-status/{status?}/{id}', 'TestimonialController@changeStatus')->name('change-status');
        Route::post('/store', 'TestimonialController@store')->name('store');
        Route::get('/trash/{id}', 'TestimonialController@trash')->name('trash');
        Route::get('/edit/{id}', 'TestimonialController@edit')->name('edit');
        Route::get('/view/{id}', 'TestimonialController@view')->name('view');
    });
    #testimonial end
    #news 5/24 suman
    Route::prefix('news')->name('news.')->group(function () {
        Route::get('/list', 'NewsController@index')->name('list');
        Route::get('/create', 'NewsController@create')->name('create');
        Route::post('/store', 'NewsController@store')->name('store');
        Route::get('/change-status/{status?}/{id}', 'NewsController@changeStatus')->name('change-status');
        Route::get('/trash/{id}', 'NewsController@trash')->name('trash');
        Route::get('/edit/{id}', 'NewsController@edit')->name('edit');
        Route::get('/view/{id}', 'NewsController@view')->name('view');
        #category
        Route::get('/category', 'NewsController@category')->name('category');
        Route::post('/add-category', 'NewsController@addCategory')->name('add-category');
        Route::get('/category-edit/{slug?}', 'NewsController@categoryEdit')->name('category-edit');
        Route::get('/category-trash/{slug?}', 'NewsController@categoryTrash')->name('category-trash');
        Route::get('/category-change-status/{slug?}/{status?}', 'NewsController@categoryChangeStatus')->name('category-change-status');
        Route::post('/category-navbar-add', 'NewsController@categoryNavbarAdd')->name('category-navbar-add');
    });
    #news end
    #advice
    Route::prefix('advice')->name('advice.')->group(function () {
        Route::get('/list', 'AdviceController@index')->name('list');
        Route::get('/create', 'AdviceController@create')->name('create');
        Route::post('/store', 'AdviceController@store')->name('store');
        Route::get('/change-status/{status?}/{id}', 'AdviceController@changeStatus')->name('change-status');
        Route::get('/trash/{id}', 'AdviceController@trash')->name('trash');
        Route::get('/edit/{id}', 'AdviceController@edit')->name('edit');
        Route::get('/view/{id}', 'AdviceController@view')->name('view');
    });
    #advice end
    #client 5/24 suman
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/list', 'ClientController@index')->name('list');
        Route::get('/create', 'ClientController@create')->name('create');
        Route::post('/store', 'ClientController@store')->name('store');
        Route::get('/change-status/{status?}/{id}', 'ClientController@changeStatus')->name('change-status');
        Route::get('/trash/{id}', 'ClientController@trash')->name('trash');
        Route::get('/edit/{id}', 'ClientController@edit')->name('edit');
    });
    #client end
    #team 5/24 suman
    Route::prefix('team')->name('team.')->group(function () {
        Route::get('/list', 'TeamController@index')->name('list');
        Route::get('/create', 'TeamController@create')->name('create');
        Route::post('/store', 'TeamController@store')->name('store');
        Route::get('/change-status/{status?}/{id}', 'TeamController@changeStatus')->name('change-status');
        Route::get('/trash/{id}', 'TeamController@trash')->name('trash');
        Route::get('/edit/{id}', 'TeamController@edit')->name('edit');
    });
    #team end
    #cms 6/27 suman
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/about-us/{id?}', 'CmsController@aboutUs')->name('about-us');
        Route::post('/about-us-save', 'CmsController@aboutUsSave')->name('about-us-save');
        Route::get('/contact-us/{id?}', 'CmsController@contactUs')->name('contact-us');
        Route::post('/contact-us-save', 'CmsController@contactUsSave')->name('contact-us-save');
        Route::get('/meta-info', 'CmsController@metaInfo')->name('meta-info');
        Route::post('/meta-info-save', 'CmsController@metaInfoSave')->name('meta-info-save');
        Route::get('/meta-info-delete/{rowid?}', 'CmsController@metaInfoDelete')->name('meta-info-delete');
        Route::post('/meta-info-get/{rowid?}', 'CmsController@metaInfoGet')->name('meta-info-get');
        #7-7
        Route::get('/home-content', 'CmsController@homeContent')->name('home-content');
        #7-8
        Route::post('/home-banner-save', 'CmsController@homeBannerSave')->name('home-banner-save');
        Route::post('/home-recruitment-content', 'CmsController@homeRecruitmentContent')->name('home-recruitment-content');
        Route::post('/home-counting', 'CmsController@homeCounting')->name('home-counting');
        Route::post('/home-recruitment-type', 'CmsController@homeRecruitmentType')->name('home-recruitment-type');
        Route::get('/home-recruitment-type-delete/{typeid?}', 'CmsController@homeRecruitmentTypeDelete')->name('home-recruitment-type-delete');
        Route::post('/home-career-text', 'CmsController@homeCareerText')->name('home-career-text');
        #looking staff
        Route::get('/permanent-recruitment', 'CmsController@permanentRecruitment')->name('permanent-recruitment');
        Route::post('/permanent-recruitment-save', 'CmsController@permanentRecruitmentSave')->name('permanent-recruitment-save');
        Route::get('/virtual-recruitment', 'CmsController@virtualRecruitment')->name('virtual-recruitment');
        Route::post('/virtual-recruitment-save', 'CmsController@virtualRecruitmentSave')->name('virtual-recruitment-save');
        Route::get('/tech-careers', 'CmsController@techCareers')->name('tech-careers');
        Route::post('/tech-careers-save', 'CmsController@techCareersSave')->name('tech-careers-save');
        Route::get('/jobs-expo', 'CmsController@jobsExpo')->name('jobs-expo');
        Route::post('/jobs-expo-save', 'CmsController@jobsExpoSave')->name('jobs-expo-save');
        Route::post('/staff-update', 'CmsController@staffUpdate')->name('staff-update');
    });
    #cms end
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/show', 'SettingController@show')
            ->name('show');
        Route::get('edit', 'SettingController@edit')
            ->name('edit');
        Route::patch('update', 'SettingController@update')
            ->name('update');
    });
    #contact us
    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('/info', 'ContactController@info')
            ->name('info');
        Route::get('/delete/{id?}', 'ContactController@trash')->name('delete');
        Route::post('/reply', 'ContactController@reply')->name('reply');
        Route::get('/full-view/{id?}', 'ContactController@fullview')->name('full.view');
    });

    #payment plan
    Route::prefix('plan')->name('plan.')->group(function () {
        Route::get('/list', [PlanController::class, 'index'])
            ->middleware(['permission:role_read'])
            ->name('list');
        Route::get('/create', [PlanController::class, 'create'])
            ->name('create');
        Route::post('/store', [PlanController::class, 'store'])
            ->name('store');
        Route::get('/{id}/edit', [PlanController::class, 'edit'])
            ->name('edit');
        Route::get('/{id}/assign-candidate', [PlanController::class, 'assign'])
            ->name('assign');
        Route::post('/{id}/store-assign-candidate', [PlanController::class, 'assignStore'])
            ->name('store-assign');
        Route::get('/show/{id}', [PlanController::class, 'show'])
            ->name('show');
        Route::patch('/{id}/update', [PlanController::class, 'update'])
            ->name('update');
        Route::patch('/{plan}/update_status', [PlanController::class, 'update_status'])
            ->name('update_status');
        Route::delete('/destroy/{plan}', [PlanController::class, 'delete'])
            ->name('destroy');
    });

    #payment subscription
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/list', [SubscriptionController::class, 'index'])
            ->middleware(['permission:role_read'])
            ->name('list');
        Route::post('/{id}/active-waiting-subscription', [SubscriptionController::class, 'activateSubscription'])
            ->name('active_waiting_subscription');
    });

    #payment slot
    Route::prefix('slot')->name('slot.')->group(function () {
        Route::get('/list', [SlotController::class, 'index'])
            ->middleware(['permission:role_read'])
            ->name('list');
        Route::get('/create', [SlotController::class, 'create'])
            ->name('create');
        Route::post('/store', [SlotController::class, 'store'])
            ->name('store');
        Route::get('/{id}/edit', [SlotController::class, 'edit'])
            ->name('edit');
        Route::get('/show/{id}', [SlotController::class, 'show'])
            ->name('show');
        Route::patch('/{id}/update', [SlotController::class, 'update'])
            ->name('update');
        Route::patch('/{slot}/update_status', [SlotController::class, 'update_status'])
            ->name('update_status');
        Route::delete('/destroy/{slot}', [SlotController::class, 'delete'])
            ->name('destroy');
    });

    Route::prefix('campaign')->name('campaign.')->group(function () {
        Route::get('/list', [CampaignController::class, 'index'])->name('index');
        Route::get('/alert/list', [CampaignController::class, 'alerts'])->name('alert');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/store', [CampaignController::class, 'store'])->name('store');
        Route::post('/filter', [CampaignController::class, 'filter'])->name('filter');
        Route::post('/update/{id}', [CampaignController::class, 'update'])->name('update');
        Route::post('/preview', [CampaignController::class, 'preview'])->name('preview');
        Route::get('/show/{id}', [CampaignController::class, 'show'])->name('show');
        Route::get('/info/{id}', [CampaignController::class, 'info'])->name('info');
        Route::get('/edit/{id}', [CampaignController::class, 'edit'])->name('edit');
        Route::get('/resend/{id}', [CampaignController::class, 'resend'])->name('resend');
        Route::delete('/destroy/{campaign:id}', [CampaignController::class, 'delete'])->name('destroy');
    });

    Route::get('/get-functional-area', 'IndustryController@getFunctionalArea')
        ->name('get_functional_area');
    Route::get('/get-country', 'LocationController@getCountry')
        ->name('get.country');
    Route::get('/get-state/{country_id}', 'LocationController@getState')
        ->name('get.state');
    Route::get('/get-city/{state_id}', 'LocationController@getCity')
        ->name('get.city');

    #advertise job
    Route::prefix('advertise')->name('advertise.')->group(function () {
        Route::get('/list', [AdvertiseController::class, 'index'])
            ->middleware(['permission:role_read'])
            ->name('list');
        Route::patch('/{advertise}/update_status', [AdvertiseController::class, 'update_status'])
            ->name('update_status');
        Route::post('/{advertise}/send-registration', [AdvertiseController::class, 'send_registration'])
            ->name('send_registration');
    });

    #advice
    Route::prefix('resource')->name('resource.')->group(function () {
        Route::get('/list', [ResourceFileController::class, 'index'])->name('list');
        Route::get('/create', [ResourceFileController::class, 'create'])->name('create');
        Route::post('/store', [ResourceFileController::class, 'store'])->name('store');
        Route::delete('/trash/{id}', [ResourceFileController::class, 'trash'])->name('trash');
    });
});
