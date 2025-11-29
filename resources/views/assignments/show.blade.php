@extends('layouts.app')

@section('title', $tugas->judul . ' - eduSPACE')

@section('content')
<div class="min-h-screen px-5 py-28 md:py-32">
    <div class="max-w-6xl mx-auto">
        <!-- Navigation -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('kelas.assignments.index') }}" class="inline-flex items-center text-white hover:text-primary transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    All Assignments
                </a>
                <span class="text-white/40">/</span>
                <a href="{{ route('kelas.show', $tugas->kelas->id_kelas) }}" class="text-white hover:text-primary transition-colors">
                    {{ $tugas->kelas->nama_kelas }}
                </a>
            </div>
            
            @if(Auth::user()->isGuru() && $tugas->kelas->id_guru == Auth::id())
            <div class="flex space-x-3">
                <a href="{{ route('kelas.tugas.edit', [$tugas->kelas->id_kelas, $tugas->id_tugas]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                 a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Assignment
                </a>
                <form action="{{ route('kelas.tugas.destroy', [$tugas->kelas->id_kelas, $tugas->id_tugas]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm"
                            onclick="return confirm('Delete this assignment? This action cannot be undone.')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Assignment
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Assignment Header -->
        <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-8 mb-8 shadow-lg shadow-primary/30">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">{{ $tugas->judul }}</h1>
                    <div class="flex items-center flex-wrap gap-4 text-white/60">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Created {{ $tugas->created_at->format('M d, Y') }}
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Class {{ $tugas->kelas->nama_kelas }}
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            By {{ $tugas->kelas->guru->nama }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deadline -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submission Deadline
                </h3>
                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-500/20 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-lg">{{ $tugas->deadline->format('l, F d, Y') }}</p>
                                <p class="text-white/60 text-sm">Due at {{ $tugas->deadline->format('H:i') }}</p>
                                @php
                                    $now = now();
                                    $diff = $tugas->deadline->diff($now);
                                @endphp
                                @if($tugas->deadline < $now)
                                    <p class="text-red-400 text-sm font-medium mt-1">Overdue by {{ $diff->days }} days</p>
                                @else
                                    <p class="text-green-400 text-sm font-medium mt-1">
                                        {{ $diff->days }} days {{ $diff->h }} hours remaining
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Assignment Description
                </h3>
                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    <p class="text-white/80 leading-relaxed text-lg whitespace-pre-line">{{ $tugas->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Student View -->
        @if(Auth::user()->isSiswa())
            @include('tugas.partials.student-view', ['tugas' => $tugas, 'pengumpulanSaya' => $pengumpulanSaya, 'kelas' => $tugas->kelas])
        @endif

        <!-- Comments Section -->
        @include('tugas.partials.comments-section', ['kelas' => $tugas->kelas, 'tugas' => $tugas])
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Global variables
    let currentParentId = null;
    let currentReplyingTo = '';

    // ==================== TOAST NOTIFICATION SYSTEM ====================

    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${getToastClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                ${getToastIcon(type)}
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
        
        // Click to dismiss
        toast.addEventListener('click', () => {
            toast.remove();
        });
    }

    function getToastClass(type) {
        const classes = {
            success: 'bg-green-500 text-white border border-green-400',
            error: 'bg-red-500 text-white border border-red-400',
            warning: 'bg-yellow-500 text-white border border-yellow-400',
            info: 'bg-blue-500 text-white border border-blue-400'
        };
        return classes[type] || classes.info;
    }

    function getToastIcon(type) {
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`
        };
        return icons[type] || icons.info;
    }
</script>
@endpush