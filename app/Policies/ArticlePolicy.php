<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->canAccessDashboard();
    }

    public function create(User $actor): bool
    {
        return $actor->canAccessDashboard();
    }

    public function update(User $actor, Article $article): bool
    {
        return $actor->canAccessDashboard();
    }

    public function delete(User $actor, Article $article): bool
    {
        return $actor->isEditorialLead();
    }
}
