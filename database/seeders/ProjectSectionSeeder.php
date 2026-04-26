<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectSection;
use Illuminate\Database\Seeder;

class ProjectSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectionsByProject = [
            'ecole-lumiere-lyon' => [
                [
                    'title' => 'Concept',
                    'section_type' => 'concept',
                    'content' => 'Le projet articule les salles de classe autour d un patio central plante pour favoriser la lumiere naturelle.',
                    'display_order' => 1,
                ],
                [
                    'title' => 'Materiaux',
                    'section_type' => 'materials',
                    'content' => 'Structure bois, isolation biosourcee et facades mineralises en teinte claire.',
                    'display_order' => 2,
                ],
                [
                    'title' => 'Approche environnementale',
                    'section_type' => 'sustainability',
                    'content' => 'Ventilation traversante, recuperation des eaux pluviales et gestion de l ombrage en cour.',
                    'display_order' => 3,
                ],
            ],
            'atelier-des-tisserands' => [
                [
                    'title' => 'Etat existant',
                    'section_type' => 'existing',
                    'content' => 'L enveloppe en briques et charpente metallique est conservee et restauree.',
                    'display_order' => 1,
                ],
                [
                    'title' => 'Transformation',
                    'section_type' => 'process',
                    'content' => 'Insertion d un volume interieur autonome pour limiter les interventions sur le bati historique.',
                    'display_order' => 2,
                ],
            ],
            'residence-belvedere' => [
                [
                    'title' => 'Plan masse',
                    'section_type' => 'urbanism',
                    'content' => 'Implantation en peigne pour ouvrir des vues et limiter les vis a vis.',
                    'display_order' => 1,
                ],
                [
                    'title' => 'Usage',
                    'section_type' => 'program',
                    'content' => 'Logements traversants avec espaces exterieurs privatifs et communs partages.',
                    'display_order' => 2,
                ],
            ],
            'hotel-de-ville-annexe' => [
                [
                    'title' => 'Diagnostic',
                    'section_type' => 'diagnosis',
                    'content' => 'Reprise structurelle ciblee et rationalisation des circulations verticales.',
                    'display_order' => 1,
                ],
                [
                    'title' => 'Mise en oeuvre',
                    'section_type' => 'process',
                    'content' => 'Phasage en site occupe pour maintenir les services publics pendant les travaux.',
                    'display_order' => 2,
                ],
            ],
        ];

        foreach ($sectionsByProject as $projectSlug => $sections) {
            $project = Project::query()->where('slug', $projectSlug)->first();

            if (! $project) {
                continue;
            }

            foreach ($sections as $section) {
                ProjectSection::query()->updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'title' => $section['title'],
                    ],
                    [
                        'section_type' => $section['section_type'],
                        'content' => $section['content'],
                        'display_order' => $section['display_order'],
                    ]
                );
            }
        }
    }
}
