<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('user.login');
        }
    }

    /**
     * Handle an incoming request and check if the user's account is disabled.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array|string|null  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        $response = parent::handle($request, $next, ...$guards);

        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If the user's account is disabled
            if ($user->status == 0) {
                // Log the user out
                Auth::logout();

                // Invalidate the session
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect based on user role
                if ($user->isAdmin == 1) {
                    return redirect()->route('login')->with('info', 'Your account has been disabled. Please contact support.');
                } else {
                    return redirect()->route('user.login')->with('info', 'Your account has been disabled. Please contact support.');
                }
            }
        }

        return $response;
    }

}
