<div class="site-top-bar small">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <span class="top-bar-date">
            <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
            {{ now()->timezone(config('app.timezone'))->translatedFormat('l, j F Y') }}
        </span>
        <span class="d-none d-md-inline">News. Information. Impact.</span>
    </div>
</div>
