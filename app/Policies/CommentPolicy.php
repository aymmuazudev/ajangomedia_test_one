<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->isAdministrativeUser()
            || in_array($actor->role, [
                User::ROLE_MANAGING_EDITOR,
                User::ROLE_COMMENT_MODERATOR,
            ], true);
    }

    public function moderate(User $actor, Comment $comment): bool
    {
        return $this->viewAny($actor);
    }
}
