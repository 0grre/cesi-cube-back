<?php

namespace App\Filters;

use JetBrains\PhpStorm\ArrayShape;
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
            'mediaLink',
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
            'id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Model summable attributes.
     */
    protected function summableAttributes(): array
    {
        return [
            //
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
     * @return array
     */
    #[ArrayShape([
        'user' => "string[]",
        'type' => "string[]",
        'category' => "string[]",
        'shared' => "string[]",
    ])]
    protected function filterableRelations(): array
    {
        return [
            'user' => [
                'firstname',
                'lastname',
                'email',
            ],
            'type' => [
                'type_name' => 'name',
            ],
            'category' => [
                'category_name' => 'name',
            ],
            'shared' => [
                'relation_type_name' => 'name',
            ],
        ];
    }

    /**
     * Relations data that can be requested with model objects.
     */
    protected function loadableRelations(): array
    {
        return [
            'user',
            'type',
            'category',
            'shared',
        ];
    }
}
