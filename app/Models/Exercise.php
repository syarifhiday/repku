<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'muscle_group_id', 'name', 'slug', 'equipment_type', 'difficulty',
        'description', 'how_to', 'illustration_path', 'video_url',
        'weight_unit_default', 'is_active',
    ];

    public function muscleGroup()
    {
        return $this->belongsTo(MuscleGroup::class);
    }

    public function workoutLogs()
    {
        return $this->hasMany(WorkoutLog::class);
    }

    /**
     * Ambil log terakhir milik user untuk exercise ini -> dasar progressive overload.
     */
    public function lastLogsForUser(int $userId, int $limit = 5)
    {
        return $this->hasMany(WorkoutLog::class)
            ->whereHas('session', fn ($q) => $q->where('user_id', $userId))
            ->latest('id')
            ->limit($limit)
            ->get();
    }
}
