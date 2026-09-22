<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!auth()->guard('admin')->check()) {
            if ($request->header('X-Inertia')) {
                return redirect()->route('admin.login');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized Access',
                ], 401);
            }

            return redirect()->route('admin.login');
        }

        return $next($request);

    }
}
