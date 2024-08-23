<?php

namespace App\Http\Controllers\Tenant\Api\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Tenant\Api\Auth\LoginRequest;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Mockery\Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Throwable;

class AuthController extends Controller
{

    public function register()
    {

//        /** @var Permission $permission */
//        $permission = Permission::query()->create([
//            'name' => 'user-all-detail',
//        ]);
//
//        /** @var Role $role */
//        $role = Role::query()->create([
//            'name' => 'SuperAdmin'
//        ]);
//
//
//        $role->givePermissionTo($permission);

        /** @var User $user */
        $user = User::query()->create([
            'name' => 'busra',
            'surname' => 'sert',
            'email' => 'busra.sert@ofisus.com.tr',
            'password' => bcrypt('123123123'),
        ]);

        /** @var UserInformation $information */
        $user->information()->create([
            'company_id' => 1,
            'department_id' => 1,
            'phone' => '123614614',
            'position_started_at' => now()->subYears(2),
        ]);

        $user->addresses()->create([
            'address' => 'Şehit Cevdet Özdemir Mahallesi Ece Caddesi 38/11',
            'country' => 'Türkiye',
            'city' => 'Ankara',
            'post_code' => '1461782524',
            'phone' => '"34"',
        ]);


        $user->emergencies()->create([
            'name' => 'Kiraz',
            'surname' => 'Avan',
            'relation' => 'Annesi',
            'phone' => '135614275',
        ]);

        $user->educations()->create([
            'name' => 'Kırıkkale Üniversitesi',
            'department' => 'Bilgisayar Mühendisliği',
            'degree' => 'Lisans',
            'status' => 'Mezun',
            'started_at' => now()->subYears(6),
            'ended_at' => now()->subYears(2),
        ]);

//        $user->assignRole($role);
        event(new UserCreated($user));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $attributes = collect($request->validated());

            /** @var User $user */
            $user = User::query()->where('email', $attributes->get('email'))->first();
            throw_unless($user, Exception::class, 'Email bulunamadı.');

            if (!Hash::check($attributes->get('password'), $user->password)) {
                throw new Exception('Hatalı şifre.');
            }

            $token = $user->createToken('token')->plainTextToken;

            return $this->sendSuccess('Giriş başarılı', [
                'access_token' => $token
            ]);
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $user->currentAccessToken()->delete();

            $user->devices()->where('deviceId', $request->deviceId)->delete();

            return $this->sendSuccess('Çıkış başarılı');
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Şifre sıfırlama linki gönderildi.'
            ]);
        } else {
            // Todo: Burayı dönüş değerine göre dinamikleştir.
            return response()->json([
                'error' => 'Geçersiz e-posta'
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $attributes = collect($request->validated());

            /** @var User $user */
            $user = auth()->user();
            if (!Hash::check($attributes->get('current_password'), $user->password)) {
                throw new Exception('Şuan ki şifre yanlış girildi.');
            }

            $user->update([
                'password' => bcrypt($attributes->get('password'))
            ]);

            return $this->sendSuccess('Şifre değiştirildi');
        } catch (Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

}
