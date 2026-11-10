@extends('layouts.admin')

@section('title', 'Tags')
@section('heading', 'Tags')

@section('content')
    <p class="text-secondary">Tags are created from the story editor. Rename or remove them here.</p>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Tag</th>
                        <th scope="col">Stories</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr>
                            <td class="fw-semibold">{{ $tag->name }}</td>
                            <td>{{ $tag->articles_count }}</td>
                            <td class="text-end text-nowrap">
                                @can('update', $tag)
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i>Rename
                                    </a>
                                @endcan
                                @can('delete', $tag)
                                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" class="d-inline"
                                          onsubmit="return confirm('Delete the tag {{ $tag->name }}? It will be removed from every story.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-secondary py-4">No tags yet. Add them when you write a story.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $tags->links() }}</div>
@endsection
