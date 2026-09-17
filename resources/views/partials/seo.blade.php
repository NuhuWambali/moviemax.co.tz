{{--
  Shared SEO + performance head block for user-facing pages.

  Expected variables (all optional):
    $seoTitle        - page title (also used for OG/Twitter)
    $seoDescription  - meta description
    $seoImagePath    - path or absolute URL (converted to absolute)
    $seoType         - og:type (website | article | video.movie | video.tv_show)
    $seoNoindex      - true on auth/member-only pages
    $seoJsonLd       - array of associative arrays rendered as JSON-LD blocks
--}}
@php
    $seoTitle       = $seoTitle ?? 'MovieMax – Watch Free Movie Trailers Online in HD';
    $seoDescription = $seoDescription ?? 'MovieMax lets you watch the latest movie trailers online in HD. Discover new release trailers, trending teasers and the most anticipated films all in one place.';
    $seoKeywords    = $seoKeywords ?? 'MovieMax, movie trailers, watch trailers online, new trailers, film trailers, HD trailers, upcoming movies, teasers';
    $seoAuthor      = $seoAuthor ?? 'MovieMax';
    $seoImage       = $seoImagePath ?? ($seoImage ?? '');
    $seoType        = $seoType ?? 'website';
    $seoNoindex     = $seoNoindex ?? false;
    $seoJsonLd      = $seoJsonLd ?? [];
    $seoUrl         = request()->path() === '/' ? rtrim(url('/'), '/') . '/' : request()->url();
    $seoSiteName    = config('app.name', 'MovieMax');
    if ($seoImage && !preg_match('~^https?://~i', $seoImage)) {
        $seoImage = url($seoImage);
    }
@endphp
<meta name="description" content="{{ $seoDescription }}">
@if($seoKeywords)<meta name="keywords" content="{{ $seoKeywords }}">@endif
<meta name="robots" content="{{ $seoNoindex ? 'noindex, follow' : 'index, follow' }}">
<meta name="author" content="{{ $seoAuthor }}">
<link rel="canonical" href="{{ $seoUrl }}">
<meta name="theme-color" content="#080808">

<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
@if($seoImage)<meta property="og:image" content="{{ $seoImage }}">@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if($seoImage)<meta name="twitter:image" content="{{ $seoImage }}">@endif

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'; this.onload=null;" crossorigin="anonymous" referrerpolicy="no-referrer">
<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

@if(!$seoNoindex)
@php
    $seoJsonLd[] = [
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        'name'     => $seoSiteName,
        'url'      => url('/'),
    ];
@endphp
@endif
@foreach($seoJsonLd as $block)
<script type="application/ld+json">{!! json_encode($block, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endforeach

<style>
    :where(a, button, input, select, textarea, summary, [tabindex]):focus-visible {
        outline: 2px solid #e50914;
        outline-offset: 2px;
        border-radius: 4px;
    }
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }
</style>