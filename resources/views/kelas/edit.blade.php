@extends('layouts.app')

@section('title', 'Edit ' . $kelas->nama_kelas . ' - eduSPACE')

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
</style>
@endpush

@section('content')
    <div class="min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="edit-class-container w-full max-w-md bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
            <a href="{{ route('kelas.show', $kelas->id_kelas) }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Class
            </a>

            <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Edit Class</h1>
            
            <form method="POST" action="{{ route('kelas.update', $kelas->id_kelas) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-6">
                    <label for="nama_kelas" class="block text-lg mb-3">Class Name</label>
                    <div class="input-with-icon relative">
                        <input type="text" id="nama_kelas" name="nama_kelas" class="form-input w-full bg-transparent border-b border-white py-2 pl-10 pr-4 text-white outline-none focus:border-primary" placeholder="Enter class name" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required autofocus>
                        
                    </div>
                    @error('nama_kelas')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="deskripsi" class="block text-lg mb-3">Description (Optional)</label>
                    <div class="input-with-icon relative">
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="form-input w-full bg-transparent border-b border-white py-2 pl-10 pr-4 text-white outline-none focus:border-primary resize-none" placeholder="Enter class description">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
                        
                    </div>
                    @error('deskripsi')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="info-box bg-primary/10 border border-primary/20 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <!-- Information Circle Icon -->
                        <svg class="w-5 h-5 text-primary mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-white/80">Class code: <strong>{{ $kelas->kode_kelas }}</strong></p>
                            <p class="text-xs text-white/60 mt-1">Class code cannot be changed. Share this code with students to allow them to join your class.</p>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
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
                
                <div class="flex gap-4">
                    <button type="submit" class="update-button flex-1 bg-primary py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all flex items-center justify-center">
                        <!-- Check Icon -->
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Class
                    </button>
                    
                    <a href="{{ route('kelas.show', $kelas->id_kelas) }}" class="cancel-button flex-1 bg-gray-500 py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-gray-500/30 hover:shadow-gray-500/50 hover:-translate-y-1 transition-all flex items-center justify-center">
                        <!-- X Icon -->
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection