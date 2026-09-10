@extends('admin.layouts.app')

@section('title', 'Edit Trailer')

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
    <h3><i class="fas fa-clapperboard"></i> Edit Trailer</h3>

    @if($trailer->thumbnail)
        <img src="{{ $trailer->thumb_url }}" style="width:100%; max-width:480px; border-radius:12px; border:1px solid var(--border); margin-bottom:1.2rem; display:block;">
    @endif

    <form method="POST" action="{{ route('admin.trailers.update', $trailer) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Trailer Title *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $trailer->title) }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $trailer->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Source Type</label>
            <select name="source_type" id="sourceType" class="form-control" onchange="toggleSource()">
                <option value="youtube" {{ old('source_type', $trailer->source_type) === 'youtube' ? 'selected' : '' }}>YouTube URL</option>
                <option value="file" {{ old('source_type', $trailer->source_type) === 'file' ? 'selected' : '' }}>Upload / File URL</option>
            </select>
        </div>

        <div class="form-group" id="youtubeGroup">
            <label>YouTube Trailer URL</label>
            <input type="url" name="trailer_url" class="form-control" value="{{ old('trailer_url', $trailer->trailer_url) }}" placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div class="form-group" id="fileGroup">
            <label>Video</label>
            @if($trailer->source_type === 'file' && $trailer->file_path)
                <div style="margin-bottom: 0.6rem; background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.3); border-radius: 10px; padding: 0.6rem 0.8rem; font-size: 0.85rem; color: #93c5fd;">
                    <i class="fas fa-file-video"></i> Current file: {{ basename($trailer->file_path) }}
                </div>
            @endif
            <input type="file" name="video_file" class="form-control" accept="video/mp4,video/webm,video/ogg">
            <input type="url" name="file_path_url" class="form-control" style="margin-top: 0.6rem;" placeholder="https://example.com/trailer.mp4" value="{{ old('file_path_url', $trailer->source_type === 'file' ? $trailer->file_path : '') }}">
        </div>

        <div class="form-group">
            <label>Thumbnail</label>
            <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
            <small style="color: var(--text-muted);">Leave empty to keep the current thumbnail.</small>
            <input type="url" name="thumbnail_url" class="form-control" style="margin-top: 0.6rem;" placeholder="https://example.com/thumb.jpg">
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $trailer->is_active) ? 'checked' : '' }} style="width:18px; height:18px;">
                Active (visible on the site)
            </label>
        </div>

        <div style="display:flex; gap: 0.8rem;">
            <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
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
    toggleSource();
</script>
@endsection