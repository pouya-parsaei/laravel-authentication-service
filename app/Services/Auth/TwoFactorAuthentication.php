<?php

namespace App\Services\Auth;

use App\Models\TwoFactor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthentication
{
    public const INVALID_CODE = 'code.invalid';
    public const ACTIVATED = 'code.activated';
    public const CODE_SENT = 'code.sent';
    const AUTHENTICATED =  'code.authenticated';

    private TwoFactor $code;

    public function __construct(private Request $request)
    {

    }

    public function requestCode(User $user)
    {
        $code = TwoFactor::generateCodeFor($user);

        $this->setSession($code);

        $code->send();

        return static::CODE_SENT;
    }

    private function setSession(TwoFactor $code)
    {
        session([
           'code_id' => $code->id,
            'user_id' => $code->user_id,
            'remember' => $this->request->remember
        ]);
    }

    public function activate()
    {
        if(!$this->isValidCode()) return static::INVALID_CODE;

        $this->getToken()->delete();

        $this->getUser()->activateTwoFactor();

        $this->forgetSession();

        return static::ACTIVATED;
    }

    private function isValidCode()
    {
        return !$this->getToken()->isExpired() && $this->getToken()->isEqualWith($this->request->code);
    }

    private function getToken()
    {
        return $this->code ?? $this->code = TwoFactor::findOrFail(session('code_id'));
    }

    private function getUser()
    {
        return User::findOrFail(session('user_id'));
    }

    private function forgetSession()
    {
        session()->forget(['code_id', 'user_id']);
    }

    public function deactivate(User $user)
    {
        $user->deactivateTwoFactor();
    }

    public function login()
    {
        if(!$this->isValidCode()) return static::INVALID_CODE;

        $this->getToken()->delete();

        Auth::login($this->getUser(),session('remember'));

        return static::AUTHENTICATED;
    }

    public function resend()
    {
        return $this->requestCode($this->getUser());
    }
}
