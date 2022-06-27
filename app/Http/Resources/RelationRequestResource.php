<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

class RelationRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    #[ArrayShape(['id' => "mixed",
        'status' => "mixed",
        'first_user' => "mixed",
        'second_user' => "mixed",
        'created_at' => "mixed",
        'updated_at' => "mixed"
    ])]
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'first_user' => $this->first_user,
            'second_user' => $this->second_user,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
