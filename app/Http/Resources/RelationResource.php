<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class RelationResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'relationType' => $this->relation_type->name,
            'firstUser' => UserResource::make($this->first_user),
            'secondUser' => UserResource::make($this->second_user),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
