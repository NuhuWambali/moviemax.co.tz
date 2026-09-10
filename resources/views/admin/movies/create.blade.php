{{-- resources/views/admin/movies/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add Movie')

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
        display: none;
    }
    
    .image-preview img {
        max-width: 200px;
        border-radius: 12px;
        border: 2px solid #e31c25;
    }
    
    .btn-primary, .btn-secondary {
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
    
    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .file-info {
        font-size: 0.75rem;
        color: #888;
        margin-top: 0.25rem;
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

<div style="">
    <form method="POST" action="{{ route('admin.movies.store') }}" enctype="multipart/form-data" id="movieForm">
        @csrf
        
        <!-- Basic Information Section -->
        <div class="form-section">
            <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
            
            <div class="form-group">
                <label>Movie Title <span class="required">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                       value="{{ old('title') }}" required placeholder="e.g., Dune: Part Two">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Description <span class="required">*</span></label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                          required placeholder="Enter movie description...">{{ old('description') }}</textarea>
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
                            <option value="Action" {{ old('genre') == 'Action' ? 'selected' : '' }}>🔥 Action</option>
                            <option value="Horror" {{ old('genre') == 'Horror' ? 'selected' : '' }}>😱 Horror</option>
                            <option value="Romance" {{ old('genre') == 'Romance' ? 'selected' : '' }}>💕 Romance</option>
                            <option value="Sci-Fi" {{ old('genre') == 'Sci-Fi' ? 'selected' : '' }}>🚀 Sci-Fi</option>
                            <option value="Comedy" {{ old('genre') == 'Comedy' ? 'selected' : '' }}>😂 Comedy</option>
                        </optgroup>
                        <optgroup label="Others">
                            <option value="Adventure" {{ old('genre') == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                            <option value="Animation" {{ old('genre') == 'Animation' ? 'selected' : '' }}>Animation</option>
                            <option value="Crime" {{ old('genre') == 'Crime' ? 'selected' : '' }}>Crime</option>
                            <option value="Drama" {{ old('genre') == 'Drama' ? 'selected' : '' }}>Drama</option>
                            <option value="Fantasy" {{ old('genre') == 'Fantasy' ? 'selected' : '' }}>Fantasy</option>
                            <option value="Thriller" {{ old('genre') == 'Thriller' ? 'selected' : '' }}>Thriller</option>
                            <option value="War" {{ old('genre') == 'War' ? 'selected' : '' }}>War</option>
                            <option value="Western" {{ old('genre') == 'Western' ? 'selected' : '' }}>Western</option>
                        </optgroup>
                    </select>
                    @error('genre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Release Year <span class="required">*</span></label>
                    <input type="number" name="release_year" class="form-control @error('release_year') is-invalid @enderror" 
                           value="{{ old('release_year', date('Y')) }}" required min="1900" max="{{ date('Y') + 5 }}">
                    @error('release_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Duration <span class="required">*</span></label>
                    <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" 
                           value="{{ old('duration') }}" required placeholder="e.g., 2h 35min">
                    @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Language <span class="required">*</span></label>
                    <select name="language" class="form-control @error('language') is-invalid @enderror" required>
                        <option value="English" {{ old('language', 'English') == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Spanish" {{ old('language') == 'Spanish' ? 'selected' : '' }}>Spanish</option>
                        <option value="French" {{ old('language') == 'French' ? 'selected' : '' }}>French</option>
                        <option value="German" {{ old('language') == 'German' ? 'selected' : '' }}>German</option>
                        <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                        <option value="Korean" {{ old('language') == 'Korean' ? 'selected' : '' }}>Korean</option>
                        <option value="Japanese" {{ old('language') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
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
                        <option value="G" {{ old('rating') == 'G' ? 'selected' : '' }}>G - General Audiences</option>
                        <option value="PG" {{ old('rating') == 'PG' ? 'selected' : '' }}>PG - Parental Guidance</option>
                        <option value="PG-13" {{ old('rating') == 'PG-13' ? 'selected' : '' }}>PG-13 - Parents Strongly Cautioned</option>
                        <option value="R" {{ old('rating') == 'R' ? 'selected' : '' }}>R - Restricted</option>
                        <option value="NC-17" {{ old('rating') == 'NC-17' ? 'selected' : '' }}>NC-17 - Adults Only</option>
                        <option value="TV-MA" {{ old('rating') == 'TV-MA' ? 'selected' : '' }}>TV-MA - Mature Audience</option>
                    </select>
                    @error('rating')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Media Section -->
        <div class="form-section">
            <h3><i class="fas fa-image"></i> Poster Image</h3>
            
            <div class="form-group">
                <label>Poster URL or Upload Image</label>
                <div class="form-row">
                    <div style="flex: 1;">
                        <input type="url" name="poster_path" id="poster_url" class="form-control" 
                               placeholder="https://image.tmdb.org/t/p/w500/xxxx.jpg" onchange="previewImage()">
                    </div>
                    <div style="width: 120px; text-align: center;">
                        <span style="color: #888;">OR</span>
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="poster_file" id="poster_file" class="form-control" accept="image/*" onchange="uploadPoster()">
                    </div>
                </div>
                <div class="file-info">Supported formats: JPG, PNG, WEBP. Max size: 5MB</div>
            </div>
            
            <div class="image-preview" id="imagePreview">
                <img id="previewImg" src="" alt="Poster Preview">
            </div>
        </div>
        
        <!-- Video Upload Section -->
        <div class="form-section">
            <h3><i class="fas fa-video"></i> Video File</h3>
            
            <div class="form-group">
                <label>Upload Movie File <span class="required">*</span></label>
                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/mp4,video/x-matroska,video/webm,video/avi,.mkv,.mp4,.avi"  onchange="uploadVideo()">
                <div class="file-info">Supported formats: MP4, MKV, AVI. Max size: 10GB</div>
                <div class="progress-bar" id="uploadProgress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label>OR Enter File Path</label>
                <input type="text" name="file_path" id="file_path" class="form-control" 
                       placeholder="movies/filename.mp4" value="{{ old('file_path') }}">
                <div class="file-info">If you uploaded a file, this will be auto-filled</div>
            </div>
            
            <div class="form-group">
                <label>File Size</label>
                <input type="text" name="file_size" id="file_size" class="form-control" 
                       placeholder="e.g., 2.4 GB" value="{{ old('file_size') }}">
            </div>

            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" id="trailer_url" class="form-control" 
                       placeholder="https://www.youtube.com/watch?v=..." value="{{ old('trailer_url') }}">
                <div class="file-info">Paste the YouTube link of the movie trailer. It will play in a modal on the site.</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Save Movie
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
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');
        
        if (url) {
            img.src = url;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }
    
    // Handle poster upload
    function uploadPoster() {
        const file = document.getElementById('poster_file').files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('previewImg');
                const preview = document.getElementById('imagePreview');
                img.src = e.target.result;
                preview.style.display = 'block';
                
                // Clear URL field
                document.getElementById('poster_url').value = '';
            };
            reader.readAsDataURL(file);
        }
    }
    
    // Handle video upload with progress
    function uploadVideo() {
        const file = document.getElementById('video_file').files[0];
        if (file) {
            // Show file size
            const sizeInGB = (file.size / (1024 * 1024 * 1024)).toFixed(2);
            document.getElementById('file_size').value = sizeInGB + ' GB';
            
            // Auto-generate file path
            const timestamp = Date.now();
            const extension = file.name.split('.').pop();
            const filename = file.name.replace(/\.[^/.]+$/, '') + '_' + timestamp + '.' + extension;
            document.getElementById('file_path').value = 'movies/' + filename;
            
            // Show progress bar
            const progressBar = document.getElementById('uploadProgress');
            progressBar.style.display = 'block';
            
            // Simulate upload (in real scenario, you'd upload to server)
            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                document.getElementById('progressFill').style.width = progress + '%';
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        progressBar.style.display = 'none';
                        Swal.fire({
                            icon: 'success',
                            title: 'File Ready',
                            text: 'Video file is ready to be saved',
                            background: '#1a1a22',
                            color: '#fff'
                        });
                    }, 500);
                }
            }, 300);
        }
    }
    
    // Form validation before submit
    document.getElementById('movieForm').addEventListener('submit', function(e) {
        const title = document.querySelector('input[name="title"]').value;
        const filePath = document.querySelector('input[name="file_path"]').value;
        
        if (!title || !filePath) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Fields',
                text: 'Please fill all required fields (*)',
                background: '#1a1a22',
                color: '#fff',
                confirmButtonColor: '#e31c25'
            });
        }
    });
    
    // Show success message if redirected with success
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