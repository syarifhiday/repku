<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutLog extends Model
{
    protected $fillable = [
        'workout_session_id', 'exercise_id', 'set_number', 'weight_unit',
        'weight_value', 'band_color', 'reps', 'rpe', 'notes',
    ];

    public function session()
    {
        return $this->belongsTo(WorkoutSession::class, 'workout_session_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
