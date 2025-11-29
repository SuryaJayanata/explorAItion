@extends('layouts.app')

@section('title', 'Kumpulkan Tugas - ' . $tugas->judul)

@section('content')
<div class="min-h-screen flex items-center justify-center px-5 py-28 md:py-32">
    <div class="w-full max-w-2xl bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
        
        <a href="{{ route('kelas.tugas.show', [$kelas->id_kelas, $tugas->id_tugas]) }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Tugas
        </a>

        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-center text-shadow shadow-white/30">
            Kumpulkan Tugas
        </h1>
        <p class="text-white/70 text-center mb-8">{{ $tugas->judul }}</p>

        @if($pengumpulan)
            <div class="bg-yellow-500/20 border border-yellow-500/30 text-yellow-300 px-4 py-3 rounded-lg mb-6">
                Anda sudah mengumpulkan tugas ini pada {{ $pengumpulan->created_at->format('d M Y H:i') }}.
                <a href="{{ route('kelas.tugas.pengumpulan.edit', [$kelas->id_kelas, $tugas->id_tugas, $pengumpulan->id_pengumpulan]) }}" 
                   class="underline ml-2">Edit pengumpulan</a>
            </div>
        @endif

        <div class="bg-white/5 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Detail Tugas</h3>
            <p class="text-white/80 mb-4">{{ $tugas->deskripsi }}</p>
            <div class="flex items-center text-red-400 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Deadline: {{ $tugas->deadline->format('d M Y H:i') }}
            </div>
        </div>

        <form action="{{ route('kelas.tugas.pengumpulan.store', [$kelas->id_kelas, $tugas->id_tugas]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label for="file_jawaban" class="block text-lg mb-3 text-white/90">File Jawaban</label>
                <input type="file" id="file_jawaban" name="file_jawaban" 
                       class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-purple-600 transition-colors"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar,.jpg,.jpeg,.png"
                       required>
                @error('file_jawaban')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-white/60 text-sm mt-2">
                    Format yang didukung: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR, JPG, JPEG, PNG (Max: 10MB)
                </p>
            </div>

            <button type="submit" 
                    class="w-full bg-primary py-4 rounded-full text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all flex items-center justify-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Kumpulkan Tugas
            </button>
        </form>
    </div>
</div>
@endsection