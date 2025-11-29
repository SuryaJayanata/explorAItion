@extends('layouts.admin')

@section('title', 'Admin Dashboard - eduSPACE')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Dashboard</h1>
    <p class="text-white/60">Overview of your platform</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm">Total Users</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_users'] }}</h3>
            </div>
            <div class="bg-blue-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-200 text-sm">Teachers</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_teachers'] }}</h3>
            </div>
            <div class="bg-green-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-200 text-sm">Students</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_students'] }}</h3>
            </div>
            <div class="bg-purple-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-200 text-sm">Classes</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_classes'] }}</h3>
            </div>
            <div class="bg-orange-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-200 text-sm">Materials</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_materials'] }}</h3>
            </div>
            <div class="bg-red-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-200 text-sm">Assignments</p>
                <h3 class="text-3xl font-bold">{{ $stats['total_assignments'] }}</h3>
            </div>
            <div class="bg-indigo-400/20 p-3 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Di bagian Recent Users, update kode ini: -->
<div class="bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm p-6">
    <h3 class="text-xl font-bold text-white mb-4">Recent Users</h3>
    <div class="space-y-4">
        @foreach($recentUsers as $user)
        <div class="flex items-center justify-between p-3 border border-white/10 rounded-lg bg-white/5">
            <div class="flex items-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                         alt="Avatar" 
                         class="w-10 h-10 rounded-full border border-white/20 object-cover mr-3">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold border border-white/20 mr-3">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-semibold text-white">{{ $user->nama }}</h4>
                    <p class="text-sm text-white/60">{{ $user->email }}</p>
                    <span class="text-xs px-2 py-1 rounded-full 
                        {{ $user->role == 'guru' ? 'bg-green-500/20 text-green-400' : 
                           ($user->role == 'siswa' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            <span class="text-sm text-white/50">{{ $user->created_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
</div>

<!-- Di bagian Recent Classes, update kode ini: -->
<div class="bg-white/5 rounded-2xl border border-white/10 p-6">
    <h3 class="text-xl font-bold text-white mb-4">Recent Classes</h3>
    <div class="space-y-4">
        @foreach($recentClasses as $class)
        <div class="flex items-center justify-between p-3 border border-white/10 rounded-lg bg-white/5">
            <div class="flex items-center">
                @if($class->guru->avatar)
                    <img src="{{ asset('storage/' . $class->guru->avatar) }}" 
                         alt="Avatar" 
                         class="w-10 h-10 rounded-full border border-white/20 object-cover mr-3">
                @else
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold border border-white/20 mr-3">
                        {{ strtoupper(substr($class->guru->nama, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="font-semibold text-white">{{ $class->nama_kelas }}</h4>
                    <p class="text-sm text-white/60">By {{ $class->guru->nama }}</p>
                    <span class="text-xs text-white/50">Code: {{ $class->kode_kelas }}</span>
                </div>
            </div>
            <span class="text-sm text-white/50">{{ $class->created_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection