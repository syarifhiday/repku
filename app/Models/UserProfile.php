<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id', 'gender', 'birthdate', 'height_cm', 'weight_kg',
        'activity_level', 'training_location', 'equipment_access',
        'experience_level', 'injury_notes', 'goal_notes', 'target_weight_kg',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate ? $this->birthdate->age : null;
    }

    public function getBmiAttribute(): ?float
    {
        if (!$this->height_cm || !$this->weight_kg) return null;
        $heightM = $this->height_cm / 100;
        return round($this->weight_kg / ($heightM * $heightM), 1);
    }

    protected $casts = [
        'birthdate' => 'date',
    ];
}
