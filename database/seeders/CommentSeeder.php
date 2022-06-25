<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (Resource::all() as $resource) {
            foreach (User::all() as $user) {
                $comment = new Comment;
                $comment->content = 'lorem ipsum commentaire test';
                $comment->resource_id = $resource->id;
                $comment->user_id = $user->id;
                $comment->save();
            }
        }
    }
}
