<footer class="site-footer mt-auto">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="brand-mark brand-mark--light mb-3">
                    AJANGO MEDIA<span>.</span>
                    <span class="brand-mark__legal">LTD</span>
                </div>
                <p class="mb-0">
                    AJANGO MEDIA LTD is a digital news, multimedia and publishing company covering Nigeria, Africa and the world.
                </p>
            </div>

            <div class="col-6 col-lg-3">
                <h2 class="footer-heading h6 text-uppercase mb-3">Sections</h2>
                <ul class="list-unstyled mb-0 footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @foreach ($navigationCategories->take(8) as $category)
                        <li><a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h2 class="footer-heading h6 text-uppercase mb-3">More sections</h2>
                <ul class="list-unstyled mb-0 footer-links">
                    @foreach ($navigationCategories->slice(8) as $category)
                        <li><a href="{{ route('categories.show', $category) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-column flex-md-row justify-content-between gap-2 mt-5 pt-4 small">
            <span>&copy; {{ now()->timezone(config('app.timezone'))->year }} AJANGO MEDIA LTD. All rights reserved.</span>
        </div>
    </div>
</footer>
