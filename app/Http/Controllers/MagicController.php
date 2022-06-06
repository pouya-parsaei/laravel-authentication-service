<?php

namespace App\Http\Controllers;


use App\Http\Requests\MagicLoginRequest;
use App\Models\LoginToken;
use App\Services\Auth\MagicAuthentication;
use Illuminate\Http\Request;

class MagicController extends Controller
{

    public function __construct(private MagicAuthentication $auth)
    {

    }

    public function showMagicForm()
    {
        return view('auth.magic-login-form');
    }

    public function sendToken(MagicLoginRequest $request)
    {
        $this->auth->requestLink();

        return back()->with('success', __('auth.magic link sent'));
    }


    public function login(LoginToken $token)
    {
        return $this->auth->authenticate($token) === $this->auth::AUTHENTICATED
            ? redirect()->route('home') :
            redirect()->route('auth.magic.login.form')->with('failed', 'auth.magic link failed to send');
    }
}
