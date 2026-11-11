<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\View\View;

class ArticleController extends Controller
{
    private const RELATED_LIMIT = 3;

    public function show(Article $article): View
    {
        abort_unless($article->isPublic(), 404);

        $article->load(['category', 'author:id,name']);

        $approvedComments = $article->comments()
            ->approved()
            ->latest('id')
            ->get();

        $relatedArticles = Article::published()
            ->where('category_id', $article->category_id)
            ->whereKeyNot($article->getKey())
            ->with(['category:id,name,slug', 'author:id,name'])
            ->latest('published_at')
            ->limit(self::RELATED_LIMIT)
            ->get();

        return view('public.article', compact('article', 'relatedArticles', 'approvedComments'));
    }
}
