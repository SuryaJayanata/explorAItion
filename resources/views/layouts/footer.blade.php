<footer class="w-11/12 mx-auto bg-purple-900/30 rounded-2xl border border-white/10 backdrop-blur-sm p-8 md:p-10 my-8 md:my-12">
    <div class="footer-content grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12">
        <div class="footer-logo">
            <div class="footer-logo-text text-3xl md:text-4xl font-tilt-warp text-shadow shadow-white/30 leading-tight">
                edu<br><span class="text-primary">SPACE.</span>
            </div>
            <p class="text-white/70 font-montserrat text-sm mt-4">
                Manage smarter, learn faster with eduSPACE.
            </p>
        </div>

        <div class="footer-nav">
            <h4 class="text-primary text-xl font-montserrat font-bold mb-5">Navigation</h4>
            <ul>
                <li class="mb-3">
                
                </li>
                <li class="mb-3">
                    <a href="{{ route('dashboard') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors flex items-center gap-2 group">
                        <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                        Classes
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('kelas.materials.index') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors flex items-center gap-2 group">
                        <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                        Materials
                    </a>
                </li>
                <li class="mb-3">
                    <a href="{{ route('kelas.assignments.index') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors flex items-center gap-2 group">
                        <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                        Assignments
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors flex items-center gap-2 group">
                        <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                        Profile
                    </a>
                </li>
            </ul>
        </div>

        <div class="footer-nav">
            <h4 class="text-primary text-xl font-montserrat font-bold mb-5">Resources</h4>
            <ul>
                <li class="mb-3">
                    <a href="{{ route('notifications.index') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors flex items-center gap-2 group">
                        <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                        Notifications
                    </a>
                </li>
             
            </ul>
        </div>

        <div class="footer-contact">
            <h4 class="text-primary text-xl font-montserrat font-bold mb-5">Contact Info</h4>

            <div class="contact-info-item flex items-center gap-4 mb-4 group hover:opacity-100 opacity-70 transition-opacity">
                <div class="contact-info-icon w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center group-hover:bg-primary/30 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="contact-info-text font-montserrat text-white block">sjayanata00@gmail.com</span>
                    <span class="contact-info-text font-montserrat text-white/70 text-xs">Primary Contact</span>
                </div>
            </div>

            <div class="contact-info-item flex items-center gap-4 mb-4 group hover:opacity-100 opacity-70 transition-opacity">
                <div class="contact-info-icon w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center group-hover:bg-primary/30 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <span class="contact-info-text font-montserrat text-white block">081515195898</span>
                    <span class="contact-info-text font-montserrat text-white/70 text-xs">WhatsApp Available</span>
                </div>
            </div>

            <!-- Admin Access (only show if user is admin) -->
            @auth
                @if(auth()->user()->role === 'admin')
                <div class="admin-access mt-4 pt-4 border-t border-white/10">
                    <a href="{{ route('admin.dashboard') }}" class="text-primary font-montserrat font-medium hover:text-white transition-colors flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 bg-primary rounded-full"></span>
                        Admin Dashboard
                    </a>
                </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="footer-bottom border-t border-white/10 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center">
        <div class="copyright text-white/70 font-montserrat text-sm mb-4 md:mb-0 text-center md:text-left">
            © 2024 eduSPACE. All rights reserved.
        </div>
        
       
    </div>
</footer>

<style>
    /* Smooth scrolling for anchor links */
    html {
        scroll-behavior: smooth;
    }

    /* Enhanced hover effects */
    .footer-nav a {
        position: relative;
        transition: all 0.3s ease;
    }

    .footer-nav a:hover {
        transform: translateX(5px);
    }

    .contact-info-item {
        transition: all 0.3s ease;
    }

    .contact-info-item:hover {
        transform: translateX(3px);
    }

    .admin-access {
        background: rgba(171, 26, 214, 0.1);
        border-radius: 8px;
        padding: 8px 12px;
    }
</style>

<script>
    // Enhanced footer functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Handle authentication state for links
        function checkAuthAndRedirect(link) {
            const href = link.getAttribute('href');
            
            // Check if the route requires authentication
            const protectedRoutes = [
                'dashboard', 
                'kelas.materials.index',
                'kelas.assignments.index',
                'profile.edit',
                'notifications.index'
            ];

            const isProtected = protectedRoutes.some(route => href.includes(route));
            
            if (isProtected) {
                // Check if user is authenticated (you might need to adjust this based on your auth setup)
                const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
                
                if (!isAuthenticated) {
                    e.preventDefault();
                    window.location.href = "{{ route('login') }}";
                    return;
                }
            }
        }

        // Add click handlers for all links
        document.querySelectorAll('footer a').forEach(link => {
            link.addEventListener('click', function(e) {
                // For anchor links, let smooth scrolling handle it
                if (this.getAttribute('href').startsWith('#')) {
                    return;
                }

                // For external links, open in new tab
                if (this.getAttribute('href').startsWith('http')) {
                    return;
                }

                // Check authentication for protected routes
                checkAuthAndRedirect(this);
            });
        });

        // Add loading states for better UX
        document.querySelectorAll('footer a[href]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href').startsWith('#') || this.getAttribute('href').startsWith('http')) {
                    return;
                }

                const originalText = this.textContent;
                this.innerHTML = '<span class="loading">Loading...</span>';
                this.style.opacity = '0.7';
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.opacity = '1';
                }, 2000);
            });
        });
    });

    // Fallback for routes that might not exist
    function handleRouteFallback(routeName, element) {
        // This function can be used to handle routes that might not be implemented yet
        element.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#' || this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                // Show a temporary message for unimplemented features
                const tempMessage = document.createElement('div');
                tempMessage.textContent = 'Feature coming soon!';
                tempMessage.className = 'fixed bottom-4 right-4 bg-primary text-white px-4 py-2 rounded-lg shadow-lg';
                document.body.appendChild(tempMessage);
                
                setTimeout(() => {
                    document.body.removeChild(tempMessage);
                }, 3000);
            }
        });
    }

    // Initialize route fallback handlers
    document.addEventListener('DOMContentLoaded', function() {
        const fallbackRoutes = ['#help', '#documentation', '#tutorials', '#privacy', '#terms', '#cookies'];
        
        fallbackRoutes.forEach(route => {
            const elements = document.querySelectorAll(`a[href="${route}"]`);
            elements.forEach(element => {
                handleRouteFallback(route, element);
            });
        });
    });
</script>