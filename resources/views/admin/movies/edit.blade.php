{{-- resources/views/admin/movies/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Movie')

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
        display: flex;
        align-items: center;
        gap: 10px;
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
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .form-group label .required {
        color: #e31c25;
        margin-left: 4px;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #1a1a22;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
        font-size: 0.9rem;
        transition: 0.2s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #e31c25;
        box-shadow: 0 0 10px rgba(227,28,37,0.2);
    }
    
    .form-control.is-invalid {
        border-color: #e31c25;
        background: rgba(227,28,37,0.1);
    }
    
    .invalid-feedback {
        color: #e31c25;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    select.form-control {
        cursor: pointer;
    }
    
    .image-preview {
        margin-top: 1rem;
        text-align: center;
    }
    
    .image-preview img {
        max-width: 200px;
        border-radius: 12px;
        border: 2px solid #e31c25;
    }
    
    .current-file {
        background: rgba(0,255,0,0.1);
        border: 1px solid rgba(0,255,0,0.3);
        padding: 0.5rem;
        border-radius: 8px;
        margin-top: 0.5rem;
        font-size: 0.8rem;
    }
    
    .btn-primary, .btn-secondary, .btn-danger {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
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
    
    .btn-primary:hover {
        background: #b0131b;
        transform: translateY(-2px);
    }
    
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        color: white;
        border: none;
    }
    
    .btn-secondary:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .btn-danger {
        background: rgba(255,0,0,0.2);
        color: #ff4444;
        border: 1px solid #ff4444;
    }
    
    .btn-danger:hover {
        background: #ff4444;
        color: white;
    }
    
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-active {
        background: rgba(0,255,0,0.2);
        color: #0f0;
    }
    
    .status-inactive {
        background: rgba(255,0,0,0.2);
        color: #f00;
    }
    
    .file-info {
        font-size: 0.75rem;
        color: #888;
        margin-top: 0.25rem;
    }
</style>

<div style=" margin: 0 auto;">
    <form method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data" id="movieForm">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Section -->
        <div class="form-section">
            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
            
            <div class="form-group">
                <label>Movie Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title', $movie->title) }}" required placeholder="e.g., Dune: Part Two">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Description <span class="required">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          required placeholder="Enter movie description...">{{ old('description', $movie->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Genre <span class="required">*</span></label>
                    <select name="genre" class="form-control @error('genre') is-invalid @enderror" required>
                        <option value="">Select Genre</option>
                        <optgroup label="Popular">
                            <option value="Action" {{ old('genre', $movie->genre) == 'Action' ? 'selected' : '' }}>🔥 Action</option>
                            <option value="Horror" {{ old('genre', $movie->genre) == 'Horror' ? 'selected' : '' }}>😱 Horror</option>
                            <option value="Romance" {{ old('genre', $movie->genre) == 'Romance' ? 'selected' : '' }}>💕 Romance</option>
                            <option value="Sci-Fi" {{ old('genre', $movie->genre) == 'Sci-Fi' ? 'selected' : '' }}>🚀 Sci-Fi</option>
                            <option value="Comedy" {{ old('genre', $movie->genre) == 'Comedy' ? 'selected' : '' }}>😂 Comedy</option>
                        </optgroup>
                        <optgroup label="Others">
                            <option value="Adventure" {{ old('genre', $movie->genre) == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                            <option value="Animation" {{ old('genre', $movie->genre) == 'Animation' ? 'selected' : '' }}>Animation</option>
                            <option value="Crime" {{ old('genre', $movie->genre) == 'Crime' ? 'selected' : '' }}>Crime</option>
                            <option value="Drama" {{ old('genre', $movie->genre) == 'Drama' ? 'selected' : '' }}>Drama</option>
                            <option value="Fantasy" {{ old('genre', $movie->genre) == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="Thriller" {{ old('genre', $movie->genre) == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                            <option value="War" {{ old('genre', $movie->genre) == 'War' ? 'selected' : '' }}>War</option>
                            <option value="Western" {{ old('genre', $movie->genre) == 'Western' ? 'selected' : '' }}>Western</option>
                        </optgroup>
                    </select>
                    @error('genre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Release Year <span class="required">*</span></label>
                    <input type="number" name="release_year" class="form-control @error('release_year') is-invalid @enderror" 
                           value="{{ old('release_year', $movie->release_year) }}" required min="1900" max="{{ date('Y') + 5 }}">
                    @error('release_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Duration <span class="required">*</span></label>
                    <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                           value="{{ old('duration', $movie->duration) }}" required placeholder="e.g., 2h 35min">
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Language <span class="required">*</span></label>
                    <select name="language" class="form-control @error('language') is-invalid @enderror" required>
                        <option value="English" {{ old('language', $movie->language) == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Spanish" {{ old('language', $movie->language) == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                        <option value="French" {{ old('language', $movie->language) == 'French' ? 'selected' : '' }}>French</option>
                        <option value="German" {{ old('language', $movie->language) == 'German' ? 'selected' : '' }}>German</option>
                        <option value="Hindi" {{ old('language', $movie->language) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                        <option value="Korean" {{ old('language', $movie->language) == 'Korean' ? 'selected' : '' }}>Korean</option>
                        <option value="Japanese" {{ old('language', $movie->language) == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                    </select>
                    @error('language')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Rating <span class="required">*</span></label>
                    <select name="rating" class="form-control @error('rating') is-invalid @enderror" required>
                        <option value="">Select Rating</option>
                        <option value="G" {{ old('rating', $movie->rating) == 'G' ? 'selected' : '' }}>G - General Audiences</option>
                        <option value="PG" {{ old('rating', $movie->rating) == 'PG' ? 'selected' : '' }}>PG - Parental Guidance</option>
                        <option value="PG-13" {{ old('rating', $movie->rating) == 'PG-13' ? 'selected' : '' }}>PG-13 - Parents Strongly Cautioned</option>
                        <option value="R" {{ old('rating', $movie->rating) == 'R' ? 'selected' : '' }}>R - Restricted</option>
                        <option value="NC-17" {{ old('rating', $movie->rating) == 'NC-17' ? 'selected' : '' }}>NC-17 - Adults Only</option>
                        <option value="TV-MA" {{ old('rating', $movie->rating) == 'TV-MA' ? 'selected' : '' }}>TV-MA - Mature Audience</option>
                    </select>
                    @error('rating')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ $movie->is_active ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ !$movie->is_active ? 'selected' : '' }}>Inactive</option>
                </select>
                <div class="file-info">Inactive movies won't be visible to users</div>
            </div>
        </div>
        
        <!-- Media Section -->
        <div class="form-section">
            <h3><i class="fas fa-image"></i> Poster Image</h3>
            
            <div class="image-preview">
                <img id="previewImg" src="{{ $movie->poster_path }}" alt="Current Poster">
                <div class="file-info" style="margin-top: 0.5rem;">Current Poster</div>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label>Change Poster (Optional)</label>
                <div class="form-row">
                    <div style="flex: 1;">
                        <input type="url" name="poster_path" id="poster_url" class="form-control" 
                               placeholder="https://image.tmdb.org/t/p/w500/xxxx.jpg" value="{{ old('poster_path', $movie->poster_path) }}" onchange="previewImage()">
                    </div>
                    <div style="width: 120px; text-align: center;">
                        <span style="color: #888;">OR</span>
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="poster_file" id="poster_file" class="form-control" accept="image/*" onchange="uploadPoster()">
                    </div>
                </div>
                <div class="file-info">Upload new poster to replace current one</div>
            </div>
        </div>
        
        <!-- Video Upload Section -->
        <div class="form-section">
            <h3><i class="fas fa-video"></i> Video File</h3>
            
            <div class="current-file">
                <i class="fas fa-file-video"></i> Current file: <strong>{{ basename($movie->file_path) }}</strong>
                <div class="file-info" style="margin-top: 0.25rem;">Size: {{ $movie->file_size ?? 'Unknown' }}</div>
            </div>
            
            <div class="form-group" style="margin-top: 1rem;">
                <label>Upload New Movie File (Optional)</label>
                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,.mkv,.mp4,.avi" onchange="uploadVideo()">
                <div class="file-info">Leave empty to keep current file. Supported formats: MP4, MKV, AVI. Max size: 10GB</div>
                <div class="progress-bar" id="uploadProgress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label>File Path (Optional)</label>
                <input type="text" name="file_path" id="file_path" class="form-control" 
                       placeholder="movies/filename.mp4" value="{{ old('file_path', $movie->file_path) }}">
                <div class="file-info">Only change if you know what you're doing</div>
            </div>
            
            <div class="form-group">
                <label>File Size</label>
                <input type="text" name="file_size" id="file_size" class="form-control" 
                       placeholder="e.g., 2.4 GB" value="{{ old('file_size', $movie->file_size) }}">
            </div>

            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" id="trailer_url" class="form-control" 
                       placeholder="https://www.youtube.com/watch?v=..." value="{{ old('trailer_url', $movie->trailer_url) }}">
                <div class="file-info">Paste the YouTube link of the movie trailer. It will play in a modal on the site.</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Update Movie
            </button>
            <a href="{{ route('admin.movies.index') }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Preview image from URL
    function previewImage() {
        const url = document.getElementById('poster_url').value;
        const img = document.getElementById('previewImg');
        
        if (url) {
            img.src = url;
        }
    }
    
    // Handle poster upload
    function uploadPoster() {
        const file = document.getElementById('poster_file').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('previewImg');
                img.src = e.target.result;
                document.getElementById('poster_url').value = '';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Handle video upload with progress
    function uploadVideo() {
        const file = document.getElementById('video_file').files[0];
        if (file) {
            const sizeInGB = (file.size / (1024 * 1024 * 1024)).toFixed(2);
            document.getElementById('file_size').value = sizeInGB + ' GB';
            
            const timestamp = Date.now();
            const extension = file.name.split('.').pop();
            const filename = file.name.replace(/\.[^/.]+$/, '') + '_' + timestamp + '.' + extension;
            document.getElementById('file_path').value = 'private/movies/' + filename;
            
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
            }, 300);
        }
    }
    
    // Show success message
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        background: '#1a1a22',
        color: '#fff',
        confirmButtonColor: '#e31c25',
        timer: 3000
    });
    @endif
</script>
@endsection