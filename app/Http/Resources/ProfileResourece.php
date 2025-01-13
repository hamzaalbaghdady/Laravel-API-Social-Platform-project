<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResourece extends JsonResource
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
            'user_id' => $this->user_id,
            'user_name' => $this->user_name,
            'about' => $this->about,
            'phone_number' => $this->phone_number,
            'profile_image' => $this->profile_image,
            'location' => $this->location,
            'education' => $this->education,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'follwers' => $this->owner->followers->count(),
            'following' => $this->owner->following->count(),
        ];
    }
}
