<?php

namespace App\Http\Controllers\Tenant\Api\Device;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Api\Device\DeviceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class DeviceController extends Controller
{
    public function store(DeviceRequest $request)
    {
        try {
            $attributes = collect($request->validated());

            /** @var User $user */
            $user = auth()->user();

            $user->devices()->create($attributes->toArray());

            return $attributes;
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }

    public function update(Request $request)
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            $user->devices()->where('deviceId', $request->deviceId)->update([
                'updated_at' => now()
            ]);

            return $this->sendSuccess('device updated');
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }
    }
}
