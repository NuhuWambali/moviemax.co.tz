{{-- resources/views/admin/movies/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Movies')

@section('content')
    <!-- Search and Add Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('admin.movies.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
            
            <!-- Export Button -->
            <a href="{{ route('admin.movies.export') }}" class="btn-secondary">
                <i class="fas fa-download"></i> Export
            </a>
        </div>
        
        <!-- Search Box -->
        <div style="display: flex; gap: 0.5rem;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #888;"></i>
                <input type="text" id="searchInput" placeholder="Search by title, genre..." 
                       style="background: #1a1a22; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 10px 10px 10px 35px; color: white; width: 250px;">
            </div>
            <select id="statusFilter" style="background: #1a1a22; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 10px; color: white;">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select id="genreFilter" style="background: #1a1a22; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 10px; color: white;">
                <option value="">All Genres</option>
                <option value="Action">Action</option>
                <option value="Horror">Horror</option>
                <option value="Romance">Romance</option>
                <option value="War">War</option>
                <option value="Sci-Fi">Sci-Fi</option>
                <option value="Comedy">Comedy</option>
                <option value="Drama">Drama</option>
                <option value="Thriller">Thriller</option>
            </select>
            <button onclick="clearFilters()" style="background: #2a2a35; border: none; border-radius: 8px; padding: 10px 15px; color: white; cursor: pointer;">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>
    
    <!-- Stats Summary -->
    <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Total Movies:</span>
            <strong style="color: #e31c25; margin-left: 0.5rem;">{{ $movies->total() }}</strong>
        </div>
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Active:</span>
            <strong style="color: #0f0; margin-left: 0.5rem;">{{ $movies->where('is_active', true)->count() }}</strong>
        </div>
        <div style="background: #121217; padding: 0.5rem 1rem; border-radius: 8px;">
            <span style="color: #888;">Inactive:</span>
            <strong style="color: #f00; margin-left: 0.5rem;">{{ $movies->where('is_active', false)->count() }}</strong>
        </div>
    </div>
    
    <!-- Movies Table -->
    <div style="overflow-x: auto;">
        <table class="data-table" id="moviesTable">
            <thead>
                <tr>
                    <th style="cursor: pointer;" onclick="sortTable(0)">ID <i class="fas fa-sort"></i></th>
                    <th>Poster</th>
                    <th style="cursor: pointer;" onclick="sortTable(2)">Title <i class="fas fa-sort"></i></th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Downloads</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="moviesTableBody">
                @foreach($movies as $index=>$movie)
                <tr data-title="{{ strtolower($movie->title) }}" data-genre="{{ $movie->genre }}" data-status="{{ $movie->is_active ? 'active' : 'inactive' }}">
                    <td>{{ $index+1 }}</td>
                    <td>
                        @if($movie->poster_path)
                            <img src="{{ $movie->poster_path }}" width="50" style="border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 75px; background: #333; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-image" style="color: #666;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $movie->title }}</strong>
                        <small style="display: block; color: #888;">{{ $movie->duration_label }}</small>
                    </td>
                    <td><span style="background: rgba(227,28,37,0.2); padding: 0.2rem 0.5rem; border-radius: 20px; font-size: 0.75rem;">{{ $movie->genre }}</span></td>
                    <td>{{ $movie->release_year }}</td>
                    <td>{{ number_format($movie->download_count) }}</td>
                    <td>
                        <span class="status-badge" style="background: {{ $movie->is_active ? 'rgba(0,255,0,0.2)' : 'rgba(255,0,0,0.2)' }}; color: {{ $movie->is_active ? '#0f0' : '#f00' }}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem;">
                            {{ $movie->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete({{ $movie->id }}, '{{ $movie->title }}')" class="btn-secondary" style="padding: 0.4rem 0.8rem; background: #e31c25;" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button onclick="confirmStatusToggle({{ $movie->id }}, '{{ $movie->title }}', {{ $movie->is_active ? 'true' : 'false' }})" class="btn-secondary" style="padding: 0.4rem 0.8rem;" title="Toggle Status">
                                <i class="fas fa-power-off"></i>
                            </button>
                        </div>
                        <!-- Hidden forms for delete and status toggle -->
                        <form id="delete-form-{{ $movie->id }}" action="{{ route('admin.movies.destroy', $movie) }}" method="POST" style="display: none;">
                            @csrf @method('DELETE')
                        </form>
                        <form id="toggle-form-{{ $movie->id }}" action="{{ route('admin.movies.toggle-status', $movie) }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
        {{ $movies->links() }}
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Search and Filter functionality
    document.getElementById('searchInput').addEventListener('keyup', filterTable);
    document.getElementById('statusFilter').addEventListener('change', filterTable);
    document.getElementById('genreFilter').addEventListener('change', filterTable);
    
    function filterTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const genreFilter = document.getElementById('genreFilter').value;
        
        const rows = document.querySelectorAll('#moviesTableBody tr');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const title = row.getAttribute('data-title') || '';
            const genre = row.getAttribute('data-genre') || '';
            const status = row.getAttribute('data-status') || '';
            
            let matchesSearch = title.includes(searchTerm);
            let matchesStatus = !statusFilter || status === statusFilter;
            let matchesGenre = !genreFilter || genre === genreFilter;
            
            if (matchesSearch && matchesStatus && matchesGenre) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show no results message
        const tbody = document.getElementById('moviesTableBody');
        const noResultsRow = document.getElementById('noResultsRow');
        
        if (visibleCount === 0) {
            if (!noResultsRow) {
                const tr = document.createElement('tr');
                tr.id = 'noResultsRow';
                tr.innerHTML = `<td colspan="9" style="text-align: center; padding: 3rem;">
                                    <i class="fas fa-search" style="font-size: 3rem; color: #888;"></i>
                                    <p style="margin-top: 1rem; color: #888;">No movies found</p>
                                </td>`;
                tbody.appendChild(tr);
            }
        } else if (noResultsRow) {
            noResultsRow.remove();
        }
    }
    
    function clearFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('genreFilter').value = '';
        filterTable();
    }
    
    // Sorting functionality
    let sortDirection = {};
    
    function sortTable(columnIndex) {
        const table = document.getElementById('moviesTable');
        const tbody = document.getElementById('moviesTableBody');
        const rows = Array.from(tbody.querySelectorAll('tr:not(#noResultsRow)'));
        
        // Get current direction
        const direction = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
        sortDirection = { [columnIndex]: direction };
        
        rows.sort((a, b) => {
            let aVal = a.cells[columnIndex].innerText.trim();
            let bVal = b.cells[columnIndex].innerText.trim();
            
            // Handle numeric values
            if (columnIndex === 0 || columnIndex === 4 || columnIndex === 6) {
                aVal = parseInt(aVal) || 0;
                bVal = parseInt(bVal) || 0;
                return direction === 'asc' ? aVal - bVal : bVal - aVal;
            }
            
            // Handle string values
            return direction === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
        });
        
        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // SweetAlert Confirm Delete
    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete "${title}"!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#1a1a22',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        });
    }
    
    // SweetAlert Confirm Status Toggle
    function confirmStatusToggle(id, title, isActive) {
        const action = isActive ? 'deactivate' : 'activate';
        const newStatus = isActive ? 'Inactive' : 'Active';
        
        Swal.fire({
            title: `Are you sure?`,
            text: `Do you want to ${action} "${title}"? It will become ${newStatus}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e31c25',
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${action} it!`,
            cancelButtonText: 'Cancel',
            background: '#1a1a22',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${id}`).submit();
            }
        });
    }
    
    // Success message after delete/update (show if session has success)
    @if(session('success'))
    Swal.fire({
        title: 'Success!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#e31c25',
        background: '#1a1a22',
        color: '#fff',
        timer: 3000,
        showConfirmButton: false
    });
    @endif
    
    // Error message if any
    @if(session('error'))
    Swal.fire({
        title: 'Error!',
        text: '{{ session('error') }}',
        icon: 'error',
        confirmButtonColor: '#e31c25',
        background: '#1a1a22',
        color: '#fff'
    });
    @endif
</script>

<style>
    /* Data table hover effect */
    .data-table tbody tr:hover {
        background: rgba(227, 28, 37, 0.05);
    }
    
    /* Button styles */
    .btn-secondary {
        background: rgba(255,255,255,0.1);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    
    .btn-secondary:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }
    
    /* Pagination styles */
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
    }
    
    .pagination li a, .pagination li span {
        padding: 0.5rem 1rem;
        background: #1a1a22;
        border-radius: 8px;
        color: white;
        text-decoration: none;
    }
    
    .pagination li.active span {
        background: #e31c25;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .data-table th, .data-table td {
            padding: 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>
@endsection