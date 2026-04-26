<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Ecoles',
                'slug' => 'ecoles',
                'description' => 'Projets scolaires: extension, reconstruction et modernisation des espaces d apprentissage.',
                'meta_title' => 'Projets d ecoles et campus',
                'meta_description' => 'Selection de projets architecturaux pour ecoles et campus en France.',
                'status' => 'published',
            ],
            [
                'name' => 'Renovation',
                'slug' => 'renovation',
                'description' => 'Renovations de batiments existants avec une approche sobre et durable.',
                'meta_title' => 'Renovation architecturale',
                'meta_description' => 'Transformation et revalorisation du patrimoine bati.',
                'status' => 'published',
            ],
            [
                'name' => 'Habitat Collectif',
                'slug' => 'habitat-collectif',
                'description' => 'Ensembles residentiels conciliant usage, lumiere et qualite d usage.',
                'meta_title' => 'Projets d habitat collectif',
                'meta_description' => 'Architecture residentielle contemporaine en milieu urbain.',
                'status' => 'published',
            ],
            [
                'name' => 'Patrimoine',
                'slug' => 'patrimoine',
                'description' => 'Interventions sur des sites historiques avec respect de l existant.',
                'meta_title' => 'Architecture et patrimoine',
                'meta_description' => 'Projets de restauration et adaptation du patrimoine.',
                'status' => 'published',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
