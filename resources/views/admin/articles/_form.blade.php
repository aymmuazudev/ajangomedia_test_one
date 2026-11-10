@php
    $scheduledValue = old('scheduled_for', $article->scheduled_for?->timezone(config('app.timezone'))->format('Y-m-d\TH:i'));
    $selectedImage = old('featured_image_path', $article->featured_image_path);
    $tags = old('tags', $tagsText);
    $actions = collect($article->availableActions())
        ->filter(fn ($label, $intent) => auth()->user()->can('transition', [$article, $intent]))
        ->all();
@endphp

<form method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($article->status === \App\Models\Article::STATUS_REJECTED && $article->editor_note)
        <div class="alert alert-warning border-0 mb-4" role="status">
            <div class="fw-semibold mb-1">Sent back for changes</div>
            <div class="text-break">{{ $article->editor_note }}</div>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="title" class="form-label">Headline</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}"
                               class="form-control form-control-lg @error('title') is-invalid @enderror" maxlength="255" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subheadline" class="form-label">Subheadline <span class="text-secondary">(optional)</span></label>
                        <input type="text" id="subheadline" name="subheadline" value="{{ old('subheadline', $article->subheadline) }}"
                               class="form-control @error('subheadline') is-invalid @enderror" maxlength="255">
                        @error('subheadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="summary" class="form-label">Summary <span class="text-secondary">(shown on cards and in search results)</span></label>
                        <textarea id="summary" name="summary" rows="3" maxlength="500"
                                  class="form-control @error('summary') is-invalid @enderror">{{ old('summary', $article->summary) }}</textarea>
                        @error('summary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-0">
                        <label for="body" class="form-label">Story</label>
                        <textarea id="body" name="body" rows="20" required
                                  class="form-control @error('body') is-invalid @enderror">{{ old('body', $article->body) }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Plain text. Line breaks are kept when the story is shown on the site.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Story details</span>
                        @include('admin.articles._status-badge', ['status' => $article->status])
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Section</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">No section</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id', $article->category_id) === $category->id)>
                                    {{ $category->parent ? $category->parent->name.' / ' : '' }}{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" id="tags" name="tags" value="{{ $tags }}"
                               class="form-control @error('tags') is-invalid @enderror" maxlength="500"
                               placeholder="Comma separated, e.g. elections, lagos">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $article->is_featured))>
                        <label class="form-check-label" for="is_featured">Feature on the homepage</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="is_breaking" name="is_breaking" value="1" @checked(old('is_breaking', $article->is_breaking))>
                        <label class="form-check-label" for="is_breaking">Show in the breaking news ticker</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_sponsored" name="is_sponsored" value="1" @checked(old('is_sponsored', $article->is_sponsored))>
                        <label class="form-check-label" for="is_sponsored">Sponsored content</label>
                    </div>

                    <div class="mb-0">
                        <label for="scheduled_for" class="form-label">Publish date and time</label>
                        <input type="datetime-local" id="scheduled_for" name="scheduled_for" value="{{ $scheduledValue }}"
                               class="form-control @error('scheduled_for') is-invalid @enderror">
                        @error('scheduled_for')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Used when you schedule a story. Times use the Lagos time zone.</div>
                    </div>
                </div>
            </div>

            @if (isset($actions['reject']))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <label for="editor_note" class="form-label fw-semibold">Note to the reporter</label>
                        <textarea id="editor_note" name="editor_note" rows="4" maxlength="2000"
                                  class="form-control @error('editor_note') is-invalid @enderror"
                                  placeholder="What needs to change before this story can be approved?">{{ old('editor_note') }}</textarea>
                        @error('editor_note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Only needed when you send a story back. The reporter sees this note.</div>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="fw-semibold mb-3">Featured image</div>
                    @include('admin.articles._image-picker', ['mediaFiles' => $mediaFiles, 'selected' => $selectedImage])
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center mt-4">
        <button type="submit" name="intent" value="save" class="btn btn-outline-primary">
            {{ $article->exists ? 'Save changes' : 'Save draft' }}
        </button>
        @foreach ($actions as $intent => $label)
            <button type="submit" name="intent" value="{{ $intent }}"
                    class="btn {{ match ($intent) { 'publish', 'approve', 'submit' => 'btn-primary', 'reject' => 'btn-outline-danger', default => 'btn-outline-secondary' } }}">
                {{ $label }}
            </button>
        @endforeach
        <a href="{{ route('admin.articles.index') }}" class="btn btn-link text-secondary">Back to stories</a>
    </div>
</form>
