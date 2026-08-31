<?php


require __DIR__.'/User/VuePages.php';

require __DIR__.'/Admin/VuePages.php';

require __DIR__.'/Employee/VuePages.php';




require __DIR__.'/User/ApiRoutes.php';

require __DIR__.'/Admin/ApiRoues.php';

require __DIR__.'/Employee/ApiRoutes.php';

Route::inertia('/', 'Test');
