<?php

namespace App\Http\Resources\Tenant\Api\News;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->haber_id,
            'title' => $this->haber_manset,
            'description' => $this->haber_aciklama,
            'content' => $this->haber_metni,
            'category' => $this->haber_kategorisi,
            'date' => Carbon::parse($this->haber_tarihi)->diffForHumans(),
            'image' => $this->manset_resim
        ];
    }
}
