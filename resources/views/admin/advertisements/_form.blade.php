@extends('layouts.admin')

@section('title', 'Advertisement')
@section('heading', isset($advertisement->id) ? 'Edit advertisement' : 'New advertisement')

@section('content')
    <form method="POST"
          action="{{ isset($advertisement->id) ? route('admin.advertisements.update', $advertisement) : route('admin.advertisements.store') }}"
          novalidate>
        @csrf
        @if (isset($advertisement->id))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Internal name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $advertisement->name) }}" required maxlength="120"
                                   class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Telecom banner, December">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="body_html" class="form-label">Advertisement content (HTML)</label>
                            <textarea id="body_html" name="body_html" rows="10" required maxlength="20000"
                                      class="font-monospace form-control @error('body_html') is-invalid @enderror"
                                      placeholder='<a href="https://example.com"><img src="https://example.com/banner.png" alt="Advert"></a>'>{{ old('body_html', $advertisement->body_html) }}</textarea>
                            @error('body_html')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Paste the HTML snippet from the advertiser. It is shown inside a labelled box on the site.</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   @checked(old('is_active', $advertisement->is_active))>
                            <label class="form-check-label" for="is_active">Active (available to display)</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="placement" class="form-label">Where it appears</label>
                            <select id="placement" name="placement" class="form-select @error('placement') is-invalid @enderror">
                                @foreach (\App\Models\Advertisement::PLACEMENTS as $value => $label)
                                    <option value="{{ $value }}" @selected(old('placement', $advertisement->placement) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('placement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Shape</label>
                            <select id="format" name="format" class="form-select @error('format') is-invalid @enderror">
                                @foreach (\App\Models\Advertisement::FORMATS as $value => $label)
                                    <option value="{{ $value }}" @selected(old('format', $advertisement->format) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Starts</label>
                            <input type="date" id="starts_at" name="starts_at"
                                   value="{{ old('starts_at', $advertisement->starts_at?->format('Y-m-d')) }}"
                                   class="form-control @error('starts_at') is-invalid @enderror">
                            @error('starts_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-0">
                            <label for="ends_at" class="form-label">Ends</label>
                            <input type="date" id="ends_at" name="ends_at"
                                   value="{{ old('ends_at', $advertisement->ends_at?->format('Y-m-d')) }}"
                                   class="form-control @error('ends_at') is-invalid @enderror">
                            @error('ends_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Leave both dates empty for an advert that runs until you switch it off.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2 align-items-center mt-4">
            <button type="submit" class="btn btn-primary">{{ isset($advertisement->id) ? 'Save changes' : 'Create advertisement' }}</button>
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-link text-secondary">Back to advertisements</a>
        </div>
    </form>
@endsection
