<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'full_name',
        'birthdate',
        'address',
        'school',
        'year',
        'course',
        'id_number',
        'email',
        'password',
        'role',
        'approved',
        'status',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthdate' => 'date',
        ];
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }


    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isFaculty()
    {
        return $this->role === 'faculty';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function getIsApprovedAttribute()
    {
        return $this->approved;
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/'.$this->profile_photo_path)
            : 'https://via.placeholder.com/120';
    }
}