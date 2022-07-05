<?php

namespace Database\Seeders;

use App\Models\RelationType;
use App\Models\Resource;
use Illuminate\Database\Seeder;

class SharedResource extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 0; $i <= 30; $i++) {
            $relation_type = RelationType::inRandomOrder()->limit(1)->first();
            $resource = Resource::inRandomOrder()->limit(1)->first();

            if (!$resource->shared()->where('relation_type_id', $relation_type->id)->exists())
            {
                $resource->shared()->attach($relation_type);
            }
        }
    }
}
