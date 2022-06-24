<?php

namespace Database\Seeders;

use App\Models\Relation;
use App\Models\RelationType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        for ($i = 0; $i < 10; $i++) {
            $first_user = User::find(rand(1, 10));
            $second_user = User::find(rand(1, 10));
            $relation_type = RelationType::find(rand(1, 5));

            $relation = Relation::where('first_user_id', $first_user->id)
                ->orWhere('second_user_id', $first_user->id)
                ->where(function($query) use ($second_user) {
                    $query->where('first_user_id', $second_user->id)
                        ->orWhere('second_user_id', $second_user->id);
                })
                ->exists();

            if(!$relation){
                $relation = new Relation;
                $relation->first_user_id = $first_user->id;
                $relation->second_user_id = $second_user->id;
                $relation->relation_type_id = $relation_type->id;
                $relation->save();
            }
        }
    }
}
