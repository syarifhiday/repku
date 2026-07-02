<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('set_number');
            $table->enum('weight_unit', ['kg', 'band_level'])->default('kg');
            $table->decimal('weight_value', 6, 2)->nullable(); // KG, atau "level" karet resistance band
            $table->string('band_color')->nullable(); // misal: hijau, biru, hitam (urutan resistensi)
            $table->integer('reps');
            $table->integer('rpe')->nullable(); // rate of perceived exertion 1-10, opsional
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_logs');
    }
};
