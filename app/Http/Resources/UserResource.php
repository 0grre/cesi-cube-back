<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'avatar' => $this->avatar,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'zipCode' => $this->zipCode,
            'city' => $this->city,
            'primaryPhone' => $this->primaryPhone,
            'secondaryPhone' => $this->secondaryPhone,
            'birthDate' => $this->birthDate,
            'createdAt' => $this->created_at->format('d/m/Y'),
            'updatedAt' => $this->updated_at->format('d/m/Y'),
            'disableAt' => $this->disable_at ? $this->disable_at->format('d/m/Y') : $this->disable_at,
            'roles' => RoleResource::collection($this->roles),
        ];
    }
}
