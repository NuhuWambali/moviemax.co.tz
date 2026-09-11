@extends('admin.layouts.app')

@section('title', 'System Users')

@section('content')
    <div class="flex-between" style="margin-bottom: 1.5rem;">
        <div style="display:flex; gap: 0.8rem; align-items:center;">
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <i class="fas fa-user-plus"></i> Create System User
            </a>
        </div>
        <form method="GET" style="display:flex; gap: 0.5rem;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                       style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 10px 10px 10px 35px; color: #fff; width: 220px;">
            </div>
            <select name="type" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 10px; color:#fff;">
                <option value="">All Types</option>
                <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>Normal Users</option>
                <option value="staff" {{ request('type') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Administrators</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>

    <div class="card table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Favorites</th>
                    <th>Comments</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <small style="display:block; color: var(--text-muted);">{{ $user->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->user_type === 'admin')
                            <span class="badge badge-red"><i class="fas fa-crown"></i> Admin</span>
                        @elseif($user->user_type === 'staff')
                            <span class="badge badge-blue"><i class="fas fa-user-cog"></i> Staff</span>
                        @else
                            <span class="badge badge-purple"><i class="fas fa-user"></i> User</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">{{ $user->is_active ? 'Active' : 'Disabled' }}</span>
                    </td>
                    <td>{{ $user->favorites_count }}</td>
                    <td>{{ $user->comments_count }}</td>
                    <td>{{ $user->created_at->diffForHumans() }}</td>
                    <td>
                        <div style="display:flex; gap: 0.4rem;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            @if($user->id !== auth()->id())
                                <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" class="btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                                <button onclick="confirmToggle({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }})" class="btn-secondary btn-sm" title="Toggle Status"><i class="fas fa-power-off"></i></button>
                            @else
                                <span style="color: var(--text-muted); font-size: 0.75rem;">(you)</span>
                            @endif
                        </div>
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        <form id="toggle-form-{{ $user->id }}" action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; padding:3rem; color: var(--text-muted);">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $users->links() }}
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Delete user?',
            text: `You are about to delete "${name}". This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            background: '#16223a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById(`delete-form-${id}`).submit();
        });
    }
    function confirmToggle(id, name, isActive) {
        const action = isActive ? 'disable' : 'enable';
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${action} "${name}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${action}!`,
            background: '#16223a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById(`toggle-form-${id}`).submit();
        });
    }
</script>
@endsection