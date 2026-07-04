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

        // weekly_schedule: index 0=Senin, 6=Minggu
        // Senin=Upper(1), Selasa=Lower(2), Rabu=Rest, Kamis=Upper(3), Jumat=Lower(4), Sabtu=Rest, Minggu=Rest
        $weeklySchedule = [1, 2, null, 3, 4, null, null];

        $program = Program::updateOrCreate(
            ['slug' => $slug],
            [
                'name'              => 'Full Body Resistance Band — Upper/Lower 4x/Minggu',
                'slug'              => $slug,
                'goal'              => 'full_body',
                'description'       => 'Program Upper/Lower Split 4x seminggu menggunakan resistance band. Senin & Kamis = Upper Body, Selasa & Jumat = Lower Body. Rabu, Sabtu, Minggu = Rest. Tidak ada 4 hari latihan berturut-turut.',
                'duration_weeks'    => 8,
                'sessions_per_week' => 4,
                'type'              => 'preset',
                'is_active'         => true,
                'location_type'     => 'rumah',
                'equipment_tags'    => ['resistance_band'],
                'cover_image_path'  => null,
                'weekly_schedule'   => $weeklySchedule,
            ]
        );

        $program->programExercises()->delete();

        $days = [
            // Day 1: SENIN — Upper A (Push focus)
            1 => [
                ['Resistance Band Chest Press',     4, 12],
                ['Resistance Band Fly',             3, 15],
                ['Resistance Band Row',             4, 12],
                ['Resistance Band Pull-Apart',      3, 15],
                ['Resistance Band Shoulder Press',  3, 12],
                ['Resistance Band Lateral Raise',   3, 15],
                ['Resistance Band Bicep Curl',      3, 12],
                ['Resistance Band Tricep Pushdown', 3, 12],
            ],
            // Day 2: SELASA — Lower A (Quad + Glute focus)
            2 => [
                ['Resistance Band Squat',               4, 15],
                ['Resistance Band Hip Thrust',          4, 15],
                ['Resistance Band Romanian Deadlift',   3, 12],
                ['Resistance Band Lateral Walk',        3, 20],
                ['Resistance Band Kickback',            3, 15],
                ['Resistance Band Clamshell',           3, 20],
                ['Plank',                               3, 45],
                ['Resistance Band Pallof Press',        3, 12],
            ],
            // Day 3: KAMIS — Upper B (Pull focus)
            3 => [
                ['Resistance Band Row',             4, 10],
                ['Resistance Band Face Pull',       4, 15],
                ['Resistance Band Pull-Apart',      3, 20],
                ['Resistance Band Shoulder Press',  4, 10],
                ['Resistance Band Lateral Raise',   3, 15],
                ['Resistance Band Chest Press',     3, 12],
                ['Resistance Band Bicep Curl',      3, 15],
                ['Resistance Band Tricep Pushdown', 3, 15],
            ],
            // Day 4: JUMAT — Lower B (Hamstring + Glute focus)
            4 => [
                ['Resistance Band Romanian Deadlift',   4, 12],
                ['Resistance Band Hip Thrust',          4, 20],
                ['Resistance Band Squat',               3, 20],
                ['Resistance Band Clamshell',           3, 20],
                ['Resistance Band Lateral Walk',        3, 20],
                ['Resistance Band Kickback',            3, 15],
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
