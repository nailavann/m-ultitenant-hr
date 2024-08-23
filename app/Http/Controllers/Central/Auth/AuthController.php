<?php

namespace App\Http\Controllers\Central\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Api\Auth\LoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $attributes = collect($request->validated());

            if (!auth()->guard('central')->attempt($attributes->toArray())) {
                return back()->withErrors(['Giriş sırasında hata.']);
            }
            return redirect()->route('central.dashboard');

        } catch (\Throwable $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->guard('central')->logout();
            $request->session()->invalidate();
            return redirect()->route('central.login');
        } catch (\Throwable $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }
}
