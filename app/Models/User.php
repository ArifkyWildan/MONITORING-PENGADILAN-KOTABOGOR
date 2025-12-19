<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role of the user
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get isians created by this user
     */
    public function isians()
    {
        return $this->hasMany(Isian::class, 'created_by');
    }

    /**
     * Get verifikasis made by this user
     */
    public function verifikasis()
    {
        return $this->hasMany(Verifikasi::class, 'verifikator_id');
    }

    /**
     * Get links created by this user
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'created_by');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    /**
     * Check if user is verifikator
     */
    public function isVerifikator(): bool
    {
        return $this->role && $this->role->name === 'verifikator';
    }

    /**
     * Check if user is pimpinan
     */
    public function isPimpinan(): bool
    {
        return $this->role && $this->role->name === 'pimpinan';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role && $this->role->name === 'user';
    }

    /**
     * Check if user can verify
     */
    public function canVerify(): bool
    {
        return $this->isVerifikator();
    }

    /**
     * Check if user can edit
     */
    public function canEdit(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }
}