<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'last_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_login' => 'datetime',
        'password' => 'hashed'
    ];

    /**
     * Update the last login timestamp.
     *
     * @return bool
     */
    public function updateLastLogin()
    {
        $this->last_login = now();
        return $this->save();
    }

    /**
     * Check if the admin has ever logged in.
     *
     * @return bool
     */
    public function hasLoggedIn()
    {
        return $this->last_login !== null;
    }

    /**
     * Get the time since last login.
     *
     * @return string|null
     */
    public function getTimeSinceLastLoginAttribute()
    {
        if (!$this->last_login) {
            return null;
        }

        return $this->last_login->diffForHumans();
    }
}