<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    private const MIN_TERM_LENGTH = 2;
    private const MAX_TERM_LENGTH = 100;

    public function index(Request $request): View
    {
        $term = trim((string) $request->query('q', ''));
        $term = mb_substr($term, 0, self::MAX_TERM_LENGTH);

        $articles = null;

        if (mb_strlen($term) >= self::MIN_TERM_LENGTH) {
            $articles = Article::published()
                ->search($term)
                ->with(['category', 'author:id,name'])
                ->latest('published_at')
                ->paginate(12)
                ->withQueryString();
        }

        return view('public.search', compact('term', 'articles'));
    }
}
