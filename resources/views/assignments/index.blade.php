@extends('layouts.app')

@section('title', 'All Assignments - eduSPACE')

@section('content')
    <div class="min-h-screen px-5 py-28 md:py-32">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">All Assignments</h1>
                <p class="text-white/60 text-lg">Browse all assignments from your classes</p>
            </div>

            <!-- Search Bar -->
            <div class="mb-8">
                <div class="relative max-w-md">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search assignments by title, description, or class..."
                           class="w-full bg-white/10 border border-white/30 rounded-xl px-6 py-4 text-white placeholder-white/40 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div id="searchResults" class="mt-2 text-white/60 text-sm hidden">
                    Found <span id="resultsCount">0</span> assignments
                </div>
            </div>

            <!-- Assignments Grid -->
            <div id="assignmentsContainer">
                @include('assignments.partials.assignments-grid', ['assignments' => $assignments])
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden text-center py-8">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-white">Loading more assignments...</span>
                </div>
            </div>

            <!-- Load More Button -->
            @if($assignments->hasMorePages())
                <div class="text-center mt-8" id="loadMoreContainer">
                    <button id="loadMoreBtn" 
                            class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                        Load More Assignments
                    </button>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ==================== AJAX SEARCH FUNCTIONALITY ====================
    
    let searchTimeout;
    let currentPage = 1;
    let isLoading = false;
    let currentQuery = '';

    // Search input handler
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.trim();
        currentQuery = query;
        currentPage = 1;
        
        clearTimeout(searchTimeout);
        
        // Jika query kosong, reload halaman untuk menampilkan semua assignments
        if (query.length === 0) {
            resetSearch();
            return;
        }
        
        // Minimal 2 karakter untuk search
        if (query.length < 2) {
            return;
        }
        
        searchTimeout = setTimeout(() => {
            performSearch(query, 1);
        }, 500);
    });

    // Perform search function
    function performSearch(query, page = 1) {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();
        
        console.log('Searching assignments for:', query, 'Page:', page);
        
        fetch(`{{ route('kelas.assignments.search') }}?query=${encodeURIComponent(query)}&page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Assignment search response:', data);
            
            if (data.success) {
                document.getElementById('assignmentsContainer').innerHTML = data.html;
                updateSearchResults(data.total);
                
                // Update load more functionality
                updateLoadMoreButton(data.has_more, page);
                
                if (data.total === 0) {
                    showToast('No assignments found for your search', 'info');
                }
            } else {
                showToast('Failed to search assignments', 'error');
            }
        })
        .catch(error => {
            console.error('Assignment search error:', error);
            showToast('Error searching assignments: ' + error.message, 'error');
        })
        .finally(() => {
            isLoading = false;
            hideLoading();
        });
    }

    // Load more functionality
    function loadMoreHandler() {
        if (isLoading) return;
        
        currentPage++;
        isLoading = true;
        showLoading();
        
        console.log('Loading more assignments, page:', currentPage, 'Query:', currentQuery);
        
        let url;
        if (currentQuery) {
            // Load more search results
            url = `{{ route('kelas.assignments.search') }}?query=${encodeURIComponent(currentQuery)}&page=${currentPage}`;
        } else {
            // Load more regular results
            url = `{{ route('kelas.assignments.load-more') }}?page=${currentPage}`;
        }
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Assignment load more response:', data);
            
            if (data.success) {
                document.getElementById('assignmentsContainer').insertAdjacentHTML('beforeend', data.html);
                updateLoadMoreButton(data.has_more, currentPage);
                
                if (data.count === 0) {
                    showToast('No more assignments to load', 'info');
                }
            } else {
                showToast(data.message || 'Failed to load more assignments', 'error');
            }
        })
        .catch(error => {
            console.error('Assignment load more error:', error);
            showToast('Error loading more assignments', 'error');
        })
        .finally(() => {
            isLoading = false;
            hideLoading();
        });
    }

    // Attach event listener to load more button
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'loadMoreBtn') {
            loadMoreHandler();
        }
    });

    // Utility functions
    function resetSearch() {
        console.log('Resetting assignment search...');
        // Simple reload untuk reset
        window.location.href = '{{ route("kelas.assignments.index") }}';
    }

    function updateSearchResults(total) {
        const resultsElement = document.getElementById('searchResults');
        const countElement = document.getElementById('resultsCount');
        
        if (resultsElement && countElement) {
            resultsElement.classList.remove('hidden');
            countElement.textContent = total;
        }
    }

    function updateLoadMoreButton(hasMore, page) {
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        
        if (!loadMoreContainer) return;
        
        if (!hasMore) {
            loadMoreContainer.innerHTML = '<p class="text-white/60">No more assignments to load</p>';
        } else {
            // Pastikan tombol load more ada
            if (!document.getElementById('loadMoreBtn')) {
                loadMoreContainer.innerHTML = `
                    <button id="loadMoreBtn" 
                            class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                        Load More Assignments
                    </button>
                `;
            }
        }
    }

    function showLoading() {
        const spinner = document.getElementById('loadingSpinner');
        if (spinner) {
            spinner.classList.remove('hidden');
        }
    }

    function hideLoading() {
        const spinner = document.getElementById('loadingSpinner');
        if (spinner) {
            spinner.classList.add('hidden');
        }
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${getToastClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                ${getToastIcon(type)}
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
        
        // Click to dismiss
        toast.addEventListener('click', () => {
            toast.remove();
        });
    }

    function getToastClass(type) {
        const classes = {
            success: 'bg-green-500 text-white border border-green-400',
            error: 'bg-red-500 text-white border border-red-400',
            info: 'bg-blue-500 text-white border border-blue-400',
            warning: 'bg-yellow-500 text-white border border-yellow-400'
        };
        return classes[type] || classes.info;
    }

    function getToastIcon(type) {
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>`
        };
        return icons[type] || icons.info;
    }

    // Infinite scroll (optional)
    window.addEventListener('scroll', function() {
        if (isLoading) return;
        
        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        
        if (loadMoreContainer && (scrollTop + clientHeight >= scrollHeight - 100)) {
            document.getElementById('loadMoreBtn')?.click();
        }
    });
</script>
@endpush