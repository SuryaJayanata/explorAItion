
@extends('layouts.admin')

@section('title', 'Manage Users - eduSPACE')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Manage Users</h1>
</div>

<div class="bg-white/5 rounded-2xl border border-white/10 backdrop-blur-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-white/10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Classes Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Classes Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($users as $user)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" 
                                     alt="Avatar" 
                                     class="w-10 h-10 rounded-full border-2 border-white/20 object-cover">
                            @else
                                <div class="flex-shrink-0 w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold border-2 border-white/20">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div class="ml-4">
                                <div class="text-sm font-medium text-white">{{ $user->nama }}</div>
                                <div class="text-sm text-white/60">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->role == 'guru' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                               ($user->role == 'siswa' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 
                               'bg-purple-500/20 text-purple-400 border border-purple-500/30') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                        <span class="bg-orange-500/20 text-orange-400 px-2 py-1 rounded-full text-xs">
                            {{ $user->kelas_diajar_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white text-center">
                        <span class="bg-indigo-500/20 text-indigo-400 px-2 py-1 rounded-full text-xs">
                            {{ $user->anggota_kelas_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white/60">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if(!$user->isAdmin())
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete {{ $user->nama }}? This action cannot be undone.')"
                                    class="bg-red-500/20 text-red-400 hover:bg-red-500/30 px-3 py-1 rounded-lg transition-colors border border-red-500/30">
                                Delete
                            </button>
                        </form>
                        @else
                        <span class="text-white/40 text-xs bg-gray-500/20 px-2 py-1 rounded border border-gray-500/30">System Admin</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-white/10">
        {{ $users->links() }}
    </div>
</div>
@endsection
