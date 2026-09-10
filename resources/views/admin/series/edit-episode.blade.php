{{-- resources/views/admin/series/edit-episode.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Episode - ' . $series->title)

@section('content')
<style>
    .form-section {
        background: #121217;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .form-section h3 {
        color: #e31c25;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #ddd;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        background: #1a1a22;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
    }
    .btn-primary, .btn-secondary, .btn-danger {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-primary {
        background: #e31c25;
        color: white;
        border: none;
    }
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        color: white;
        border: none;
    }
    .btn-danger {
        background: rgba(255,0,0,0.2);
        color: #ff4444;
        border: 1px solid #ff4444;
    }
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .current-file {
        background: rgba(0,255,0,0.1);
        border: 1px solid rgba(0,255,0,0.3);
        padding: 0.5rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        font-size: 0.8rem;
    }
    .progress-bar {
        width: 100%;
        height: 4px;
        background: #2a2a35;
        border-radius: 4px;
        margin-top: 0.5rem;
        display: none;
    }
    .progress-fill {
        height: 100%;
        background: #e31c25;
        border-radius: 4px;
        width: 0%;
        transition: width 0.3s;
    }
</style>

<div style="margin: 0 auto;">
    <h1>Edit Episode</h1>
    <small style="color: #888;">{{ $series->title }} - S{{ $episode->season_number }}E{{ $episode->episode_number }}</small>

    <form method="POST" action="{{ route('admin.series.episodes.update', [$series, $episode]) }}" enctype="multipart/form-data" style="margin-top: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div class="form-section">
            <h3><i class="fas fa-tv"></i> Episode Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Season Number *</label>
                    <input type="number" name="season_number" class="form-control" value="{{ $episode->season_number }}" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Episode Number *</label>
                    <input type="number" name="episode_number" class="form-control" value="{{ $episode->episode_number }}" min="1" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Episode Title *</label>
                <input type="text" name="episode_title" class="form-control" value="{{ $episode->episode_title }}" required>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ $episode->description }}</textarea>
            </div>
            
            <div class="form-group">
                <label>Duration *</label>
                <input type="text" name="duration" class="form-control" value="{{ $episode->duration }}" required>
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-video"></i> Video File</h3>
            
            @if($episode->file_path)
            <div class="current-file">
                <i class="fas fa-file-video"></i> Current file: <strong>{{ basename($episode->file_path) }}</strong>
                <div class="file-info" style="margin-top: 0.25rem;">Size: {{ $episode->file_size ?? 'Unknown' }}</div>
            </div>
            @endif
            
            <div class="form-group" style="margin-top: 1rem;">
                <label>Upload New Video (Optional)</label>
                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,.mkv,.mp4,.avi" onchange="updateVideoInfo()">
                <div class="progress-bar" id="uploadProgress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <small class="file-info">Leave empty to keep current file</small>
            </div>
            
            <div class="form-group">
                <label>File Path (Optional)</label>
                <input type="text" name="file_path" id="file_path" class="form-control" value="{{ $episode->file_path }}">
                <small class="file-info">Only change if you know what you're doing</small>
            </div>
            
            <div class="form-group">
                <label>File Size</label>
                <input type="text" name="file_size" id="file_size" class="form-control" value="{{ $episode->file_size }}">
            </div>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Episode
            </button>
            <a href="{{ route('admin.series.episodes', $series) }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function updateVideoInfo() {
        const file = document.getElementById('video_file').files[0];
        if (file) {
            const sizeInGB = (file.size / (1024 * 1024 * 1024)).toFixed(2);
            document.getElementById('file_size').value = sizeInGB + ' GB';
            
            const progressBar = document.getElementById('uploadProgress');
            progressBar.style.display = 'block';
            
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                document.getElementById('progressFill').style.width = progress + '%';
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        progressBar.style.display = 'none';
                    }, 500);
                }
            }, 200);
        }
    }
</script>
@endsection