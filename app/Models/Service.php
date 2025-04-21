<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class Service extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'services';

    protected $fillable = [
        'lsp_id',
        'title',
        'description',
        'category',
        'price',
        'duration_minutes',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float',
        'duration_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function lsp()
    {
        return $this->belongsTo(User::class, 'lsp_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}