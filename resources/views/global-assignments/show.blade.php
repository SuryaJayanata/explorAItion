@extends('layouts.app')

@section('title', $tugas->judul . ' - eduSPACE')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-start px-5 py-28 md:py-32">
    <div class="w-full max-w-4xl mx-auto">
        
        <!-- Back Button -->
        <a href="{{ route('kelas.assignments.index') }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to All Assignments
        </a>

        <!-- Assignment Detail Container -->
        <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 md:p-8 shadow-lg shadow-primary/30 mb-8">
            
            <!-- Assignment Header -->
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $tugas->judul }}</h1>
                <div class="flex flex-wrap items-center gap-4">
                    <span class="bg-primary/20 text-primary text-sm px-3 py-1 rounded-full">
                        {{ $tugas->kelas->nama_kelas }}
                    </span>
                    <span class="bg-red-500/20 text-red-400 text-sm px-3 py-1 rounded-full">
                        Due: {{ $tugas->deadline->format('M d, Y H:i') }}
                    </span>
                </div>
            </div>

            <!-- Assignment Description -->
            <div class="bg-white/5 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Description</h3>
                <p class="text-white/80 leading-relaxed">{{ $tugas->deskripsi }}</p>
            </div>

            <!-- Student Submission Section -->
            @if(Auth::user()->isSiswa())
            <div class="bg-white/5 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">My Submission</h3>
                
                @php
                    $pengumpulanSaya = $tugas->pengumpulan->where('id_user', Auth::id())->first();
                    $nilaiSaya = $pengumpulanSaya ? $pengumpulanSaya->nilai : null;
                @endphp

                @if($pengumpulanSaya)
                    <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-green-300 font-semibold">Submitted</p>
                                <p class="text-green-400 text-sm">On: {{ $pengumpulanSaya->created_at->format('M d, Y H:i') }}</p>
                                @if($nilaiSaya)
                                    <p class="text-yellow-300 text-sm mt-1">Grade: {{ $nilaiSaya->nilai }}</p>
                                    @if($nilaiSaya->komentar_guru)
                                        <p class="text-white/80 text-sm mt-1">Teacher's Comment: {{ $nilaiSaya->komentar_guru }}</p>
                                    @endif
                                @else
                                    <p class="text-yellow-400 text-sm mt-1">Not graded yet</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ asset('storage/' . $pengumpulanSaya->file_jawaban) }}" 
                                   target="_blank" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                    View File
                                </a>
                                <a href="{{ route('kelas.tugas.pengumpulan.edit', [$tugas->kelas->id_kelas, $tugas->id_tugas, $pengumpulanSaya->id_pengumpulan]) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-white/70 mb-4">You haven't submitted this assignment yet</p>
                        <a href="{{ route('kelas.tugas.pengumpulan.create', [$tugas->kelas->id_kelas, $tugas->id_tugas]) }}" 
                           class="bg-primary hover:bg-purple-600 text-white px-6 py-3 rounded-lg transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Submit Assignment
                        </a>
                    </div>
                @endif
            </div>
            @endif

            <!-- Comments Section -->
            <div class="bg-white/5 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Comments</h3>
                
                @if($tugas->komentar->count() > 0)
                    <div class="space-y-4">
                        @foreach($tugas->komentar as $komentar)
                            <div class="bg-white/5 rounded-lg p-4">
                                <div class="flex items-start mb-2">
                                    @if($komentar->user->avatar)
                                        <img src="{{ asset('storage/' . $komentar->user->avatar) }}" 
                                             alt="Avatar" 
                                             class="w-8 h-8 rounded-full mr-3">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ strtoupper(substr($komentar->user->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $komentar->user->nama }}</p>
                                        <p class="text-white/60 text-xs">{{ $komentar->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-white/80">{{ $komentar->isi }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-white/60 text-center py-4">No comments yet.</p>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center">
            <a href="{{ route('kelas.show', $tugas->kelas->id_kelas) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Go to Class
            </a>
        </div>
    </div>
</div>
@endsection