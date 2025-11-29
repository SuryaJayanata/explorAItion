@extends('layouts.app')

@section('title', 'All Materials - eduSPACE')

@section('content')
    <div class="min-h-screen px-5 py-28 md:py-32">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">All Materials</h1>
                <p class="text-white/60 text-lg">Browse all learning materials from your classes</p>
            </div>

            <!-- Search Bar -->
            <div class="mb-8">
                <div class="relative max-w-md">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search materials by title, description, or class..."
                           class="w-full bg-white/10 border border-white/30 rounded-xl px-6 py-4 text-white placeholder-white/40 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <div id="searchResults" class="mt-2 text-white/60 text-sm hidden">
                    Found <span id="resultsCount">0</span> materials
                </div>
            </div>

            <!-- Materials Grid -->
            <div id="materialsContainer">
                @include('materials.partials.materials-grid', ['materials' => $materials])
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden text-center py-8">
                <div class="inline-flex items-center px-4 py-2 bg-white/10 rounded-lg">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-white">Loading more materials...</span>
                </div>
            </div>

            <!-- Load More Button -->
            @if($materials->hasMorePages())
                <div class="text-center mt-8" id="loadMoreContainer">
                    <button id="loadMoreBtn" 
                            class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                        Load More Materials
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
        
        if (query.length === 0) {
            resetSearch();
            return;
        }
        
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
        
        fetch(`{{ route('kelas.materials.search') }}?query=${encodeURIComponent(query)}&page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('materialsContainer').innerHTML = data.html;
                updateSearchResults(data.total);
                
                // Update load more functionality
                updateLoadMoreButton(data.has_more, page);
            } else {
                showToast('Failed to search materials', 'error');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            showToast('Error searching materials', 'error');
        })
        .finally(() => {
            isLoading = false;
            hideLoading();
        });
    }

    // Load more functionality
    document.getElementById('loadMoreBtn')?.addEventListener('click', function() {
        if (isLoading) return;
        
        currentPage++;
        isLoading = true;
        showLoading();
        
        if (currentQuery) {
            // Load more search results
            fetch(`{{ route('kelas.materials.search') }}?query=${encodeURIComponent(currentQuery)}&page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('materialsContainer').insertAdjacentHTML('beforeend', data.html);
                    updateLoadMoreButton(data.has_more, currentPage);
                }
            })
            .finally(() => {
                isLoading = false;
                hideLoading();
            });
        } else {
            // Load more regular results
            fetch(`{{ route('kelas.materials.load-more') }}?page=${currentPage}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('materialsContainer').insertAdjacentHTML('beforeend', data.html);
                    updateLoadMoreButton(data.has_more, currentPage);
                } else {
                    document.getElementById('loadMoreContainer').innerHTML = '<p class="text-white/60">No more materials to load</p>';
                }
            })
            .finally(() => {
                isLoading = false;
                hideLoading();
            });
        }
    });

    // Utility functions
    function resetSearch() {
        currentQuery = '';
        currentPage = 1;
        location.reload(); // Simple reset by reloading page
    }

    function updateSearchResults(total) {
        const resultsElement = document.getElementById('searchResults');
        const countElement = document.getElementById('resultsCount');
        
        resultsElement.classList.remove('hidden');
        countElement.textContent = total;
    }

    function updateLoadMoreButton(hasMore, page) {
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        
        if (!hasMore) {
            if (loadMoreContainer) {
                loadMoreContainer.innerHTML = '<p class="text-white/60">No more materials to load</p>';
            }
        } else {
            if (!loadMoreBtn && loadMoreContainer) {
                loadMoreContainer.innerHTML = `
                    <button id="loadMoreBtn" 
                            class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                        Load More Materials
                    </button>
                `;
                // Re-attach event listener
                document.getElementById('loadMoreBtn').addEventListener('click', loadMoreHandler);
            }
        }
    }

    function showLoading() {
        document.getElementById('loadingSpinner').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingSpinner').classList.add('hidden');
    }

    function showToast(message, type = 'info') {
        // Your existing toast function
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${getToastClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                ${getToastIcon(type)}
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
        
        toast.addEventListener('click', () => {
            toast.remove();
        });
    }

    function getToastClass(type) {
        const classes = {
            success: 'bg-green-500 text-white border border-green-400',
            error: 'bg-red-500 text-white border border-red-400',
            info: 'bg-blue-500 text-white border border-blue-400'
        };
        return classes[type] || classes.info;
    }

    function getToastIcon(type) {
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`
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