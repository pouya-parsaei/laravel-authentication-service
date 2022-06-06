<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $this->validateForm($request);

        $response =Password::broker()->sendResetLink($request->only('email'));

        if($response !== Password::RESET_LINK_SENT)
            return back()->with('failed',__('auth.failed to send reset password link'));

        return back()->with('success',__('auth.reset password link sent'));
    }

    private function validateForm($request)
    {
        $request->validate([
            'email' => ['required','email','exists:users']
        ]);
    }
}
