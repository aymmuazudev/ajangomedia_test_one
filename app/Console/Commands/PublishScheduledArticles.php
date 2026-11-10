<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish scheduled stories whose publication time has arrived';

    public function handle(): int
    {
        Article::query()
            ->where('status', Article::STATUS_SCHEDULED)
            ->where('scheduled_for', '<=', now())
            ->get()
            ->each(function (Article $article) {
                $article->forceFill([
                    'status' => Article::STATUS_PUBLISHED,
                    'published_at' => $article->scheduled_for,
                    'scheduled_for' => null,
                ])->save();
            });

        return self::SUCCESS;
    }
}
