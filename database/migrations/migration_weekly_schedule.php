<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Array 7 elemen index 0=Senin s/d 6=Minggu
            // Nilai: integer = nomor program day, null = rest day
            // Contoh Mon/Tue/Thu/Fri: [1, 2, null, 3, 4, null, null]
            // Contoh Mon/Wed/Fri: [1, null, 2, null, 3, null, null]
            // null = pakai logic lama (berurutan)
            $table->json('weekly_schedule')->nullable()->after('equipment_tags');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('weekly_schedule');
        });
    }
};
