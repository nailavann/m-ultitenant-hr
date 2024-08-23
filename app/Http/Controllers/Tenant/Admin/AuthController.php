<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Api\Auth\LoginRequest;
use App\Http\Requests\Tenant\Api\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $attributes = collect($request->validated());
            $user = User::query()->where('email', $attributes->get('email'))->first();
            throw_unless($user, \Exception::class, 'Email bulunamadı.');
            throw_unless($user->email === 'nailavann@hotmail.com', \Exception::class, 'Yetkiniz yok.');

            if (Auth::attempt($attributes->toArray())) {
                return redirect()->route('tenant.dashboard');
            }

            return back()->withErrors(['E-posta adresi veya şifre hatalı.']);
        } catch (\Throwable $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        //Todo: Burayıda dinamikleştir.
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('tenant.password.success')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        try {
            auth()->logout();
            $request->session()->invalidate();
            return redirect()->route('tenant.login');
        } catch (\Throwable $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }
}
