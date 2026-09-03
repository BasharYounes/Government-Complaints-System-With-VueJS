<?php

use Illuminate\Support\Facades\Route;

Route::get('/firebase-messaging-sw.js', function () {

    return response()
        ->view('firebase-messaging-sw')
        ->header(
            'Content-Type',
            'application/javascript'
        )
        ->header(
            'Service-Worker-Allowed',
            '/'
        )
        ->header(
            'Cache-Control',
            'no-cache, no-store, must-revalidate'
        );

});

require __DIR__.'/User/VuePages.php';

require __DIR__.'/Admin/VuePages.php';

require __DIR__.'/Employee/VuePages.php';




require __DIR__.'/User/ApiRoutes.php';

require __DIR__.'/Admin/ApiRoues.php';

require __DIR__.'/Employee/ApiRoutes.php';

Route::inertia('/', 'Test');
