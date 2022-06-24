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
    public function run()
    {
        $relationType = [
            'conjoint(e)',
            'ami(e)',
            'famille',
            'professionnel',
            'aucun',
        ];

        foreach ($relationType as $relation_type) {
            $resourceType = new RelationType();
            $resourceType->name = $relation_type;
            $resourceType->save();
        }

        foreach (User::all() as $user) {

            foreach (RelationType::all() as $relation_type) {

                for ($i = 0; $i <= 30; $i++) {
                    $second_user = User::find(rand(1, 10));

                    $relation = DB::table('relations')
                        ->whereIn('first_user_id', [$user->id, $second_user->id])
                        ->whereIn('second_user_id', [$user->id, $second_user->id])
                        ->exists();

                    if (!$relation) {
                        $relation = new Relation;
                        $relation->first_user_id = $user->id;
                        $relation->second_user_id = $second_user->id;
                        $relation->relation_type_id = $relation_type->id;
                        $relation->save();
                    }
                }
            }
        }
    }
}
