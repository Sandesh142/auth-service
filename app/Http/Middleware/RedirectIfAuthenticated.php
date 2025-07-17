<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If authenticated with 'web' guard, redirect to 'admin.dashboard'
                if ($guard === 'web') {
                    return redirect()->route('admin.dashboard');
                }
                // If authenticated with 'superadmin' guard, redirect to 'superadmin.dashboard'
                if ($guard === 'superadmin') {
                    return redirect()->route('superadmin.dashboard');
                }
                // Default fallback for any other authenticated guard
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
