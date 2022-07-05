<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class RelationResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    #[ArrayShape([
        'id' => "mixed",
        'is_accepted' => "mixed",
        'relationType' => "mixed",
        'firstUser' => "mixed",
        'secondUser' => "mixed",
        'createdAt' => "mixed",
        'updatedAt' => "mixed"
    ])]
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'is_accepted' => $this->status,
            'relationType' => $this->relation_type->name,
            'firstUser' => UserResource::make($this->first_user),
            'secondUser' => UserResource::make($this->second_user),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
