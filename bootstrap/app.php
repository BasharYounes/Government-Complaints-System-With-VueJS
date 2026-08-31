<?php

use App\Exceptions\Handler;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append:[
            HandleInertiaRequests::class,
        ]);

        $middleware->api(append: [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \Illuminate\Http\Middleware\HandleCors::class,

        ]);
        $middleware->alias([
            'AuthenticateEmployee' => \App\Http\Middleware\Employee\EmployeeMidlleware::class,
            'AuthenticateAdmin' => \App\Http\Middleware\Admin\AdminMiddleware::class,
            'AuthenticateUser' => \App\Http\Middleware\User\UserMiddleware::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);

        $middleware->trustHosts(at: ['your-domain.com']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (Throwable $e, $request) {
            $handler = app(Handler::class);
            return $handler->render($request, $e);
        });
    })->create();
