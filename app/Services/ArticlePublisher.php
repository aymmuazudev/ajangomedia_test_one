<?php

namespace App\Services;

use App\Mail\ArticlePublished;
use App\Models\Article;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ArticlePublisher
{
    public const INTENTS = [
        'save',
        'submit',
        'approve',
        'reject',
        'publish',
        'schedule',
        'unpublish',
        'archive',
        'restore',
        'cancel_schedule',
    ];

    public function apply(Article $article, string $intent, ?CarbonInterface $scheduledFor): void
    {
        match ($intent) {
            'save' => $article->status === Article::STATUS_SCHEDULED
                ? $this->schedule($article, $scheduledFor)
                : $article->save(),
            'submit' => $this->submit($article),
            'approve' => $this->move($article, [Article::STATUS_SUBMITTED], Article::STATUS_APPROVED),
            'reject' => $this->move(
                $article,
                [Article::STATUS_SUBMITTED, Article::STATUS_APPROVED],
                Article::STATUS_REJECTED,
            ),
            'publish' => $this->publish($article),
            'schedule' => $this->schedule($article, $scheduledFor),
            'unpublish' => $this->move($article, [Article::STATUS_PUBLISHED], Article::STATUS_UNPUBLISHED),
            'archive' => $this->move(
                $article,
                [
                    Article::STATUS_DRAFT,
                    Article::STATUS_PUBLISHED,
                    Article::STATUS_UNPUBLISHED,
                    Article::STATUS_REJECTED,
                ],
                Article::STATUS_ARCHIVED,
            ),
            'restore' => $this->move($article, [Article::STATUS_ARCHIVED], Article::STATUS_DRAFT),
            'cancel_schedule' => $this->move($article, [Article::STATUS_SCHEDULED], Article::STATUS_DRAFT),
        };
    }

    private function submit(Article $article): void
    {
        $article->editor_note = null;

        $this->move($article, [Article::STATUS_DRAFT, Article::STATUS_REJECTED], Article::STATUS_SUBMITTED);
    }

    private function publish(Article $article): void
    {
        $this->assertCanMoveFrom($article, [
            Article::STATUS_DRAFT,
            Article::STATUS_APPROVED,
            Article::STATUS_SCHEDULED,
            Article::STATUS_UNPUBLISHED,
        ]);

        $wasNotPublishedBefore = $article->published_at === null;
        $publishedAt = $article->status === Article::STATUS_SCHEDULED && $article->scheduled_for
            ? $article->scheduled_for
            : now();

        $article->status = Article::STATUS_PUBLISHED;
        $article->published_at = $publishedAt;
        $article->scheduled_for = null;
        $article->save();

        if ($wasNotPublishedBefore && $article->author && $article->author->email) {
            Mail::to($article->author->email)->queue(new ArticlePublished($article));
        }
    }

    private function schedule(Article $article, ?CarbonInterface $scheduledFor): void
    {
        if ($scheduledFor === null || $scheduledFor->lte(now())) {
            throw ValidationException::withMessages([
                'scheduled_for' => 'Choose a date and time in the future to schedule this story.',
            ]);
        }

        $this->assertCanMoveFrom($article, [
            Article::STATUS_DRAFT,
            Article::STATUS_APPROVED,
            Article::STATUS_SCHEDULED,
        ]);

        $article->status = Article::STATUS_SCHEDULED;
        $article->scheduled_for = $scheduledFor;
        $article->save();
    }

    private function move(Article $article, array $allowedFrom, string $to): void
    {
        $this->assertCanMoveFrom($article, $allowedFrom);

        $article->status = $to;
        if ($to !== Article::STATUS_SCHEDULED) {
            $article->scheduled_for = null;
        }
        $article->save();
    }

    private function assertCanMoveFrom(Article $article, array $allowedFrom): void
    {
        if (in_array($article->status, $allowedFrom, true)) {
            return;
        }

        throw ValidationException::withMessages([
            'intent' => 'That action is not available while the story is '.strtolower($article->statusLabel()).'.',
        ]);
    }
}
