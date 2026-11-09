<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isAdministrativeUser();
    }

    public function create(User $actor): bool
    {
        return $actor->isAdministrativeUser();
    }

    public function update(User $actor, User $target): bool
    {
        if (! $actor->isAdministrativeUser()) {
            return false;
        }

        return $actor->isSuperAdministrator() || ! $target->isAdministrativeUser();
    }
}
