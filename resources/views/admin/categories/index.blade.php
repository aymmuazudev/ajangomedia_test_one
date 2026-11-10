@extends('layouts.admin')

@section('title', 'Sections')
@section('heading', 'Sections')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New section
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Parent</th>
                        <th scope="col">Order</th>
                        <th scope="col">Stories</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td>{{ $category->parent?->name ?? 'Top level' }}</td>
                            <td>{{ $category->display_order }}</td>
                            <td>{{ $category->articles_count }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                @can('delete', $category)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
                                          onsubmit="return confirm('Delete the section {{ $category->name }}?');">
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
                        <tr><td colspan="5" class="text-center text-secondary py-4">No sections yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
