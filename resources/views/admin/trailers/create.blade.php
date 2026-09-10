@extends('admin.layouts.app')

@section('title', 'Upload Trailer')

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

<div class="card" style="max-width: 720px;">
    <h3><i class="fas fa-clapperboard"></i> Upload a New Trailer</h3>
    <form method="POST" action="{{ route('admin.trailers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Trailer Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g., Dune: Part Two – Official Trailer">
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" placeholder="Short description shown on the trailer page">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Source Type</label>
            <select name="source_type" id="sourceType" class="form-control" onchange="toggleSource()">
                <option value="youtube" {{ old('source_type') === 'file' ? '' : 'selected' }}>YouTube URL</option>
                <option value="file" {{ old('source_type') === 'file' ? 'selected' : '' }}>Upload / File URL</option>
            </select>
        </div>

        <div class="form-group" id="youtubeGroup">
            <label>YouTube Trailer URL *</label>
            <input type="url" name="trailer_url" class="form-control" value="{{ old('trailer_url') }}" placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div class="form-group" id="fileGroup" style="display: none;">
            <label>Upload Video File</label>
            <input type="file" name="video_file" class="form-control" accept="video/mp4,video/webm,video/ogg">
            <small style="color: var(--text-muted);">MP4/WebM/Ogg. Alternatively provide a direct file URL below.</small>
            <input type="url" name="file_path_url" class="form-control" style="margin-top: 0.6rem;" placeholder="https://example.com/trailer.mp4">
        </div>

        <div class="form-group">
            <label>Thumbnail Image</label>
            <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
            <small style="color: var(--text-muted);">Wide thumbnail recommended (1280x720). Alternatively paste an image URL below.</small>
            <input type="url" name="thumbnail_url" class="form-control" style="margin-top: 0.6rem;" placeholder="https://example.com/thumb.jpg">
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" checked style="width:18px; height:18px;">
                Active (visible on the site)
            </label>
        </div>

        <div style="display:flex; gap: 0.8rem;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Trailer</button>
            <a href="{{ route('admin.trailers.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function toggleSource() {
        const type = document.getElementById('sourceType').value;
        document.getElementById('youtubeGroup').style.display = type === 'youtube' ? '' : 'none';
        document.getElementById('fileGroup').style.display = type === 'file' ? '' : 'none';
    }
</script>
@endsection