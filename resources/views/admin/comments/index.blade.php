@extends('layouts.admin')

@section('title', 'Comments')
@section('heading', 'Comment moderation')

@section('content')
    <ul class="nav nav-pills mb-3 gap-2">
        <li class="nav-item">
            <a class="nav-link {{ $currentStatus === 'all' ? 'active' : 'text-secondary' }}"
               href="{{ route('admin.comments.index') }}">
                All <span class="badge text-bg-light text-dark ms-1">{{ array_sum($counts) }}</span>
            </a>
        </li>
        @foreach (\App\Models\Comment::STATUS_LABELS as $value => $label)
            <li class="nav-item">
                <a class="nav-link {{ $currentStatus === $value ? 'active' : 'text-secondary' }}"
                   href="{{ route('admin.comments.index', ['status' => $value]) }}">
                    {{ $label }} <span class="badge text-bg-light text-dark ms-1">{{ $counts[$value] ?? 0 }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Comment</th>
                        <th scope="col">Story</th>
                        <th scope="col">Status</th>
                        <th scope="col">Received</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($comments as $comment)
                        <tr>
                            <td style="max-width: 380px;">
                                <div class="fw-semibold">{{ $comment->author_name }}</div>
                                <div class="text-break">{{ $comment->body }}</div>
                                @if ($comment->author_email)
                                    <div class="small text-secondary">{{ $comment->author_email }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($comment->article)
                                    <a href="{{ route('articles.show', $comment->article) }}" target="_blank" rel="noopener"
                                       class="text-decoration-none">{{ \Illuminate\Support\Str::limit($comment->article->title, 50) }}</a>
                                @else
                                    <span class="text-secondary">Removed story</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ ['pending' => 'text-bg-warning', 'approved' => 'text-bg-success', 'rejected' => 'text-bg-danger'][$comment->status] ?? 'text-bg-secondary' }}">
                                    {{ $comment->statusLabel() }}
                                </span>
                            </td>
                            <td class="small text-nowrap">{{ $comment->created_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}</td>
                            <td class="text-end text-nowrap">
                                <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                    @if ($comment->status !== \App\Models\Comment::STATUS_APPROVED)
                                        <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                        </form>
                                    @endif
                                    @if ($comment->status !== \App\Models\Comment::STATUS_REJECTED)
                                        <form method="POST" action="{{ route('admin.comments.reject', $comment) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Reject</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                                          onsubmit="return confirm('Delete this comment permanently? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">No comments here yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $comments->links() }}</div>
@endsection
