<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email',Rule::unique('users','email')],
            'password' => ['required', 'confirmed', Rules\Password::default()],
        ]);

        $user = User::create($request->all());

        event(new UserRegistered($user));
        Auth::login($user);

        return redirect()->route('home')->with('success', __('auth.Register Successfully'));
    }
}
