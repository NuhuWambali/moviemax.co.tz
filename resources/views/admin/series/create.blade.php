{{-- resources/views/admin/series/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add Series')

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
</style>

<div style=" margin: 0 auto;">
    <h1>Add New Series</h1>
    
    <form method="POST" action="{{ route('admin.series.store') }}" enctype="multipart/form-data" style="margin-top: 1.5rem;">
        @csrf
        
        <div class="form-section">
            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
            
            <div class="form-group">
                <label>Series Title *</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g., Stranger Things">
            </div>
            
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" class="form-control" rows="4" required placeholder="Series description..."></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Genre *</label>
                    <select name="genre" class="form-control" required>
                        <option value="">Select Genre</option>
                        <option value="Action">Action</option>
                        <option value="Horror">Horror</option>
                        <option value="Romance">Romance</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Drama">Drama</option>
                        <option value="Thriller">Thriller</option>
                        <option value="Fantasy">Fantasy</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Release Year *</label>
                    <input type="number" name="release_year" class="form-control" value="{{ date('Y') }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Language *</label>
                    <input type="text" name="language" class="form-control" value="English" required>
                </div>
                
                <div class="form-group">
                    <label>Rating *</label>
                    <select name="rating" class="form-control" required>
                        <option value="PG">PG</option>
                        <option value="PG-13">PG-13</option>
                        <option value="R">R</option>
                        <option value="TV-MA">TV-MA</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Number of Seasons *</label>
                    <input type="number" name="seasons_count" class="form-control" value="1" min="1" required>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3><i class="fas fa-image"></i> Images</h3>
            
            <div class="form-group">
                <label>Poster URL</label>
                <input type="url" name="poster_path" class="form-control" placeholder="https://image.tmdb.org/t/p/w500/xxxx.jpg">
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-video"></i> Trailer</h3>
            
            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" class="form-control" placeholder="https://www.youtube.com/watch?v=...">
                <div class="file-info">Paste the YouTube link of the series trailer. It will play in a modal on the site.</div>
            </div>
        </div>
        
        <div class="button-group">
            <button type="submit" class="btn-primary">
                <i class="fas fa-save"></i> Save Series
            </button>
            <a href="{{ route('admin.series.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection