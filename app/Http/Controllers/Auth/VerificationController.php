<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'send');
    }

    public function send()
    {
        if (Auth::user()->hasVerifiedEmail())
            return redirect()->route('home');

        Auth::user()->sendEmailVerificationNotification();

        return back()->with('success', __('auth.verification email sent'));
    }

    public function verify(Request $request)
    {
        if ($request->user()->email !== $request->query('email'))
            throw new AuthorizationException;

        if ($request->user()->hasVerifiedEmail())
            return redirect()->route('home');

        $request->user()->markEmailAsVerified();

        session()->forget('mustVerifyEmail');

        return redirect()->route('home')->with('success', __('auth.email verified'));

    }
}
