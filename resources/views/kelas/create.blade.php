@extends('layouts.app')

@section('title', 'eduSPACE - Create Class')

@push('styles')
<style>
    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        border-color: #BD18EF;
        box-shadow: 0 1px 0 0 #BD18EF;
    }

    .info-box {
        transition: all 0.3s ease;
    }

    .info-box:hover {
        background: rgba(189, 24, 239, 0.15);
        border-color: rgba(189, 24, 239, 0.3);
    }

    /* perbaikan posisi ikon di kanan */
    .input-with-icon svg {
        right: 0.75rem;
        left: auto;
        top: 50%;
        transform: translateY(-50%);
    }

    /* supaya form lebih naik */
    .create-class-container {
        margin-top: -40px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center px-5 py-14 md:py-16">
    <div class="create-class-container w-full max-w-md bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
        
        <!-- Tombol kembali -->
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Dashboard
        </a>

        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">
            Create New Class
        </h1>
        
        <!-- Form -->
        <form method="POST" action="{{ route('kelas.store') }}">
            @csrf
            
            <!-- Nama kelas -->
            <div class="form-group mb-6">
                <label for="nama_kelas" class="block text-lg mb-3 text-white/90">Class Name</label>
                <div class="input-with-icon relative">
                    <input type="text" id="nama_kelas" name="nama_kelas" 
                           class="form-input w-full bg-transparent border-b border-white py-2 pr-10 pl-3 text-white outline-none focus:border-primary" 
                           placeholder="Enter class name" value="{{ old('nama_kelas') }}" required autofocus>
                    <svg class="absolute w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13
                                 C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253
                                 m0-13C13.168 5.477 14.754 5 16.5 5
                                 c1.746 0 3.332.477 4.5 1.253v13
                                 C19.832 18.477 18.246 18 16.5 18
                                 c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                @error('nama_kelas')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group mb-6">
                <label for="deskripsi" class="block text-lg mb-3 text-white/90">Description (Optional)</label>
                <div class="input-with-icon relative">
                    <textarea id="deskripsi" name="deskripsi" rows="3" 
                              class="form-input w-full bg-transparent border-b border-white py-2 pr-10 pl-3 text-white outline-none focus:border-primary resize-none" 
                              placeholder="Enter class description">{{ old('deskripsi') }}</textarea>
                    <svg class="absolute w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 5H7
                                 a2 2 0 01-2-2V5a2 2 0 012-2
                                 h5.586a1 1 0 01.707.293
                                 l5.414 5.414a1 1 0 01.293.707
                                 V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                @error('deskripsi')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info box -->
            <div class="info-box bg-primary/10 border border-primary/20 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-primary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M13 16h-1v-4h-1m1-4h.01
                                 M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm text-white/80">
                            A unique class code will be automatically generated after creation.
                        </p>
                        <p class="text-xs text-white/60 mt-1">
                            Share this code with students to allow them to join your class.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Tombol submit -->
            <button type="submit" 
                    class="create-button w-full bg-primary py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all flex items-center justify-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v16m8-8H4"/>
                </svg>
                Create Class
            </button>
        </form>
    </div>
</div>
@endsection
