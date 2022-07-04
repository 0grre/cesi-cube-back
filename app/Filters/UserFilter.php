<?php

namespace App\Filters;

use JetBrains\PhpStorm\ArrayShape;
use Omalizadeh\QueryFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
     * @return string[]
     */
    protected function selectableAttributes(): array
    {
        return [
            'id',
            'email',
            'avatar',
            'firstname',
            'lastname',
            'address1',
            'address2',
            'zipCode',
            'city',
            'primaryPhone',
            'secondaryPhone',
            'birthDate',
            'created_at',
            'updated_at',
            'disabled_at',
        ];
    }

    /**
     * @return string[]
     */
    protected function sortableAttributes(): array
    {
        return [
            'id',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * @return array
     */
    protected function summableAttributes(): array
    {
        return [
            //
        ];
    }

    /**
     * @return string[]
     */
    protected function filterableAttributes(): array
    {
        return [
            'id',
            'email',
            'avatar',
            'firstname',
            'lastname',
            'address1',
            'address2',
            'zipCode',
            'city',
            'primaryPhone',
            'secondaryPhone',
            'birthDate',
            'created_at',
            'updated_at',
            'disabled_at',
        ];
    }

    /**
     * @return string[][]
     */
    #[ArrayShape([
        'roles' => "string[]",
    ])]
    protected function filterableRelations(): array
    {
        return [
            'roles' => [
                'name' => 'name',
            ],
        ];
    }

    /**
     * @return string[]
     */
    protected function loadableRelations(): array
    {
        return [
            'roles'
        ];
    }
}
