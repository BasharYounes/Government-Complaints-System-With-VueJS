<?php

use App\Http\Controllers\AdminComplaintController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GovernmentEntitiesController;
use Illuminate\Support\Facades\Route;




Route::post('/registerAdmin',[AuthController::class,'registerAdmin']);
Route::post('/loginAdmin',[AuthController::class,'loginAdmin']);

Route::middleware(['AuthenticateAdmin','role:super_admin'])->prefix('admin')->group(function () {
    Route::get('/complaints', [AdminComplaintController::class, 'index'])
        ->middleware('permission:view-all-complaints');

    Route::get('/employees', [AdminComplaintController::class, 'listUsers'])
        ->middleware('permission:view-employees');

    Route::get('/complaints/{complaintId}/audit-logs', [AdminComplaintController::class, 'complaintAuditLogs'])
        ->middleware('permission:view-complaint-audit-logs');

    Route::get('/statistics', [AdminComplaintController::class, 'statistics'])
        ->middleware('permission:view-statistics');

    Route::get('/reports/monthly/csv', [AdminComplaintController::class, 'monthlyCsv'])
        ->middleware('permission:export-monthly-csv');

    Route::get('/reports/monthly/pdf', [AdminComplaintController::class, 'monthlyPdf'])
        ->middleware('permission:export-monthly-pdf');

    Route::post('logout', [AuthController::class, 'logoutAdmin']);

    Route::post('/registerEmployee',[AuthController::class,'registerEmployee'])
         ->middleware('permission:manage-users');

    Route::get('/government-entities', [GovernmentEntitiesController::class, 'index']);

    Route::post('/search-complaints',[AdminComplaintController::class,'searchComplaints'])
         ->middleware('permission:view-employees' );

    Route::post('/search-employee',[AdminComplaintController::class,'searchEmployees'])
        ->middleware('permission:manage-users');

});
