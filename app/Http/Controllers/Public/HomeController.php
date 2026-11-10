<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const HERO_LIMIT = 3;
    private const LATEST_LIMIT = 9;
    private const SECTION_LIMIT = 4;
    private const MOST_READ_LIMIT = 5;
    private const BREAKING_LIMIT = 5;

    public function index(): View
    {
        $featured = Article::published()
            ->where('is_featured', true)
            ->with(['category', 'author:id,name'])
            ->latest('published_at')
            ->limit(self::HERO_LIMIT)
            ->get();

        $heroStories = $featured->isNotEmpty()
            ? $featured
            : Article::published()
                ->with(['category', 'author:id,name'])
                ->latest('published_at')
                ->limit(self::HERO_LIMIT)
                ->get();

        $latestStories = Article::published()
            ->with(['category', 'author:id,name'])
            ->whereNotIn('id', $heroStories->pluck('id'))
            ->latest('published_at')
            ->limit(self::LATEST_LIMIT)
            ->get();

        $categorySections = Category::whereNull('parent_id')
            ->orderBy('display_order')
            ->get()
            ->each(function (Category $category) {
                $category->setRelation('latestPublished', $category->articles()
                    ->published()
                    ->with(['category:id,name,slug', 'author:id,name'])
                    ->latest('published_at')
                    ->limit(self::SECTION_LIMIT)
                    ->get());
            })
            ->filter(fn (Category $category) => $category->latestPublished->isNotEmpty());

        $mostRead = Article::published()
            ->where('view_count', '>', 0)
            ->with('author:id,name')
            ->orderByDesc('view_count')
            ->limit(self::MOST_READ_LIMIT)
            ->get();

        $breakingNews = Article::published()
            ->where('is_breaking', true)
            ->latest('published_at')
            ->limit(self::BREAKING_LIMIT)
            ->get(['id', 'title', 'slug']);

        return view('public.home', compact(
            'heroStories',
            'latestStories',
            'categorySections',
            'mostRead',
            'breakingNews',
        ));
    }
}
