{{-- resources/views/admin/series/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'TV Series')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <a href="{{ route('admin.series.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Series
        </a>
    </div>
    
    <div>
        <input type="text" id="searchInput" placeholder="Search series..." 
               style="background: #1a1a22; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 10px; color: white; width: 250px;">
    </div>
</div>

<div style="overflow-x: auto;">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Poster</th>
                <th>Title</th>
                <th>Genre</th>
                <th>Year</th>
                <th>Seasons</th>
                <th>Downloads</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($series as $s)
            <tr data-title="{{ strtolower($s->title) }}">
                <td>{{ $s->id }}</td>
                <td>
                    @if($s->poster_path)
                        <img src="{{ $s->poster_path }}" width="50" style="border-radius: 8px;">
                    @else
                        <div style="width: 50px; height: 75px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tv" style="color: #666;"></i>
                        </div>
                    @endif
                </td>
                <td><strong>{{ $s->title }}</strong></td>
                <td>{{ $s->genre }}</td>
                <td>{{ $s->release_year }}</td>
                <td>{{ $s->seasons_count }}</td>
                <td>{{ number_format($s->download_count ?? 0) }}</td>
                <td>
                    <span style="background: {{ $s->is_active ? 'rgba(0,255,0,0.2)' : 'rgba(255,0,0,0.2)' }}; color: {{ $s->is_active ? '#0f0' : '#f00' }}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                        {{ $s->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.series.episodes', $s) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Episodes">
                            <i class="fas fa-list"></i>
                        </a>
                        <a href="{{ route('admin.series.edit', $s) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete({{ $s->id }}, '{{ addslashes($s->title) }}')" class="btn-secondary" style="padding: 0.4rem 0.8rem; background: #e31c25;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                      
                        <form action="  {{ route('admin.series.toggle-status', $s) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </form>
                    </div>
                    <form id="delete-form-{{ $s->id }}" action="{{ route('admin.series.destroy', $s) }}" method="POST" style="display: none;">
                        @csrf @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 3rem;">
                    <i class="fas fa-tv" style="font-size: 3rem; color: #888;"></i>
                    <p style="margin-top: 1rem;">No series found. Click "Add New Series" to create one.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1rem;">
    {{ $series->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Search filter
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const title = row.getAttribute('data-title') || '';
            row.style.display = title.includes(searchTerm) ? '' : 'none';
        });
    });
    
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Delete Series?',
            text: `Are you sure you want to delete "${title}"? All episodes will also be deleted!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            background: '#1a1a22',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
    
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        background: '#1a1a22',
        color: '#fff',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
</script>
@endsection