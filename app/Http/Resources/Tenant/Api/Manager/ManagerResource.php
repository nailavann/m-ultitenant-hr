<?php

namespace App\Http\Resources\Tenant\Api\Manager;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'fullName' => $this->fullName . ' (' . $this->pivot->priority . '. Yönetici' . ')',
        ];
    }
}
