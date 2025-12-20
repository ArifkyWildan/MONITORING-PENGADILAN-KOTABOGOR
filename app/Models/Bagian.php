<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bagian',  // ✅ SUDAH BENAR
        'slug'
    ];

    /**
     * Get isians for this bagian
     */
    public function isians()
    {
        return $this->hasMany(Isian::class);
    }
}