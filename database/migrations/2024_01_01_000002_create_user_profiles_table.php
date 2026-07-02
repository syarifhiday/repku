<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('gender', ['pria', 'wanita'])->nullable();
            $table->date('birthdate')->nullable();
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->enum('activity_level', ['sangat_jarang', 'jarang', 'sedang', 'aktif', 'sangat_aktif'])->nullable();
            $table->enum('training_location', ['gym', 'rumah', 'keduanya'])->default('keduanya');
            $table->enum('equipment_access', ['dumbell', 'resistance_band', 'keduanya', 'tidak_ada'])->default('keduanya');
            $table->enum('experience_level', ['pemula', 'menengah', 'lanjutan'])->default('pemula');
            $table->text('injury_notes')->nullable();
            $table->text('goal_notes')->nullable();
            $table->decimal('target_weight_kg', 5, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
