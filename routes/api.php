<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'AuthController@register');

    Route::post('/check-email', 'AuthController@emailExist');
    Route::post('/check-mobile', 'AuthController@mobileExist');

    Route::post('/get-countries', 'LocationController@getCountry');
    Route::post('/get-states', 'LocationController@getState');
    Route::post('/get-cities', 'LocationController@getCity');

    Route::post('/forgot-password', 'PasswordController@forgotPassword');
    Route::post('/reset-password', 'PasswordController@resetPassword');

    Route::post('/linkedin-social-login', 'AuthController@socialMediaLinkedinLogin');

    Route::post('/mobile-otp', 'VerificationController@mobileOtp');
    Route::post('/verify-mobile-otp', 'VerificationController@verifyMobileOtp');
    // Route::post('/mobile-otp', 'VerificationController@emailOtp');
    // Route::post('/verify-mobile-otp', 'VerificationController@verifyEmailOtp');

    Route::post('/contact', 'ContactController@contact');
    Route::post('/about', 'CommonController@about');
    Route::post('/terms-conditions', 'CommonController@termsConditions');
    Route::post('/help', 'CommonController@help');
    Route::post('/linkedin-token', 'AuthController@linkedinToken');
    Route::post('/test-mail', 'TestController@testMail');

    Route::middleware('auth:api')->group(function () {
        // Testing route end
        Route::post('/logout', 'AuthController@logout');
        Route::post('/change-password', 'PasswordController@changePassword');
        Route::post('/get-benefit', 'CommonController@getBenefits');

        // Verify User0.
        // Route::get('/email-otp', 'VerificationController@emailOtp');;
        // Route::post('/verify-email-otp', 'VerificationController@verifyEmailOtp');
        // tempurary use for unavilabele sms getway

        // Email mobile test on user profile Edit
        // Route::post('/check-user-email', 'UserController@emailExist');
        // Route::post('/check-user-mobile', 'UserController@mobileExist');

        Route::get('/get-notification', 'NotificationController@list');
        Route::get('/get-language', 'CommonController@getLanguage');

        Route::post('/get-skill', 'CommonController@getSkills');
        Route::post('/get-get-functional-area', 'CommonController@getFunctionalArea');
        Route::post('/get-get-qualifications', 'CommonController@getQualifications');
        Route::post('/upload-file', 'UserController@fileUpdate');
        Route::post('/linkedin-access-token', 'AuthController@linkedinAccessToken');

        Route::post('/delete-account', 'SettingController@deleteAccount');

        Route::post('/coupon-listing', 'CouponController@listing');
        Route::post('/coupon-details', 'CouponController@details');
        Route::post('/get_version', 'UserController@GetVersion');

        Route::prefix('candidate')->group(function () {

            Route::post('/my-profile', 'UserController@myProfile');
            Route::post('/get-profile', 'UserController@getProfile');
            Route::post('/update-profile', 'UserController@profileUpdate');
            Route::post('/upload-file', 'UserController@fileUpdate');
            Route::post('/add-edit-experience', 'CandidateController@addEditExperience');
            Route::post('/remove-experience', 'CandidateController@removeExperience');

            #6-29
            Route::post('/update-cover-letter', 'CandidateController@updateCoverLetter');
            // Route::post('/get_version', 'CandidateController@GetVersion');
            Route::post('/event-listing', 'EventController@List');
            Route::post('/event-details', 'EventController@Details');

            Route::post('/find-job', 'SearchController@findJob');

            Route::post('/job-listing', 'JobPostController@listing');
            Route::post('/job-details', 'JobPostController@details');
            Route::post('/bookmarked-job', 'JobPostController@jobBookMarked');
            Route::post('/apply-job', 'JobPostController@applyJob');
            Route::post('/bookmarked-job-list', 'JobPostController@bookmarkJobList');
            Route::post('/applied-job-list', 'JobPostController@appliedJobList');
            Route::post('/report-job', 'JobPostController@reportJob');
            Route::post('/not-interest-job', 'JobPostController@notInterestJob');

            Route::post('/coach-list', 'CoachController@coachList');
            Route::post('/coach-details', 'CoachController@coachDetails');
            Route::post('/bookmarked-coach', 'CoachController@bookmarkCoach');
            Route::post('/bookmarked-coach-list', 'CoachController@bookmarkCoachList');
            Route::post('/company-bookmark', 'EmployerController@companyBookmark');
            Route::post('/company-bookmark-list', 'EmployerController@companyBookmarkList');

            Route::post('/skill-update', 'CandidateController@skillUpdate');
            Route::post('/qualification-update', 'CandidateController@qualificationUpdate');
            #6-28
            Route::post('/update-resume', 'CandidateController@updateResumeCoverLetter');
        });

        Route::prefix('employer')->group(function () {
            Route::post('/my-profile', 'UserController@myProfile');
            Route::post('/get-profile', 'UserController@getProfile');
            Route::post('/update-profile', 'EmployerController@profileUpdate');
            Route::post('/upload-file', 'UserController@fileUpdate');
            // Route::get('/profile-edit', 'UserController@profileEdit');
            // Route::post('/profile-update', 'UserController@profileUpdate');

            Route::post('/find-candidate', 'SearchController@findCandidate');

            Route::post('/job-listing', 'JobPostController@listing');
            Route::post('/draft-job-listing', 'JobPostController@draftListing');
            Route::post('/job-add', 'JobPostController@JobCreate');
            Route::post('/update-job', 'JobPostController@update');
            Route::post('/publish-job', 'JobPostController@publishJob');
            Route::post('/job-details', 'JobPostController@details');
            Route::post('/remove-job', 'JobPostController@remove');

            Route::post('/applied-list', 'JobPostController@appliedCandidateListByJob');
            Route::post('/change-status-applied-candidate', 'JobPostController@changeStatus');
            Route::post('/shortlisted-candidate-list', 'JobPostController@shortListedList');

            Route::post('/transaction-list', 'CommonController@getTransactionHistory');
        });

        Route::prefix('coach')->group(function () {
            Route::post('/my-profile', 'UserController@myProfile');
            Route::post('/get-profile', 'UserController@getProfile');
            Route::post('/update-profile', 'CoachController@profileUpdate');
            // Route::get('/profile-edit', 'UserController@profileEdit');
            // Route::post('/profile-update', 'UserController@profileUpdate');
        });
    });
});
