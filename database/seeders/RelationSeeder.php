<?php

namespace Database\Seeders;

use App\Models\Relation;
use App\Models\RelationType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        foreach (User::all() as $user) {
            for ($i = 0; $i <= 5; $i++) {
                $second_user = User::inRandomOrder()->limit(1)->first();
                $relation_type = RelationType::inRandomOrder()->limit(1)->first();

                $relation_check = DB::table('relations')
                    ->where('relation_type_id', $relation_type->id)
                    ->whereIn('first_user_id', [$user->id, $second_user->id])
                    ->whereIn('second_user_id', [$user->id, $second_user->id])
                    ->exists();

                if (!$relation_check) {
                    $relation = new Relation;
                    $relation->is_accepted = rand(0, 1);
                    $relation->first_user_id = $user->id;
                    $relation->second_user_id = $second_user->id;
                    $relation->relation_type_id = $relation_type->id;
                    $relation->save();
                }
            }
        }
    }
}
