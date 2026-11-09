<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sign in') | AJANGO MEDIA</title>
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
        .guest-brand-accent { color: var(--ajango-orange); }
    </style>
</head>
<body class="bg-light">
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="text-center mb-4">
                <span class="h4 fw-bold text-black">AJANGO MEDIA <span class="guest-brand-accent">Admin</span></span>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    <h1 class="h4 mb-4">@yield('heading')</h1>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
