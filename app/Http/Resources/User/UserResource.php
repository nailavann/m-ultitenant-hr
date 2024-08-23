<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Adress\AdressesResource;
use App\Http\Resources\Education\EducationResource;
use App\Http\Resources\Emergency\EmergenciesResource;
use App\Http\Resources\Tenant\Api\Leave\LeaveInformationResource;
use App\Http\Resources\Tenant\Api\Role\RoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'surname' => $this->surname,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'information' => UserInformationResource::make($this->whenLoaded('information')),
            'leaveInformation' => LeaveInformationResource::make($this->whenLoaded('leaveInformation')),
            'addresses' => AdressesResource::collection($this->whenLoaded('addresses')),
            'emergencies' => EmergenciesResource::collection($this->whenLoaded('emergencies')),
            'educations' => EducationResource::collection($this->whenLoaded('educations')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
