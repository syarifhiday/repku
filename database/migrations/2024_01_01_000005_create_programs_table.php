<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('goal', [
                'kecilin_perut',
                'kecilin_bokong',
                'full_body_muscle',
                'menguruskan_badan',
                'membesarkan_badan',
                'custom',
            ])->default('custom');
            $table->text('description')->nullable();
            $table->integer('duration_weeks')->default(8);
            $table->integer('sessions_per_week')->default(3);
            $table->enum('type', ['preset', 'custom'])->default('preset');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
