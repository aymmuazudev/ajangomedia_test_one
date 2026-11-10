@props(['status'])

@php
    $badgeClass = match ($status) {
        'published' => 'text-bg-success',
        'scheduled' => 'text-bg-info',
        'unpublished' => 'text-bg-warning',
        'archived' => 'text-bg-dark',
        default => 'text-bg-secondary',
    };
@endphp

<span class="badge {{ $badgeClass }}">{{ \App\Models\Article::STATUS_LABELS[$status] ?? ucfirst($status) }}</span>
