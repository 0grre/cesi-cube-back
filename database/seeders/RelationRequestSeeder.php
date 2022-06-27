<?php

namespace Database\Seeders;

use App\Models\RelationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $status = [
            'pending',
            'accepted',
            'rejected'
        ];

        foreach (User::all() as $user) {

            for ($i = 0; $i <= 50; $i++) {
                $second_user = User::inRandomOrder()->limit(1)->first();

                $relation_request = DB::table('relations')
                    ->whereIn('first_user_id', [$user->id, $second_user->id])
                    ->whereIn('second_user_id', [$user->id, $second_user->id])
                    ->exists();

                if (!$relation_request and $user->id != $second_user->id) {
                    $relation_request = new RelationRequest();
                    $relation_request->status = $status[rand(0,2)];
                    $relation_request->first_user_id = $user->id;
                    $relation_request->second_user_id = $second_user->id;
                    $relation_request->save();
                }
            }
        }
    }
}
