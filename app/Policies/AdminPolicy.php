<?php

namespace App\Policies;

use App\Models\User;

/**
 * AdminPolicy - Centralized authorization for admin actions
 * 
 * This policy should be used to check if a user can perform admin actions
 * Usage: $this->authorize('admin', User::class);
 */
class AdminPolicy
{
    /**
     * Check if user is admin
     */
    public function admin(User $user): bool
    {
        return $user->role === 'admin' && $user->status_akun === 'aktif';
    }

    /**
     * Check if user can access admin dashboard
     */
    public function viewDashboard(User $user): bool
    {
        return $this->admin($user);
    }

    /**
     * Check if user can manage users
     */
    public function manageUsers(User $user): bool
    {
        return $this->admin($user);
    }

    /**
     * Check if user can manage documents
     */
    public function manageDocuments(User $user): bool
    {
        return $this->admin($user);
    }

    /**
     * Check if user can view reports
     */
    public function viewReports(User $user): bool
    {
        return $this->admin($user);
    }

    /**
     * Check if user can verify accounts
     */
    public function verifyAccounts(User $user): bool
    {
        return $this->admin($user);
    }

    /**
     * Check if user can configure settings
     */
    public function configureSettings(User $user): bool
    {
        return $this->admin($user);
    }
}
