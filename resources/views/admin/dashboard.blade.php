@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-secondary small">Total accounts</div>
                <div class="h3 mb-0">{{ number_format($stats['users']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-secondary small">Active accounts</div>
                <div class="h3 mb-0">{{ number_format($stats['active_users']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-secondary small">Administrators</div>
                <div class="h3 mb-0">{{ number_format($stats['administrators']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-secondary small">News reporters</div>
                <div class="h3 mb-0">{{ number_format($stats['reporters']) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Recent account activity</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">When</th>
                        <th scope="col">Account</th>
                        <th scope="col">Activity</th>
                        <th scope="col">IP address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentActivity as $entry)
                        <tr>
                            <td class="text-nowrap small">{{ $entry->created_at->timezone(config('app.timezone'))->format('d M Y, H:i') }}</td>
                            <td>{{ $entry->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $entry->description ?? $entry->action }}</td>
                            <td class="small">{{ $entry->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">No activity recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
