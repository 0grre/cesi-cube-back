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
        $relation_types = [
            'conjoint(e)',
            'ami(e)',
            'famille',
            'professionnel',
            'aucun',
        ];

        foreach ($relation_types as $relation_type) {
            $resourceType = new RelationType();
            $resourceType->name = $relation_type;
            $resourceType->save();
        }

        foreach (User::all() as $user) {

            $relation_requests = DB::table('relation_requests')
                ->where('first_user_id', 1)
                ->orWhere('second_user_id', 1)
                ->get();

            foreach ($relation_requests as $relation_request) {

                if ($relation_request->status = 'accepted') {
                    $second_user = User::inRandomOrder()->limit(1)->first();
                    $relation = new Relation;
                    $relation->first_user_id = $user->id;
                    $relation->second_user_id = $second_user->id;
                    $relation->relation_type_id = RelationType::inRandomOrder()->limit(1)->first()->id;
                    $relation->save();
                }
            }
        }
    }
}
