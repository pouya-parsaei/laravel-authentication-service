<?php

namespace App\Services\Auth;

use App\Models\LoginToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicAuthentication
{
    public const INVALID_TOKEN = 'token.invalid';
    public const AUTHENTICATED = 'authenticated';
    public function __construct(private Request $request)
    {

    }

    public function requestLink()
    {
        $user = $this->getUser();
        $user->createMagicLoginToken()->send([
            'remember' => $this->request->has('remember')
        ]);
    }

    private function getUser()
    {
        return User::where('email',$this->request->email)->firstOrFail();
    }

    public function authenticate(LoginToken $token)
    {
        $token->delete();

        if($token->isExpired()){
            return self::INVALID_TOKEN;
        }

        Auth::login($token->user,$this->request->query('remember'));

        return self::AUTHENTICATED;
    }
}

