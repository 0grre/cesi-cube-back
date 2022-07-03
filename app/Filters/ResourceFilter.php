<?php

namespace App\Filters;

use Omalizadeh\QueryFilter\ModelFilter;

class ResourceFilter extends ModelFilter
{
    /**
     * Model selectable attributes. these attributes can be selected alone.
     */
    protected function selectableAttributes(): array
    {
        return [
            'id',
            'title',
            'views',
            'richTextContent',
            'mediaUrl',
            'status',
            'scope',
            'type_id',
            'category_id',
            'user_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Model sortable attributes.
     */
    protected function sortableAttributes(): array
    {
        return [
            //
        ];
    }

    /**
     * Model summable attributes.
     */
    protected function summableAttributes(): array
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Model filterable attributes.
     */
    protected function filterableAttributes(): array
    {
        return [
            'id',
            'title',
            'views',
            'richTextContent',
            'mediaUrl',
            'status',
            'scope',
            'type_id',
            'category_id',
            'user_id',
            'created_at',
            'updated_at',
            'deleted_at',
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
            'users' => [
                'user_id' => 'user_id',
            ],
        ];
    }

    /**
     * Relations data that can be requested with model objects.
     */
    protected function loadableRelations(): array
    {
        return [
            'users',
        ];
    }
}
