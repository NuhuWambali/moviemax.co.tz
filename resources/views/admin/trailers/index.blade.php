@extends('admin.layouts.app')

@section('title', 'Trailers')

@section('content')
    <div class="flex-between" style="margin-bottom: 1.5rem;">
        <div style="display:flex; gap: 0.8rem; align-items:center;">
            <a href="{{ route('admin.trailers.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Upload Trailer
            </a>
        </div>
        <div style="display:flex; gap: 0.5rem;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" id="searchInput" placeholder="Search trailers..."
                       style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; padding: 10px 10px 10px 35px; color: #fff; width: 250px;">
            </div>
        </div>
    </div>

    <div class="card table-card">
        <table class="data-table" id="trailersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Source</th>
                    <th>Views</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="trailersTableBody">
                @forelse($trailers as $trailer)
                <tr data-title="{{ strtolower($trailer->title) }}">
                    <td>{{ $trailer->id }}</td>
                    <td>
                        @if($trailer->thumbnail)
                            <img src="{{ $trailer->thumb_url }}" class="thumb-sm" alt="">
                        @else
                            <div style="width:46px; height:64px; background: var(--bg-deep); border-radius:8px; display:flex; align-items:center; justify-content:center; border:1px solid var(--border);">
                                <i class="fas fa-video" style="color: var(--text-muted);"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $trailer->title }}</strong>
                        @if($trailer->description)
                            <small style="display:block; color: var(--text-muted); max-width: 320px; white-space: normal;">{{ \Illuminate\Support\Str::limit($trailer->description, 60) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($trailer->source_type === 'file')
                            <span class="badge badge-blue"><i class="fas fa-file-video"></i> File</span>
                        @else
                            <span class="badge badge-red"><i class="fab fa-youtube"></i> YouTube</span>
                        @endif
                    </td>
                    <td>{{ number_format($trailer->views) }}</td>
                    <td>{{ $trailer->likesCount() }}</td>
                    <td>{{ $trailer->comments()->count() }}</td>
                    <td>
                        <span class="badge {{ $trailer->is_active ? 'badge-green' : 'badge-red' }}">
                            {{ $trailer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap: 0.4rem;">
                            <a href="{{ route('trailers.show', $trailer->slug) }}" class="btn-secondary btn-sm" target="_blank" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('admin.trailers.edit', $trailer) }}" class="btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                            <button onclick="confirmDelete({{ $trailer->id }}, '{{ $trailer->title }}')" class="btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                            <button onclick="confirmStatusToggle({{ $trailer->id }}, '{{ $trailer->title }}', {{ $trailer->is_active ? 'true' : 'false' }})" class="btn-secondary btn-sm" title="Toggle Status"><i class="fas fa-power-off"></i></button>
                        </div>
                        <form id="delete-form-{{ $trailer->id }}" action="{{ route('admin.trailers.destroy', $trailer) }}" method="POST" style="display:none;">
                            @csrf @method('DELETE')
                        </form>
                        <form id="toggle-form-{{ $trailer->id }}" action="{{ route('admin.trailers.toggle-status', $trailer) }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:3rem; color: var(--text-muted);">
                        <i class="fas fa-video" style="font-size:2.5rem; margin-bottom:1rem; display:block;"></i>
                        No trailers yet. <a href="{{ route('admin.trailers.create') }}" style="color: var(--accent);">Upload the first trailer.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $trailers->links() }}
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#trailersTableBody tr[data-title]').forEach(row => {
            row.style.display = row.getAttribute('data-title').includes(term) ? '' : 'none';
        });
    });

    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Delete trailer?',
            text: `You are about to delete "${title}". This cannot be undone.`,
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

    function confirmStatusToggle(id, title, isActive) {
        const action = isActive ? 'deactivate' : 'activate';
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${action} "${title}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${action} it!`,
            background: '#16223a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById(`toggle-form-${id}`).submit();
        });
    }
</script>
@endsection