@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <h3><i class="fas fa-clapperboard"></i> Total Trailers</h3>
            <div class="value">{{ $stats['trailers'] }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-eye"></i> Active Trailers</h3>
            <div class="value">{{ $stats['active_trailers'] }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-users"></i> Total Users</h3>
            <div class="value">{{ $stats['users'] }} <span>({{ $stats['system_users'] ?? 0 }} system)</span></div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-play-circle"></i> Total Views</h3>
            <div class="value">{{ number_format_short($stats['total_views']) }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-heart"></i> Favorites</h3>
            <div class="value">{{ number_format_short($stats['favorites']) }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-bolt"></i> Views Today</h3>
            <div class="value">{{ number_format_short($stats['views_today']) }}</div>
        </div>
        <div class="stat-card">
            <h3><i class="fas fa-calendar-week"></i> Views This Week</h3>
            <div class="value">{{ number_format_short($stats['views_week']) }}</div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="card">
            <h3><i class="fas fa-chart-line"></i> Monthly Trailer Views Trend</h3>
            <div class="chart-container">
                <canvas id="viewsChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3><i class="fas fa-chart-pie"></i> Trailer Activity</h3>
            <div class="chart-container">
                <canvas id="contentChart"></canvas>
            </div>
        </div>
        <div class="card">
            <h3><i class="fas fa-chart-area"></i> Daily Trailer Views (Last 14 Days)</h3>
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="recent-section">
        <div class="card">
            <div class="flex-between">
                <h3><i class="fas fa-clock"></i> Recently Added Trailers</h3>
                <a href="{{ route('admin.trailers.create') }}" class="btn-primary btn-sm"><i class="fas fa-plus"></i> Add Trailer</a>
            </div>
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr><th>Title</th><th>Views</th><th>Status</th><th>Added</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentTrailers as $trailer)
                        <tr>
                            <td>{{ $trailer->title }}</td>
                            <td>{{ number_format($trailer->views) }}</td>
                            <td>
                                <span style="background: {{ $trailer->is_active ? 'rgba(0,255,0,0.2)' : 'rgba(255,0,0,0.2)' }}; color: {{ $trailer->is_active ? '#0f0' : '#f00' }}; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem;">
                                    {{ $trailer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $trailer->created_at ? $trailer->created_at->diffForHumans() : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h3><i class="fas fa-fire"></i> Top Watched Trailers</h3>
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr><th>Title</th><th>Views</th><th>Added</th></tr>
                    </thead>
                    <tbody>
                        @foreach($topTrailers as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ number_format($item->views) }}</td>
                            <td>{{ $item->created_at ? $item->created_at->diffForHumans() : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="recent-section">
        <div class="card">
            <h3><i class="fas fa-history"></i> Recently Watched (Last 5 Watches)</h3>
            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr><th>Trailer</th><th>User</th><th>Watched At</th></tr>
                    </thead>
                    <tbody>
                        @foreach($recentWatches as $watch)
                        <tr>
                            <td>{{ $watch->trailer?->title ?? '—' }}</td>
                            <td>{{ $watch->user?->name ?? 'Guest' }}</td>
                            <td>{{ optional($watch->watched_at)->diffForHumans() ?? '—' }}</td>
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
    const ctx1 = document.getElementById('viewsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Trailer Views',
                data: {!! json_encode($monthlyViews ?? array_fill(0, 12, 0)) !!},
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
            labels: ['Active Trailers', 'Inactive Trailers'],
            datasets: [{
                data: [{{ $stats['active_trailers'] }}, {{ $stats['trailers'] - $stats['active_trailers'] }}],
                backgroundColor: ['#e31c25', '#7a0e15'],
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

    const ctx3 = document.getElementById('dailyChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: {!! json_encode($last14DaysLabels ?? []) !!},
            datasets: [{
                label: 'Trailer Views',
                data: {!! json_encode($last14Days ?? array_fill(0, 14, 0)) !!},
                backgroundColor: 'rgba(227, 28, 37, 0.75)',
                borderColor: '#e31c25',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#7d8aa6' } },
                x: { grid: { display: false }, ticks: { color: '#7d8aa6' } }
            }
        }
    });
</script>
@endsection