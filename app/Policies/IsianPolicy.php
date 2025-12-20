<?php

namespace App\Policies;

use App\Models\Isian;
use App\Models\User;

class IsianPolicy
{
    /**
     * Admin bypass all authorization checks
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role && $user->role->name === 'admin') {
            return true;
        }
        return null;
    }

    /**
     * Determine if user can view statistics (Dashboard)
     * ROLE: ALL
     */
    public function viewStatistics(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view any isian (List page)
     * ROLE: ALL
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view the isian
     * ROLE: ALL
     */
    public function view(User $user, Isian $isian): bool
    {
        return true;
    }

    /**
     * Determine if user can create isian
     * ROLE: ADMIN, VERIFIKATOR, USER
     */
    public function create(User $user): bool
    {
        if (!$user->role) {
            return false;
        }

        // Admin, Verifikator, dan User bisa create
        return in_array($user->role->name, ['admin', 'verifikator', 'user']);
    }

    /**
     * Determine if user can update the isian
     * ROLE: ADMIN only
     */
    public function update(User $user, Isian $isian): bool
    {
        return $user->role && $user->role->name === 'admin';
    }

    /**
     * Determine if user can delete the isian
     * ROLE: ADMIN only
     */
    public function delete(User $user, Isian $isian): bool
    {
        return $user->role && $user->role->name === 'admin';
    }

    /**
     * Determine if user can add link to isian
     * ROLE: ADMIN & USER
     */
    public function addLink(User $user, Isian $isian): bool
    {
        if (!$user->role) {
            return false;
        }

        // Isian sudah punya link? Tidak bisa tambah lagi
        if ($isian->hasLink()) {
            return false;
        }

        // Admin dan User bisa add link
        return in_array($user->role->name, ['admin', 'user']);
    }

    /**
     * Determine if user can verify isian
     * ROLE: ADMIN & VERIFIKATOR
     */
    public function verify(User $user): bool
    {
        return $user->role && in_array($user->role->name, ['admin', 'verifikator']);
    }

    /**
     * Determine if user can view reports
     * ROLE: ADMIN & PIMPINAN
     */
    public function viewReports(User $user): bool
    {
        return $user->role && in_array($user->role->name, ['admin', 'pimpinan']);
    }
}