<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_bagian',
        'slug'
    ];

    /**
     * Get isians for this bagian
     */
    public function isians()
    {
        return $this->hasMany(Isian::class);
    }

    /**
     * Get verifikators for this bagian
     */
    public function verifikators()
    {
        return $this->hasMany(User::class)->whereHas('role', function($query) {
            $query->where('name', 'verifikator');
        });
    }

    /**
     * Get all users in this bagian
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if bagian has verifikator
     */
    public function hasVerifikator(): bool
    {
        return $this->verifikators()->exists();
    }

    /**
     * Get the main verifikator for this bagian
     */
    public function getMainVerifikator()
    {
        return $this->verifikators()->first();
    }
}