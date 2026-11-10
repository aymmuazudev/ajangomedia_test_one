<?php

namespace App\Policies;

use App\Models\MediaFile;
use App\Models\User;

class MediaFilePolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->canAccessDashboard();
    }

    public function create(User $actor): bool
    {
        return $actor->canAccessDashboard();
    }

    public function delete(User $actor, MediaFile $mediaFile): bool
    {
        return $actor->isEditorialLead() || $mediaFile->uploaded_by === $actor->id;
    }
}
