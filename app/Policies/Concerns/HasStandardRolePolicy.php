<?php

namespace App\Policies\Concerns;

use App\Models\User;

/**
 * Standard content policy shared by every content model (Services, Case
 * Studies, Testimonials, Posts, Categories, Inquiries, Pages, Media):
 *
 *   super_admin / admin / editor — full CRUD.
 *   commenter                    — read-only (view, never create/edit/delete).
 *
 * Filament falls back to "allow" for any ability with no matching policy
 * method, so every ability content resources actually use is defined here
 * (including the bulk-action `*Any` variants Filament checks separately).
 */
trait HasStandardRolePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, mixed $record): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canManageContent();
    }

    public function update(User $user, mixed $record): bool
    {
        return $user->canManageContent();
    }

    public function delete(User $user, mixed $record): bool
    {
        return $user->canManageContent();
    }

    public function deleteAny(User $user): bool
    {
        return $user->canManageContent();
    }

    public function restore(User $user, mixed $record): bool
    {
        return $user->canManageContent();
    }

    public function forceDelete(User $user, mixed $record): bool
    {
        return $user->isSuperAdmin();
    }
}
