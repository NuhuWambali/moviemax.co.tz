{{-- resources/views/admin/analytics/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Visitor Analytics')

@section('content')
<style>
    .stat-card {
        background: #121217;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid rgba(255,255,255,0.05);
        transition: 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        border-color: rgba(227,28,37,0.3);
    }
    
    .online-dot {
        width: 10px;
        height: 10px;
        background: #0f0;
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
        100% { opacity: 1; transform: scale(1); }
    }
    
    .visitor-table {
        width: 100%;
    }
    
    .visitor-table th, .visitor-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    .device-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 500;
    }
    
    .device-desktop { background: rgba(0,100,255,0.2); color: #44f; }
    .device-mobile { background: rgba(0,255,0,0.2); color: #0f0; }
    .device-tablet { background: rgba(255,100,0,0.2); color: #fa0; }
    
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #e31c25;
        animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .visitor-row:hover {
        background: rgba(227,28,37,0.05);
    }
    
    /* 3-column layout */
    .three-columns {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    /* 2-column layout */
    .two-columns {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .info-card {
        background: #121217;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .info-card h3 {
        margin-bottom: 1rem;
        font-size: 1rem;
        color: #e31c25;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 0.5rem;
    }
    
    .info-list {
        max-height: 280px;
        overflow-y: auto;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    @media (max-width: 1000px) {
        .three-columns, .two-columns {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="analytics-container">
    <!-- Header -->
    <div class="top-bar" style="margin-bottom: 1.5rem;">
        <div>
            <h1><i class="fas fa-chart-line"></i> Visitor Analytics</h1>
            <small style="color: #888;">Real-time visitor tracking and analytics</small>
        </div>
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div class="online-indicator">
                <span class="online-dot"></span>
                <span id="onlineCount" style="margin-left: 0.5rem;">Loading...</span>
                <small style="color: #888;"> online now</small>
            </div>
            <select id="daysFilter" onchange="window.location.href='?days='+this.value" style="background: #1a1a22; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 0.5rem 1rem; color: white;">
                <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 days</option>
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="60" {{ $days == 60 ? 'selected' : '' }}>Last 60 days</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 days</option>
            </select>
            <button onclick="refreshData()" class="btn-secondary" style="padding: 0.5rem 1rem;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('admin.analytics.clear') }}" class="btn-secondary" onclick="return confirm('Clear old visitor data?')">
                <i class="fas fa-trash"></i> Clear Old Data
            </a>
        </div>
    </div>
    
    <!-- Stats Cards Row 1 -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <h3>Unique Visitors</h3>
                <div class="value">{{ number_format($stats['total_visitors']) }}</div>
                <small style="color: #888;">All time unique</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3>Page Views</h3>
                <div class="value">{{ number_format($stats['total_page_views']) }}</div>
                <small style="color: #888;">Total interactions</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3>Today's Visitors</h3>
                <div class="value">{{ number_format($stats['today_visitors']) }}</div>
                <small style="color: #888;">New today</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3>Avg. Views/Visitor</h3>
                <div class="value">{{ number_format($stats['average_views_per_visitor'], 1) }}</div>
                <small style="color: #888;">Pages per session</small>
            </div>
        </div>
    </div>
    
    
    <!-- Stats Cards Row 2 -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-calendar-week"></i> This Week</h3>
                <div class="value">{{ number_format($stats['week_visitors'] ?? 0) }}</div>
                <small style="color: #888;">Last 7 days</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-calendar-month"></i> This Month</h3>
                <div class="value">{{ number_format($stats['month_visitors'] ?? 0) }}</div>
                <small style="color: #888;">Last 30 days</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-chart-line"></i> Bounce Rate</h3>
                <div class="value">{{ number_format($stats['bounce_rate'] ?? 0) }}%</div>
                <small style="color: #888;">Single page visits</small>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <h3><i class="fas fa-stopwatch"></i> Avg. Session</h3>
                <div class="value">{{ $stats['avg_session_minutes'] ?? 0 }} min</div>
                <small style="color: #888;">Time on site</small>
            </div>
        </div>
    </div>
    
    <div class="two-columns">
        <div class="info-card">
            <h3><i class="fas fa-mobile-alt"></i> Device Distribution</h3>
            <div class="chart-container" style="height: 250px;">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-globe"></i> Browser Distribution</h3>
            <div class="chart-container" style="height: 250px;">
                <canvas id="browserChart"></canvas>
            </div>
        </div>
        
     
    </div>
    

    <!-- Visitor Trends Chart (Full Width) -->
    <div class="chart-card" style="margin-bottom: 1.5rem;">
        <h3><i class="fas fa-chart-line"></i> Visitor Trends (Last {{ $days }} days)</h3>
        <div class="chart-container" style="height: 350px;">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>
    
    <!-- Three Columns: Devices, Browsers, Operating Systems -->
 

    <div class="one-column">
        <div class="info-card">
            <h3><i class="fas fa-chart-line"></i> Peak Hours (Last 24h)</h3>
            <div class="chart-container" style="height: 250px;">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
    </div>


    <!-- Two Columns: Peak Hours & Top Countries -->
    <div class="two-columns">
       
        <div class="info-card">
            <h3><i class="fab fa-windows"></i> Operating Systems</h3>
            <div class="info-list">
                @forelse($operatingSystems ?? [] as $os)
                <div class="info-item">
                    <span><i class="fab fa-{{ strtolower(str_replace(' ', '', $os->os)) }}"></i> {{ $os->os ?? 'Unknown' }}</span>
                    <span style="color: #e31c25;">{{ number_format($os->total) }} ({{ round(($os->total / max($stats['total_visitors'], 1)) * 100, 1) }}%)</span>
                </div>
                @empty
                <p style="color: #888; text-align: center; padding: 2rem;">No OS data yet</p>
                @endforelse
            </div>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-globe"></i> Top Countries</h3>
            <div class="info-list">
                @if($countries->count() > 0)
                    @foreach($countries as $country)
                    <div class="info-item">
                        <span><i class="fas fa-flag"></i> {{ $country->country ?? 'Unknown' }}</span>
                        <span style="color: #e31c25;">{{ number_format($country->total) }} ({{ round(($country->total / max($stats['total_visitors'], 1)) * 100, 1) }}%)</span>
                    </div>
                    @endforeach
                @else
                    <p style="color: #888; text-align: center; padding: 2rem;">Geolocation data available after integrating IP API</p>
                @endif
            </div>
        </div>
    </div>
    


    <!-- Two Columns: Most Visited Pages & Recent Visitors -->
    <div class="two-columns">
        <div class="info-card">
            <h3><i class="fas fa-fire"></i> Most Visited Pages</h3>
            <div class="info-list" style="max-height: 400px;">
                <table class="visitor-table">
                    <thead>
                        <tr><th>Page URL</th><th>Views</th><th>%</th></tr>
                    </thead>
                    <tbody>
                        @foreach($topPages as $page)
                        <tr>
                            <td>
                                <a href="{{ $page->page_url }}" target="_blank" style="color: #e31c25; text-decoration: none; font-size: 0.8rem;">
                                    {{ \Illuminate\Support\Str::limit($page->page_url, 40) }}
                                </a>
                            </td>
                            <td style="text-align: center;">{{ number_format($page->views) }}</td>
                            <td style="text-align: center;">{{ round(($page->views / max($stats['total_page_views'], 1)) * 100, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="info-card">
            <h3><i class="fas fa-clock"></i> Recent Visitors ({{ $recentVisitors->total() }})</h3>
            {{-- Export buttons --}}
            <div style="display: flex; gap: 0.5rem; margin-bottom: 0.75rem; align-items: center;">
                {{-- CSV export button --}}
                <form action="{{ route('admin.analytics.export') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-secondary small" style="background: #e31c25; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 4px; font-size: 0.75rem;">
                        <i class="fas fa-file-csv"></i> CSV
                    </button>
                </form>
                {{-- PDF export button (using simple table download) --}}
                <a href="javascript:exportVisitorsPDF()" class="btn-secondary small" style="background: #28a745; color: white; padding: 0.4rem 0.8rem; border: none; border-radius: 4px; font-size: 0.75rem;">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
            {{-- Pagination --}}
            {!! $recentVisitors->links() !!} 
            {{-- Visitor table --}}
            <div class="info-list" style="max-height: 600px; overflow-x: auto;">
                {{-- Table header --}}
                <table class="visitor-table" style="width: 100%; min-width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 8%;">#</th>
                            <th style="width: 18%;">IP Address</th>
                            <th style="width: 15%;">Browser</th>
                            <th style="width: 12%;">OS</th>
                            <th style="width: 12%;">Device</th>
                            <th style="width: 10%;">Country</th>
                            <th style="width: 12%;">First Visit</th>
                            <th style="width: 12%;">Last Visit</th>
                            <th style="width: 8%;">Visits</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentVisitors as $visitor)
                        <tr style="background: {{ $loop->odd ? 'rgba(255,255,255,0.03)' : 'transparent' }};">
                            <td style="padding: 0.5rem;">{{ $loop->iteration }}</td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">{{ $visitor->ip_address }}</td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">{{ $visitor->browser ?? 'Unknown' }}</td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">{{ $visitor->os ?? 'Unknown' }}</td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">
                                {{ ucfirst($visitor->device_type ?? 'Desktop') }}
                            </td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">{{ $visitor->country ?? 'Unknown' }}</td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">
                                @if($visitor->created_at)
                                    {{ $visitor->created_at->format('M d, H:i') }} ({{ $visitor->created_at->diffForHumans() }})
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 0.5rem; font-size: 0.75rem;">
                                @if($visitor->last_visit)
                                    {{ $visitor->last_visit->format('M d, H:i') }} ({{ $visitor->last_visit->diffForHumans() }})
                                @else
                                    N/A
                                @endif
                            </td>
                            <td style="padding: 0.5rem; font-size: 0.75rem; text-align: center;">{{ $visitor->visit_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Empty state --}}
                @if($recentVisitors->isEmpty())
                <p style="color: #888; text-align: center; padding: 2rem;">No visitors yet. Visit your website to see data!</p>
                @endif
            </div>
            {{-- Pagination --}}
            {!! $recentVisitors->links() !!}
        </div>
        {{-- End export/pagination --}}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Visitor Trend Chart
    const dailyData = @json($dailyData);
    new Chart(document.getElementById('visitorChart'), {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [
                {
                    label: 'Unique Visitors',
                    data: dailyData.map(d => d.visitors),
                    borderColor: '#e31c25',
                    backgroundColor: 'rgba(227, 28, 37, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#e31c25',
                    pointBorderColor: '#fff',
                    pointRadius: 4
                },
                {
                    label: 'Page Views',
                    data: dailyData.map(d => d.views),
                    borderColor: '#ff6b6b',
                    backgroundColor: 'rgba(255, 107, 107, 0.05)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ff6b6b',
                    pointBorderColor: '#fff',
                    pointRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { labels: { color: '#fff' } },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa', stepSize: 1 } },
                x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa', rotation: -45 } }
            }
        }
    });
    
    // Device Chart
    const devices = @json($devices);
    const deviceLabels = devices.length > 0 ? devices.map(d => d.device_type || 'Unknown') : ['Desktop', 'Mobile', 'Tablet'];
    const deviceData = devices.length > 0 ? devices.map(d => d.total) : [1, 0, 0];
    
    new Chart(document.getElementById('deviceChart'), {
        type: 'doughnut',
        data: {
            labels: deviceLabels,
            datasets: [{
                data: deviceData,
                backgroundColor: ['#e31c25', '#ff6b6b', '#ffb4b4', '#888'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom', labels: { color: '#fff' } },
                tooltip: { callbacks: { label: (context) => `${context.label}: ${context.raw} visitors (${Math.round(context.raw / deviceData.reduce((a,b) => a+b, 0) * 100)}%)` } }
            }
        }
    });
    
    // Browser Chart (Bar)
    const browsers = @json($browsers);
    new Chart(document.getElementById('browserChart'), {
        type: 'bar',
        data: {
            labels: browsers.map(d => d.browser || 'Unknown'),
            datasets: [{
                label: 'Visitors',
                data: browsers.map(d => d.total),
                backgroundColor: '#e31c25',
                borderRadius: 8,
                barPercentage: 0.7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: { callbacks: { label: (context) => `${context.raw} visitors` } }
            },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa', stepSize: 1 } },
                x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa' } }
            }
        }
    });
    
    // Hourly Chart
    const hourlyData = @json($hourlyActivity);
    new Chart(document.getElementById('hourlyChart'), {
        type: 'line',
        data: {
            labels: hourlyData.map(d => d.hour),
            datasets: [{
                label: 'Active Visitors',
                data: hourlyData.map(d => d.visitors),
                borderColor: '#e31c25',
                backgroundColor: 'rgba(227, 28, 37, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: (context) => {
                    const value = context.raw;
                    const max = Math.max(...hourlyData.map(d => d.visitors));
                    return value === max && value > 0 ? '#e31c25' : '#ff6b6b';
                },
                pointRadius: (context) => {
                    const value = context.raw;
                    const max = Math.max(...hourlyData.map(d => d.visitors));
                    return value === max && value > 0 ? 6 : 3;
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { labels: { color: '#fff' } },
                tooltip: { callbacks: { label: (context) => `${context.raw} visitors active` } }
            },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa', stepSize: 1 } },
                x: { grid: { color: 'rgba(255,255,255,0.1)' }, ticks: { color: '#aaa', rotation: -45 } }
            }
        }
    });
    
    // Real-time online counter
    function updateOnlineCount() {
        const onlineSpan = document.getElementById('onlineCount');
        onlineSpan.innerHTML = '<span class="loading-spinner"></span>';
        
        fetch('{{ route("admin.analytics.realtime") }}')
            .then(response => response.json())
            .then(data => {
                onlineSpan.innerHTML = data.online_now;
            })
            .catch(error => {
                onlineSpan.innerHTML = '0';
                console.error('Error fetching online count:', error);
            });
    }
    
    function refreshData() {
        location.reload();
    }

    // PDF export for visitors table
    function exportVisitorsPDF() {
        // Print the visitor table
        const srcTable = document.querySelector('.visitor-table');
        const tableHTML = srcTable ? srcTable.outerHTML : '<p>No visitor data available.</p>';
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Visitor Analytics - PDF Export</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .title { text-align: center; margin-bottom: 20px; color: #e31c25; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        th, td { border: 1px solid #dee2e6; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                    </style>
                </head>
                <body>
                    <h3 class="title">Visitor Analytics - Visitor List</h3>
                    ${tableHTML}
                    <button onclick="window.print()" style="margin-top: 20px; padding: 8px 15px; background: #e31c25; color: white; border: none; border-radius: 4px;">Print/Export PDF</button>
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.focus();
    }
    
    updateOnlineCount();
    setInterval(updateOnlineCount, 30000);
</script>
@endsection