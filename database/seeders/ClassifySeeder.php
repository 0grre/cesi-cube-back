<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RelationType;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $categories = [
            'Communication',
            'Cultures',
            'Développement personnel',
            'Intelligence émotionnelle',
            'Loisirs',
            'Monde professionnel',
            'Parentalité',
            'Qualité de vie',
            'Recherche de sens',
            'Santé physique',
            'Santé psychique',
            'Spiritualité',
            'Vie affective'
        ];

        $types = [
            'Activité / Jeu à réaliser Article',
            'Carte défi',
            'Cours au format PDF Exercice / Atelier Fiche de lecture',
            'Jeu Vidéo en ligne'
        ];

        $relation_types = [
            'conjoint(e)',
            'ami(e)',
            'famille',
            'professionnel',
            'aucun',
        ];

        foreach($categories as $category){
            $resourceCategory = new Category();
            $resourceCategory->name = $category;
            $resourceCategory->save();
        }

        foreach($types as $type){
            $resourceType = new Type();
            $resourceType->name = $type;
            $resourceType->save();
        }

        foreach ($relation_types as $relation_type) {
            $resourceType = new RelationType();
            $resourceType->name = $relation_type;
            $resourceType->save();
        }
    }
}
