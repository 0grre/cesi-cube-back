<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'readLater' => UserIdResource::collection($this->read_later),
            'favorites' => UserIdResource::collection($this->favorites),
            'exploited' => UserIdResource::collection($this->exploited),
            'author' => UserResource::make($this->user),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
