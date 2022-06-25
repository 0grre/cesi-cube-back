<?php

namespace Database\Seeders;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run(): void
    {
        $status = [
            'pending',
            'accepted',
            'rejected'
        ];

        $scopes = [
            'private',
            'public',
            'shared'
        ];

        foreach (User::all() as $user) {
            for ($i = 0; $i < 5; $i++) {
                $resource = new Resource();
                $resource->title = "Lorem ipsum dolor";
                $resource->views = rand(1, 99);
                $resource->richTextContent = "Lorem ipsum dolor sit amet, consectetur adipiscing, link test incididunt,
                ut labore et dolore magna aliqua. Vitae sapien pellentesque habitant morbi tristique senectus et.
                Diam maecenas sed enim ut. Accumsan lacus vel facilisis volutpat est. Ut aliquam purus sit amet luctus.
                Lorem ipsum dolor sit amet consectetur adipiscing elit ut.";
                $resource->tags = "test tag";
                $resource->status = $status[rand(0,2)];
                $resource->scope = $scopes[rand(0,2)];
                $resource->is_exploited = true;
                $resource->type_id = rand(1, 4);
                $resource->category_id = rand(1, 13);
                $resource->user_id = $user->id;
                $resource->save();
            }
        }
    }
}
