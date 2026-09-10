{{-- resources/views/admin/series/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Series')

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
    .btn-primary, .btn-secondary {
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
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
    }
    .status-active {
        background: rgba(0,255,0,0.2);
        color: #0f0;
    }
    .status-inactive {
        background: rgba(255,0,0,0.2);
        color: #f00;
    }
</style>

<div style=" margin: 0 auto;">
    <h1>Edit Series: {{ $series->title }}</h1>
    
    <form method="POST" action="{{ route('admin.series.update', $series) }}" enctype="multipart/form-data" style="margin-top: 1.5rem;">
        @csrf
        @method('PUT')
        
        <div class="form-section">
            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
            
            <div class="form-group">
                <label>Series Title *</label>
                <input type="text" name="title" class="form-control" value="{{ $series->title }}" required>
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" class="form-control" rows="4" required>{{ $series->description }}</textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Genre *</label>
                    <select name="genre" class="form-control" required>
                        <option value="">Select Genre</option>
                        <option value="Action" {{ $series->genre == 'Action' ? 'selected' : '' }}>Action</option>
                        <option value="Horror" {{ $series->genre == 'Horror' ? 'selected' : '' }}>Horror</option>
                        <option value="Romance" {{ $series->genre == 'Romance' ? 'selected' : '' }}>Romance</option>
                        <option value="Sci-Fi" {{ $series->genre == 'Sci-Fi' ? 'selected' : '' }}>Sci-Fi</option>
                        <option value="Comedy" {{ $series->genre == 'Comedy' ? 'selected' : '' }}>Comedy</option>
                        <option value="Drama" {{ $series->genre == 'Drama' ? 'selected' : '' }}>Drama</option>
                        <option value="Thriller" {{ $series->genre == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                        <option value="Fantasy" {{ $series->genre == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Release Year *</label>
                    <input type="number" name="release_year" class="form-control" value="{{ $series->release_year }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Language *</label>
                    <input type="text" name="language" class="form-control" value="{{ $series->language }}" required>
                </div>
                
                <div class="form-group">
                    <label>Rating *</label>
                    <select name="rating" class="form-control" required>
                        <option value="PG" {{ $series->rating == 'PG' ? 'selected' : '' }}>PG</option>
                        <option value="PG-13" {{ $series->rating == 'PG-13' ? 'selected' : '' }}>PG-13</option>
                        <option value="R" {{ $series->rating == 'R' ? 'selected' : '' }}>R</option>
                        <option value="TV-MA" {{ $series->rating == 'TV-MA' ? 'selected' : '' }}>TV-MA</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Number of Seasons *</label>
                    <input type="number" name="seasons_count" class="form-control" value="{{ $series->seasons_count }}" min="1" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $series->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$series->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-image"></i> Images</h3>
            
            @if($series->poster_path)
            <div style="margin-bottom: 1rem;">
                <img src="{{ $series->poster_path }}" alt="Current Poster" style="max-width: 150px; border-radius: 8px;">
                <p><small>Current Poster</small></p>
            </div>
            @endif
            
            <div class="form-group">
                <label>Poster URL (Leave empty to keep current)</label>
                <input type="url" name="poster_path" class="form-control" placeholder="https://image.tmdb.org/t/p/w500/xxxx.jpg" value="{{ $series->poster_path }}">
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-video"></i> Trailer</h3>
            
            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="{{ $series->trailer_url }}">
                <div class="file-info">Paste the YouTube link of the series trailer. It will play in a modal on the site.</div>
            </div>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Update Series
            </button>
            <a href="{{ route('admin.series.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection