<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    private const PER_PAGE = 25;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Comment::class);

        $status = (string) $request->query('status', 'all');
        if (! in_array($status, ['all', ...Comment::STATUSES], true)) {
            $status = 'all';
        }

        $comments = Comment::query()
            ->with('article:id,title,slug')
            ->when($status !== 'all', fn ($query) => $query->where('status', $status))
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        $counts = Comment::query()
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        return view('admin.comments.index', [
            'comments' => $comments,
            'counts' => $counts,
            'currentStatus' => $status,
            'pendingCount' => (int) ($counts[Comment::STATUS_PENDING] ?? 0),
        ]);
    }

    public function approve(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('moderate', $comment);

        $comment->update(['status' => Comment::STATUS_APPROVED]);

        ActivityLog::record($request->user(), 'comment.approved', "Approved a comment on \"{$comment->article->title}\".");

        return back()->with('status', 'The comment has been approved and is now shown with the story.');
    }

    public function reject(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('moderate', $comment);

        $comment->update(['status' => Comment::STATUS_REJECTED]);

        ActivityLog::record($request->user(), 'comment.rejected', "Rejected a comment on \"{$comment->article->title}\".");

        return back()->with('status', 'The comment has been rejected and is hidden from the story.');
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('moderate', $comment);

        $comment->delete();

        ActivityLog::record($request->user(), 'comment.deleted', 'Deleted a comment permanently.');

        return back()->with('status', 'The comment has been deleted.');
    }
}
