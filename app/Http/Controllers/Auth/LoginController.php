<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\TwoFactorConfirmCodeRequest;
use App\Models\User;
use App\Rules\Recaptcha;
use App\Services\Auth\Traits\ThrottlesLogins;
use App\Services\Auth\TwoFactorAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use  ThrottlesLogins;

    public function __construct(private TwoFactorAuthentication $twoFactor)
    {

    }

    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $this->validateForm($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if (!$this->isValidCredentials($request)) {
            $this->incrementLoginAttempts($request);
            return $this->sendLoginFailedResponse();
        }

        $user = $this->getUser($request);

        if ($user->hasTwoFactor()) {
            $this->twoFactor->requestCode($user);
            return $this->sendHasTwoFactorResponse();
        }

        Auth::login($user, $request->remember);
        return $this->sendSuccessResponse();
    }

    private function validateForm(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email', 'exists:users'],
                'password' => ['required'],
                'g-recaptcha-response' => ['required', new Recaptcha]
            ],
            [
                'g-recaptcha-response.required' => __('auth.recaptcha')
            ]
        );
    }

    private function isValidCredentials($request)
    {
        return Auth::validate($request->only(['email', 'password']));
    }

    private function sendLoginFailedResponse()
    {
        return back()->with('failed', __('auth.failed'));
    }

    private function getUser(Request $request)
    {
        return User::where('email', $request->email)->firstOrFail();
    }

    protected function sendHasTwoFactorResponse()
    {
        return redirect()->route('auth.login.code.form');
    }

    private function sendSuccessResponse()
    {
        session()->regenerate();
        return redirect()->intended();
    }

    public function showCodeForm()
    {
        return view('auth.two-factor.login-code');
    }

    public function confirmCode(TwoFactorConfirmCodeRequest $request)
    {
        $response = $this->twoFactor->login();

        return $response === $this->twoFactor::AUTHENTICATED
            ? $this->sendSuccessResponse()
            : back()->with('failed', __('auth.invalid code'));

    }

    public function logout()
    {
        session()->invalidate();

        Auth::logout();

        return redirect()->route('home');
    }

}
