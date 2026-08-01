<?php

namespace App\Policies;

use App\Models\User;

/**
 * Team management is admin-tier only (super_admin/admin) — editors and
 * commenters never see the Users list at all. Within admin-tier: super_admin
 * outranks everyone; admin may only manage editor/commenter accounts, never
 * other admins or super_admins, and never itself for deletion (see
 * User::outranks()).
 */
class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminTier();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdminTier();
    }

    public function create(User $user): bool
    {
        return $user->isAdminTier();
    }

    public function update(User $user, User $model): bool
    {
        return $user->outranks($model) || $user->is($model);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->outranks($model) && ! $user->is($model);
    }

    public function deleteAny(User $user): bool
    {
        return $user->isAdminTier();
    }

    public function restore(User $user, User $model): bool
    {
        return $user->outranks($model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->isSuperAdmin() && ! $user->is($model);
    }
}
