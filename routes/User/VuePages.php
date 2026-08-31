<?php

use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;




Route::inertia('user-log-in', 'User/Auth/LogIn')->name('user.log-in');
Route::inertia('user-register', 'User/Auth/Register')->name('user.register');
Route::inertia('verify-code', 'User/Auth/VerifyCode')->name('user.verify-code');
Route::inertia('resend-code', 'User/Auth/ResendCode')->name('user.resend-code');
Route::inertia('forget-password', 'User/Auth/ForgetPassword')->name('user.forget-password');
Route::inertia('check-code', 'User/Auth/CheckCode')->name('user.check-code');
Route::inertia('reset-password', 'User/Auth/ResetPassword')->name('user.reset-password');
Route::get('home', [ComplaintController::class, 'home'])->middleware('api')->name('user.home');
Route::inertia('create-complaint', 'User/Complaint/CreateComplaint')->name('user.create-complaint');
