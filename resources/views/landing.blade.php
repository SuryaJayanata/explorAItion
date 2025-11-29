<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eduSPACE - Manage Smarter, Learn Faster</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
.hero {
    margin-top: -60px;
    padding-top: 0;
}
/* Efek Neon untuk Teks - VERSI LEBIH KECIL */
.neon-text {
    text-shadow: 
        0 0 2px rgba(255, 255, 255, 0.3),
        0 0 4px rgba(255, 255, 255, 0.2),
        0 0 6px rgba(255, 255, 255, 0.1);
}
/* Loading spinner */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
.neon-primary {
    text-shadow: 
        0 0 3px rgba(171, 26, 214, 0.4),
        0 0 6px rgba(171, 26, 214, 0.3),
        0 0 9px rgba(171, 26, 214, 0.2);
}

/* Hover effect untuk neon - VERSI LEBIH KECIL */
.neon-hover:hover {
    text-shadow: 
        0 0 3px rgba(255, 255, 255, 0.4),
        0 0 6px rgba(255, 255, 255, 0.3),
        0 0 9px rgba(255, 255, 255, 0.2);
    transition: text-shadow 0.3s ease;
}
/* Gambar Astro di Hero Section */
.hero img[src*="Astro.png"] {
    width: 700px !important; /* Ukuran lebih kecil */
    margin-left: 300px;
    height: auto;
}
/* Hover effect untuk login register */
.nav-links a:hover {
    color: #ab1ad6 !important; /* Warna primary ungu */
}

/* Atau lebih spesifik */
.text-shadow.shadow-white\\/50.hover\\:text-primary:hover {
    color: #ab1ad6 !important;
}

/* Footer Specific Styles */
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

/* Smooth scroll behavior */
html {
    scroll-behavior: smooth;
}

/* Style untuk section yang di-scroll */
section {
    scroll-margin-top: 100px; /* Offset untuk header fixed */
}

/* Hover effects untuk footer links */
.footer-nav a {
    transition: all 0.3s ease;
}

.footer-nav a:hover {
    transform: translateX(5px);
    color: #ab1ad6 !important;
}

</style>

</head>
<body class="bg-secondary text-white font-poppins overflow-x-hidden relative">
    <!-- Background Blobs -->
    <div class="bg-blob blob-1 fixed w-64 h-64 md:w-80 md:h-80 right-10 top-20"></div>
    <div class="bg-blob blob-2 fixed w-64 h-64 md:w-80 md:h-80 left-10 bottom-20"></div>
    <div class="bg-blob blob-3 fixed w-64 h-64 md:w-80 md:h-80 left-10 top-20"></div>

    <!-- Header -->
    <header class="w-full h-16 md:h-28 top-0 z-50 flex justify-between items-center px-5 md:px-20 relative z-[100]">
        <div class="logo text-2xl md:text-4xl font-tilt-warp text-shadow-lg shadow-white/30 neon-text">
            edu<span class="text-primary neon-primary">SPACE.</span>
        </div>
        <div class="nav-links flex gap-4 md:gap-12">
    <a href="{{ route('login') }}" class="text-sm md:text-lg text-shadow shadow-white/50 hover:text-primary transition-colors">Login</a>
    <a href="{{ route('register') }}" class="text-sm md:text-lg text-shadow shadow-white/50 hover:text-primary transition-colors">Register</a>
</div>
    </header>

    <!-- Hero Section -->
    <section class="hero min-h-screen flex flex-col md:flex-row items-center justify-center px-5 md:px-20 pt-15 md:pt-25 pb-10 md:pb-20 relative overflow-hidden">
        <!-- 🔹 Astro 1 Background (belakang layer hero) -->
        <img src="assets/Astro.png" 
             alt="Astro Illustration" 
             class="absolute inset-0 w-[300px] md:w-[500px] opacity-30 pointer-events-none select-none object-contain left-[300px] top-10 z-0">

        <div class="hero-content max-w-3xl md:mr-10 text-center md:text-left mb-10 md:mb-0 z-10 relative">
            <h1 class="hero-title text-4xl md:text-6xl lg:text-7xl font-anton mb-6 text-shadow shadow-white/10 leading-tight neon-text">
                Manage Smarter,<br><span class="text-primary neon-primary">Learn Faster</span>
            </h1>
            <p class="hero-description text-base md:text-xl font-medium mb-8 max-w-2xl mx-auto md:mx-0 leading-relaxed">
                A smarter way to organize <span class="text-primary neon-primary">education</span> and
                <span class="text-primary neon-primary">projects</span> together.
            </p>
            <a href="{{ route('register') }}" class="cta-button bg-primary px-8 py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all inline-block mt-5">
                Get Started
            </a>
        </div>

        <div class="hero-image w-full md:w-2/5 max-w-md opacity-80 z-10 relative">
            <img src="assets/example.png" alt="Education Illustration" class="w-full h-auto">
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section w-full px-5 md:px-20 py-16 md:py-24 relative overflow-hidden">
        <h2 class="section-title text-3xl md:text-5xl font-anton mb-12 md:mb-16 text-center text-shadow shadow-white/30 relative z-10 neon-text">
            <span class="text-primary neon-primary">Main</span> Features
        </h2>

        <div class="features-grid grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 max-w-6xl mx-auto relative z-10">
            <div class="feature-item flex flex-col md:flex-row items-center text-center md:text-left gap-6">
                <div class="feature-icon">
                    <img src="assets/Icon1.png" alt="Class Management Icon" class="w-24 h-24">
                </div>
                <div class="feature-text">
                    <h3 class="text-xl md:text-2xl font-montserrat font-semibold mb-2 text-shadow shadow-white/30 neon-text">Class Management</h3>
                </div>
            </div>

            <div class="feature-item flex flex-col md:flex-row items-center text-center md:text-left gap-6">
                <div class="feature-icon">
                    <img src="assets/Icon2.png" alt="Assignments Icon" class="w-24 h-24">
                </div>
                <div class="feature-text">
                    <h3 class="text-xl md:text-2xl font-montserrat font-semibold mb-2 text-shadow shadow-white/30 neon-text">Assignments & Submissions</h3>
                </div>
            </div>

            <div class="feature-item flex flex-col md:flex-row items-center text-center md:text-left gap-6">
                <div class="feature-icon">
                    <img src="assets/Icon3.png" alt="Multi-Role Icon" class="w-24 h-24">
                </div>
                <div class="feature-text">
                    <h3 class="text-xl md:text-2xl font-montserrat font-semibold mb-2 text-shadow shadow-white/30 neon-text">Multi-Role Login</h3>
                </div>
            </div>

            <div class="feature-item flex flex-col md:flex-row items-center text-center md:text-left gap-6">
                <div class="feature-icon">
                    <img src="assets/Icon4.png" alt="Access Materials Anywhere" class="w-24 h-24">
                </div>
                <div class="feature-text">
                    <h3 class="text-xl md:text-2xl font-montserrat font-semibold mb-2 text-shadow shadow-white/30 neon-text">Access Materials Anywhere</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section id="why" class="section w-full px-5 md:px-20 py-16 md:py-24 relative">
        <h2 class="section-title text-3xl md:text-5xl font-anton mb-12 md:mb-16 text-center text-shadow shadow-white/30 neon-text">
            <span class="text-primary neon-primary">Why</span> edu<span class="text-primary neon-primary">SPACE.</span>?
        </h2>

        <div class="benefits-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 max-w-6xl mx-auto">
            <div class="benefit-card bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm p-6 text-center transition-all hover:-translate-y-2 hover:bg-white/15">
                <div class="benefit-icon mb-5">
                    <img src="assets/Icon1b.png" alt="All-in-One Icon" class="w-32 h-32 mx-auto">
                </div>
                <h3 class="benefit-title text-xl font-montserrat font-bold mb-3 text-shadow shadow-white/30 neon-text">All-in-One Classroom</h3>
                <p class="benefit-description text-sm font-montserrat opacity-80 leading-relaxed">
                    Manage classes, assignments, and submissions in one platform.
                </p>
            </div>

            <div class="benefit-card bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm p-6 text-center transition-all hover:-translate-y-2 hover:bg-white/15">
                <div class="benefit-icon mb-5">
                    <img src="assets/Icon2b.png" alt="Simple Icon" class="w-32 h-32 mx-auto">
                </div>
                <h3 class="benefit-title text-xl font-montserrat font-bold mb-3 text-shadow shadow-white/30 neon-text">For Teachers & Students</h3>
                <p class="benefit-description text-sm font-montserrat opacity-80 leading-relaxed">
                    Designed to support both teaching and learning activities.
                </p>
            </div>

            <div class="benefit-card bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm p-6 text-center transition-all hover:-translate-y-2 hover:bg-white/15">
                <div class="benefit-icon mb-5">
                    <img src="assets/Icon3b.png" alt="For Everyone Icon" class="w-32 h-32 mx-auto">
                </div>
                <h3 class="benefit-title text-xl font-montserrat font-bold mb-3 text-shadow shadow-white/30 neon-text">Simple, Yet Powerful</h3>
                <p class="benefit-description text-sm font-montserrat opacity-80 leading-relaxed">
                    Clean interface that keeps focus on learning, not confusion.
                </p>
            </div>

            <div class="benefit-card bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm p-6 text-center transition-all hover:-translate-y-2 hover:bg-white/15">
                <div class="benefit-icon mb-5">
                    <img src="assets/Icon4b.png" alt="Future Ready Icon" class="w-32 h-32 mx-auto">
                </div>
                <h3 class="benefit-title text-xl font-montserrat font-bold mb-3 text-shadow shadow-white/30 neon-text">Future Ready</h3>
                <p class="benefit-description text-sm font-montserrat opacity-80 leading-relaxed">
                    Flexible features that can grow with your learning needs.
                </p>
            </div>
        </div>
    </section>

<!-- Contact Section -->
<section id="contact" class="contact-section px-5 md:px-20 py-16 md:py-24 max-w-6xl mx-auto flex flex-col md:flex-row gap-12 md:gap-16">
    <div class="contact-info w-full md:w-2/5">
        <h2 class="contact-title text-3xl md:text-4xl font-anton mb-8 text-shadow shadow-white/30 neon-text">
            <span class="text-primary neon-primary">Have questions or need support?</span> We'd love to hear from you.
        </h2>

        <div class="contact-details mt-10">
            <div class="contact-item flex items-center gap-5 mb-8">
                <div class="contact-icon">
                    <img src="assets/Icon1c.png" alt="Email Icon" class="w-10 h-10">
                </div>
                <span class="text-lg font-montserrat font-medium text-shadow shadow-white/30 neon-text">sjayanata00@gmail.com</span>
            </div>

            <div class="contact-item flex items-center gap-5">
                <div class="contact-icon">
                    <img src="assets/Icon2c.png" alt="Phone Icon" class="w-10 h-10">
                </div>
                <span class="text-lg font-montserrat font-medium text-shadow shadow-white/30 neon-text">081515195898</span>
            </div>
        </div>
    </div>

    <div class="contact-form w-full md:w-3/5 bg-white/10 rounded-2xl border border-white/10 backdrop-blur-sm p-6 md:p-8">
        <h3 class="form-title text-2xl font-poppins font-bold mb-6 text-center text-shadow shadow-white/30 neon-text">Contact Us</h3>

        <form id="contactForm">
            @csrf
            <div class="form-group mb-6">
                <label for="name" class="block text-lg font-poppins mb-3">Name *</label>
                <input type="text" id="name" name="name" required 
                       class="w-full bg-transparent border-b border-white py-2 px-1 text-white outline-none focus:border-primary transition-colors">
                <div class="error-message text-red-400 text-sm mt-1 hidden" id="nameError"></div>
            </div>

            <div class="form-group mb-6">
                <label for="email" class="block text-lg font-poppins mb-3">Email *</label>
                <input type="email" id="email" name="email" required
                       class="w-full bg-transparent border-b border-white py-2 px-1 text-white outline-none focus:border-primary transition-colors">
                <div class="error-message text-red-400 text-sm mt-1 hidden" id="emailError"></div>
            </div>

            <div class="form-group mb-8">
                <label for="message" class="block text-lg font-poppins mb-3">Message *</label>
                <textarea id="message" name="message" rows="4" required
                          class="w-full bg-transparent border-b border-white py-2 px-1 text-white outline-none focus:border-primary resize-none transition-colors"></textarea>
                <div class="error-message text-red-400 text-sm mt-1 hidden" id="messageError"></div>
            </div>

            <div id="formMessage" class="hidden mb-4 p-3 rounded-lg text-center"></div>

            <button type="submit" id="submitBtn" 
                    class="submit-btn w-full md:w-auto mx-auto block border-2 border-primary rounded-full px-8 py-3 text-white font-poppins font-medium hover:bg-primary/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                Send →
            </button>
            
            <div id="loadingSpinner" class="hidden text-center mt-4">
                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-primary"></div>
                <span class="ml-2">Sending message...</span>
            </div>
        </form>
    </div>
</section>

    <!-- Footer -->
    <footer class="w-11/12 mx-auto bg-purple-900/30 rounded-2xl border border-white/10 backdrop-blur-sm p-8 md:p-10 my-10">
        <div class="footer-content grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12">
            <div class="footer-logo">
                <div class="footer-logo-text text-3xl md:text-4xl font-tilt-warp text-shadow shadow-white/30 leading-tight neon-text">
                    edu<br><span class="text-primary neon-primary">SPACE.</span>
                </div>
                <p class="text-white/70 font-montserrat text-sm mt-4">
                    Manage smarter, learn faster with eduSPACE.
                </p>
            </div>

            <div class="footer-nav">
                <h4 class="text-primary text-xl font-montserrat font-bold mb-5 neon-primary">Get Started</h4>
                <ul>
                    <li class="mb-3">
                        <a href="{{ route('login') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Login
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('register') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Register
                        </a>
                    </li>
                    <li>
                        <a href="#features" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Features
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-nav">
                <h4 class="text-primary text-xl font-montserrat font-bold mb-5 neon-primary">Explore</h4>
                <ul>
                    <li class="mb-3">
                        <a href="#why" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Why eduSPACE?
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#contact" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Contact Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/') }}" class="text-white font-montserrat font-medium hover:text-primary transition-colors neon-hover flex items-center gap-2 group">
                            <span class="w-1 h-1 bg-primary rounded-full group-hover:scale-150 transition-transform"></span>
                            Home
                        </a>
                    </li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4 class="text-primary text-xl font-montserrat font-bold mb-5 neon-primary">Contact Info</h4>

                <div class="contact-info-item flex items-center gap-4 mb-4 group hover:opacity-100 opacity-70 transition-opacity">
                    <div class="contact-info-icon w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center group-hover:bg-primary/30 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <span class="contact-info-text font-montserrat text-white block neon-text">sjayanata00@gmail.com</span>
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
                        <span class="contact-info-text font-montserrat text-white block neon-text">081515195898</span>
                        <span class="contact-info-text font-montserrat text-white/70 text-xs">WhatsApp Available</span>
                    </div>
                </div>

                <!-- Admin Access (only show if user is admin) -->
                @auth
                    @if(auth()->user()->role === 'admin')
                    <div class="admin-access mt-4 pt-4 border-t border-white/10">
                        <a href="{{ route('admin.dashboard') }}" class="text-primary font-montserrat font-medium hover:text-white transition-colors flex items-center gap-2 text-sm neon-hover">
                            <span class="w-2 h-2 bg-primary rounded-full"></span>
                            Admin Dashboard
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
        </div>

        
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const formMessage = document.getElementById('formMessage');

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset messages
        resetMessages();
        
        // Validate form
        if (!validateForm()) {
            return;
        }

        // Show loading
        setLoading(true);

        try {
            const formData = new FormData(this);
            
            const response = await fetch('/contact', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showMessage(data.message, 'success');
                contactForm.reset();
            } else {
                if (data.errors) {
                    showFieldErrors(data.errors);
                } else {
                    showMessage(data.message || 'An error occurred. Please try again.', 'error');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Network error. Please check your connection and try again.', 'error');
        } finally {
            setLoading(false);
        }
    });

    function validateForm() {
        let isValid = true;
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const message = document.getElementById('message').value.trim();

        if (!name) {
            showFieldError('nameError', 'Name is required');
            isValid = false;
        }

        if (!email) {
            showFieldError('emailError', 'Email is required');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showFieldError('emailError', 'Please enter a valid email address');
            isValid = false;
        }

        if (!message) {
            showFieldError('messageError', 'Message is required');
            isValid = false;
        } else if (message.length < 10) {
            showFieldError('messageError', 'Message must be at least 10 characters long');
            isValid = false;
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showFieldError(fieldId, message) {
        const errorElement = document.getElementById(fieldId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    function showFieldErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(field + 'Error');
            if (errorElement) {
                errorElement.textContent = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        });
    }

    function showMessage(message, type) {
        formMessage.textContent = message;
        formMessage.className = `p-3 rounded-lg text-center ${
            type === 'success' ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 
            'bg-red-500/20 text-red-300 border border-red-500/30'
        }`;
        formMessage.classList.remove('hidden');
        
        // Auto hide success message after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                formMessage.classList.add('hidden');
            }, 5000);
        }
    }

    function resetMessages() {
        // Hide all error messages
        document.querySelectorAll('.error-message').forEach(el => {
            el.classList.add('hidden');
        });
        formMessage.classList.add('hidden');
    }

    function setLoading(loading) {
        if (loading) {
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            submitBtn.classList.add('opacity-50');
        } else {
            submitBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
            submitBtn.classList.remove('opacity-50');
        }
    }

    // Real-time validation
    document.querySelectorAll('#contactForm input, #contactForm textarea').forEach(input => {
        input.addEventListener('input', function() {
            const errorElement = document.getElementById(this.name + 'Error');
            if (errorElement) {
                errorElement.classList.add('hidden');
            }
        });
    });
});

        // Enhanced smooth scrolling dengan offset yang lebih baik
        function smoothScrollToSection(sectionId) {
            const section = document.querySelector(sectionId);
            if (section) {
                const header = document.querySelector('header');
                const headerHeight = header ? header.offsetHeight : 0;
                const sectionPosition = section.offsetTop - headerHeight - 40;
                
                window.scrollTo({
                    top: sectionPosition,
                    behavior: 'smooth'
                });
            }
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
            anchor.addEventListener("click", function (e) {
                e.preventDefault();

                const targetId = this.getAttribute("href");
                if (targetId === "#") return;

                smoothScrollToSection(targetId);
            });
        });

        // Event listeners untuk link footer
        document.addEventListener('DOMContentLoaded', function() {
            // Handle footer navigation
            const footerLinks = document.querySelectorAll('footer a[href^="#"]');
            footerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('href');
                    smoothScrollToSection(targetSection);
                });
            });

            // Handle external links (login/register)
            const externalLinks = document.querySelectorAll('footer a[href*="login"], footer a[href*="register"]');
            externalLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Biarkan link berfungsi normal
                    // Tidak perlu preventDefault() karena kita ingin redirect ke halaman login/register
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
    </script>
</body>
</html> 