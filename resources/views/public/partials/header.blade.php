<header class="site-header bg-white">
    <div class="container d-flex align-items-center justify-content-between gap-3 py-3">
        <a href="{{ route('home') }}" class="brand-mark text-decoration-none" aria-label="AJANGO MEDIA home">
            AJANGO MEDIA<span>.</span>
            <span class="brand-mark__legal">LTD</span>
        </a>

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light site-icon-button"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#siteSearch"
                    aria-expanded="false"
                    aria-controls="siteSearch"
                    aria-label="Open search">
                <i class="bi bi-search" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="collapse border-top bg-light" id="siteSearch">
        <div class="container py-3">
            <form method="GET" action="{{ route('search') }}" role="search" class="d-flex gap-2">
                <label for="site-search-input" class="visually-hidden">Search news</label>
                <input type="search"
                       id="site-search-input"
                       name="q"
                       value="{{ request('q') }}"
                       class="form-control form-control-lg"
                       placeholder="Search headlines, stories and reports"
                       minlength="2"
                       maxlength="100"
                       required>
                <button type="submit" class="btn btn-primary px-4">Search</button>
            </form>
        </div>
    </div>
</header>
