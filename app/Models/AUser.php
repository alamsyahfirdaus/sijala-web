<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class AUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'a_users';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi Survey
     */
    public function surveys()
    {
        return $this->hasMany(ASurvey::class, 'user_id');
    }

    /**
     * Relasi Notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(ANotification::class, 'user_id');
    }
}