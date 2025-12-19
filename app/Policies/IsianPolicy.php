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
        return true;
    }

    /**
     * Determine if user can view any isian
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine if user can view single isian
     */
    public function view(User $user, Isian $isian): bool
    {
        return true;
    }

    /**
     * Determine if user can create isian
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can update isian
     * PENTING: Jika dipanggil tanpa $isian, jadikan optional
     */
    public function update(User $user, ?Isian $isian = null): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can delete isian
     */
    public function delete(User $user, Isian $isian): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if user can add link to isian
     */
    public function addLink(User $user, Isian $isian): bool
    {
        return $user->isUser();
    }

    /**
     * Determine if user can verify isian
     */
    public function verify(User $user, ?Isian $isian = null): bool
    {
        return $user->isVerifikator();
    }
}