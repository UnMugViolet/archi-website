<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $this->call([
                CategorySeeder::class,
                ProjectSeeder::class,
                ProjectMetricSeeder::class,
                ProjectSectionSeeder::class,
                AwardOrPublicationSeeder::class,
                ImageSeeder::class,
            ]);
        });
    }
}
