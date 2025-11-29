
@extends('layouts.admin')

@section('title', 'Manage Classes - eduSPACE')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Manage Classes</h1>
</div>

<div class="bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-white/10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Class</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Teacher</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Members</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Materials</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Assignments</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($classes as $class)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-white">{{ $class->nama_kelas }}</div>
                        <div class="text-sm text-white/60">Code: {{ $class->kode_kelas }}</div>
                        @if($class->deskripsi)
                        <div class="text-xs text-white/40 mt-1 line-clamp-1">{{ $class->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($class->guru->avatar)
                                <img src="{{ asset('storage/' . $class->guru->avatar) }}" 
                                     alt="Avatar" 
                                     class="w-8 h-8 rounded-full border border-white/20 object-cover mr-3">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold border border-white/20 mr-3">
                                    {{ strtoupper(substr($class->guru->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm text-white">{{ $class->guru->nama }}</div>
                                <div class="text-xs text-white/60">{{ $class->guru->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded-full text-xs">
                            {{ $class->anggota_count }} members
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">
                            {{ $class->materi_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full text-xs">
                            {{ $class->tugas_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white/60">
                        {{ $class->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('admin.classes.delete', $class) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete {{ $class->nama_kelas }}? This will also delete all materials and assignments.')"
                                    class="bg-red-500/20 text-red-400 hover:bg-red-500/30 px-3 py-1 rounded-lg transition-colors border border-red-500/30">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-white/10">
        {{ $classes->links() }}
    </div>
</div>
@endsection
