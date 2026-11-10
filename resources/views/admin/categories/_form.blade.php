<div class="row">
    <div class="col-lg-7 col-xl-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ $action }}" novalidate>
                    @csrf
                    @if ($method !== 'POST')
                        @method($method)
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Section name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                               class="form-control @error('name') is-invalid @enderror" maxlength="80" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($category->exists)
                            <div class="form-text">The web address of this section does not change when you rename it.</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent section</label>
                        <select id="parent_id" name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                            <option value="">Top level (shown in the main menu)</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}" @selected((int) old('parent_id', $category->parent_id) === $parent->id)>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-secondary">(optional)</span></label>
                        <textarea id="description" name="description" rows="3" maxlength="500"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="display_order" class="form-label">Display order</label>
                        <input type="number" id="display_order" name="display_order" min="0" max="9999"
                               value="{{ old('display_order', $category->display_order) }}"
                               class="form-control @error('display_order') is-invalid @enderror" required>
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Lower numbers appear first in the menu.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
