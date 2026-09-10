{{-- resources/views/admin/series/episodes.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Episodes - ' . $series->title)

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1>{{ $series->title }}</h1>
        <small style="color: #888;">Manage episodes for this series</small>
    </div>
    <div style="display: flex; gap: 1rem;">
        <a href="{{ route('admin.series.episodes.create', $series) }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add Episode
        </a>
        <a href="{{ route('admin.series.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Series
        </a>
    </div>
</div>

@if($episodes->count() > 0)
<div style="overflow-x: auto;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Season</th>
                <th>Episode</th>
                <th>Title</th>
                <th>Duration</th>
                <th>File Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($episodes as $episode)
            <tr>
                <td><span class="season-badge">Season {{ $episode->season_number }}</span></td>
                <td><span class="episode-badge">Episode {{ $episode->episode_number }}</span></td>
                <td>
                    <strong>{{ $episode->episode_title ?? $episode->title }}</strong>
                    @if($episode->description)
                    <br><small style="color: #888;">{{ \Illuminate\Support\Str::limit($episode->description, 60) }}</small>
                    @endif
                </td>
                <td>{{ $episode->duration }}</td>
                <td>{{ $episode->file_size ?? 'N/A' }}</td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('admin.series.episodes.edit', [$series, $episode->id]) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDeleteEpisode({{ $episode->id }}, 'S{{ $episode->season_number }}E{{ $episode->episode_number }}')" class="btn-secondary" style="padding: 0.4rem 0.8rem; background: #e31c25;" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-episode-form-{{ $episode->id }}" action="{{ route('admin.series.episodes.destroy', [$series, $episode->id]) }}" method="POST" style="display: none;">
                        @csrf @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div style="text-align: center; padding: 3rem; background: #121217; border-radius: 16px;">
    <i class="fas fa-tv" style="font-size: 3rem; color: #888;"></i>
    <p style="margin-top: 1rem; color: #888;">No episodes yet. Click "Add Episode" to create one.</p>
    <a href="{{ route('admin.series.episodes.create', $series) }}" class="btn-primary" style="margin-top: 1rem;">
        <i class="fas fa-plus"></i> Add First Episode
    </a>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteEpisode(id, title) {
        Swal.fire({
            title: 'Delete Episode?',
            text: `Are you sure you want to delete "${title}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#1a1a22',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-episode-form-${id}`).submit();
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
    
    @if($errors->any())
    Swal.fire({
        title: 'Error!',
        text: '{{ $errors->first() }}',
        icon: 'error',
        background: '#1a1a22',
        color: '#fff',
        confirmButtonColor: '#e31c25'
    });
    @endif
</script>

<style>
    .season-badge {
        background: rgba(227,28,37,0.2);
        color: #e31c25;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .episode-badge {
        background: white;
        color: #000;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>
@endsection