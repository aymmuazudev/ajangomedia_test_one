<nav class="navbar navbar-expand-lg bg-white site-nav border-bottom" aria-label="Main navigation">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation"
                aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavigation">
            @php
                $activeCategory = request()->route('category');
            @endphp
            <ul class="navbar-nav site-nav__list">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"
                       @if (request()->routeIs('home')) aria-current="page" @endif>Home</a>
                </li>
                @foreach ($navigationCategories as $category)
                    @php
                        $isActive = $activeCategory instanceof \App\Models\Category && $activeCategory->is($category);
                    @endphp
                    <li class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ route('categories.show', $category) }}"
                           @if ($isActive) aria-current="page" @endif>{{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>
