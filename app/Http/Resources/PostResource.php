<?php

namespace App\Http\Resources;

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
            "id" => $this->id,
            "creator_id" => $this->creator_id,
            "auther" => $this->profile->user_name,
            "profile_id" => $this->profile_id,
            "content" => $this->content,
            "image" => $this->image,
            "comments_count" => $this->comments()->count(),
            "reactions_count" => $this->reaction()->count(),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
