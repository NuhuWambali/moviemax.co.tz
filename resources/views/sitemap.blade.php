<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@php
    $static = [
        ['loc' => url('/'), 'freq' => 'daily', 'priority' => '1.0'],
        ['loc' => url('/movies'), 'freq' => 'daily', 'priority' => '0.9'],
        ['loc' => url('/series'), 'freq' => 'daily', 'priority' => '0.9'],
        ['loc' => url('/trailers'), 'freq' => 'daily', 'priority' => '0.8'],
        ['loc' => url('/about'), 'freq' => 'monthly', 'priority' => '0.5'],
    ];
    foreach ($genres as $genre) {
        $static[] = ['loc' => url('/genre/' . rawurlencode($genre)), 'freq' => 'weekly', 'priority' => '0.6'];
    }
@endphp
@foreach($static as $page)
    <url>
        <loc>{{ $page['loc'] }}</loc>
        <changefreq>{{ $page['freq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
    </url>
@endforeach
@foreach($movies as $movie)
    <url>
        <loc>{{ url('/movies/' . $movie->slug) }}</loc>
        <lastmod>{{ optional($movie->updated_at)->toDateString() ?? date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach($series as $item)
    <url>
        <loc>{{ url('/series/' . $item->id) }}</loc>
        <lastmod>{{ optional($item->updated_at)->toDateString() ?? date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
@foreach($trailers as $trailer)
    <url>
        <loc>{{ url('/trailers/' . $trailer->id) }}</loc>
        <lastmod>{{ optional($trailer->updated_at)->toDateString() ?? date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
@endforeach
</urlset>