<?php

namespace Database\Seeders;

use App\Models\MuscleGroup;
use Illuminate\Database\Seeder;

class MuscleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = ['Dada', 'Punggung', 'Bahu', 'Lengan', 'Perut / Core', 'Bokong', 'Kaki', 'Full Body'];
        foreach ($groups as $g) {
            MuscleGroup::create(['name' => $g, 'slug' => str()->slug($g)]);
        }
    }
}
