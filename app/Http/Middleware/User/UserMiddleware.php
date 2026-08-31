<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        auth()->shouldUse('api');

        // if (!auth()->guard('api')->check()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized Access',
        //     ], 401);
        // }

        return $next($request);

    }
}
