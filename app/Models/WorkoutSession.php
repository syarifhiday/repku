<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $fillable = [
        'user_id', 'program_enrollment_id', 'session_date', 'day_number',
        'location', 'notes', 'duration_minutes',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(ProgramEnrollment::class, 'program_enrollment_id');
    }

    public function logs()
    {
        return $this->hasMany(WorkoutLog::class);
    }
}
