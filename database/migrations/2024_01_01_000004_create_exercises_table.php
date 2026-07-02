<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('muscle_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('equipment_type', ['dumbell', 'resistance_band', 'bodyweight', 'lainnya'])->default('dumbell');
            $table->enum('difficulty', ['pemula', 'menengah', 'lanjutan'])->default('pemula');
            $table->text('description');
            $table->text('how_to')->nullable();
            $table->string('illustration_path')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('weight_unit_default', ['kg', 'band_level'])->default('kg');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
