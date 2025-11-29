@extends('layouts.app')

@section('title', 'Add Material - eduSPACE')

@section('content')
<div class="min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
    <div class="w-full max-w-md bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
        <a href="{{ route('kelas.show', $kelas->id_kelas) }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Class
        </a>

        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-shadow shadow-white/30">Add Material</h1>
        
        <form method="POST" action="{{ route('kelas.materi.store', $kelas->id_kelas) }}" enctype="multipart/form-data">
            @csrf
            <!-- Form fields sama seperti di modal -->
            <div class="mb-4">
                <label for="judul" class="block text-lg mb-2">Title</label>
                <input type="text" id="judul" name="judul" class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary" placeholder="Enter material title" required>
            </div>
            
            <div class="mb-4">
                <label for="deskripsi" class="block text-lg mb-2">Description</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full bg-transparent border-b border-white py-2 text-white outline-none focus:border-primary resize-none" placeholder="Enter material description" required></textarea>
            </div>
            
            <div class="mb-6">
                <label for="file" class="block text-lg mb-2">File (Optional)</label>
                <input type="file" id="file" name="file" class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80">
                <p class="text-xs text-white/60 mt-1">Supported formats: PDF, DOC, DOCX, PPT, PPTX, TXT. Max: 2MB</p>
            </div>
            
            <button type="submit" class="w-full bg-primary py-3 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all">
                Add Material
            </button>
        </form>
    </div>
</div>
@endsection