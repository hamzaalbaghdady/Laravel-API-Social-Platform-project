<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comments' => $this->collection->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'creator_id' => $comment->creator_id,
                    'post_id' => $comment->post_id,
                    'parent_id' => $comment->parent_id,
                    "reactions_count" => $comment->reaction()->count(),
                    'created_at' => $comment->created_at,
                    'updated_at' => $comment->updated_at,
                ];
            }),
            'count' => $this->count()
        ];
    }
}
