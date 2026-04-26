<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Ecole Lumiere Lyon',
                'slug' => 'ecole-lumiere-lyon',
                'description' => 'Restructuration d une ecole primaire avec extension bois et nouvelle cour permeable.',
                'meta_title' => 'Ecole Lumiere Lyon',
                'meta_description' => 'Projet scolaire a Lyon: extension, performance thermique et qualite d usage.',
                'status' => 'published',
            ],
            [
                'title' => 'Atelier des Tisserands',
                'slug' => 'atelier-des-tisserands',
                'description' => 'Rehabilitation d un ancien atelier industriel en espaces creatifs et bureaux.',
                'meta_title' => 'Atelier des Tisserands',
                'meta_description' => 'Transformation d un batiment industriel en lieu mixte a usage culturel.',
                'status' => 'published',
            ],
            [
                'title' => 'Residence Belvedere',
                'slug' => 'residence-belvedere',
                'description' => 'Immeuble de logements intermediaires avec loggias, patios et toitures plantees.',
                'meta_title' => 'Residence Belvedere',
                'meta_description' => 'Projet d habitat collectif avec attention portee au confort d ete.',
                'status' => 'published',
            ],
            [
                'title' => 'Hotel de Ville Annexe',
                'slug' => 'hotel-de-ville-annexe',
                'description' => 'Renovation lourde d un batiment public des annees 30 avec accesibilite complete.',
                'meta_title' => 'Hotel de Ville Annexe',
                'meta_description' => 'Renovation d un equipement public et remise aux normes environnementales.',
                'status' => 'published',
            ],
        ];

        foreach ($projects as $project) {
            Project::query()->updateOrCreate(
                ['slug' => $project['slug']],
                $project
            );
        }

        $projectCategories = [
            'ecole-lumiere-lyon' => [
                'ecoles' => 1,
                'renovation' => 2,
            ],
            'atelier-des-tisserands' => [
                'renovation' => 1,
                'patrimoine' => 2,
            ],
            'residence-belvedere' => [
                'habitat-collectif' => 1,
            ],
            'hotel-de-ville-annexe' => [
                'renovation' => 1,
                'patrimoine' => 2,
            ],
        ];

        foreach ($projectCategories as $projectSlug => $categoriesWithOrder) {
            $project = Project::query()->where('slug', $projectSlug)->first();

            if (! $project) {
                continue;
            }

            $syncPayload = [];

            foreach ($categoriesWithOrder as $categorySlug => $displayOrder) {
                $category = Category::query()->where('slug', $categorySlug)->first();

                if ($category) {
                    $syncPayload[$category->id] = ['display_order' => $displayOrder];
                }
            }

            if ($syncPayload !== []) {
                $project->categories()->sync($syncPayload);
            }
        }
    }
}
