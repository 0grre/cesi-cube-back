<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProgressionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 0; $i <= 25; $i++) {
            $user = User::inRandomOrder()->limit(1)->first();
            $resource = Resource::inRandomOrder()->limit(1)->first();

            if (!$user->favorites()->where('resource_id', $resource->id)->exists())
            {
                $user->favorites()->attach($resource);
            }
        }
        for ($i = 0; $i <= 25; $i++) {
            $user = User::inRandomOrder()->limit(1)->first();
            $resource = Resource::inRandomOrder()->limit(1)->first();

            if (!$user->read_later()->where('resource_id', $resource->id)->exists())
            {
                $user->read_later()->attach($resource);
            }
        }
    }
}
