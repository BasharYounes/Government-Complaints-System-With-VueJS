<?php

use Illuminate\Support\Facades\Route;


    Route::inertia('admin/login', 'Admin/Auth/Login')->name('admin.login');


Route::middleware(['AuthenticateAdmin', 'role:super_admin,admin'])
    ->prefix('admin')
    ->group(function () {
        Route::inertia('/dashboard', 'Admin/Dashboard')->name('admin.dashboard');
        Route::inertia('/complaints/manage', 'Admin/Complaints/Index')->name('admin.complaints');
        Route::inertia('/employees/manage', 'Admin/Employees/Index')->name('admin.employees');
        Route::inertia('/reports', 'Admin/Reports')->name('admin.reports');
        Route::inertia('/audit-logs', 'Admin/AuditLogs')->name('admin.audit-logs');
    });
