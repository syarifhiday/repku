<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'created_by', 'name', 'slug', 'goal', 'description',
        'duration_weeks', 'sessions_per_week', 'type', 'is_active',
        'cover_image_path', 'location_type', 'equipment_tags',
        'weekly_schedule',
    ];

    protected $casts = [
        'equipment_tags'  => 'array',
        'weekly_schedule' => 'array',
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

    /**
     * Jadwal mingguan default (berurutan) kalau weekly_schedule null.
     * Mengembalikan array 7 elemen [Mon..Sun], nilai = program day number atau null.
     */
    public function resolvedWeeklySchedule(): array
    {
        if (!empty($this->weekly_schedule)) {
            return array_values($this->weekly_schedule);
        }

        // Fallback: isi hari pertama sampai sessions_per_week, sisanya rest
        $schedule = [];
        for ($i = 0; $i < 7; $i++) {
            $schedule[] = $i < $this->sessions_per_week ? ($i + 1) : null;
        }
        return $schedule;
    }

    public static function goalLabels(): array
    {
        return [
            'kecilin_perut'       => 'Kecilin Perut',
            'kecilin_bokong'      => 'Kecilin / Bentuk Bokong',
            'full_body_muscle'    => 'Bangun Full Body Muscle',
            'menguruskan_badan'   => 'Menguruskan Badan',
            'membesarkan_badan'   => 'Membesarkan Badan (Bulking)',
            'latihan_perut'       => 'Latihan Perut',
            'latihan_kaki'        => 'Latihan Kaki',
            'latihan_bokong_paha' => 'Latihan Bokong & Paha',
            'latihan_dada'        => 'Latihan Dada',
            'latihan_bahu_lengan' => 'Latihan Bahu & Lengan',
            'latihan_punggung'    => 'Latihan Punggung',
            'full_body'           => 'Full Body Workout',
            'custom'              => 'Custom / Buatan Sendiri',
        ];
    }

    public static function locationLabels(): array
    {
        return ['gym' => 'Gym', 'rumah' => 'Rumah', 'keduanya' => 'Gym & Rumah'];
    }

    public static function equipmentLabels(): array
    {
        return [
            'barbell'         => 'Barbell',
            'dumbell'         => 'Dumbbell',
            'cable'           => 'Cable',
            'mesin_gym'       => 'Mesin Gym',
            'resistance_band' => 'Resistance Band',
            'pull_up_bar'     => 'Pull-Up Bar',
            'kettlebell'      => 'Kettlebell',
            'bodyweight'      => 'Tanpa Alat',
        ];
    }

    public static function dayNames(): array
    {
        return ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    }
}
