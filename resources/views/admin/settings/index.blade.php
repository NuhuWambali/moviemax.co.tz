{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Site Settings')

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

    .form-group .hint {
        display: block;
        color: #889;
        font-size: 0.75rem;
        margin-top: 0.3rem;
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

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .image-preview {
        margin-top: 1rem;
        text-align: center;
    }

    .image-preview img {
        max-height: 90px;
        max-width: 240px;
        border-radius: 12px;
        padding: 8px 14px;
        background: #0d0d12;
        border: 2px dashed #e31c25;
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
        border: none;
    }

    .btn-primary {
        background: #e31c25;
        color: white;
    }

    .btn-primary:hover { background: #b30610; }

    .btn-secondary {
        background: rgba(255,255,255,0.08);
        color: #ccc;
    }

    .btn-secondary:hover { background: rgba(255,255,255,0.14); color: #fff; }

    .save-bar {
        display: flex;
        justify-content: flex-end;
        gap: 0.8rem;
        margin-bottom: 2rem;
    }

    .alert-success {
        background: rgba(0,200,0,0.1);
        border: 1px solid rgba(0,200,0,0.3);
        color: #7dff9a;
        padding: 0.8rem 1rem;
        border-radius: 8px;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-remove {
        color: #ff8a80;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 0.5rem;
        cursor: pointer;
    }
</style>

@if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-section">
        <h3><i class="fas fa-building"></i> Company Information</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Site Name</label>
                <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? 'MOVIEMAX') }}" required>
            </div>
            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
            </div>
            <div class="form-group">
                <label>WhatsApp Number</label>
                <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" placeholder="e.g. +255688349680">
                <span class="hint">Used for the "Talk to developer" chat button.</span>
            </div>
        </div>
        <div class="form-group">
            <label>Tagline / Short Description</label>
            <textarea name="tagline" class="form-control">{{ old('tagline', $settings['tagline'] ?? '') }}</textarea>
            <span class="hint">Shown on the homepage hero.</span>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-hand-holding-heart"></i> Buy Me A Coffee</h3>
        <div class="form-group">
            <label>Buy Me A Coffee Link</label>
            <input type="url" name="buy_me_coffee_url" class="form-control" value="{{ old('buy_me_coffee_url', $settings['buy_me_coffee_url'] ?? '') }}" placeholder="https://www.buymeacoffee.com/yourname">
            <span class="hint">Shown in the floating Support button so viewers can donate.</span>
        </div>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-image"></i> Logo</h3>
        <div class="form-group">
            <label>Upload Logo</label>
            <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/webp,image/svg+xml">
            <span class="hint">PNG, JPG, WEBP or SVG, max 2MB. Replaces the site name in the header and footer.</span>
        </div>
        <div class="image-preview">
            @if(!empty($settings['logo_path']))
                <img src="{{ $settings['logo_path'] }}" alt="Current logo">
                <label class="checkbox-remove">
                    <input type="checkbox" name="remove_logo" value="1"> Remove current logo
                </label>
            @else
                <p style="color:#889; font-size:0.8rem;">No logo uploaded yet.</p>
            @endif
        </div>
    </div>

    <div class="save-bar">
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary"><i class="fas fa-times"></i> Cancel</a>
        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Save Settings</button>
    </div>
</form>
@endsection