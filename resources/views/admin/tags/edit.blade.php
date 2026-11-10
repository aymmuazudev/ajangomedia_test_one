@extends('layouts.admin')

@section('title', 'Rename tag')
@section('heading', 'Rename tag')

@section('content')
    <div class="row">
        <div class="col-lg-7 col-xl-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.tags.update', $tag) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">Tag name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $tag->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" maxlength="60" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
