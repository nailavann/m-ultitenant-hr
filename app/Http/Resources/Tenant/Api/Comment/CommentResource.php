<?php

namespace App\Http\Resources\Tenant\Api\Comment;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this->text,
            'user' => UserResource::make($this->user),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
