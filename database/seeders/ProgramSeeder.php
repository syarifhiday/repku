<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        // Helper: cari exercise by name, return id. Skip kalau tidak ada.
        $ex = function (string $name): ?int {
            $e = Exercise::where('name', $name)->first();
            return $e?->id;
        };

        $programs = [

            // ======= MENGURUSKAN BADAN =======
            [
                'name' => 'Fat Loss — Gym (Upper/Lower)',
                'goal' => 'menguruskan_badan',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym', 'dumbell'],
                'desc' => 'Program pembakaran lemak berbasis compound movement dengan intensitas tinggi. Kombinasi Upper/Lower Split 4x/seminggu. Berdasarkan prinsip Schoenfeld (2017): volume 10+ set/otot/minggu dengan repetisi tinggi dan istirahat pendek.',
                'weeks' => 10, 'sessions' => 4,
                'days' => [
                    1 => [// Upper Pull
                        ['Barbell Bent-Over Row', 4, 12],
                        ['Lat Pulldown', 3, 15],
                        ['Face Pull', 3, 15],
                        ['Barbell Bicep Curl', 3, 12],
                        ['Cable Crunch', 3, 15],
                    ],
                    2 => [// Lower
                        ['Barbell Back Squat', 4, 12],
                        ['Barbell Romanian Deadlift', 3, 12],
                        ['Leg Press', 3, 15],
                        ['Walking Lunge', 3, 12],
                        ['Mountain Climber', 3, 30],
                    ],
                    3 => [// Upper Push
                        ['Barbell Bench Press', 4, 12],
                        ['Incline Barbell Bench Press', 3, 12],
                        ['Cable Fly', 3, 15],
                        ['Barbell Overhead Press', 3, 12],
                        ['Cable Tricep Pushdown', 3, 12],
                    ],
                    4 => [// Full Body Metabolic
                        ['Barbell Deadlift', 4, 10],
                        ['Barbell Thruster', 3, 10],
                        ['Pull-Up', 3, 8],
                        ['Mountain Climber', 3, 30],
                        ['Burpee', 3, 10],
                    ],
                ],
            ],
            [
                'name' => 'Fat Loss — Dumbbell Home (Full Body Circuit)',
                'goal' => 'menguruskan_badan',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Program fat loss 3x/minggu dengan dumbbell di rumah. Full body circuit untuk memaksimalkan kalori terbakar dengan alat minimal. Setiap sesi melatih seluruh tubuh.',
                'weeks' => 10, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Dumbbell Goblet Squat', 4, 15],
                        ['Dumbbell Bench Press', 3, 12],
                        ['Single-Arm Dumbbell Row', 3, 12],
                        ['Dumbbell Shoulder Press', 3, 12],
                        ['Dumbbell Russian Twist', 3, 20],
                        ['Dumbbell Thruster', 3, 12],
                    ],
                    2 => [
                        ['Dumbbell Romanian Deadlift', 4, 12],
                        ['Dumbbell Incline Press', 3, 12],
                        ['Dumbbell Bent-Over Row', 3, 12],
                        ['Dumbbell Lateral Raise', 3, 15],
                        ['Dumbbell Lunges', 3, 12],
                        ['Leg Raise', 3, 15],
                    ],
                    3 => [
                        ['Dumbbell Clean and Press', 4, 10],
                        ['Dumbbell Bulgarian Split Squat', 3, 10],
                        ['Dumbbell Floor Press', 3, 12],
                        ['Dumbbell Bicep Curl', 3, 12],
                        ['Dumbbell Tricep Kickback', 3, 12],
                        ['Mountain Climber', 3, 30],
                    ],
                ],
            ],
            [
                'name' => 'Fat Loss — Tanpa Alat (HIIT Bodyweight)',
                'goal' => 'menguruskan_badan',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Program HIIT bodyweight murni tanpa peralatan. 3x/minggu, setiap sesi kombinasi strength dan cardio. Cocok untuk yang ingin mulai dari nol atau sedang traveling.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Burpee', 4, 12],
                        ['Push-Up', 4, 15],
                        ['Bodyweight Squat', 4, 20],
                        ['Mountain Climber', 3, 30],
                        ['Bicycle Crunch', 3, 20],
                    ],
                    2 => [
                        ['Jump Squat', 4, 12],
                        ['Wide Push-Up', 3, 15],
                        ['Walking Lunge', 4, 12],
                        ['Plank', 3, 60],
                        ['Burpee', 3, 10],
                    ],
                    3 => [
                        ['Push-Up', 4, 15],
                        ['Pistol Squat', 3, 8],
                        ['Burpee', 4, 10],
                        ['Mountain Climber', 4, 30],
                        ['V-Up', 3, 15],
                    ],
                ],
            ],

            // ======= MEMBESARKAN BADAN =======
            [
                'name' => 'Bulking — Gym Push/Pull/Legs',
                'goal' => 'membesarkan_badan',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym', 'dumbell'],
                'desc' => 'Program PPL 6x/minggu untuk hipertrofi maksimal. Berdasarkan Schoenfeld et al. (2019): melatih tiap otot 2–3x/minggu dengan total 12–20 set/otot/minggu. Rep range 6–12 untuk hipertrofi optimal.',
                'weeks' => 12, 'sessions' => 6,
                'days' => [
                    1 => [// Push A
                        ['Barbell Bench Press', 4, 8],
                        ['Incline Barbell Bench Press', 4, 10],
                        ['Cable Fly', 3, 12],
                        ['Barbell Overhead Press', 4, 8],
                        ['Cable Lateral Raise', 3, 15],
                        ['Skull Crusher', 3, 10],
                    ],
                    2 => [// Pull A
                        ['Barbell Deadlift', 4, 6],
                        ['Barbell Bent-Over Row', 4, 8],
                        ['Lat Pulldown', 4, 10],
                        ['Seated Cable Row', 3, 12],
                        ['Face Pull', 3, 15],
                        ['EZ-Bar Curl', 3, 10],
                    ],
                    3 => [// Legs A
                        ['Barbell Back Squat', 5, 8],
                        ['Barbell Romanian Deadlift', 4, 10],
                        ['Leg Press', 3, 12],
                        ['Leg Curl', 3, 12],
                        ['Leg Extension', 3, 15],
                        ['Dumbbell Calf Raise', 4, 15],
                    ],
                    4 => [// Push B
                        ['Incline Barbell Bench Press', 4, 8],
                        ['Pec Deck / Butterfly Machine', 3, 12],
                        ['Cable Fly', 3, 12],
                        ['Arnold Press', 4, 10],
                        ['Dumbbell Lateral Raise', 3, 15],
                        ['Cable Tricep Pushdown', 3, 12],
                    ],
                    5 => [// Pull B
                        ['T-Bar Row', 4, 8],
                        ['Wide-Grip Pull-Up', 4, 8],
                        ['Straight Arm Pulldown', 3, 12],
                        ['Dumbbell Rear Delt Fly', 3, 15],
                        ['Hammer Curl', 3, 12],
                        ['Preacher Curl', 3, 10],
                    ],
                    6 => [// Legs B
                        ['Hack Squat', 4, 10],
                        ['Dumbbell Romanian Deadlift', 4, 10],
                        ['Leg Press', 3, 15],
                        ['Barbell Hip Thrust', 4, 12],
                        ['Nordic Hamstring Curl', 3, 8],
                        ['Bodyweight Calf Raise', 4, 20],
                    ],
                ],
            ],
            [
                'name' => 'Bulking — Dumbbell Upper/Lower',
                'goal' => 'membesarkan_badan',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Program hipertrofi 4x/minggu dengan dumbbell. Upper/Lower split untuk frekuensi latihan 2x/minggu per otot. Berdasarkan prinsip: minimal 10 set/otot/minggu, rep range 8–15.',
                'weeks' => 12, 'sessions' => 4,
                'days' => [
                    1 => [// Upper A
                        ['Dumbbell Bench Press', 4, 10],
                        ['Dumbbell Incline Press', 3, 12],
                        ['Single-Arm Dumbbell Row', 4, 10],
                        ['Dumbbell Shoulder Press', 4, 10],
                        ['Dumbbell Bicep Curl', 3, 12],
                        ['Dumbbell Overhead Tricep Extension', 3, 12],
                    ],
                    2 => [// Lower A
                        ['Dumbbell Romanian Deadlift', 4, 10],
                        ['Dumbbell Goblet Squat', 4, 12],
                        ['Dumbbell Hip Thrust', 4, 12],
                        ['Dumbbell Lunges', 3, 12],
                        ['Dumbbell Calf Raise', 3, 15],
                    ],
                    3 => [// Upper B
                        ['Dumbbell Floor Press', 4, 10],
                        ['Dumbbell Fly', 3, 12],
                        ['Dumbbell Bent-Over Row', 4, 10],
                        ['Arnold Press', 3, 12],
                        ['Hammer Curl', 3, 12],
                        ['Dumbbell Tricep Kickback', 3, 12],
                    ],
                    4 => [// Lower B
                        ['Dumbbell Bulgarian Split Squat', 4, 10],
                        ['Dumbbell Romanian Deadlift', 3, 12],
                        ['Dumbbell Step-Up', 3, 12],
                        ['Dumbbell Glute Bridge', 4, 15],
                        ['Dumbbell Calf Raise', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Bulking — Resistance Band',
                'goal' => 'membesarkan_badan',
                'location_type' => 'rumah',
                'equipment_tags' => ['resistance_band'],
                'desc' => 'Program hipertrofi dengan resistance band. Constant tension dari band sangat efektif untuk pump dan hipertrofi. 3x/minggu full body.',
                'weeks' => 10, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Resistance Band Chest Press', 4, 15],
                        ['Resistance Band Row', 4, 15],
                        ['Resistance Band Shoulder Press', 3, 15],
                        ['Resistance Band Bicep Curl', 3, 15],
                        ['Resistance Band Tricep Pushdown', 3, 15],
                    ],
                    2 => [
                        ['Resistance Band Squat', 4, 20],
                        ['Resistance Band Romanian Deadlift', 4, 15],
                        ['Resistance Band Hip Thrust', 4, 20],
                        ['Resistance Band Lateral Walk', 3, 20],
                        ['Resistance Band Pallof Press', 3, 15],
                    ],
                    3 => [
                        ['Resistance Band Fly', 3, 15],
                        ['Resistance Band Lat Pulldown', 4, 15],
                        ['Resistance Band Face Pull', 3, 15],
                        ['Resistance Band Lateral Raise', 3, 15],
                        ['Resistance Band Clamshell', 3, 20],
                        ['Resistance Band Kickback', 3, 15],
                    ],
                ],
            ],

            // ======= LATIHAN PERUT =======
            [
                'name' => 'Core Strength — Gym',
                'goal' => 'latihan_perut',
                'location_type' => 'gym',
                'equipment_tags' => ['cable', 'pull_up_bar'],
                'desc' => 'Program core intensif di gym menggunakan cable dan pull-up bar. Termasuk weighted exercises untuk overload progresif. 3x/minggu, 8 minggu.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Hanging Leg Raise', 4, 12],
                        ['Cable Crunch', 4, 15],
                        ['Cable Woodchopper', 3, 12],
                        ['Pallof Press', 3, 15],
                        ['Plank', 3, 60],
                    ],
                    2 => [
                        ['Ab Wheel Rollout', 4, 10],
                        ['Cable Crunch', 3, 15],
                        ['Hanging Leg Raise', 3, 12],
                        ['Side Plank', 3, 45],
                        ['Bicycle Crunch', 3, 20],
                    ],
                    3 => [
                        ['Cable Woodchopper', 4, 12],
                        ['Hanging Leg Raise', 4, 12],
                        ['Cable Crunch', 3, 15],
                        ['Pallof Press', 3, 15],
                        ['V-Up', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Core Strength — Tanpa Alat',
                'goal' => 'latihan_perut',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Program core bodyweight progressif. Dimulai dari gerakan dasar menuju yang lebih menantang. Berdasarkan Stuart McGill Big 3 untuk fondasi core yang sehat dan kuat.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [// McGill Big 3 + Lower Abs
                        ['Plank', 3, 45],
                        ['Side Plank', 3, 30],
                        ['Dead Bug', 3, 10],
                        ['Leg Raise', 4, 12],
                        ['Mountain Climber', 3, 20],
                    ],
                    2 => [// Oblique + Anti-rotation
                        ['Bicycle Crunch', 4, 20],
                        ['Hollow Body Hold', 3, 30],
                        ['Side Plank', 3, 30],
                        ['V-Up', 3, 12],
                        ['Crunch', 3, 20],
                    ],
                    3 => [// Full Core
                        ['Plank', 4, 60],
                        ['Reverse Crunch', 4, 15],
                        ['Bicycle Crunch', 3, 20],
                        ['V-Up', 3, 15],
                        ['Mountain Climber', 3, 30],
                    ],
                ],
            ],

            // ======= LATIHAN KAKI =======
            [
                'name' => 'Leg Day — Gym (Quad & Ham Focus)',
                'goal' => 'latihan_kaki',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'mesin_gym', 'dumbell'],
                'desc' => 'Program kaki gym 2x/minggu dengan volume tinggi. Day 1 fokus quad, Day 2 fokus hamstring. Berdasarkan prinsip balance quad:hamstring untuk mencegah cedera.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [// Quad Focus
                        ['Barbell Back Squat', 5, 8],
                        ['Leg Press', 4, 12],
                        ['Hack Squat', 3, 12],
                        ['Leg Extension', 3, 15],
                        ['Bodyweight Calf Raise', 4, 20],
                    ],
                    2 => [// Hamstring Focus
                        ['Barbell Romanian Deadlift', 5, 8],
                        ['Leg Curl', 4, 12],
                        ['Dumbbell Bulgarian Split Squat', 3, 10],
                        ['Nordic Hamstring Curl', 3, 8],
                        ['Dumbbell Calf Raise', 4, 15],
                    ],
                ],
            ],
            [
                'name' => 'Leg Day — Dumbbell Home',
                'goal' => 'latihan_kaki',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Program kaki dengan dumbbell 3x/minggu. Mencakup quad, hamstring, dan betis. Intensitas tinggi dengan beban dumbbell.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Dumbbell Goblet Squat', 4, 12],
                        ['Dumbbell Romanian Deadlift', 4, 12],
                        ['Dumbbell Lunges', 3, 12],
                        ['Dumbbell Calf Raise', 3, 15],
                    ],
                    2 => [
                        ['Dumbbell Bulgarian Split Squat', 4, 10],
                        ['Dumbbell Step-Up', 3, 12],
                        ['Dumbbell Romanian Deadlift', 3, 12],
                        ['Dumbbell Calf Raise', 3, 15],
                    ],
                    3 => [
                        ['Dumbbell Goblet Squat', 4, 15],
                        ['Walking Lunge', 3, 12],
                        ['Dumbbell Romanian Deadlift', 4, 12],
                        ['Bodyweight Calf Raise', 3, 20],
                    ],
                ],
            ],
            [
                'name' => 'Leg Day — Tanpa Alat (Kalistenik)',
                'goal' => 'latihan_kaki',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Program kaki tanpa alat menggunakan calisthenics dan plyometrics. Sangat efektif untuk kekuatan fungsional dan daya ledak.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Bodyweight Squat', 4, 20],
                        ['Walking Lunge', 4, 12],
                        ['Jump Squat', 3, 12],
                        ['Wall Sit', 3, 45],
                        ['Bodyweight Calf Raise', 4, 25],
                    ],
                    2 => [
                        ['Jump Squat', 4, 15],
                        ['Walking Lunge', 3, 15],
                        ['Pistol Squat', 3, 6],
                        ['Nordic Hamstring Curl', 3, 5],
                        ['Bodyweight Calf Raise', 3, 25],
                    ],
                    3 => [
                        ['Pistol Squat', 4, 8],
                        ['Jump Squat', 3, 15],
                        ['Bodyweight Squat', 3, 25],
                        ['Wall Sit', 3, 60],
                        ['Bodyweight Calf Raise', 4, 30],
                    ],
                ],
            ],

            // ======= BOKONG & PAHA =======
            [
                'name' => 'Glute & Thigh — Gym',
                'goal' => 'latihan_bokong_paha',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym', 'dumbell'],
                'desc' => 'Program glute & paha 3x/minggu di gym. Barbell Hip Thrust sebagai gerakan utama (aktivasi glute tertinggi, Contreras et al. 2015). Kombinasi hip-dominant + knee-dominant exercises.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [// Hip Thrust Day
                        ['Barbell Hip Thrust', 5, 10],
                        ['Barbell Back Squat', 4, 10],
                        ['Cable Kickback', 3, 15],
                        ['Leg Curl', 3, 12],
                        ['Dumbbell Romanian Deadlift', 3, 12],
                    ],
                    2 => [// Split Squat Day
                        ['Dumbbell Bulgarian Split Squat', 4, 10],
                        ['Smith Machine Hip Thrust', 4, 12],
                        ['Leg Press', 3, 15],
                        ['Barbell Romanian Deadlift', 3, 10],
                        ['Reverse Pec Deck', 3, 15],
                    ],
                    3 => [// Volume Day
                        ['Barbell Hip Thrust', 4, 12],
                        ['Hack Squat', 3, 12],
                        ['Cable Kickback', 3, 15],
                        ['Barbell Romanian Deadlift', 4, 10],
                        ['Leg Curl', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Glute & Thigh — Resistance Band Home',
                'goal' => 'latihan_bokong_paha',
                'location_type' => 'rumah',
                'equipment_tags' => ['resistance_band', 'dumbell'],
                'desc' => 'Program glute & paha di rumah menggunakan resistance band dan dumbbell. Fokus pada hip thrust dan variasi squat. 3x/minggu.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Resistance Band Hip Thrust', 5, 15],
                        ['Dumbbell Goblet Squat', 4, 12],
                        ['Resistance Band Lateral Walk', 3, 20],
                        ['Dumbbell Romanian Deadlift', 3, 12],
                        ['Resistance Band Kickback', 3, 15],
                    ],
                    2 => [
                        ['Dumbbell Hip Thrust', 4, 12],
                        ['Resistance Band Squat', 4, 15],
                        ['Dumbbell Bulgarian Split Squat', 3, 10],
                        ['Resistance Band Clamshell', 3, 20],
                        ['Donkey Kick', 3, 15],
                    ],
                    3 => [
                        ['Resistance Band Hip Thrust', 4, 20],
                        ['Dumbbell Lunges', 4, 12],
                        ['Resistance Band Romanian Deadlift', 3, 15],
                        ['Fire Hydrant', 3, 15],
                        ['Glute Bridge', 4, 20],
                    ],
                ],
            ],
            [
                'name' => 'Glute & Thigh — Tanpa Alat',
                'goal' => 'latihan_bokong_paha',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Program bodyweight untuk glute dan paha. Tanpa alat apapun. Cocok untuk pemula atau yang sedang traveling.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Glute Bridge', 5, 20],
                        ['Bodyweight Squat', 4, 20],
                        ['Donkey Kick', 3, 15],
                        ['Fire Hydrant', 3, 15],
                        ['Walking Lunge', 3, 12],
                    ],
                    2 => [
                        ['Glute Bridge', 4, 25],
                        ['Jump Squat', 4, 12],
                        ['Pistol Squat', 3, 8],
                        ['Donkey Kick', 3, 15],
                        ['Wall Sit', 3, 45],
                    ],
                    3 => [
                        ['Glute Bridge', 5, 20],
                        ['Walking Lunge', 4, 15],
                        ['Jump Squat', 3, 15],
                        ['Fire Hydrant', 3, 15],
                        ['Bodyweight Calf Raise', 3, 25],
                    ],
                ],
            ],

            // ======= LATIHAN DADA =======
            [
                'name' => 'Chest Day — Gym',
                'goal' => 'latihan_dada',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym'],
                'desc' => 'Program dada gym 2x/minggu. Day 1 fokus flat & lower chest, Day 2 fokus upper chest dan isolasi. Volume 12–16 set/minggu untuk hipertrofi optimal.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [// Flat + Lower
                        ['Barbell Bench Press', 5, 8],
                        ['Chest Dip', 4, 10],
                        ['Cable Fly', 3, 12],
                        ['Pec Deck / Butterfly Machine', 3, 15],
                    ],
                    2 => [// Incline + Isolation
                        ['Incline Barbell Bench Press', 5, 8],
                        ['Barbell Bench Press', 3, 12],
                        ['Cable Fly', 3, 12],
                        ['Pec Deck / Butterfly Machine', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Chest Day — Dumbbell Home',
                'goal' => 'latihan_dada',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Program dada dengan dumbbell di rumah. Dumbbell press lebih dalam range of motion dari barbell. 2x/minggu.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [
                        ['Dumbbell Floor Press', 4, 10],
                        ['Dumbbell Fly', 3, 12],
                        ['Dumbbell Incline Press', 4, 10],
                        ['Push-Up', 3, 15],
                    ],
                    2 => [
                        ['Dumbbell Incline Press', 4, 10],
                        ['Dumbbell Floor Press', 3, 12],
                        ['Dumbbell Fly', 3, 12],
                        ['Diamond Push-Up', 3, 12],
                    ],
                ],
            ],
            [
                'name' => 'Chest Day — Push-Up & Kalistenik',
                'goal' => 'latihan_dada',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Program dada dengan push-up dan kalistenik. Tanpa alat apapun. Variasi push-up untuk melatih semua bagian dada. 3x/minggu.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Push-Up', 5, 15],
                        ['Wide Push-Up', 4, 15],
                        ['Decline Push-Up', 3, 12],
                        ['Chest Dip', 3, 10],
                    ],
                    2 => [
                        ['Decline Push-Up', 4, 12],
                        ['Diamond Push-Up', 4, 10],
                        ['Push-Up', 4, 15],
                        ['Pike Push-Up', 3, 12],
                    ],
                    3 => [
                        ['Archer Push-Up', 4, 8],
                        ['Wide Push-Up', 3, 15],
                        ['Diamond Push-Up', 3, 12],
                        ['Chest Dip', 4, 10],
                    ],
                ],
            ],

            // ======= BAHU & LENGAN =======
            [
                'name' => 'Shoulders & Arms — Gym',
                'goal' => 'latihan_bahu_lengan',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym', 'dumbell'],
                'desc' => 'Program bahu dan lengan gym 2x/minggu. Day 1 fokus bahu + tricep, Day 2 fokus lengan. Volume cukup karena bahu dan lengan sudah dilatih saat chest dan back day.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [// Shoulders + Tricep
                        ['Barbell Overhead Press', 4, 8],
                        ['Dumbbell Lateral Raise', 4, 12],
                        ['Cable Front Raise', 3, 12],
                        ['Reverse Pec Deck', 3, 15],
                        ['Cable Tricep Pushdown', 4, 12],
                        ['Skull Crusher', 3, 10],
                    ],
                    2 => [// Bicep + Bahu Lengkap
                        ['Arnold Press', 4, 10],
                        ['Cable Lateral Raise', 3, 15],
                        ['Face Pull', 3, 15],
                        ['EZ-Bar Curl', 4, 10],
                        ['Hammer Curl', 3, 12],
                        ['Preacher Curl', 3, 10],
                    ],
                ],
            ],
            [
                'name' => 'Shoulders & Arms — Dumbbell Home',
                'goal' => 'latihan_bahu_lengan',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Program bahu dan lengan dengan dumbbell 2x/minggu. Efektif untuk membentuk bahu lebar dan lengan berisi.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [// Shoulders
                        ['Dumbbell Shoulder Press', 4, 10],
                        ['Dumbbell Lateral Raise', 4, 12],
                        ['Dumbbell Front Raise', 3, 12],
                        ['Dumbbell Rear Delt Fly', 3, 12],
                        ['Arnold Press', 3, 10],
                    ],
                    2 => [// Arms
                        ['Dumbbell Bicep Curl', 4, 12],
                        ['Hammer Curl', 3, 12],
                        ['Concentration Curl', 3, 12],
                        ['Dumbbell Overhead Tricep Extension', 4, 12],
                        ['Dumbbell Tricep Kickback', 3, 12],
                    ],
                ],
            ],
            [
                'name' => 'Shoulders & Arms — Resistance Band',
                'goal' => 'latihan_bahu_lengan',
                'location_type' => 'rumah',
                'equipment_tags' => ['resistance_band'],
                'desc' => 'Program bahu dan lengan dengan resistance band. Constant tension dari band sangat baik untuk pump dan pembentukan otot.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [
                        ['Resistance Band Shoulder Press', 4, 15],
                        ['Resistance Band Lateral Raise', 4, 15],
                        ['Resistance Band Face Pull', 3, 15],
                        ['Resistance Band Tricep Pushdown', 4, 15],
                    ],
                    2 => [
                        ['Resistance Band Shoulder Press', 3, 15],
                        ['Resistance Band Lateral Raise', 3, 15],
                        ['Resistance Band Bicep Curl', 4, 15],
                        ['Resistance Band Tricep Pushdown', 3, 15],
                    ],
                ],
            ],

            // ======= LATIHAN PUNGGUNG =======
            [
                'name' => 'Back Day — Gym (Lat & Thickness)',
                'goal' => 'latihan_punggung',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'mesin_gym', 'pull_up_bar'],
                'desc' => 'Program punggung gym 2x/minggu. Day 1 fokus lebar (lat pull), Day 2 fokus ketebalan (row). Kombinasi vertikal dan horizontal pull untuk punggung sempurna.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [// Lebar (Lat Focus)
                        ['Wide-Grip Pull-Up', 4, 8],
                        ['Lat Pulldown', 4, 10],
                        ['Straight Arm Pulldown', 3, 12],
                        ['Seated Cable Row', 3, 12],
                        ['Face Pull', 3, 15],
                    ],
                    2 => [// Tebal (Row Focus)
                        ['Barbell Deadlift', 4, 6],
                        ['Barbell Bent-Over Row', 5, 8],
                        ['T-Bar Row', 4, 10],
                        ['Seated Cable Row', 3, 12],
                        ['Face Pull', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Back Day — Pull-Up & Dumbbell',
                'goal' => 'latihan_punggung',
                'location_type' => 'rumah',
                'equipment_tags' => ['pull_up_bar', 'dumbell'],
                'desc' => 'Program punggung dengan pull-up bar dan dumbbell. Sangat efektif. Pull-up adalah raja latihan punggung bodyweight. 2x/minggu.',
                'weeks' => 8, 'sessions' => 2,
                'days' => [
                    1 => [
                        ['Pull-Up', 5, 8],
                        ['Single-Arm Dumbbell Row', 4, 10],
                        ['Chin-Up', 3, 8],
                        ['Dumbbell Bent-Over Row', 3, 10],
                        ['Superman', 3, 15],
                    ],
                    2 => [
                        ['Chin-Up', 4, 8],
                        ['Dumbbell Bent-Over Row', 4, 10],
                        ['Pull-Up', 3, 8],
                        ['Dumbbell Deadlift', 3, 10],
                        ['Inverted Row', 3, 12],
                    ],
                ],
            ],
            [
                'name' => 'Back Day — Resistance Band',
                'goal' => 'latihan_punggung',
                'location_type' => 'rumah',
                'equipment_tags' => ['resistance_band'],
                'desc' => 'Program punggung dengan resistance band. Tanpa pull-up bar. Fokus pada row dan pull-apart untuk postur dan punggung yang lebih kuat.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Resistance Band Row', 5, 15],
                        ['Resistance Band Pull-Apart', 4, 15],
                        ['Resistance Band Face Pull', 3, 15],
                        ['Superman', 3, 15],
                    ],
                    2 => [
                        ['Resistance Band Row', 4, 15],
                        ['Resistance Band Pull-Apart', 4, 20],
                        ['Resistance Band Face Pull', 4, 15],
                        ['Inverted Row', 3, 12],
                    ],
                    3 => [
                        ['Resistance Band Row', 5, 15],
                        ['Resistance Band Pull-Apart', 3, 20],
                        ['Resistance Band Face Pull', 3, 15],
                        ['Superman', 3, 15],
                    ],
                ],
            ],

            // ======= FULL BODY =======
            [
                'name' => 'Full Body — Gym (Compound Heavy)',
                'goal' => 'full_body',
                'location_type' => 'gym',
                'equipment_tags' => ['barbell', 'cable', 'dumbell'],
                'desc' => 'Full body workout 3x/minggu dengan compound movements berat. Ideal untuk pemula gym atau yang memiliki waktu terbatas. Melatih semua otot utama dalam 1 sesi.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Barbell Deadlift', 4, 5],
                        ['Barbell Bench Press', 3, 8],
                        ['Barbell Bent-Over Row', 3, 8],
                        ['Barbell Overhead Press', 3, 8],
                        ['Plank', 3, 45],
                    ],
                    2 => [
                        ['Barbell Back Squat', 4, 6],
                        ['Incline Barbell Bench Press', 3, 10],
                        ['Lat Pulldown', 3, 10],
                        ['Dumbbell Shoulder Press', 3, 10],
                        ['Hanging Leg Raise', 3, 12],
                    ],
                    3 => [
                        ['Barbell Deadlift', 4, 5],
                        ['Barbell Thruster', 3, 10],
                        ['Pull-Up', 3, 8],
                        ['Barbell Romanian Deadlift', 3, 10],
                        ['Cable Crunch', 3, 15],
                    ],
                ],
            ],
            [
                'name' => 'Full Body — Dumbbell Home',
                'goal' => 'full_body',
                'location_type' => 'rumah',
                'equipment_tags' => ['dumbell'],
                'desc' => 'Full body dumbbell 3x/minggu. Setiap sesi melatih seluruh tubuh. Efisien waktu, tidak butuh gym. Cocok untuk pemula atau yang baru mulai home workout.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Dumbbell Goblet Squat', 4, 12],
                        ['Dumbbell Floor Press', 3, 10],
                        ['Single-Arm Dumbbell Row', 3, 10],
                        ['Dumbbell Shoulder Press', 3, 10],
                        ['Plank', 3, 45],
                    ],
                    2 => [
                        ['Dumbbell Romanian Deadlift', 4, 10],
                        ['Dumbbell Incline Press', 3, 12],
                        ['Dumbbell Bent-Over Row', 3, 12],
                        ['Dumbbell Lateral Raise', 3, 12],
                        ['Leg Raise', 3, 12],
                    ],
                    3 => [
                        ['Dumbbell Thruster', 4, 10],
                        ['Dumbbell Bulgarian Split Squat', 3, 10],
                        ['Dumbbell Clean and Press', 3, 10],
                        ['Dumbbell Bicep Curl', 3, 12],
                        ['Bicycle Crunch', 3, 20],
                    ],
                ],
            ],
            [
                'name' => 'Full Body — Tanpa Alat (Kalistenik Dasar)',
                'goal' => 'full_body',
                'location_type' => 'rumah',
                'equipment_tags' => ['bodyweight'],
                'desc' => 'Full body kalistenik tanpa alat apapun. 3x/minggu. Program terbaik untuk mulai dari nol. Melatih push, pull, dan lower body dalam setiap sesi.',
                'weeks' => 8, 'sessions' => 3,
                'days' => [
                    1 => [
                        ['Push-Up', 4, 12],
                        ['Bodyweight Squat', 4, 20],
                        ['Inverted Row', 3, 10],
                        ['Plank', 3, 45],
                        ['Mountain Climber', 3, 20],
                    ],
                    2 => [
                        ['Wide Push-Up', 4, 12],
                        ['Walking Lunge', 4, 12],
                        ['Pull-Up', 3, 6],
                        ['Side Plank', 3, 30],
                        ['Burpee', 3, 10],
                    ],
                    3 => [
                        ['Diamond Push-Up', 3, 10],
                        ['Jump Squat', 4, 12],
                        ['Chin-Up', 3, 6],
                        ['Hollow Body Hold', 3, 30],
                        ['Burpee', 3, 12],
                    ],
                ],
            ],

        ];

        foreach ($programs as $p) {
            $slug = Str::slug($p['name']);

            $program = Program::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'             => $p['name'],
                    'slug'             => $slug,
                    'goal'             => $p['goal'],
                    'description'      => $p['desc'],
                    'duration_weeks'   => $p['weeks'],
                    'sessions_per_week'=> $p['sessions'],
                    'type'             => 'preset',
                    'is_active'        => true,
                    'location_type'    => $p['location_type'],
                    'equipment_tags'   => $p['equipment_tags'],
                    'cover_image_path' => null,
                ]
            );

            // Hapus program exercises lama lalu buat ulang
            $program->programExercises()->delete();

            foreach ($p['days'] as $dayNumber => $exercises) {
                foreach ($exercises as $order => [$name, $sets, $reps]) {
                    $exerciseId = $ex($name);
                    if (!$exerciseId) {
                        $this->command->warn("Exercise not found: {$name}");
                        continue;
                    }
                    $program->programExercises()->create([
                        'exercise_id'  => $exerciseId,
                        'day_number'   => $dayNumber,
                        'order'        => $order + 1,
                        'target_sets'  => $sets,
                        'target_reps'  => $reps,
                    ]);
                }
            }
        }
    }
}
