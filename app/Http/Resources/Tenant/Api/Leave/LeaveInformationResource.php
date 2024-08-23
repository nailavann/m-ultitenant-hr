<?php

namespace App\Http\Resources\Tenant\Api\Leave;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaveInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'entitlement' => $this->entitlement,
            'usedDays' => $this->used_days,
            'remainingDays' => $this->remaining_days,
            'carryOverDays' => $this->carryover_days,
        ];
    }
}
