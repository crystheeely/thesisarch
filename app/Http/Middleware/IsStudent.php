<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if the user is authenticated and their role is 'student'
         if (auth()->check() && auth()->user()->role === 'student') {
            return $next($request);
        }

        // If not, abort with a 403 error (Forbidden)
        abort(403, 'Access denied - Students only');
    }
    }

