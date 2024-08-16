<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if (Auth::guard('admin')->check()) { // Check if admin user is authenticated
//            return $next($request); // Allow access to protected routes
//        }
//        return redirect()->route('admin.login');
        return $next($request); // Allow access to protected routes

    }
}
