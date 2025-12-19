<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Isian extends Model
{
    use HasFactory;

    protected $fillable = [
        'bagian_id',
        'daftar_isi',
        'created_by'
    ];

    /**
     * Get the bagian that owns the isian
     */
    public function bagian()
    {
        return $this->belongsTo(Bagian::class);
    }

    /**
     * Get the user who created the isian
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the link for this isian
     */
    public function link()
    {
        return $this->hasOne(Link::class);
    }

    /**
     * Get the verifikasi for this isian
     */
    public function verifikasi()
    {
        return $this->hasOne(Verifikasi::class);
    }

    /**
     * Check if isian has link
     */
    public function hasLink(): bool
    {
        return $this->link()->exists();
    }

    /**
     * Check if isian is verified
     */
    public function isVerified(): bool
    {
        return $this->verifikasi()->exists();
    }

    /**
     * Check if isian can be verified
     */
    public function canBeVerified(): bool
    {
        return $this->hasLink() && !$this->isVerified();
    }

    /**
     * Get status attribute
     */
    public function getStatusAttribute(): string
    {
        if (!$this->hasLink()) {
            return 'belum_isi_link';
        }
        
        if (!$this->isVerified()) {
            return 'menunggu_verifikasi';
        }

        return $this->verifikasi->status;
    }
}