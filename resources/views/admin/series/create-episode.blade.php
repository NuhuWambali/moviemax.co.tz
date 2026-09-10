{{-- resources/views/admin/series/create-episode.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add Episode - ' . $series->title)

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
    .form-group label .required {
        color: #e31c25;
        margin-left: 3px;
    }
    .form-control {
        width: 100%;
        padding: 0.75rem;
        background: #1a1a22;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
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
        display: block;
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
        transition: 0.2s;
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
    
    /* Alert Styles */
    .alert-danger {
        background: rgba(227,28,37,0.15);
        border: 1px solid #e31c25;
        color: #e31c25;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-danger i {
        font-size: 1.2rem;
    }
    
    .alert-danger ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .alert-success {
        background: rgba(0,255,0,0.1);
        border: 1px solid #0f0;
        color: #0f0;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .file-info {
        font-size: 0.7rem;
        color: #888;
        margin-top: 0.25rem;
        display: block;
    }
</style>

<div style="max-width: 900px; margin: 0 auto;">
    <h1><i class="fas fa-plus-circle"></i> Add Episode to {{ $series->title }}</h1>
    <small style="color: #888;">Add a new episode to this series</small>

    <!-- Display Validation Errors -->
    @if($errors->any())
    <div class="alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 0.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Display Success Message -->
    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle"></i>
        <div>{{ session('success') }}</div>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.series.episodes.store', $series) }}" enctype="multipart/form-data" style="margin-top: 1.5rem;">
        @csrf
        
        <!-- Episode Information Section -->
        <div class="form-section">
            <h3><i class="fas fa-tv"></i> Episode Information</h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Season Number <span class="required">*</span></label>
                    <input type="number" name="season_number" class="form-control @error('season_number') is-invalid @enderror" value="{{ old('season_number', 1) }}" min="1" required>
                    @error('season_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label>Episode Number <span class="required">*</span></label>
                    <input type="number" name="episode_number" class="form-control @error('episode_number') is-invalid @enderror" value="{{ old('episode_number', 1) }}" min="1" required>
                    @error('episode_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label>Episode Title <span class="required">*</span></label>
                <input type="text" name="episode_title" class="form-control @error('episode_title') is-invalid @enderror" value="{{ old('episode_title') }}" required placeholder="e.g., The Beginning">
                @error('episode_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Episode description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Duration <span class="required">*</span></label>
                <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" required placeholder="e.g., 45min">
                @error('duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Video File Section -->
        <div class="form-section">
            <h3><i class="fas fa-video"></i> Video File</h3>
            
            <div class="form-group">
                <label>Upload Video File</label>
                <input type="file" name="video_file" id="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/mp4,video/x-matroska,video/webm,video/avi,.mkv,.mp4,.avi" onchange="updateVideoInfo()">
                @error('video_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="progress-bar" id="uploadProgress">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <small class="file-info">Supported: MP4, MKV, AVI. Max size: 10GB</small>
            </div>
            
            <div class="form-group">
                <label>OR Enter File Path</label>
                <input type="text" name="file_path" id="file_path" class="form-control @error('file_path') is-invalid @enderror" value="{{ old('file_path') }}" placeholder="private/episodes/filename.mp4">
                @error('file_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="file-info">Only if file is already uploaded manually</small>
            </div>
            
            <div class="form-group">
                <label>File Size</label>
                <input type="text" name="file_size" id="file_size" class="form-control" value="{{ old('file_size') }}" placeholder="e.g., 1.2 GB">
                <small class="file-info">Will be auto-filled if you upload a file</small>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-group">
            <button type="submit" class="btn-primary" id="submitBtn">
                <i class="fas fa-save"></i> Save Episode
            </button>
            <a href="{{ route('admin.series.episodes', $series) }}" class="btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateVideoInfo() {
        const fileInput = document.getElementById('video_file');
        const file = fileInput.files[0];
        
        if (!file) return;
        
        // Validate file size (10GB max = 10 * 1024 * 1024 * 1024)
        const maxSize = 10 * 1024 * 1024 * 1024;
        if (file.size > maxSize) {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                text: 'File size exceeds 10GB limit. Please compress your file or use a smaller file.',
                background: '#1a1a22',
                color: '#fff',
                confirmButtonColor: '#e31c25'
            });
            fileInput.value = '';
            document.getElementById('file_size').value = '';
            return;
        }
        
        // Validate file type
        const allowedExtensions = ['mp4', 'mkv', 'avi'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid File Type',
                text: 'Please upload MP4, MKV, or AVI files only.',
                background: '#1a1a22',
                color: '#fff',
                confirmButtonColor: '#e31c25'
            });
            fileInput.value = '';
            document.getElementById('file_size').value = '';
            return;
        }
        
        // Calculate and display file size
        const sizeInGB = (file.size / (1024 * 1024 * 1024)).toFixed(2);
        document.getElementById('file_size').value = sizeInGB + ' GB';
        
        // Auto-generate file path suggestion
        const timestamp = Date.now();
        const suggestedPath = `private/episodes/episode_${timestamp}.${fileExtension}`;
        const filePathInput = document.getElementById('file_path');
        if (!filePathInput.value) {
            filePathInput.placeholder = suggestedPath;
        }
        
        // Show progress bar
        const progressBar = document.getElementById('uploadProgress');
        const progressFill = document.getElementById('progressFill');
        progressBar.style.display = 'block';
        
        // Simulate file processing
        let progress = 0;
        const interval = setInterval(() => {
            progress += 10;
            progressFill.style.width = progress + '%';
            
            if (progress >= 100) {
                clearInterval(interval);
                setTimeout(() => {
                    progressBar.style.display = 'none';
                    Swal.fire({
                        icon: 'success',
                        title: 'File Ready',
                        text: 'Video file is ready to be saved.',
                        background: '#1a1a22',
                        color: '#fff',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }, 500);
            }
        }, 200);
    }
    
    // Form validation before submit
    document.getElementById('submitBtn')?.addEventListener('click', function(e) {
        const seasonNumber = document.querySelector('input[name="season_number"]').value;
        const episodeNumber = document.querySelector('input[name="episode_number"]').value;
        const episodeTitle = document.querySelector('input[name="episode_title"]').value;
        const duration = document.querySelector('input[name="duration"]').value;
        const filePath = document.querySelector('input[name="file_path"]').value;
        const videoFile = document.querySelector('input[name="video_file"]').files[0];
        
        if (!seasonNumber || !episodeNumber || !episodeTitle || !duration) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Missing Fields',
                text: 'Please fill all required fields (*)',
                background: '#1a1a22',
                color: '#fff',
                confirmButtonColor: '#e31c25'
            });
            return;
        }
        
        if (!filePath && !videoFile) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Video Required',
                text: 'Please either upload a video file or provide a file path.',
                background: '#1a1a22',
                color: '#fff',
                confirmButtonColor: '#e31c25'
            });
        }
    });
    
    // Show validation errors with SweetAlert if any
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        background: '#1a1a22',
        color: '#fff',
        confirmButtonColor: '#e31c25'
    });
    @endif
    
    // Show success message
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        background: '#1a1a22',
        color: '#fff',
        confirmButtonColor: '#e31c25',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
</script>
@endsection