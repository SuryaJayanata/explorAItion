<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings - eduSPACE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary text-white font-poppins min-h-screen overflow-x-hidden relative">
    <!-- Background Blobs -->
    <div class="bg-blob blob-1 fixed w-64 h-64 md:w-80 md:h-80 right-10 top-20"></div>
    <div class="bg-blob blob-2 fixed w-64 h-64 md:w-80 md:h-80 left-10 bottom-20"></div>
    <div class="bg-blob blob-3 fixed w-64 h-64 md:w-80 md:h-80 left-10 top-20"></div>

    <!-- Include Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="main-content min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="profile-container w-full max-w-5xl bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30  p-8 md:p-12 relative z-10 shadow-lg shadow-primary/30">

            <!-- Back Button -->
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>

            <!-- Success Messages -->
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Profile Settings</h1>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Picture Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white/5 rounded-xl p-6 text-center">
                        <div class="mb-4">
                            @if($user->avatar)
                                <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture" class="w-32 h-32 rounded-full mx-auto border-4 border-primary object-cover">
                            @else
                                <div id="avatar-preview" class="w-32 h-32 rounded-full mx-auto border-4 border-primary bg-primary flex items-center justify-center text-white text-4xl font-bold">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ $user->nama }}</h3>
                        <p class="text-white/60 text-sm mb-1">{{ $user->email }}</p>
                        <span class="inline-block bg-primary/20 text-primary text-xs px-3 py-1 rounded-full">
                            {{ $user->isGuru() ? 'Teacher' : 'Student' }}
                        </span>
                    </div>
                </div>

                <!-- Forms Section -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Update Profile Form -->
                    <div class="bg-white/5 rounded-xl p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Personal Information
                        </h3>
                        
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="nama" class="block text-sm font-medium mb-2">Full Name</label>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" 
                                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white outline-none focus:border-primary transition-colors" required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white outline-none focus:border-primary transition-colors" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="avatar" class="block text-sm font-medium mb-2">Profile Picture</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*" 
                                       class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80">
                                <p class="text-xs text-white/60 mt-1">Max file size: 2MB. Supported formats: JPG, PNG, GIF.</p>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary hover:bg-primary/80 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                Update Profile
                            </button>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div class="bg-white/5 rounded-xl p-6">
                        <h3 class="text-xl font-semibold mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Change Password
                        </h3>
                        
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="current_password" class="block text-sm font-medium mb-2">Current Password</label>
                                    <input type="password" id="current_password" name="current_password" 
                                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white outline-none focus:border-primary transition-colors" required>
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-medium mb-2">New Password</label>
                                    <input type="password" id="password" name="password" 
                                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white outline-none focus:border-primary transition-colors" required>
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm New Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" 
                                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white outline-none focus:border-primary transition-colors" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-primary hover:bg-primary/80 text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Avatar preview functionality
        document.getElementById('avatar').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('avatar-preview');
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const newPreview = document.createElement('img');
                        newPreview.id = 'avatar-preview';
                        newPreview.src = e.target.result;
                        newPreview.alt = 'Profile Picture';
                        newPreview.className = 'w-32 h-32 rounded-full mx-auto border-4 border-primary object-cover';
                        preview.parentNode.replaceChild(newPreview, preview);
                    }
                }
                
                reader.readAsDataURL(file);
            }
        });
    </script>

    <style>
        .bg-blob {
            border-radius: 50%;
            background: rgba(199.32, 68.73, 239.06, 0.2);
            filter: blur(112.8px);
            z-index: 0;
        }
    </style>
</body>
</html>