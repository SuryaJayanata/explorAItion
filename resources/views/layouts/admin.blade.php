<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - eduSPACE')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .avatar-image {
        border: 2px solid rgba(255, 255, 255, 0.2);
        object-fit: cover;
        }

        .avatar-fallback {
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: linear-gradient(135deg, #BD18EF, #4F46E5);
        }
        .bg-primary { background-color: #BD18EF; }
        .text-primary { color: #BD18EF; }
        .bg-secondary { background-color: #1a0933; }
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(40px);
            opacity: 0.1;
            z-index: 0;
        }
        .blob-1 { background: #BD18EF; }
        .blob-2 { background: #4F46E5; }
        .blob-3 { background: #06B6D4; }
    </style>
</head>
<body class="bg-secondary text-white font-poppins min-h-screen overflow-x-hidden relative">
    <!-- Background Blobs -->
    <div class="bg-blob blob-1 w-64 h-64 md:w-80 md:h-80 right-10 top-20"></div>
    <div class="bg-blob blob-2 w-64 h-64 md:w-80 md:h-80 left-10 bottom-20"></div>
    <div class="bg-blob blob-3 w-64 h-64 md:w-80 md:h-80 left-10 top-20"></div>

    <div class="flex h-screen relative z-10">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800/80 backdrop-blur-md text-white border-r border-white/10">
            <div class="p-4 border-b border-white/10">
                <h1 class="text-2xl font-bold font-tilt-warp">edu<span class="text-primary">SPACE.</span></h1>
                <p class="text-sm text-white/60">Admin Panel</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-3 px-4 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-primary' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </div>
                </a>
                <a href="{{ route('admin.users') }}" class="block py-3 px-4 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.users') ? 'bg-white/10 text-primary' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        Users
                    </div>
                </a>
                <a href="{{ route('admin.classes') }}" class="block py-3 px-4 hover:bg-white/10 transition-colors {{ request()->routeIs('admin.classes') ? 'bg-white/10 text-primary' : '' }}">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Classes
                    </div>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-4 border-t border-white/10 pt-4">
                    @csrf
                    <button type="submit" class="w-full text-left block py-3 px-4 hover:bg-white/10 transition-colors text-red-400">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </div>
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>