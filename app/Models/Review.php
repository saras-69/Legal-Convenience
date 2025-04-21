<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Review extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    protected $fillable = [
        'booking_id',
        'citizen_id',
        'lsp_id',
        'rating',
        'comment',
        'lsp_response',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function lsp()
    {
        return $this->belongsTo(User::class, 'lsp_id');
    }
}