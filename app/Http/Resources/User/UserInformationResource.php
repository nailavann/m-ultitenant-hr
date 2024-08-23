<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Adress\AdressesResource;
use App\Http\Resources\Company\CompanyResource;
use App\Http\Resources\Department\DepartmentResource;
use App\Http\Resources\Education\EducationResource;
use App\Http\Resources\Emergency\EmergenciesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'phone' => $this->phone,
            'positionStartedAt' => $this->position_started_at,
            'birthDate' => $this->birth_date,
            'gender' => $this->gender ? 'Erkek' : 'Kadın',
            'bloodType' => $this->blood_type,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'company' => CompanyResource::make($this->whenLoaded('company')),
        ];
    }
}
