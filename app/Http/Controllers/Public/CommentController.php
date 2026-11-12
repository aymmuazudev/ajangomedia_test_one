<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\CommentAwaitingModeration;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommentController extends Controller
{
    private const MODERATOR_ROLES = [
        User::ROLE_SUPER_ADMINISTRATOR,
        User::ROLE_ADMINISTRATOR,
        User::ROLE_MANAGING_EDITOR,
        User::ROLE_COMMENT_MODERATOR,
    ];

    public function store(Request $request, Article $article): RedirectResponse
    {
        abort_unless($article->isPublic(), 404);

        $data = $request->validate([
            'author_name' => ['required', 'string', 'max:80'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ]);

        $comment = Comment::create([
            ...$data,
            'article_id' => $article->id,
            'status' => Comment::STATUS_PENDING,
            'ip_address' => $request->ip(),
        ]);

        $this->notifyModerators($comment);

        return back()->with('comment_status', 'Thank you. Your comment will appear once a moderator approves it.');
    }

    private function notifyModerators(Comment $comment): void
    {
        $moderationUrl = url(route('admin.comments.index', ['status' => 'pending'], false));

        User::query()
            ->where('is_active', true)
            ->whereIn('role', self::MODERATOR_ROLES)
            ->pluck('email')
            ->chunk(50, function ($emails) use ($comment, $moderationUrl) {
                foreach ($emails as $email) {
                    Mail::to($email)->queue(new CommentAwaitingModeration($comment, $moderationUrl));
                }
            });
    }
}
