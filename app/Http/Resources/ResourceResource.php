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
            'type' => ClassifyResource::make($this->type),
            'category' => ClassifyResource::make($this->category),
            'views' => $this->views,
            'richTextContent' => $this->richTextContent,
            'mediaUrl' => $this->mediaUrl,
            'mediaLink' => $this->mediaLink,
            'status' => $this->status,
            'scope' => ClassifyResource::collection($this->shared()->get()),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'deletedAt' => $this->deleted_at,
            'readLater' => IDResource::collection($this->read_later()->get()),
            'favorites' => IDResource::collection($this->favorites()->get()),
            'exploited' => IDResource::collection($this->exploited()->get()),
            'author' => UserResource::make($this->user),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
