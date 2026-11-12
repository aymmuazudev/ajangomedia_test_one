<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\ArticlePublisher;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish scheduled stories whose publication time has arrived';

    public function handle(ArticlePublisher $publisher): int
    {
        Article::query()
            ->where('status', Article::STATUS_SCHEDULED)
            ->where('scheduled_for', '<=', now())
            ->get()
            ->each(function (Article $article) use ($publisher) {
                $publisher->apply($article, 'publish', null);
            });

        return self::SUCCESS;
    }
}
