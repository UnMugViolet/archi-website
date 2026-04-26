<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class HomepageController extends Controller
{
    public function index(): Response
    {
        $projects = Project::query()
            ->where('status', 'published')
            ->with([
                'images' => function ($query): void {
                    $query->orderBy('display_order');
                },
            ])
            ->orderBy('id')
            ->get()
            ->map(function (Project $project): array {
                $coverImage = $project->images->firstWhere('is_thumbnail', true) ?? $project->images->first();

                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'cover_image' => $coverImage ? '/storage/'.$coverImage->path : null,
                ];
            })
            ->values();

        return Inertia::render('Homepage', [
            'projects' => $projects,
        ]);
    }
}
