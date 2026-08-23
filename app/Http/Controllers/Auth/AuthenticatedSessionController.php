<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\view;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        addJavascriptFile('assets/js/custom/authentication/sign-in/general.js');

        return view('pages.auth.login');
    }

    public function user_login()
    {
        return view('frontend.pages.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // admin login system
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = $request->user();

        if ($user->isAdmin != 1) {
            Auth::logout();
            return response()->json([
                'errors' =>  'Access denied. You are not authorized to access the admin panel.'
            ], 403);
        }

        // Check if the user's account is inactive
        if ($user->status == 0) {
            Auth::logout();
            return response()->json([
                'errors' => ['email' => 'Your account is inactive. Please contact support.']
            ], 403);
        }

        $request->session()->regenerate();

        $user->update([
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp(),
        ]);

        session()->flash('success', 'Logged in successfully.');
    }


    public function userStore(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if the user's status is 0
            if ($user->status === 0) {
                Auth::logout();
                return response()->json([
                    'errors' => ['email' => 'Your account is inactive. Please contact support.']
                ], 403);
            }

            $request->session()->regenerate();

            $request->user()->update([
                'last_login_at' => now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp(),
            ]);

            session()->flash('success', 'Logged in successfully.');

            return response()->json(['redirect' => route('user.dashboard')], 200);
        }

        return response()->json([
            'errors' => ['email' => 'Your email or password is incorrect. Please try again.']
        ], 422);
    }



    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        session()->flash('success', 'Log out!!');
        return redirect('/');
    }
}
