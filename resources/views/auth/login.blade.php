<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eduSPACE - Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary text-white font-poppins min-h-screen overflow-x-hidden relative">
    <!-- Background Blobs -->
    <div class="bg-blob blob-1 fixed w-64 h-64 md:w-80 md:h-80 right-10 top-20"></div>
    <div class="bg-blob blob-2 fixed w-64 h-64 md:w-80 md:h-80 left-10 bottom-20"></div>
    <div class="bg-blob blob-3 fixed w-64 h-64 md:w-80 md:h-80 left-10 top-20"></div>

    <!-- Header -->
    <header class="w-full h-16 md:h-28 fixed top-0 z-50 bg-secondary/80 backdrop-blur-md flex justify-between items-center px-5 md:px-16">
        <div class="logo text-2xl md:text-4xl font-tilt-warp text-shadow-lg shadow-white/30">
            edu<span class="text-primary">SPACE.</span>
        </div>
        <div class="nav-links flex gap-4 md:gap-12">
            <a href="{{ route('login') }}" class="text-sm md:text-lg text-primary text-shadow shadow-white/50 transition-colors">Login</a>
            <a href="{{ route('register') }}" class="text-sm md:text-lg text-white text-shadow shadow-white/50 hover:text-primary transition-colors">Register</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="login-container w-full max-w-md bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
            <h1 class="login-title text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Login</h1>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group mb-6">
                    <label for="email" class="block text-lg mb-3">Email</label>
                    <div class="input-with-icon relative">
                        <input type="email" id="email" name="email" class="form-input w-full bg-transparent border-b border-white py-2 pr-10 text-white outline-none focus:border-primary transition-colors duration-200" placeholder="Enter your email" required>
                        <!-- Email Icon -->
                        <div class="input-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group mb-6">
                    <label for="password" class="block text-lg mb-3">Password</label>
                    <div class="input-with-icon relative">
                        <input type="password" id="password" name="password" class="form-input w-full bg-transparent border-b border-white py-2 pr-10 text-white outline-none focus:border-primary transition-colors duration-200" placeholder="Enter your password" required>
                        <!-- Password Icon -->
                        <div class="input-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <button type="submit" class="login-button w-full bg-primary py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:bg-primary/90 transition-all duration-300 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Login
                </button>
            </form>

            <div class="text-center mt-6">
                <p class="text-sm text-white/80">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-primary hover:underline font-medium transition-colors">Register here</a>
                </p>
            </div>

            
        </div>
    </main>

    <style>
        .bg-blob {
            border-radius: 50%;
            background: rgba(199.32, 68.73, 239.06, 0.2);
            filter: blur(112.8px);
            z-index: 0;
        }

        /* Input focus effects */
        .form-input:focus {
            transform: translateY(-1px);
        }

        /* Smooth transitions for all interactive elements */
        .login-button:hover {
            transform: translateY(-2px);
        }

        /* Social login button hover effects */
        .social-login button:hover {
            transform: translateY(-1px);
            border-color: rgba(171, 26, 214, 0.5);
        }

        /* Checkbox styling */
        input[type="checkbox"]:checked {
            background-color: #ab1ad6;
            border-color: #ab1ad6;
        }

        /* Placeholder styling */
        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Link hover effects */
        a:hover {
            transform: translateY(-1px);
        }
    </style>
</body>
</html>