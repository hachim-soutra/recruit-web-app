<?php

//Site Routes

use App\Http\Controllers\CkeditorController;

Route::namespace('Site')->middleware('revalidate')
    ->group(__DIR__ . '/site/site.php');

//Admin Routes
Route::namespace('Admin')->middleware('revalidate')
    ->name('admin.')->prefix('admin')
    ->group(__DIR__ . '/admin/admin.php');


// 301 Redirects - 2023-08-17
Route::redirect('/tag/jobs/', '/careers/tag/jobs/', 301);
Route::redirect('/find-jobs/', '/careers/find-jobs/', 301);
Route::redirect('/public/app', '/careers/app/', 301);
Route::redirect('/category/recruitment-tips/', '/careers/category/recruitment-tips/', 301);
Route::redirect('/blog/', '/careers/blog/', 301);


Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');
