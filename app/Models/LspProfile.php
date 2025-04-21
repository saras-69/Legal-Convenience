<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class LspProfile extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'lsp_profiles';

    protected $fillable = [
        'user_id',
        'service_type', // ['advocate', 'arbitrator', 'mediator', 'notary', 'document_writer']
        'specialization',
        'experience_years',
        'license_number',
        'id_proof_type',
        'id_proof_number',
        'id_proof_document',
        'qualification',
        'bio',
        'verification_status', // 'pending', 'verified', 'rejected'
        'rejection_reason',
        'available_days',
        'available_hours',
        'latitude',
        'longitude',
        'service_radius',
    ];

    protected $casts = [
        'available_days' => 'array',
        'available_hours' => 'array',
        'experience_years' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'lsp_id', 'user_id');
    }
}