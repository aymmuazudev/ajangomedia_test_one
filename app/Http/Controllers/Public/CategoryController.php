<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        $articles = $category->articles()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name'])
            ->latest('published_at')
            ->paginate(12);

        return view('public.category', compact('category', 'articles'));
    }
}
