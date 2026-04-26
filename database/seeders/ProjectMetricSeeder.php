<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectMetric;
use Illuminate\Database\Seeder;

class ProjectMetricSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metricsByProject = [
            'ecole-lumiere-lyon' => [
                ['label' => 'Surface', 'value' => '3200', 'unit' => 'm2', 'display_order' => 1],
                ['label' => 'Livraison', 'value' => '2025', 'unit' => null, 'display_order' => 2],
                ['label' => 'Budget', 'value' => '5.8', 'unit' => 'MEUR', 'display_order' => 3],
            ],
            'atelier-des-tisserands' => [
                ['label' => 'Surface', 'value' => '2100', 'unit' => 'm2', 'display_order' => 1],
                ['label' => 'Livraison', 'value' => '2024', 'unit' => null, 'display_order' => 2],
                ['label' => 'Budget', 'value' => '3.1', 'unit' => 'MEUR', 'display_order' => 3],
            ],
            'residence-belvedere' => [
                ['label' => 'Logements', 'value' => '64', 'unit' => null, 'display_order' => 1],
                ['label' => 'Surface', 'value' => '5400', 'unit' => 'm2', 'display_order' => 2],
                ['label' => 'Livraison', 'value' => '2026', 'unit' => null, 'display_order' => 3],
            ],
            'hotel-de-ville-annexe' => [
                ['label' => 'Surface', 'value' => '2800', 'unit' => 'm2', 'display_order' => 1],
                ['label' => 'Livraison', 'value' => '2025', 'unit' => null, 'display_order' => 2],
                ['label' => 'Economie energie', 'value' => '42', 'unit' => '%', 'display_order' => 3],
            ],
        ];

        foreach ($metricsByProject as $projectSlug => $metrics) {
            $project = Project::query()->where('slug', $projectSlug)->first();

            if (! $project) {
                continue;
            }

            foreach ($metrics as $metric) {
                ProjectMetric::query()->updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'label' => $metric['label'],
                    ],
                    [
                        'value' => $metric['value'],
                        'unit' => $metric['unit'],
                        'display_order' => $metric['display_order'],
                    ]
                );
            }
        }
    }
}
