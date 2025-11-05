<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        ];
    }

    // Role helpers
    public const ROLE_CITIZEN = 'citizen';
    public const ROLE_OFFICIAL = 'official';
    public const ROLE_CREW = 'crew';

    public function isCitizen(): bool
    {
        return $this->role === self::ROLE_CITIZEN;
    }

    public function isOfficial(): bool
    {
        return $this->role === self::ROLE_OFFICIAL;
    }

    public function isCrew(): bool
    {
        return $this->role === self::ROLE_CREW;
    }
}