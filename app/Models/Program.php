<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'created_by', 'name', 'slug', 'goal', 'description',
        'duration_weeks', 'sessions_per_week', 'type', 'is_active',
        'cover_image_path', 'location_type', 'equipment_tags',
    ];

    protected $casts = [
        'equipment_tags' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function programExercises()
    {
        return $this->hasMany(ProgramExercise::class)->orderBy('day_number')->orderBy('order');
    }

    public function exercisesByDay()
    {
        return $this->programExercises()->with('exercise.muscleGroup')->get()->groupBy('day_number');
    }

    public function enrollments()
    {
        return $this->hasMany(ProgramEnrollment::class);
    }

    public static function goalLabels(): array
    {
        return [
            'kecilin_perut'      => 'Kecilin Perut',
            'kecilin_bokong'     => 'Kecilin / Bentuk Bokong',
            'full_body_muscle'   => 'Bangun Full Body Muscle',
            'menguruskan_badan'  => 'Menguruskan Badan',
            'membesarkan_badan'  => 'Membesarkan Badan (Bulking)',
            'latihan_perut'      => 'Latihan Perut',
            'latihan_kaki'       => 'Latihan Kaki',
            'latihan_bokong_paha'=> 'Latihan Bokong & Paha',
            'latihan_dada'       => 'Latihan Dada',
            'latihan_bahu_lengan'=> 'Latihan Bahu & Lengan',
            'latihan_punggung'   => 'Latihan Punggung',
            'full_body'          => 'Full Body Workout',
            'custom'             => 'Custom / Buatan Sendiri',
        ];
    }

    public static function locationLabels(): array
    {
        return ['gym' => 'Gym', 'rumah' => 'Rumah', 'keduanya' => 'Gym & Rumah'];
    }

    public static function equipmentLabels(): array
    {
        return [
            'barbell'        => 'Barbell',
            'dumbell'        => 'Dumbbell',
            'cable'          => 'Cable',
            'mesin_gym'      => 'Mesin Gym',
            'resistance_band'=> 'Resistance Band',
            'pull_up_bar'    => 'Pull-Up Bar',
            'kettlebell'     => 'Kettlebell',
            'bodyweight'     => 'Tanpa Alat',
        ];
    }
}
