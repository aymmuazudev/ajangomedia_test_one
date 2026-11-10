@extends('layouts.admin')

@section('title', 'Stories')
@section('heading', auth()->user()->isNewsReporter() ? 'My stories' : 'Stories')

@section('content')
    @if ($reporterCounts !== null)
        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
            @foreach ([
                ['label' => 'Drafts', 'status' => \App\Models\Article::STATUS_DRAFT],
                ['label' => 'Awaiting review', 'status' => \App\Models\Article::STATUS_SUBMITTED],
                ['label' => 'Sent back to you', 'status' => \App\Models\Article::STATUS_REJECTED],
                ['label' => 'Published', 'status' => \App\Models\Article::STATUS_PUBLISHED],
            ] as $card)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="small text-secondary">{{ $card['label'] }}</div>
                            <div class="fs-3 fw-semibold">{{ $reporterCounts[$card['status']] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <form method="GET" action="{{ route('admin.articles.index') }}" class="d-flex flex-wrap gap-2" role="search">
            <input type="search" name="q" value="{{ $filters['search'] }}" class="form-control" placeholder="Search headlines" aria-label="Search stories">
            <select name="status" class="form-select w-auto" aria-label="Filter by status">
                <option value="">All statuses</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="category" class="form-select w-auto" aria-label="Filter by section">
                <option value="0">All sections</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected($filters['categoryId'] === $category->id)>
                        {{ $category->parent ? $category->parent->name.' / ' : '' }}{{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-secondary">Filter</button>
        </form>
        @can('create', \App\Models\Article::class)
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>New story
            </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Headline</th>
                        <th scope="col">Section</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                        <th scope="col">Flags</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td>
                                @can('update', $article)
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="fw-semibold text-decoration-none">{{ $article->title }}</a>
                                @else
                                    <span class="fw-semibold">{{ $article->title }}</span>
                                @endcan
                                <div class="small text-secondary">{{ $article->author?->name }}</div>
                            </td>
                            <td>{{ $article->category?->name ?? 'None' }}</td>
                            <td>@include('admin.articles._status-badge', ['status' => $article->status])</td>
                            <td class="small text-nowrap">
                                @if ($article->status === \App\Models\Article::STATUS_SCHEDULED && $article->scheduled_for)
                                    Scheduled {{ $article->scheduled_for->timezone(config('app.timezone'))->format('j M Y, H:i') }}
                                @elseif ($article->published_at)
                                    Published {{ $article->published_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}
                                @else
                                    Updated {{ $article->updated_at->timezone(config('app.timezone'))->format('j M Y, H:i') }}
                                @endif
                            </td>
                            <td class="small">
                                @if ($article->is_featured) <span class="badge text-bg-primary">Featured</span> @endif
                                @if ($article->is_breaking) <span class="badge text-bg-warning">Breaking</span> @endif
                                @if ($article->is_sponsored) <span class="badge text-bg-secondary">Sponsored</span> @endif
                            </td>
                            <td class="text-end text-nowrap">
                                @can('update', $article)
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">No stories match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $articles->links() }}</div>
@endsection
