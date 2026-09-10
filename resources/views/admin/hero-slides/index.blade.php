{{-- resources/views/admin/hero-slides/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Hero Slides')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <a href="{{ route('admin.hero-slides.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Add New Slide
        </a>

        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">These are the wide banner images shown on the home page hero slideshow.</span>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Total Slides:</span>
            <strong style="color: #e31c25; margin-left: 0.5rem;">{{ $slides->total() }}</strong>
        </div>
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Active:</span>
            <strong style="color: #0f0; margin-left: 0.5rem;">{{ $slides->where('is_active', true)->count() }}</strong>
        </div>
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Inactive:</span>
            <strong style="color: #f00; margin-left: 0.5rem;">{{ $slides->where('is_active', false)->count() }}</strong>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success" style="padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Trailer</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slides as $slide)
                <tr>
                    <td>{{ $slide->sort_order }}</td>
                    <td>
                        @if($slide->image_path)
                            <img src="{{ $slide->image_path }}" width="100" height="40" style="border-radius: 8px; object-fit: cover;">
                        @else
                            <div style="width: 100px; height: 40px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #666;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $slide->title }}</strong>
                        @if($slide->tagline)
                            <small style="display: block; color: #888;">{{ $slide->tagline }}</small>
                        @endif
                    </td>
                    <td>
                        @if($slide->trailer_url)
                            <span style="background: rgba(227,28,37,0.2); padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.75rem;">
                                <i class="fas fa-video"></i> Trailer
                            </span>
                        @else
                            <span style="color: #666; font-size: 0.8rem;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($slide->link_type === 'movie')
                            <span style="font-size: 0.8rem; color: #aaa;">Movie #{{ $slide->link_id }}</span>
                        @elseif($slide->link_type === 'series')
                            <span style="font-size: 0.8rem; color: #aaa;">Series #{{ $slide->link_id }}</span>
                        @else
                            <span style="color: #666; font-size: 0.8rem;">None</span>
                        @endif
                    </td>
                    <td>
                        <span style="background: {{ $slide->is_active ? 'rgba(0,255,0,0.2)' : 'rgba(255,0,0,0.2)' }}; color: {{ $slide->is_active ? '#0f0' : '#f00' }}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                            {{ $slide->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $slide->id }}, '{{ $slide->title }}')" class="btn-secondary" style="padding: 0.4rem 0.8rem; background: #e31c25;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button onclick="confirmStatusToggle({{ $slide->id }}, '{{ $slide->title }}', {{ $slide->is_active ? 'true' : 'false' }})" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </div>
                        <form id="delete-form-{{ $slide->id }}" action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" style="display: none;">
                            @csrf @method('DELETE')
                        </form>
                        <form id="toggle-form-{{ $slide->id }}" action="{{ route('admin.hero-slides.toggle-status', $slide) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #888; padding: 2rem;">
                        No hero slides yet. Click "Add New Slide" to create your first banner.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $slides->links() }}
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Delete slide?',
            text: title,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel'
        }).then(res => {
            if (res.isConfirmed) document.getElementById('delete-form-' + id).submit();
        });
    }
    function confirmStatusToggle(id, title, active) {
        Swal.fire({
            title: active ? 'Deactivate slide?' : 'Activate slide?',
            text: title,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            confirmButtonText: active ? 'Deactivate' : 'Activate',
            cancelButtonText: 'Cancel'
        }).then(res => {
            if (res.isConfirmed) document.getElementById('toggle-form-' + id).submit();
        });
    }
</script>
@endsection