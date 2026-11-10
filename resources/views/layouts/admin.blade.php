<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Administration') | AJANGO MEDIA</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --ajango-blue: #0058D4;
            --ajango-orange: #FE7600;
            --ajango-black: #000000;
        }
        .btn-primary {
            --bs-btn-bg: var(--ajango-blue);
            --bs-btn-border-color: var(--ajango-blue);
            --bs-btn-hover-bg: #0047AA;
            --bs-btn-hover-border-color: #0047AA;
            --bs-btn-active-bg: #0047AA;
            --bs-btn-active-border-color: #0047AA;
            --bs-btn-disabled-bg: var(--ajango-blue);
            --bs-btn-disabled-border-color: var(--ajango-blue);
        }
        .admin-brand-accent { color: var(--ajango-orange); }
        .admin-sidebar .nav-link { color: #1f2937; border-left: 3px solid transparent; }
        .admin-sidebar .nav-link:hover { color: var(--ajango-blue); }
        .admin-sidebar .nav-link.active { color: var(--ajango-blue); border-left-color: var(--ajango-blue); background: #f5f6f8; font-weight: 600; }
        .image-picker__option { display: block; cursor: pointer; border: 2px solid transparent; border-radius: .375rem; overflow: hidden; background: #f5f6f8; }
        .image-picker__none { min-height: 4.5rem; }
        .btn-check:checked + .image-picker__option { border-color: var(--ajango-blue); }
        .btn-check:focus-visible + .image-picker__option { outline: 2px solid var(--ajango-blue); outline-offset: 2px; }
        .image-picker .ratio > img { position: absolute; inset: 0; width: 100%; height: 100%; }
    </style>
</head>
<body class="bg-light">
<header class="navbar navbar-dark px-3 py-2" style="background-color: var(--ajango-black);">
    <a class="navbar-brand fw-semibold" href="{{ route('admin.dashboard') }}">
        AJANGO MEDIA <span class="admin-brand-accent">Admin</span>
    </a>
    <div class="d-flex align-items-center gap-3 ms-auto">
        <span class="text-white small d-none d-sm-inline">{{ auth()->user()->name }} &middot; {{ auth()->user()->roleLabel() }}</span>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right me-1"></i>Sign out
            </button>
        </form>
    </div>
</header>

<div class="container-fluid">
    <div class="row">
        <aside class="col-lg-2 bg-white border-end admin-sidebar py-3">
            <nav class="nav flex-lg-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-house me-2"></i>Dashboard
                </a>
                @can('viewAny', App\Models\Article::class)
                    <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}" href="{{ route('admin.articles.index') }}">
                        <i class="bi bi-file-earmark-text me-2"></i>Stories
                    </a>
                @endcan
                @can('viewAny', App\Models\MediaFile::class)
                    <a class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                        <i class="bi bi-images me-2"></i>Media library
                    </a>
                @endcan
                @can('viewAny', App\Models\Category::class)
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-grid me-2"></i>Sections
                    </a>
                @endcan
                @can('viewAny', App\Models\Tag::class)
                    <a class="nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
                        <i class="bi bi-tags me-2"></i>Tags
                    </a>
                @endcan
                @can('viewAny', App\Models\User::class)
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-people me-2"></i>Users
                    </a>
                @endcan
            </nav>
        </aside>

        <main class="col-lg-10 py-4 px-3 px-lg-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="h3 mb-4">@yield('heading')</h1>
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
