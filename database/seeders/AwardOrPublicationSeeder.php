<?php

namespace Database\Seeders;

use App\Models\AwardOrPublication;
use App\Models\Project;
use Illuminate\Database\Seeder;

class AwardOrPublicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            [
                'project_slug' => 'ecole-lumiere-lyon',
                'entry_type' => 'award',
                'title' => 'Prix Regional Bois Construction',
                'issuer_or_publisher' => 'Ordre des Architectes Auvergne-Rhone-Alpes',
                'url' => 'https://example.com/prix-bois-construction',
                'published_on' => '2025-11-20',
                'excerpt' => 'Distinction pour la qualite environnementale et la sobrite constructive du projet scolaire.',
                'display_order' => 1,
            ],
            [
                'project_slug' => 'atelier-des-tisserands',
                'entry_type' => 'publication',
                'title' => 'Rehabiliter l industriel: Atelier des Tisserands',
                'issuer_or_publisher' => 'Revue AMC',
                'url' => 'https://example.com/amc-atelier-tisserands',
                'published_on' => '2024-09-01',
                'excerpt' => 'Article sur la reconversion des friches productives en equipements creatifs.',
                'display_order' => 1,
            ],
            [
                'project_slug' => 'hotel-de-ville-annexe',
                'entry_type' => 'publication',
                'title' => 'Moderniser le patrimoine civique',
                'issuer_or_publisher' => 'Le Moniteur',
                'url' => 'https://example.com/moniteur-patrimoine-civique',
                'published_on' => '2025-05-14',
                'excerpt' => 'Retour d experience sur un chantier en site occupe pour un equipement public.',
                'display_order' => 1,
            ],
        ];

        foreach ($entries as $entry) {
            $project = Project::query()->where('slug', $entry['project_slug'])->first();

            if (! $project) {
                continue;
            }

            AwardOrPublication::query()->updateOrCreate(
                [
                    'project_id' => $project->id,
                    'title' => $entry['title'],
                ],
                [
                    'entry_type' => $entry['entry_type'],
                    'issuer_or_publisher' => $entry['issuer_or_publisher'],
                    'url' => $entry['url'],
                    'published_on' => $entry['published_on'],
                    'excerpt' => $entry['excerpt'],
                    'display_order' => $entry['display_order'],
                ]
            );
        }
    }
}
