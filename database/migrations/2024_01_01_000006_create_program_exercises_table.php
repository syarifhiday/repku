<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('day_number')->default(1); // hari ke-berapa dalam siklus mingguan
            $table->integer('order')->default(1);
            $table->integer('target_sets')->default(3);
            $table->integer('target_reps')->default(12);
            $table->decimal('suggested_start_weight', 6, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_exercises');
    }
};
