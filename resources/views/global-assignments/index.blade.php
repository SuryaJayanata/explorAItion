@extends('layouts.app')

@section('title', 'All Assignments - eduSPACE')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-start px-5 py-28 md:py-32">
    <div class="w-full max-w-6xl mx-auto">
        
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center">All Assignments</h1>

        @if($assignments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignments as $assignment)
                    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 hover:shadow-lg hover:shadow-primary/30 transition-all">
                        <h3 class="text-xl font-semibold mb-2 line-clamp-2">{{ $assignment->judul }}</h3>
                        <p class="text-white/70 text-sm mb-3 line-clamp-2">{{ $assignment->deskripsi }}</p>
                        
                        <div class="flex items-center text-red-400 text-sm mb-2">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Due: {{ $assignment->deadline->format('M d, Y H:i') }}
                        </div>
                        
                        <div class="flex items-center text-blue-400 text-sm mb-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $assignment->kelas->nama_kelas }}
                        </div>
                        
                        <a href="{{ route('kelas.assignments.show', $assignment->id_tugas) }}" 
                           class="block w-full bg-primary hover:bg-purple-600 text-white text-center py-2 rounded-lg transition-colors">
                            View Assignment
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $assignments->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-white/70">No assignments found.</p>
            </div>
        @endif
    </div>
</div>
@endsection