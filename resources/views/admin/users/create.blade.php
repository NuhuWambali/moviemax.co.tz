@extends('admin.layouts.app')

@section('title', 'Create System User')

@section('content')
@if($errors->any())
    <div class="alert alert-error">
        <div>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
        <button onclick="this.parentElement.remove()">&times;</button>
    </div>
@endif

<div class="card" style="max-width: 560px;">
    <h3><i class="fas fa-user-plus"></i> Create System User</h3>
    <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.2rem;">
        System users can log in and access the admin panel. Normal visitors register from the main site.
    </p>
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role *</label>
            <select name="user_type" class="form-control" required>
                <option value="staff" {{ old('user_type') === 'admin' ? '' : 'selected' }}>Staff (manage content)</option>
                <option value="admin" {{ old('user_type') === 'admin' ? 'selected' : '' }}>Administrator (full access)</option>
            </select>
        </div>

        <div style="display:flex; gap: 0.8rem;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection