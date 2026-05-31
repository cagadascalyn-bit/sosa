<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'address', 'gender', 'phone', 'profile_picture', 'profile_picture_base64', 'role',
    ];

    /**
     * Returns the base64 data URI if available, otherwise null.
     * Views should use this instead of checking profile_picture directly.
     */
    public function getAvatarAttribute(): ?string
    {
        if ($this->profile_picture_base64) {
            return $this->profile_picture_base64;
        }
        return null;
    }

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
