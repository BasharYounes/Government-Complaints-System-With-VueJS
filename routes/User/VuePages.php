<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest User Pages
|--------------------------------------------------------------------------
*/

Route::inertia(
    'user-log-in',
    'User/Auth/LogIn'
)->name('user.log-in');

Route::inertia(
    'user-register',
    'User/Auth/Register'
)->name('user.register');

Route::inertia(
    'verify-code',
    'User/Auth/VerifyCode'
)->name('user.verify-code');

Route::inertia(
    'resend-code',
    'User/Auth/ResendCode'
)->name('user.resend-code');

Route::inertia(
    'forget-password',
    'User/Auth/ForgetPassword'
)->name('user.forget-password');

Route::inertia(
    'check-code',
    'User/Auth/CheckCode'
)->name('user.check-code');

Route::inertia(
    'reset-password',
    'User/Auth/ResetPassword'
)->name('user.reset-password');


/*
|--------------------------------------------------------------------------
| Authenticated User Pages
|--------------------------------------------------------------------------
*/

Route::middleware('AuthenticateUser')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Home
    |--------------------------------------------------------------------------
    */

    Route::get(
        'home',
        [ComplaintController::class, 'home']
    )->name('user.home');


    /*
    |--------------------------------------------------------------------------
    | Complaints
    |--------------------------------------------------------------------------
    */

    Route::inertia(
        'create-complaint',
        'User/Complaint/CreateComplaint'
    )->name('user.create-complaint');


    Route::get(
        'complaints',
        [ComplaintController::class, 'getComplaintsforUser']
    )->name('user.complaints');


    Route::get(
        'complaints/track',
        [ComplaintController::class, 'track']
    )->name('user.complaints.track');


    Route::get(
        'complaints/{id}',
        [ComplaintController::class, 'show']
    )
        ->whereNumber('id')
        ->name('user.complaints.show');

        
    Route::patch(
            'complaints/{id}',
            [ComplaintController::class, 'update']
        )
            ->whereNumber('id')
            ->name('user.complaints.update');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        'profile',
        [AuthController::class, 'getUser']
    )->name('user.profile');


    /*
    |--------------------------------------------------------------------------
    | Support
    |--------------------------------------------------------------------------
    */

    Route::inertia(
        'support',
        'User/Support/Support'
    )->name('user.support');

});
