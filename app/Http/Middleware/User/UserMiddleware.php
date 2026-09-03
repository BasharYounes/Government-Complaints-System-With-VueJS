<?php

namespace App\Http\Middleware\User;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('web');

        if (!Auth::guard('web')->check()) {
            return redirect()
                ->route('user.log-in')
                ->with('error', 'يرجى تسجيل الدخول أولاً');
        }

        return $next($request);
    }
}
