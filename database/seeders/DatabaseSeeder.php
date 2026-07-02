<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti email ini dengan email Google kamu sendiri agar otomatis jadi admin saat login pertama kali
        User::updateOrCreate(
            ['email' => 'admin@gymforge.test'],
            ['name' => 'Admin GymForge', 'role' => 'admin']
        );

        $this->call([
            MuscleGroupSeeder::class,
            ExerciseSeeder::class,
            ProgramSeeder::class,
        ]);
    }
}
