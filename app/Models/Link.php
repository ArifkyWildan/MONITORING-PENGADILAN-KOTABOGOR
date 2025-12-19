<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'isian_id',
        'text_hyperlink',
        'url_link',
        'created_by'
    ];

    /**
     * Get the isian that owns the link
     */
    public function isian()
    {
        return $this->belongsTo(Isian::class);
    }

    /**
     * Get the user who created the link
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}