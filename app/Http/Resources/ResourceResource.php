<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type->name,
            'category' => $this->category->name,
            'views' => $this->views,
            'richTextContent' => $this->richTextContent,
            'mediaUrl' => $this->mediaUrl,
            'status' => $this->status,
            'scope' => $this->scope,
            'createdAt' => $this->created_at->format('d/m/Y'),
            'updatedAt' => $this->updated_at->format('d/m/Y'),
            'author' => UserResource::make($this->user),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
