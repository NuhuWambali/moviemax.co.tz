@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-film"></i> Total Movies</h3>
            <div class="value">{{ $stats['movies'] }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-tv"></i> TV Series</h3>
            <div class="value">{{ $stats['series'] }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-clapperboard"></i> Trailers</h3>
            <div class="value">{{ $stats['trailers'] ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-users"></i> Total Users</h3>
            <div class="value">{{ $stats['users'] }} <span>({{ $stats['system_users'] ?? 0 }} system)</span></div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-download"></i> Total Downloads</h3>
            <div class="value">{{ number_format_short($stats['total_downloads']) }}</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="card">
            <h3><i class="fas fa-chart-line"></i> Monthly Downloads Trend</h3>
            <div class="chart-container">
                <canvas id="downloadsChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3><i class="fas fa-chart-pie"></i> Content Distribution</h3>
            <div class="chart-container">
                <canvas id="contentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="recent-section">
        <div class="card">
            <div class="flex-between">
                <h3><i class="fas fa-clock"></i> Recently Added Movies</h3>
                <a href="{{ route('admin.movies.create') }}" class="btn-primary btn-sm"><i class="fas fa-plus"></i> Add Movie</a>
            </div>
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr><th>Title</th><th>Downloads</th><th>Added</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentMovies as $movie)
                        <tr>
                            <td>{{ $movie->title }}</td>
                            <td>{{ number_format($movie->download_count) }}</td>
                            <td>{{ $movie->created_at ? $movie->created_at->diffForHumans() : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-fire"></i> Top Downloads</h3>
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr><th>Title</th><th>Genre</th><th>Downloads</th></tr>
                    </thead>
                    <tbody>
                        @foreach($topDownloads as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->genre }}</td>
                            <td>{{ number_format($item->download_count) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx1 = document.getElementById('downloadsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Downloads',
                data: {!! json_encode($monthlyDownloads ?? array_fill(0, 12, rand(100, 500))) !!},
                borderColor: '#e31c25',
                backgroundColor: 'rgba(227, 28, 37, 0.12)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#e31c25',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#a6b3cc' } } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#7d8aa6' } },
                x: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#7d8aa6' } }
            }
        }
    });

    const ctx2 = document.getElementById('contentChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Movies', 'TV Series', 'Episodes'],
            datasets: [{
                data: [{{ $stats['movies'] }}, {{ $stats['series'] }}, {{ $stats['episodes'] ?? 0 }}],
                backgroundColor: ['#e31c25', '#b30610', '#7a0e15'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: '#a6b3cc', padding: 20 } } }
        }
    });
</script>
@endsection