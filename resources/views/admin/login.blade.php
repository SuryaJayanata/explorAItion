<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - eduSPACE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-secondary text-white font-poppins min-h-screen overflow-x-hidden relative">
    <div class="bg-blob blob-1 fixed w-64 h-64 md:w-80 md:h-80 right-10 top-20"></div>
    <div class="bg-blob blob-2 fixed w-64 h-64 md:w-80 md:h-80 left-10 bottom-20"></div>
    <div class="bg-blob blob-3 fixed w-64 h-64 md:w-80 md:h-80 left-10 top-20"></div>

    <main class="main-content min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="login-container w-full max-w-md bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
            <div class="text-center mb-6">
                <h1 class="text-2xl md:text-3xl font-tilt-warp text-shadow-lg shadow-white/30">
                    edu<span class="text-primary">SPACE.</span>
                </h1>
                <p class="text-white/60 mt-2">Admin Panel</p>
            </div>
            
            <h1 class="login-title text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Admin Login</h1>
            
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-group mb-6">
                    <label for="username" class="block text-lg mb-3">Username</label>
                    <input type="text" id="username" name="username" class="form-input w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" value="admin" required>
                </div>
                
                <div class="form-group mb-6">
                    <label for="password" class="block text-lg mb-3">Password</label>
                    <input type="password" id="password" name="password" class="form-input w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" value="admin123" required>
                </div>
                
                <button type="submit" class="login-button w-full bg-primary py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all">
                    Login as Admin
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('landing') }}" class="text-primary hover:underline">Back to main site</a>
            </div>
        </div>
    </main>
</body>
</html>