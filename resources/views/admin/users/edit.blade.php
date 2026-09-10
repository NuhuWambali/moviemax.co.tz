@extends('admin.layouts.app')

@section('title', 'Edit User')

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
    <h3><i class="fas fa-user-edit"></i> Edit User</h3>
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label>New Password (leave empty to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label>Type *</label>
            <select name="user_type" class="form-control" required>
                <option value="user" {{ old('user_type', $user->user_type) === 'user' ? 'selected' : '' }}>Normal User</option>
                <option value="staff" {{ old('user_type', $user->user_type) === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ old('user_type', $user->user_type) === 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="width:18px; height:18px;">
                Active (can log in)
            </label>
        </div>

        <div style="display:flex; gap: 0.8rem;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection