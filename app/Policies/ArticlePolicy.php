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
        if (! $actor->canAccessDashboard()) {
            return false;
        }

        if (! $actor->isNewsReporter()) {
            return true;
        }

        return $article->author_id === $actor->id
            && in_array($article->status, [Article::STATUS_DRAFT, Article::STATUS_REJECTED], true);
    }

    public function delete(User $actor, Article $article): bool
    {
        return $actor->isEditorialLead();
    }

    public function transition(User $actor, Article $article, string $intent): bool
    {
        return match ($intent) {
            'save', 'submit' => $this->update($actor, $article),
            'approve', 'reject' => $actor->isEditorialLead(),
            default => $actor->canAccessDashboard() && ! $actor->isNewsReporter(),
        };
    }
}
