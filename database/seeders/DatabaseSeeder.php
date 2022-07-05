<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ClassifySeeder::class,
            ResourceSeeder::class,
            CommentSeeder::class,
            RelationSeeder::class,
            ProgressionUserSeeder::class,
        ]);
    }
}
