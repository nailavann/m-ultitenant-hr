<?php

namespace App\Http\Resources\Tenant\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
           'name' => $this->name,
           'type' => $this->type,
           'url' => $this->path,
           'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
       ];
    }
}
