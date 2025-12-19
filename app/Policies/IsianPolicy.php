<?php

namespace App\Policies;

use App\Models\Isian;
use App\Models\User;

class IsianPolicy
{
    /**
     * Determine if user can view statistics (Dashboard)
     */
    public function viewStatistics(User $user): bool
    {
        // Semua authenticated user bisa melihat dashboard
        return true;
        
        // ATAU jika hanya role tertentu:
        // return in_array($user->role, ['admin', 'verifikator', 'operator']);
    }

    /**
     * Determine if user can view any isian (List page)
     */
    public function viewAny(User $user): bool
    {
        // Semua authenticated user bisa melihat list
        return true;
    }

    /**
     * Determine if user can view the isian
     */
    public function view(User $user, Isian $isian): bool
    {
        // Semua authenticated user bisa melihat detail isian
        return true;
    }

    /**
     * Determine if user can create isian
     */
    public function create(User $user): bool
    {
        // Hanya admin dan operator yang bisa create
        return in_array($user->role, ['admin', 'operator']);
    }

    /**
     * Determine if user can update the isian
     */
    public function update(User $user, Isian $isian): bool
    {
        // Admin bisa update semua, operator hanya yang dia buat
        return $user->role === 'admin' || 
               ($user->role === 'operator' && $isian->created_by === $user->id);
    }

    /**
     * Determine if user can delete the isian
     */
    public function delete(User $user, Isian $isian): bool
    {
        // Hanya admin yang bisa delete
        return $user->role === 'admin';
    }

    /**
     * Determine if user can add link to isian
     */
    public function addLink(User $user, Isian $isian): bool
    {
        // Admin dan operator bisa add link
        // Operator hanya bisa add link ke isian yang dia buat
        return $user->role === 'admin' || 
               ($user->role === 'operator' && $isian->created_by === $user->id);
    }

    /**
     * Determine if user can verify isian
     */
    public function verify(User $user): bool
    {
        // Hanya admin dan verifikator yang bisa verify
        return in_array($user->role, ['admin', 'verifikator']);
    }
}