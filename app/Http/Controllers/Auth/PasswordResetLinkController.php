<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function createforAdmin()
    {
        addJavascriptFile('assets/js/custom/authentication/reset-password/reset-password.js');

        return view('pages.auth.forgot-password');
    }


    public function create()
    {
        return view('frontend.pages.auth.forget-password');
    }


    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->where('status',1)->first();
        if (!$user) {
            return response()->json(['errors' => ['email' => 'User not found.']], 404);
        }

        $status = Password::broker()->sendResetLink($request->only('email'), function ($user, $token) {
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user->email, $user->name, $user->isAdmin));
        });

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('info', 'Reset link sent! Check your email.');
            return response()->json(['message' => 'Reset link sent! Check your email.'], 200);
        }


        return response()->json(['errors' => ['email' => 'Failed to send reset link.']], 422);
    }

}
