@extends('layouts.app')

@section('title', 'Edit Assignment - eduSPACE')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="w-full max-w-2xl bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
            <a href="{{ route('kelas.tugas.show', [$kelas->id_kelas, $tugas->id_tugas]) }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Assignment
            </a>

            <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Edit Assignment</h1>
            
            <form method="POST" action="{{ route('kelas.tugas.update', [$kelas->id_kelas, $tugas->id_tugas]) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="judul" class="block text-lg mb-3">Title</label>
                    <input type="text" id="judul" name="judul" 
                           class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" 
                           placeholder="Enter assignment title" 
                           value="{{ old('judul', $tugas->judul) }}" 
                           required>
                    @error('judul')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="deskripsi" class="block text-lg mb-3">Description</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" 
                              class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary resize-none" 
                              placeholder="Enter assignment description" 
                              required>{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="deadline" class="block text-lg mb-3">Deadline</label>
                    <input type="datetime-local" id="deadline" name="deadline" 
                           class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" 
                           value="{{ old('deadline', $tugas->deadline->format('Y-m-d\\TH:i')) }}" 
                           required>
                    @error('deadline')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-primary py-3 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all">
                        Update Assignment
                    </button>
                    <a href="{{ route('kelas.tugas.show', [$kelas->id_kelas, $tugas->id_tugas]) }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 py-3 rounded-full text-white text-lg font-medium text-center shadow-lg shadow-gray-500/30 hover:shadow-gray-500/50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection