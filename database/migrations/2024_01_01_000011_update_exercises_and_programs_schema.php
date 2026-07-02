<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update equipment_type enum di exercises (tambah barbell, cable, mesin_gym, pull_up_bar, kettlebell)
        DB::statement("ALTER TABLE exercises MODIFY COLUMN equipment_type ENUM(
            'dumbell','barbell','cable','mesin_gym','resistance_band',
            'pull_up_bar','kettlebell','bodyweight','lainnya'
        ) NOT NULL DEFAULT 'bodyweight'");

        // Update goal enum di programs
        DB::statement("ALTER TABLE programs MODIFY COLUMN goal ENUM(
            'kecilin_perut','kecilin_bokong',
            'full_body_muscle','menguruskan_badan','membesarkan_badan',
            'latihan_perut','latihan_kaki','latihan_bokong_paha',
            'latihan_dada','latihan_bahu_lengan','latihan_punggung',
            'full_body','custom'
        ) NOT NULL DEFAULT 'custom'");

        // Tambah kolom baru ke programs
        Schema::table('programs', function (Blueprint $table) {
            $table->string('cover_image_path')->nullable()->after('is_active');
            $table->enum('location_type', ['gym', 'rumah', 'keduanya'])->default('keduanya')->after('cover_image_path');
            $table->json('equipment_tags')->nullable()->after('location_type');
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['cover_image_path', 'location_type', 'equipment_tags']);
        });
    }
};
