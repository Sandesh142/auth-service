<?php
namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $timestamp = now()->toDateTimeString();
        Log::debug("AUTH HANDLE [$timestamp] - path: {$request->path()}, guards: ", $guards);

        // Determine if the user is authenticated under any of the provided guards
        $authenticated = false;
        foreach ($guards as $guard) {
            $isAuthenticated = Auth::guard($guard)->check();
            Log::debug("Checking guard: $guard - Status: " . ($isAuthenticated ? 'true' : 'false'));
            if ($isAuthenticated) {
                $authenticated = true;
                break; // Break as soon as one guard is authenticated
            }
        }

        Log::debug("Final authenticated: " . ($authenticated ? 'true' : 'false'));

        if (!$authenticated) {
            Log::debug("Authentication failed - redirecting or aborting.");
            if ($request->expectsJson()) {
                abort(401, 'Unauthenticated.');
            }

            // If the user is trying to access superadmin routes
            if ($request->is('superadmin/*')) {
                return redirect()->route('superadmin.login');
            }

            // Otherwise, redirect to the admin login
            return redirect()->route('admin.login');
        }

        return $next($request);
    }

    /**
     * This method is part of the parent class but should not be directly called
     * if the handle() method correctly processes authentication.
     */
    protected function redirectTo(Request $request): ?string
    {
        return route('admin.login');  // Default fallback if redirectTo is used
    }
}
