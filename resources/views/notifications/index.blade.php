@extends('layouts.app')

@section('title', 'Notifications - eduSPACE')

@section('content')
<div class="min-h-screen px-5 py-28 md:py-32">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Notifications</h1>
                <p class="text-white/60">Your recent notifications and alerts</p>
            </div>
            
            @if($notifications->where('dibaca', false)->count() > 0)
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark all as read
                </button>
            </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
            <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 transition-all hover:border-primary/50 {{ $notification->dibaca ? 'opacity-70' : '' }}">
                <div class="flex items-start space-x-4">
                    <!-- Notification Icon -->
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        @switch($notification->tipe)
                            @case('materi_baru')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @break
                            @case('tugas_baru')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                @break
                            @case('nilai_diberikan')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @break
                            @case('komentar_baru')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                @break
                            @case('siswa_bergabung')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                @break
                            @case('tugas_dikumpulkan')
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @break
                        @endswitch
                    </div>

                    <!-- Notification Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-white font-semibold text-lg">{{ $notification->judul }}</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-white/40 text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                                @if(!$notification->dibaca)
                                <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                                @endif
                            </div>
                        </div>
                        
                        <p class="text-white/70 mb-3">{{ $notification->pesan }}</p>
                        
                        <div class="flex items-center space-x-3">
                            @if($notification->tautan)
                            <a href="{{ $notification->tautan }}" 
                               class="inline-flex items-center px-3 py-1 bg-primary/20 text-primary text-sm rounded-lg hover:bg-primary/30 transition-colors">
                                View
                            </a>
                            @endif
                            
                            @if(!$notification->dibaca)
                            <form action="{{ route('notifications.markAsRead', $notification->id_notifikasi) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-white/40 hover:text-white text-sm transition-colors">
                                    Mark as read
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <svg class="w-24 h-24 mx-auto text-white/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M15 17h5l-5 5v-5zM10.24 8.56a5.97 5.97 0 01-4.66-7.5 1 1 0 00-1.2-1.2 5.97 5.97 0 01-7.5 4.66 1 1 0 00-1.2 1.2 5.97 5.97 0 014.66 7.5 1 1 0 001.2 1.2 5.97 5.97 0 017.5-4.66 1 1 0 001.2-1.2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-white/60 mb-3">No notifications yet</h3>
                <p class="text-white/40">Notifications will appear here when you receive updates.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-8">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection