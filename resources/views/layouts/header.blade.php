<header class="w-full h-16 md:h-28 fixed top-0 z-50 -md flex justify-between items-center px-5 md:px-16">
    <div class="logo text-2xl md:text-4xl font-tilt-warp text-shadow-lg shadow-white/30">
        <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition-opacity">edu<span class="text-primary">SPACE.</span></a>
    </div>
    
    <div class="nav-center hidden md:flex bg-gradient-to-r from-transparent to-transparent rounded-full outline outline-2 outline-purple-400 backdrop-blur-sm px-6 py-3">
        <a href="{{ route('dashboard') }}" class="text-white text-lg font-medium mx-4 hover:text-primary transition-colors {{ request()->routeIs('kelas.*') && !request()->routeIs('kelas.materials.*') && !request()->routeIs('kelas.assignments.*') ? 'text-primary' : '' }}">Classes</a>
        <a href="{{ route('kelas.materials.index') }}" class="text-white text-lg font-medium mx-4 hover:text-primary transition-colors {{ request()->routeIs('kelas.materials.*') ? 'text-primary' : '' }}">Materials</a>
        <a href="{{ route('kelas.assignments.index') }}" class="text-white text-lg font-medium mx-4 hover:text-primary transition-colors {{ request()->routeIs('kelas.assignments.*') ? 'text-primary' : '' }}">Assignments</a>
    </div>

    <!-- Notifications Dropdown -->
<div class="nav-right hidden md:flex items-center" x-data="{ open: false, notificationsOpen: false, unreadCount: 0, notifications: [] }" 
     x-init="
        // Load unread count on page load
        fetch('{{ route('notifications.unreadCount') }}')
            .then(response => response.json())
            .then(data => unreadCount = data.count);
        
        // Load notifications when dropdown opens
        $watch('notificationsOpen', function(value) {
            if (value) {
                fetch('{{ route('notifications.unreadList') }}')
                    .then(response => response.json())
                    .then(data => notifications = data.notifications);
            }
        });
     ">
    
    <!-- Notifications Bell -->
    <div class="relative mr-4">
        <button @click="notificationsOpen = !notificationsOpen" 
                class="flex items-center text-white hover:text-primary transition-colors p-2 rounded-lg hover:bg-white/5 relative">
                <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 21h4a2 2 0 0 0 2-2H8a2 2 0 0 0 2 2Z" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M18 16V11C18 7.686 16.209 5 13 4.272V4a1 1 0 1 0-2 0v.272C7.791 5 6 7.686 6 11v5l-2 2v1h16v-1l-2-2Z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

            
            <!-- Unread Count Badge -->
            <span x-show="unreadCount > 0" 
                  x-text="unreadCount"
                  class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center animate-pulse font-semibold">
            </span>
        </button>

        <!-- Notifications Dropdown -->
        <div x-show="notificationsOpen" 
             @click.away="notificationsOpen = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-80 bg-gradient-to-b from-gray-800 to-purple-900 rounded-xl shadow-lg border border-white/20 backdrop-blur-md z-50 overflow-hidden"
             style="display: none;">
            
            <!-- Notifications Header -->
            <div class="px-4 py-3 border-b border-white/10 flex justify-between items-center">
                <h3 class="text-white font-semibold">Notifications</h3>
                <button x-show="unreadCount > 0" 
                        @click="
                            fetch('{{ route('notifications.markAllAsRead') }}', { method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} })
                                .then(response => response.json())
                                .then(data => {
                                    unreadCount = 0;
                                    notifications.forEach(notification => notification.dibaca = true);
                                });
                        "
                        class="text-primary hover:text-purple-300 text-sm font-medium transition-colors">
                   
                </button>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                <template x-if="notifications.length === 0">
                    <div class="px-4 py-8 text-center">
                        <svg class="w-12 h-12 text-white/30 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M15 17h6m-6 0v-6c0-2.209-1.791-4-4-4S7 8.791 7 11v6m-2 0h14M9 17v1a3 3 0 006 0v-1"/>
                        </svg>
                        <p class="text-white/60 text-sm">No new notifications</p>
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id_notifikasi">
                    <a :href="notification.tautan || '#'" 
                       @click="
                            fetch(`/notifications/${notification.id_notifikasi}/read`, { 
                                method: 'POST', 
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'} 
                            })
                                .then(response => response.json())
                                .then(data => {
                                    unreadCount = data.unread_count;
                                    notificationsOpen = false;
                                });
                       "
                       class="block px-4 py-3 border-b border-white/10 hover:bg-white/5 transition-colors group">
                        <div class="flex items-start space-x-3">
                            <!-- Notification Icon -->
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center">
                                <template x-if="notification.tipe === 'materi_baru'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </template>
                                <template x-if="notification.tipe === 'tugas_baru'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </template>
                                <template x-if="notification.tipe === 'nilai_diberikan'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </template>
                                <template x-if="notification.tipe === 'komentar_baru'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </template>
                                <template x-if="notification.tipe === 'siswa_bergabung'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                </template>
                                <template x-if="notification.tipe === 'tugas_dikumpulkan'">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </template>
                            </div>
                            
                            <!-- Notification Content -->
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-medium text-sm mb-1" x-text="notification.judul"></p>
                                <p class="text-white/70 text-xs leading-relaxed" x-text="notification.pesan"></p>
                                <p class="text-white/40 text-xs mt-1" x-text="new Date(notification.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })"></p>
                            </div>
                            
                            <!-- Unread Indicator -->
                            <div class="flex-shrink-0 w-2 h-2 bg-primary rounded-full animate-pulse" x-show="!notification.dibaca"></div>
                        </div>
                    </a>
                </template>
            </div>

            <!-- Notifications Footer -->
            <div class="px-4 py-3 border-t border-white/10">
                <a href="{{ route('notifications.index') }}" 
                   class="block text-center text-primary hover:text-purple-300 text-sm font-medium transition-colors">
                    View all notifications
                </a>
            </div>
        </div>
    </div>

    <!-- Avatar Dropdown (existing code) -->
    <div class="relative">
        <!-- Avatar Button -->
        <button @click="open = !open" class="flex items-center bg-gradient-to-r from-transparent to-transparent rounded-full outline outline-2 outline-purple-400 backdrop-blur-sm px-4 py-2 hover:bg-white/5 transition-colors space-x-4">
            <div class="user-name text-white text-lg font-medium text-shadow shadow-white/30">
                {{ Auth::user()->nama }}
            </div>
            @if(Auth::user()->avatar)
                <img class="user-avatar w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar">
            @else
                <div class="user-avatar w-10 h-10 rounded-full border-2 border-white bg-primary flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
            @endif
            <!-- Dropdown Arrow -->
            <svg class="w-4 h-4 ml-2 text-white transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
            @click.away="open = false" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 mt-2 w-48 bg-gradient-to-b from-gray-800 to-purple-900 rounded-xl shadow-lg border border-white/20 backdrop-blur-md z-50 overflow-hidden"
            style="display: none;">
            
            <!-- User Info -->
            <div class="px-4 py-3 border-b border-white/10">
                <p class="text-sm font-medium text-white">{{ Auth::user()->nama }}</p>
                <p class="text-sm text-white/60 truncate">{{ Auth::user()->email }}</p>
                <p class="text-xs text-primary mt-1">{{ Auth::user()->isGuru() ? 'Teacher' : 'Student' }}</p>
            </div>
            
            <!-- Profile Link -->
            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-white hover:bg-white/10 transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profile Settings
            </a>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-white hover:bg-red-500/20 hover:text-red-300 transition-colors border-t border-white/10">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
    
    </div>
    
    <!-- Hamburger Menu Button -->
    <div class="hamburger md:hidden flex flex-col justify-between w-9 h-7 cursor-pointer z-50 bg-primary/20 rounded-lg p-1.5" id="hamburger-menu">
        <span class="block h-0.5 w-full bg-white rounded"></span>
        <span class="block h-0.5 w-full bg-white rounded"></span>
        <span class="block h-0.5 w-full bg-white rounded"></span>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu fixed inset-0 bg-secondary/98 backdrop-blur-xl z-40 flex flex-col items-center justify-center opacity-0 transform -translate-y-full transition-all duration-400" id="mobile-menu">
    <div class="user-section flex flex-col items-center mb-8 pb-6 border-b border-white/10 w-full max-w-xs">
        @if(Auth::user()->avatar)
            <img class="mobile-user-avatar w-16 h-16 rounded-full border-2 border-white object-cover mb-4" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="User Avatar">
        @else
            <div class="mobile-user-avatar w-16 h-16 rounded-full border-2 border-white bg-primary flex items-center justify-center text-white font-bold text-2xl mb-4">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
        @endif
        <div class="mobile-user-name text-white text-xl font-medium text-center">
            {{ Auth::user()->nama }}
        </div>
        <div class="mobile-user-role text-primary text-sm font-medium mt-1">
            {{ Auth::user()->isGuru() ? 'Teacher' : 'Student' }}
        </div>
    </div>
    
    <a href="{{ route('dashboard') }}" class="text-white text-xl py-3 px-6 my-2 hover:text-primary transition-colors text-center w-full max-w-xs flex items-center justify-center {{ request()->routeIs('kelas.*') && !request()->routeIs('kelas.materials.*') && !request()->routeIs('kelas.assignments.*') ? 'text-primary' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        Classes
    </a>
    
    <a href="{{ route('kelas.materials.index') }}" class="text-white text-xl py-3 px-6 my-2 hover:text-primary transition-colors text-center w-full max-w-xs flex items-center justify-center {{ request()->routeIs('kelas.materials.*') ? 'text-primary' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Materials
    </a>
    
    <a href="{{ route('kelas.assignments.index') }}" class="text-white text-xl py-3 px-6 my-2 hover:text-primary transition-colors text-center w-full max-w-xs flex items-center justify-center {{ request()->routeIs('kelas.assignments.*') ? 'text-primary' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Assignments
    </a>
    
    <a href="{{ route('profile.edit') }}" class="text-white text-xl py-3 px-6 my-2 hover:text-primary transition-colors text-center w-full max-w-xs flex items-center justify-center {{ request()->routeIs('profile.*') ? 'text-primary' : '' }}">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        Profile
    </a>
    
    <!-- Logout Form -->
    <form method="POST" action="{{ route('logout') }}" class="w-full max-w-xs">
        @csrf
        <button type="submit" class="text-white text-xl py-3 px-6 my-2 hover:text-primary transition-colors w-full text-center flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </button>
    </form>
</div>

@push('scripts')
<script>
    // Hamburger toggle
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (hamburgerMenu && mobileMenu) {
            hamburgerMenu.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = mobileMenu.classList.contains('opacity-0');
                
                if (isHidden) {
                    mobileMenu.classList.remove('opacity-0', '-translate-y-full', 'pointer-events-none');
                    mobileMenu.classList.add('opacity-100', 'translate-y-0');
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenu.classList.remove('opacity-100', 'translate-y-0');
                    mobileMenu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                    document.body.style.overflow = 'auto';
                }
            });

            // Close menu when clicking link
            mobileMenu.querySelectorAll('a, button').forEach(element => {
                element.addEventListener('click', () => {
                    mobileMenu.classList.remove('opacity-100', 'translate-y-0');
                    mobileMenu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                    document.body.style.overflow = 'auto';
                });
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!mobileMenu.classList.contains('opacity-0') && 
                    !mobileMenu.contains(e.target) && 
                    !hamburgerMenu.contains(e.target)) {
                    mobileMenu.classList.remove('opacity-100', 'translate-y-0');
                    mobileMenu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                    document.body.style.overflow = 'auto';
                }
            });

            // Close menu with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !mobileMenu.classList.contains('opacity-0')) {
                    mobileMenu.classList.remove('opacity-100', 'translate-y-0');
                    mobileMenu.classList.add('opacity-0', '-translate-y-full', 'pointer-events-none');
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
</script>
@endpush