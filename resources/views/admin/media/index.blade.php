@extends('layouts.admin')

@section('title', 'Media library')
@section('heading', 'Media library')

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="row g-3 align-items-end" novalidate>
                @csrf
                <div class="col-md-6">
                    <label for="files" class="form-label">Images</label>
                    <input type="file" id="files" name="files[]" multiple accept="image/jpeg,image/png,image/webp"
                           class="form-control" required>
                    <div class="form-text">JPG, PNG or WebP. Up to 8 MB each, 10 files at a time.</div>
                </div>
                <div class="col-md-4">
                    <label for="alt_text" class="form-label">Description for screen readers <span class="text-secondary">(optional)</span></label>
                    <input type="text" id="alt_text" name="alt_text" maxlength="255" class="form-control" value="{{ old('alt_text') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if ($files->isEmpty())
        <div class="text-center text-secondary py-5">No images uploaded yet.</div>
    @else
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
            @foreach ($files as $file)
                <div class="col">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="ratio ratio-4x3 bg-light">
                            <img src="{{ route('media.show', ['path' => $file->path]) }}" alt="{{ $file->alt_text ?? $file->original_name }}"
                                 loading="lazy" class="object-fit-cover card-img-top">
                        </div>
                        <div class="card-body small">
                            <div class="fw-semibold text-truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</div>
                            <div class="text-secondary">
                                {{ $file->width && $file->height ? $file->width.' x '.$file->height.' px' : 'Image' }}
                                &middot; {{ number_format($file->size / 1024, 0) }} KB
                            </div>
                            <div class="text-secondary">Uploaded {{ $file->created_at->timezone(config('app.timezone'))->format('j M Y') }}</div>
                        </div>
                        @can('delete', $file)
                            <div class="card-footer bg-white border-0 pt-0">
                                <form method="POST" action="{{ route('admin.media.destroy', $file) }}"
                                      onsubmit="return confirm('Delete this image from the media library?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">{{ $files->links() }}</div>
    @endif
@endsection
