<?php

namespace App\Http\Resources\Tenant\Api\Post;

use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
           'image' => $this->image,
           'isLiked' => $this->isLiked,
           'user' => UserResource::make($this->user),
           'comments_count' => $this->comments_count,
           'likes_count' => $this->likes_count,
           'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
       ];
    }
}
