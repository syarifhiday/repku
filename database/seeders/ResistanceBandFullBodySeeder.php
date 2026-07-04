<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResistanceBandFullBodySeeder extends Seeder
{
    public function run(): void
    {
        $ex = function (string $name): int {
            return Exercise::where('name', $name)->firstOrFail()->id;
        };

        $slug = Str::slug('Full Body Resistance Band 4x Week Upper Lower');

        $program = Program::updateOrCreate(
            ['slug' => $slug],
            [
                'name'              => 'Full Body Resistance Band — Upper/Lower 4x/Minggu',
                'slug'              => $slug,
                'goal'              => 'full_body_muscle',
                'description'       => 'Program Upper/Lower Split 4x seminggu menggunakan resistance band. Senin & Kamis = Upper Body (dada, punggung, bahu, lengan), Selasa & Jumat = Lower Body (kaki, glute, core). Rabu, Sabtu, Minggu = Rest. Berdasarkan prinsip melatih tiap otot 2x/minggu untuk hipertrofi dan kekuatan optimal dengan alat minimal.',
                'duration_weeks'    => 8,
                'sessions_per_week' => 4,
                'type'              => 'preset',
                'is_active'         => true,
                'location_type'     => 'rumah',
                'equipment_tags'    => ['resistance_band'],
                'cover_image_path'  => null,
            ]
        );

        $program->programExercises()->delete();

        $days = [
            // ── DAY 1: SENIN — Upper Body A ─────────────────────────
            // Fokus: Dada + Punggung (push & pull horizontal)
            // Volume: 4 gerakan utama + 2 isolasi
            1 => [
                // Push horizontal
                ['Resistance Band Chest Press',         4, 12],
                ['Resistance Band Fly',                 3, 15],
                // Pull horizontal
                ['Resistance Band Row',                 4, 12],
                ['Resistance Band Pull-Apart',          3, 15],
                // Bahu
                ['Resistance Band Shoulder Press',      3, 12],
                ['Resistance Band Lateral Raise',       3, 15],
                // Arm finish
                ['Resistance Band Bicep Curl',          3, 12],
                ['Resistance Band Tricep Pushdown',     3, 12],
            ],

            // ── DAY 2: SELASA — Lower Body A ────────────────────────
            // Fokus: Quad + Glute dominan
            2 => [
                // Compound lower
                ['Resistance Band Squat',               4, 15],
                ['Resistance Band Hip Thrust',          4, 15],
                ['Resistance Band Romanian Deadlift',   3, 12],
                // Isolasi glute
                ['Resistance Band Lateral Walk',        3, 20],
                ['Resistance Band Kickback',            3, 15],
                ['Resistance Band Clamshell',           3, 20],
                // Core
                ['Plank',                               3, 45],
                ['Resistance Band Pallof Press',        3, 12],
            ],

            // ── DAY 3: KAMIS — Upper Body B ─────────────────────────
            // Fokus: Punggung + Bahu (pull vertikal & rear delt)
            // Sedikit berbeda dari Day 1 untuk variasi stimulus
            3 => [
                // Pull vertikal (simulasi lat pulldown)
                ['Resistance Band Row',                 4, 10],
                ['Resistance Band Face Pull',           4, 15],
                ['Resistance Band Pull-Apart',          3, 20],
                // Push overhead
                ['Resistance Band Shoulder Press',      4, 10],
                ['Resistance Band Lateral Raise',       3, 15],
                // Chest
                ['Resistance Band Chest Press',         3, 12],
                // Arm finish
                ['Resistance Band Bicep Curl',          3, 15],
                ['Resistance Band Tricep Pushdown',     3, 15],
            ],

            // ── DAY 4: JUMAT — Lower Body B ─────────────────────────
            // Fokus: Hamstring + Glute dominan (lebih hip-hinge)
            4 => [
                // Hip hinge dominan
                ['Resistance Band Romanian Deadlift',   4, 12],
                ['Resistance Band Hip Thrust',          4, 20],
                // Squat variasi
                ['Resistance Band Squat',               3, 20],
                // Isolasi glute
                ['Resistance Band Clamshell',           3, 20],
                ['Resistance Band Lateral Walk',        3, 20],
                ['Resistance Band Kickback',            3, 15],
                // Core finish
                ['Resistance Band Woodchopper',         3, 12],
                ['Plank',                               3, 60],
            ],
        ];

        foreach ($days as $dayNumber => $exercises) {
            foreach ($exercises as $order => [$name, $sets, $reps]) {
                $program->programExercises()->create([
                    'exercise_id'  => $ex($name),
                    'day_number'   => $dayNumber,
                    'order'        => $order + 1,
                    'target_sets'  => $sets,
                    'target_reps'  => $reps,
                ]);
            }
        }

        $this->command->info('✓ Program Full Body Resistance Band 4x/minggu berhasil dibuat.');
    }
}