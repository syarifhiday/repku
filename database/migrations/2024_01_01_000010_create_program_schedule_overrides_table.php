<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_schedule_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_enrollment_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            // workout = paksa workout di hari rest, rest = ganti workout jadi rest, skip = absen workout
            $table->enum('override_type', ['workout', 'rest', 'skip']);
            $table->integer('day_number')->nullable(); // kalau override ke workout, pakai day berapa
            $table->text('notes')->nullable(); // catatan harian per tanggal
            $table->unique(['program_enrollment_id', 'date']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_schedule_overrides');
    }
};
