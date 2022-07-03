<?php

namespace App\Filters;

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
     * Attributes on relations that can be filtered.
     *
     * 'relation_name' => [
     *      'filter_key' => 'db_column_name',
     *  ],
     * 'relation_name' => [
     *      'filter_key_and_db_column_name',
     *  ],
     */
    protected function filterableRelations(): array
    {
        return [
            'role' => [
                'filter_key' => 'db_column_name',
            ],
            'relations' => [
                'filter_key_and_db_column_name',
            ],
        ];
    }

    /**
     * @return string[]
     */
    protected function loadableRelations(): array
    {
        return [
            'role'
        ];
    }
}
