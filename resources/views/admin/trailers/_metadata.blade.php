@php
    $genreList = [
        'Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary',
        'Drama', 'Fantasy', 'Horror', 'Music', 'Mystery', 'Romance',
        'Sci-Fi', 'Thriller', 'War', 'Western',
    ];
    $typeList = [
        'Official Trailer', 'Teaser', 'Final Trailer', 'International Trailer',
        'TV Spot', 'Red Band Trailer', 'Clip', 'Featurette',
    ];
    $langList = [
        'English', 'Spanish', 'French', 'Hindi', 'Mandarin', 'Korean',
        'Japanese', 'Portuguese', 'Arabic', 'Swahili', 'German', 'Italian',
    ];
    $countryList = [
        'United States', 'United Kingdom', 'India', 'South Korea', 'Japan',
        'China', 'France', 'Germany', 'Nigeria', 'Tanzania', 'Kenya',
        'Mexico', 'Brazil', 'Spain', 'Italy',
    ];
    $v = fn ($field, $default = '') => isset($trailer) && $trailer ? ($trailer->{$field} ?? $default) : old($field, $default);
@endphp

<hr style="border-color: var(--border); margin: 1.2rem 0;">
<p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;"><strong><i class="fas fa-film"></i> Metadata</strong></p>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
    <div class="form-group">
        <label>Trailer Type</label>
        <select name="trailer_type" class="form-control">
            @foreach($typeList as $t)
                <option value="{{ $t }}" {{ $v('trailer_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Genre</label>
        <select name="genre" class="form-control">
            <option value="">— None —</option>
            @foreach($genreList as $g)
                <option value="{{ $g }}" {{ $v('genre') === $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Duration (seconds)</label>
        <input type="number" name="duration" class="form-control" min="1" max="600" value="{{ $v('duration') }}" placeholder="e.g. 150">
    </div>

    <div class="form-group">
        <label>Release Date</label>
        <input type="date" name="release_date" class="form-control" value="{{ $v('release_date') ? date('Y-m-d', strtotime($v('release_date'))) : '' }}">
    </div>

    <div class="form-group">
        <label>Year</label>
        <input type="number" name="year" class="form-control" min="1900" max="{{ date('Y') + 3 }}" value="{{ $v('year') }}" placeholder="e.g. 2026">
    </div>

    <div class="form-group">
        <label>Language</label>
        <select name="language" class="form-control">
            <option value="">— None —</option>
            @foreach($langList as $l)
                <option value="{{ $l }}" {{ $v('language') === $l ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Country / Region</label>
        <select name="country" class="form-control">
            <option value="">— None —</option>
            @foreach($countryList as $c)
                <option value="{{ $c }}" {{ $v('country') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    </div>
</div>

<hr style="border-color: var(--border); margin: 1.2rem 0;">
<p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;"><strong><i class="fas fa-star"></i> Promotion</strong></p>

<div style="display: flex; gap: 1.6rem; flex-wrap: wrap;">
    <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size: 0.85rem;">
        <input type="checkbox" name="featured" value="1" {{ $v('featured') ? 'checked' : '' }} style="width:16px; height:16px; accent-color: var(--accent);">
        Featured
    </label>
    <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size: 0.85rem;">
        <input type="checkbox" name="trending" value="1" {{ $v('trending') ? 'checked' : '' }} style="width:16px; height:16px; accent-color: var(--accent);">
        Trending
    </label>
    <label style="display:flex; align-items:center; gap:6px; cursor:pointer; font-size: 0.85rem;">
        <input type="checkbox" name="trailer_of_the_day" value="1" {{ $v('trailer_of_the_day') ? 'checked' : '' }} style="width:16px; height:16px; accent-color: var(--accent);">
        Trailer of the Day
    </label>
</div>