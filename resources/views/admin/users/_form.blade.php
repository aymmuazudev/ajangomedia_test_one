@php
    $isActive = old() ? (bool) old('is_active') : ($user ? $user->is_active : true);
@endphp

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
                        <label for="name" class="form-label">Full name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user?->name) }}"
                               class="form-control @error('name') is-invalid @enderror" maxlength="120" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user?->email) }}"
                               class="form-control @error('email') is-invalid @enderror" maxlength="255" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $user?->role ?? 'news_reporter') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked($isActive)>
                        <label class="form-check-label" for="is_active">Account is active</label>
                        <div class="form-text">Deactivated accounts cannot sign in.</div>
                    </div>

                    @if (! $user)
                        <p class="small text-secondary">The new user will receive an email with a link to choose their own password.</p>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
