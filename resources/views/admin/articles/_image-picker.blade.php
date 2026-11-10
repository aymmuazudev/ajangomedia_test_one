@props(['mediaFiles', 'selected' => null])

<div class="row row-cols-3 g-2 image-picker">
    <div class="col">
        <input type="radio" class="btn-check" name="featured_image_path" id="featured-none" value=""
               @checked(! $selected)>
        <label class="image-picker__option image-picker__none d-flex align-items-center justify-content-center small" for="featured-none">
            No image
        </label>
    </div>
    @foreach ($mediaFiles as $file)
        <div class="col">
            <input type="radio" class="btn-check" name="featured_image_path" id="featured-{{ $file->id }}"
                   value="{{ $file->path }}" @checked($selected === $file->path)>
            <label class="image-picker__option ratio ratio-1x1" for="featured-{{ $file->id }}">
                <img src="{{ route('media.show', ['path' => $file->path]) }}" alt="{{ $file->alt_text ?? $file->original_name }}"
                     loading="lazy" class="object-fit-cover">
            </label>
        </div>
    @endforeach
</div>
<div class="form-text">
    Showing the latest uploads. Add more in the <a href="{{ route('admin.media.index') }}" target="_blank" rel="noopener">media library</a>.
</div>
