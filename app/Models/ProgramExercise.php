<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramExercise extends Model
{
    protected $fillable = [
        'program_id', 'exercise_id', 'day_number', 'order',
        'target_sets', 'target_reps', 'suggested_start_weight',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
