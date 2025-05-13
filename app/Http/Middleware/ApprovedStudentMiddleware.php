<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedStudentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'student' && Auth::user()->is_approved) {
            return $next($request);
        }

        abort(403, 'Your account is not approved yet.');
    }
}

