<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwoFactorConfirmCodeRequest;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{

    public function __construct(private TwoFactorAuthentication $twoFactor)
    {

    }

    public function showToggleForm()
    {
        return view('auth.two-factor.toggle');
    }

    public function activate()
    {
        $response = $this->twoFactor->requestCode(Auth::user());

        return $response === $this->twoFactor::CODE_SENT
            ? redirect()->route('auth.two.factor.code.form')
            : back()->with('failed', __('auth.two factor failed to send code'));
    }

    public function showEnterCodeForm()
    {
        return view('auth.two-factor.code-form');
    }

    public function confirmCode(TwoFactorConfirmCodeRequest $request)
    {

        $response = $this->twoFactor->activate();

        return $response === $this->twoFactor::ACTIVATED
            ? redirect()->route('home')->with('success', __('auth.two factor activated'))
            : back()->with('failed', __('auth.two factor failed to active'));
    }



    public function deactivate()
    {
        $this->twoFactor->deactivate(Auth::user());

        return back()->with('success',__('auth.deactivated successfully'));
    }

    public function resend()
    {
        $this->twoFactor->resend();

        return back()->with('success',__('auth.code resend successful'));
    }
}
