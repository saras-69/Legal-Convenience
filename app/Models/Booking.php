<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Booking extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'bookings';

    protected $fillable = [
        'citizen_id',
        'lsp_id',
        'service_id',
        'booking_date',
        'booking_time',
        'status', // 'pending', 'confirmed', 'completed', 'cancelled'
        'payment_status', // 'pending', 'paid', 'refunded'
        'payment_id',
        'amount',
        'transaction_id',
        'notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'float',
    ];

    public function citizen()
    {
        return $this->belongsTo(User::class, 'citizen_id');
    }

    public function lsp()
    {
        return $this->belongsTo(User::class, 'lsp_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}