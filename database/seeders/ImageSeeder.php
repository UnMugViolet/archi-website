<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        $projectFiles = collect(Storage::disk('public')->allFiles('projects'))
            ->filter(function (string $path) use ($allowedExtensions): bool {
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                return in_array($extension, $allowedExtensions, true);
            })
            ->values()
            ->all();

        usort($projectFiles, 'strnatcasecmp');

        $groupedByFolder = [];

        foreach ($projectFiles as $path) {
            $segments = explode('/', $path);

            if (count($segments) < 3) {
                continue;
            }

            $folder = $segments[1];
            $groupedByFolder[$folder][] = $path;
        }

        foreach ($groupedByFolder as $folder => $paths) {
            $projectId = (int) $folder;

            if ($projectId <= 0) {
                continue;
            }

            $project = Project::query()->find($projectId);

            if (! $project) {
                continue;
            }

            $seededPaths = [];

            foreach ($paths as $index => $path) {
                $seededPaths[] = $path;
                $baseName = pathinfo($path, PATHINFO_FILENAME);
                $altText = Str::title(str_replace(['-', '_'], ' ', $baseName));

                Image::query()->updateOrCreate(
                    [
                        'imageable_type' => Project::class,
                        'imageable_id' => $project->id,
                        'path' => $path,
                    ],
                    [
                        'disk' => 'public',
                        'alt_text' => $altText,
                        'caption' => null,
                        'display_order' => $index + 1,
                        'is_thumbnail' => $index === 0,
                    ]
                );
            }

            Image::query()
                ->where('imageable_type', Project::class)
                ->where('imageable_id', $project->id)
                ->whereNotIn('path', $seededPaths)
                ->delete();
        }

        Image::query()
            ->where('imageable_type', '!=', Project::class)
            ->delete();
    }
}
