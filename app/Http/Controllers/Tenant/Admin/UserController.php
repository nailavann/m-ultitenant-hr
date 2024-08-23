<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Admin\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('tenant.admin.user.create_user');
    }

    public function store(CreateUserRequest $request)
    {
        try {
            $attributes = collect($request->validated());

            $password = Str::random(8);
            $attributes->put('password', bcrypt($password));

            throw_unless(User::query()->create($attributes->toArray()), \Exception::class, 'Kullanıcı oluşturulurken bir sorun oluştu.');

        } catch (\Throwable  $exception) {
            return back()->withErrors([$exception->getMessage()]);
        }
    }
}
