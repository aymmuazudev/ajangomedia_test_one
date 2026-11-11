<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;

class AdvertisementPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isAdministrativeUser()
            || $actor->role === User::ROLE_ADVERTISEMENT_MANAGER;
    }

    public function create(User $actor): bool
    {
        return $this->viewAny($actor);
    }

    public function update(User $actor, Advertisement $advertisement): bool
    {
        return $this->viewAny($actor);
    }

    public function delete(User $actor, Advertisement $advertisement): bool
    {
        return $this->viewAny($actor);
    }
}
