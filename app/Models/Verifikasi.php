<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'isian_id',
        'status',
        'deskripsi',
        'verifikator_id',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime'
    ];

    /**
     * Get the isian that owns the verifikasi
     */
    public function isian()
    {
        return $this->belongsTo(Isian::class);
    }

    /**
     * Get the verifikator who made the verification
     */
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}