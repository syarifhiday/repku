<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramScheduleOverride extends Model
{
    protected $fillable = [
        'user_id', 'program_enrollment_id', 'date',
        'override_type', 'day_number', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function enrollment()
    {
        return $this->belongsTo(ProgramEnrollment::class, 'program_enrollment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
