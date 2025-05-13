<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and if the student is approved
        if (Auth::check() && Auth::user()->role === 'student' && !Auth::user()->status == 'approved') {
            // Redirect to a page indicating the user is awaiting approval
            return redirect()->route('student.awaiting-approval');
        }

        return $next($request);
    }
}

