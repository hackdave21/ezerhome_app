<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('users.manage');
    }

    /** Chacun voit et modifie son propre compte ; l'Admin gère les autres. */
    public function view(User $user, User $target): bool
    {
        return $user->is($target) || $user->can('users.manage');
    }

    public function update(User $user, User $target): bool
    {
        return $user->is($target) || $user->can('users.manage');
    }

    public function deactivate(User $user, User $target): bool
    {
        return $user->can('accounts.deactivate') && ! $user->is($target);
    }

    public function delete(User $user, User $target): bool
    {
        return $user->can('users.manage') && ! $user->is($target);
    }
}
