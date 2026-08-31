<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\EmployeeComplaintController;
use Illuminate\Support\Facades\Route;



Route::post('/loginEmployee',[AuthController::class,'loginEmployee']);

Route::middleware(['AuthenticateEmployee','role:employee'])->prefix('employee')->group(function () {
    Route::get('/complaints', [EmployeeComplaintController::class, 'index'])->middleware('permission:view-complaint');
    Route::post('/update-complaints/{complaintId}', [EmployeeComplaintController::class, 'updateStatus'])->middleware('permission:update-complaint');
    Route::post('check-editing/{complaintId}',[ComplaintController::class,'edit']);
    Route::post('complaints/{complaintId}/request-information', [EmployeeComplaintController::class, 'RequestAdditionalInformation'])->middleware('permission:RequestAdditionalInformation');
    Route::post('logout', [AuthController::class, 'logoutEmployee'])->middleware('permission:logout-employee');
    Route::get('/all-complaints', [EmployeeComplaintController::class, 'getAllComplaint']);
    Route::get('/show-complaint/{complaintId}', [EmployeeComplaintController::class, 'show'])->middleware('permission:view-complaint');
});
