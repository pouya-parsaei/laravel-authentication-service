<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\MagicController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('home');

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisterController::class, 'create'])->name('register-form');
        Route::post('register', [RegisterController::class, 'store'])->name('register');

        Route::get('login', [LoginController::class, 'create'])->name('login-form');
        Route::post('login', [LoginController::class, 'store'])->name('login');
        Route::get('login/code', [LoginController::class, 'showCodeForm'])->name('login.code.form');
        Route::post('login/code', [LoginController::class, 'confirmCode'])->name('login.code');
        Route::post('login/code', [LoginController::class, 'confirmCode'])->name('login.code');

        Route::get('magic/login', [MagicController::class, 'showMagicForm'])->name('magic.login.form');
        Route::post('magic/login', [MagicController::class, 'sendToken'])->name('magic.login.send.token');
        Route::get('magic/login/{token}', [MagicController::class, 'login'])->name('magic.login');

        Route::get('redirect/{provider}',[SocialController::class,'redirectToProvider'])->name('login.provider.redirect');
        Route::get('{provider}/callback',[SocialController::class,'providerCallback'])->name('login.provider.callback');

        Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot.form');
        Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.send.reset.link');
        Route::get('password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');



    });

    Route::middleware('auth')->group(function () {
        Route::get('email/send-verification', [VerificationController::class, 'send'])->name('email.send.verification');
        Route::get('email/verify', [VerificationController::class, 'verify'])->name('email.verify');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('two-factor/toggle',[TwoFactorController::class, 'showToggleForm'])->name('two.factor.toggle.form');
        Route::get('two-factor/activate',[TwoFactorController::class, 'activate'])->name('two.factor.activate');
        Route::get('two-factor/deactivate',[TwoFactorController::class, 'deactivate'])->name('two.factor.deactivate');
        Route::get('two-factor/code',[TwoFactorController::class, 'showEnterCodeForm'])->name('two.factor.code.form');
        Route::post('two-factor/code',[TwoFactorController::class, 'confirmCode'])->name('two.factor.code');
    });

});

Route::get('two-factor/resend', [TwoFactorController::class, 'resend'])->name('auth.two.factor.resend');
