<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function show(Project $project): Response
    {
        abort_unless($project->status === 'published', 404);

        $project->load([
            'categories' => function ($query): void {
                $query->orderByPivot('display_order');
            },
            'images' => function ($query): void {
                $query->orderBy('display_order');
            },
            'sections' => function ($query): void {
                $query->orderBy('display_order');
            },
            'metrics' => function ($query): void {
                $query->orderBy('display_order');
            },
            'awardOrPublication' => function ($query): void {
                $query->orderBy('display_order');
            },
        ]);

        return Inertia::render('ProjectShow', [
            'project' => [
                'id' => $project->id,
                'title' => $project->title,
                'slug' => $project->slug,
                'description' => $project->description,
                'meta_title' => $project->meta_title,
                'meta_description' => $project->meta_description,
                'categories' => $project->categories->map(fn ($category): array => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ])->values(),
                'images' => $project->images->map(fn ($image): array => [
                    'id' => $image->id,
                    'path' => '/storage/'.$image->path,
                    'alt_text' => $image->alt_text,
                    'caption' => $image->caption,
                    'is_thumbnail' => (bool) $image->is_thumbnail,
                    'display_order' => $image->display_order,
                ])->values(),
                'sections' => $project->sections->map(fn ($section): array => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'section_type' => $section->section_type,
                    'content' => $section->content,
                    'display_order' => $section->display_order,
                ])->values(),
                'metrics' => $project->metrics->map(fn ($metric): array => [
                    'id' => $metric->id,
                    'label' => $metric->label,
                    'value' => $metric->value,
                    'unit' => $metric->unit,
                    'display_order' => $metric->display_order,
                ])->values(),
                'awards_or_publication' => $project->awardOrPublication->map(fn ($entry): array => [
                    'id' => $entry->id,
                    'entry_type' => $entry->entry_type,
                    'title' => $entry->title,
                    'issuer_or_publisher' => $entry->issuer_or_publisher,
                    'url' => $entry->url,
                    'published_on' => $entry->published_on,
                    'excerpt' => $entry->excerpt,
                    'display_order' => $entry->display_order,
                ])->values(),
            ],
        ]);
    }
}
