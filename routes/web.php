<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index'])->name('home');

Route::get('/{project:slug}', [ProjectController::class, 'show'])
    ->where('project', '^(?!admin|access-admin-fanny|dashboard|orchid).*')
    ->name('projects.show');
