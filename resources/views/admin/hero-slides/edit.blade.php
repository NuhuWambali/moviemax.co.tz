{{-- resources/views/admin/hero-slides/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Edit Hero Slide')

@section('content')
    <div style="max-width: 800px;">
        @if($errors->any())
            <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.3); color: #f88; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.hero-slides.update', $heroSlide) }}" enctype="multipart/form-data" style="background: #121217; padding: 2rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Slide Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $heroSlide->title) }}" required placeholder="e.g., Dune: Part Two">
            </div>

            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $heroSlide->tagline) }}" placeholder="Short line shown under the title">
            </div>

            <div class="form-group">
                <label>Wide Image (recommended 1600x640 or larger)</label>
                @if($heroSlide->image_path)
                    <img src="{{ $heroSlide->image_path }}" style="width: 100%; max-width: 600px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); margin-bottom: 0.75rem; display: block;">
                @endif
                <input type="file" name="hero_file" class="form-control" accept="image/*" onchange="previewImage()">
                <small style="color: #888;">Leave empty to keep the current image.</small>
                <div style="margin-top: 0.75rem;">
                    <label style="color: #aaa; font-size: 0.9rem;">— or replace with an image URL —</label>
                    <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $heroSlide->image_path) }}" placeholder="https://example.com/banner.jpg">
                </div>
                <div id="imagePreview" style="margin-top: 1rem;"></div>
            </div>

            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" class="form-control" value="{{ old('trailer_url', $heroSlide->trailer_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                <small style="color: #888;">Adds a "Watch Trailer" button on this slide.</small>
            </div>

            <div class="form-group">
                <label>Link to Content (optional)</label>
                <select name="link_type" id="linkType" class="form-control" onchange="toggleLinkSelects()">
                    <option value="">— No link —</option>
                    <option value="movie" {{ old('link_type', $heroSlide->link_type) === 'movie' ? 'selected' : '' }}>Link to a Movie</option>
                    <option value="series" {{ old('link_type', $heroSlide->link_type) === 'series' ? 'selected' : '' }}>Link to a Series</option>
                </select>
                <div id="movieSelect" style="display: none; margin-top: 0.75rem;">
                    <label>Select Movie</label>
                    <select name="link_id" id="movieSelectElt" class="form-control">
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('link_type', $heroSlide->link_type) === 'movie' && (int) old('link_id', $heroSlide->link_id) === $movie->id ? 'selected' : '' }}>{{ $movie->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="seriesSelect" style="display: none; margin-top: 0.75rem;">
                    <label>Select Series</label>
                    <select name="link_id" id="seriesSelectElt" class="form-control">
                        @foreach($series as $item)
                            <option value="{{ $item->id }}" {{ old('link_type', $heroSlide->link_type) === 'series' && (int) old('link_id', $heroSlide->link_id) === $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
                <small style="color: #888;">Shows a "Watch Now" button linking to this movie/series page.</small>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $heroSlide->sort_order) }}" min="0">
                <small style="color: #888;">Lower numbers appear first.</small>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; color: white;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                    Active (shown on homepage)
                </label>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                <a href="{{ route('admin.hero-slides.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    function previewImage() {
        const input = document.querySelector('input[name="hero_file"]');
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.cssText = 'width: 100%; max-width: 600px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);';
                preview.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function toggleLinkSelects() {
        const type = document.getElementById('linkType').value;
        document.getElementById('movieSelect').style.display = type === 'movie' ? 'block' : 'none';
        document.getElementById('seriesSelect').style.display = type === 'series' ? 'block' : 'none';
    }
    toggleLinkSelects();
</script>
@endsection