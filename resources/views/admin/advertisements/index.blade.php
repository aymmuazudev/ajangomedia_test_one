@extends('layouts.admin')

@section('title', 'Advertisements')
@section('heading', 'Advertisements')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        @can('create', App\Models\Advertisement::class)
            <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>New advertisement
            </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Placement</th>
                        <th scope="col">Format</th>
                        <th scope="col">Schedule</th>
                        <th scope="col">State</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($advertisements as $advertisement)
                        <tr>
                            <td class="fw-semibold">{{ $advertisement->name }}</td>
                            <td class="small">{{ $advertisement->placementLabel() }}</td>
                            <td class="small">{{ $advertisement->formatLabel() }}</td>
                            <td class="small text-nowrap">
                                @if ($advertisement->starts_at || $advertisement->ends_at)
                                    {{ $advertisement->starts_at?->timezone(config('app.timezone'))->format('j M Y') ?? 'No start' }}
                                    &ndash;
                                    {{ $advertisement->ends_at?->timezone(config('app.timezone'))->format('j M Y') ?? 'No end' }}
                                @else
                                    Always
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $advertisement->isRunning() ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $advertisement->isRunning() ? 'Running' : 'Not running' }}
                                </span>
                            </td>
                            <td class="text-end text-nowrap">
                                @can('update', $advertisement)
                                    <a href="{{ route('admin.advertisements.edit', $advertisement) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                @endcan
                                @can('delete', $advertisement)
                                    <form method="POST" action="{{ route('admin.advertisements.destroy', $advertisement) }}" class="d-inline"
                                          onsubmit="return confirm('Delete this advertisement? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">No advertisements yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $advertisements->links() }}</div>
@endsection
