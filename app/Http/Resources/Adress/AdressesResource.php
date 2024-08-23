<?php

namespace App\Http\Resources\Adress;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdressesResource extends JsonResource
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
            'address' => $this->address,
            'country' => $this->country,
            'city' => $this->city,
            'postCode' => $this->post_code,
            'phone' => $this->phone,
        ];
    }
}
