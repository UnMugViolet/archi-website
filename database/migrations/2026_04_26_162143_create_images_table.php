<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->morphs('imageable');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('alt_text')->nullable();
            $table->text('caption')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_thumbnail')->default(false);
            $table->timestamps();

            $table->index(['imageable_type', 'imageable_id', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
