<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->isPublic(), 404);

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:80'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        Comment::create([
            ...$data,
            'article_id' => $article->id,
            'status' => Comment::STATUS_PENDING,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('comment_status', 'Thank you. Your comment will appear once a moderator approves it.');
    }
}
