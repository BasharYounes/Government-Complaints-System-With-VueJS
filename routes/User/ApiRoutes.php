<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GovernmentEntitiesController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



// register User "Citizen"
Route::post('/register',[AuthController::class,'RegisterUser']);
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('login');
Route::post('/verify-code',[AuthController::class,'VerifyCode'])->name('verify-code')->middleware('throttle:5,1');
Route::post('/resend-code',[AuthController::class,'ResendCode'])->middleware('throttle:3,10')->name('resend-code');
Route::post('/forget-password',[ForgetPasswordController::class,'forgotPassword'])->name('forget-password');
Route::post('/check-code',[ForgetPasswordController::class,'checkCode'])->name('check-code');
Route::post('/reset-password',[ForgetPasswordController::class,'resetPassword'])->name('reset-password');



Route::middleware(['AuthenticateUser'])->group(function () {

    Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
    Route::post('/edit-profile',  [AuthController::class, 'EditInformation']);

    Route::post('/store-fcm-token', [AuthController::class, 'storeFCM_Token']);

    Route::prefix('notifications')
        ->controller(NotificationController::class)
        ->group(function () {

            Route::get(
                '/',
                'index'
            );

            Route::patch(
                '/read-all',
                'markAllAsRead'
            );

            Route::patch(
                '/{id}/read',
                'markAsRead'
            )->whereNumber('id');

        });

    Route::prefix('complaints')->group(function () {
        Route::post('create', [ComplaintController::class, 'create']);
        Route::get('show/{id}', [ComplaintController::class, 'show']);
        Route::patch(
            'complaints/{id}',
            [ComplaintController::class, 'update']
        )
            ->whereNumber('id')
            ->name('user.complaints.update');
        Route::delete('delete/{id}', [ComplaintController::class, 'destroy']);
        Route::post('add-attachment/{id}', [ComplaintController::class, 'addAttachment']);
        Route::get('get-user-complaints', [ComplaintController::class, 'getComplaintsforUser']);
    });


    Route::prefix('attachments')->group(function () {
        Route::get('/show/{id}', [AttachmentController::class, 'show']);
    });

});
  Route::prefix('government-entities')->group(function () {
        Route::get('/all-entities', [GovernmentEntitiesController::class, 'index']);
    });
