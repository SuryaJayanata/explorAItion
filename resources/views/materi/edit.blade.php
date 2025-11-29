@extends('layouts.app')

@section('title', 'Edit Material - eduSPACE')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
        <div class="w-full max-w-2xl bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
            <a href="{{ route('kelas.materi.show', [$kelas->id_kelas, $materi->id_materi]) }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Material
            </a>

            <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Edit Material</h1>
            
            <form method="POST" action="{{ route('kelas.materi.update', [$kelas->id_kelas, $materi->id_materi]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="judul" class="block text-lg mb-3">Title</label>
                    <input type="text" id="judul" name="judul" 
                           class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" 
                           placeholder="Enter material title" 
                           value="{{ old('judul', $materi->judul) }}" 
                           required>
                    @error('judul')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="deskripsi" class="block text-lg mb-3">Description</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" 
                              class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary resize-none" 
                              placeholder="Enter material description" 
                              required>{{ old('deskripsi', $materi->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="file" class="block text-lg mb-3">File (Optional)</label>
                    
                    @if($materi->file)
                        <div class="bg-white/5 rounded-lg p-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-white font-medium">Current file: {{ basename($materi->file) }}</p>
                                        <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm">Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <input type="file" id="file" name="file" 
                           class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80">
                    <p class="text-xs text-white/60 mt-1">Leave empty to keep current file. Supported formats: PDF, DOC, DOCX, PPT, PPTX, TXT. Max: 2MB</p>
                    @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 bg-primary py-3 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all">
                        Update Material
                    </button>
                    <a href="{{ route('kelas.materi.show', [$kelas->id_kelas, $materi->id_materi]) }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 py-3 rounded-full text-white text-lg font-medium text-center shadow-lg shadow-gray-500/30 hover:shadow-gray-500/50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection