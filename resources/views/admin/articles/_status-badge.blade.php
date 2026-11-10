@props(['status'])

@php
    $badgeClass = match ($status) {
        'published' => 'text-bg-success',
        'scheduled' => 'text-bg-info',
        'submitted' => 'text-bg-warning',
        'approved' => 'text-bg-primary',
        'rejected' => 'text-bg-danger',
        'unpublished' => 'text-bg-warning',
        'archived' => 'text-bg-dark',
        default => 'text-bg-secondary',
    };
@endphp

<span class="badge {{ $badgeClass }}">{{ \App\Models\Article::STATUS_LABELS[$status] ?? ucfirst($status) }}</span>
