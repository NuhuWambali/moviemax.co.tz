{{-- resources/views/admin/hero-slides/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Add Hero Slide')

@section('content')
    <div style="max-width: 800px;">
        @if($errors->any())
            <div style="background: rgba(255,0,0,0.1); border: 1px solid rgba(255,0,0,0.3); color: #f88; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data" style="background: #121217; padding: 2rem; border-radius: 16px; border: 1px solid rgba(255,255,255,0.05);">
            @csrf

            <div class="form-group">
                <label>Slide Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g., Dune: Part Two">
            </div>

            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline') }}" placeholder="Short line shown under the title">
            </div>

            <div class="form-group">
                <label>Wide Image (recommended 1600x640 or larger)</label>
                <input type="file" name="hero_file" class="form-control" accept="image/*" onchange="previewImage()">
                <small style="color: #888;">Landscape / wide banner image — this is what slides on the homepage hero.</small>
                <div style="margin-top: 0.75rem;">
                    <label style="color: #aaa; font-size: 0.9rem;">— or paste an image URL —</label>
                    <input type="url" name="image_url" class="form-control" value="{{ old('image_url') }}" placeholder="https://example.com/banner.jpg">
                </div>
                <div id="imagePreview" style="margin-top: 1rem;"></div>
            </div>

            <div class="form-group">
                <label>Trailer URL (YouTube)</label>
                <input type="url" name="trailer_url" class="form-control" value="{{ old('trailer_url') }}" placeholder="https://www.youtube.com/watch?v=...">
                <small style="color: #888;">Adds a "Watch Trailer" button on this slide.</small>
            </div>

            <div class="form-group">
                <label>Link to Trailer (optional)</label>
                <select name="link_type" id="linkType" class="form-control" onchange="toggleLinkSelects()">
                    <option value="">— No link —</option>
                    <option value="trailer" {{ old('link_type') === 'trailer' ? 'selected' : '' }}>Link to a Trailer</option>
                </select>
                <div id="trailerSelect" style="display: none; margin-top: 0.75rem;">
                    <label>Select Trailer</label>
                    <select name="link_id" id="trailerSelectElt" class="form-control">
                        @foreach($trailers as $trailer)
                            <option value="{{ $trailer->id }}" {{ old('link_type') === 'trailer' && (int) old('link_id') === $trailer->id ? 'selected' : '' }}>{{ $trailer->title }}</option>
                        @endforeach
                    </select>
                </div>
                <small style="color: #888;">Shows a "More Info" button on the home hero linking to this trailer page.</small>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                <small style="color: #888;">Lower numbers appear first.</small>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 8px; color: white;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px;">
                    Active (shown on homepage)
                </label>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Slide</button>
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
        document.getElementById('trailerSelect').style.display = type === 'trailer' ? 'block' : 'none';
    }
    toggleLinkSelects();
</script>
@endsection