<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Eloquent\Model; // Use the new base model
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model implements
    \Illuminate\Contracts\Auth\Authenticatable,
    \Illuminate\Contracts\Auth\CanResetPassword,
    \Illuminate\Contracts\Auth\Access\Authorizable
{
    use Authenticatable, Authorizable, CanResetPassword;
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role', // 'citizen', 'lsp', 'admin'
        'is_verified',
        'reward_points',
        'profile_image',
        'address',
        'city',
        'state',
        'pincode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    public function lspProfile()
    {
        return $this->hasOne(LspProfile::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'citizen_id');
    }

    public function providedServices()
    {
        return $this->hasMany(Booking::class, 'lsp_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'citizen_id');
    }

    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'lsp_id');
    }
}