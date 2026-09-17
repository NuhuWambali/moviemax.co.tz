@if ($paginator && $paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = 2;
        $shown = collect(range(1, $last))->filter(function ($page) use ($current, $last, $window) {
            return $page === 1 || $page === $last || abs($page - $current) <= $window;
        });
    @endphp
    <ul class="pagination">
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            @if ($paginator->onFirstPage())
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
            @else
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a>
            @endif
        </li>

        @foreach ($shown as $page)
            @if ($page > 1 && !$shown->contains($page - 1))
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @endif

            @if ($page === $current)
                <li class="page-item active" aria-current="page"><span class="page-link active">{{ $page }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
            @endif
        @endforeach

        <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
            @if ($paginator->hasMorePages())
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            @endif
        </li>
    </ul>
@endif