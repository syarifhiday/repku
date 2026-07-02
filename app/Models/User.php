<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'google_id', 'avatar', 'role',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function enrollments()
    {
        return $this->hasMany(ProgramEnrollment::class);
    }

    public function activeEnrollment()
    {
        return $this->hasOne(ProgramEnrollment::class)->where('status', 'active')->latestOfMany();
    }

    public function workoutSessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}
