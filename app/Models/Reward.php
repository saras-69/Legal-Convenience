<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reward extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'rewards';

    protected $fillable = [
        'user_id',
        'points',
        'type', // 'earned', 'redeemed'
        'source', // 'booking_completed', 'referral', 'redemption'
        'reference_id', // booking_id or other reference
        'description',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}