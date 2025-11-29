<div class="comment bg-white/5 rounded-lg p-4 border border-white/10 {{ $level > 0 ? 'ml-6' : '' }}" 
     id="comment-{{ $komentar->id_komentar }}">
    
    <!-- Comment Header -->
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center space-x-2">
            @if($komentar->user->avatar)
                <img src="{{ asset('storage/' . $komentar->user->avatar) }}" 
                     class="w-8 h-8 rounded-full border border-white/20">
            @else
                <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                    {{ strtoupper(substr($komentar->user->nama, 0, 1)) }}
                </div>
            @endif
            <div>
                <div class="flex items-center space-x-2">
                    <span class="text-white font-medium text-sm">{{ $komentar->user->nama }}</span>
                    @if($komentar->user->id_user == $materi->kelas->id_guru)
                        <span class="bg-primary/20 text-primary text-xs px-2 py-0.5 rounded">Guru</span>
                    @endif
                </div>
                <span class="text-white/50 text-xs">{{ $komentar->created_at->format('d M Y H:i') }}</span>
            </div>
        </div>
        
        @if($komentar->id_user == Auth::id() || Auth::user()->isGuru())
        <form action="{{ route('kelas.materi.komentar.destroy', [$materi->kelas->id_kelas, $materi->id_materi, $komentar->id_komentar]) }}" 
              method="POST" class="delete-comment-form">
            @csrf @method('DELETE')
            <button type="submit" class="text-white/40 hover:text-red-400 text-sm">Hapus</button>
        </form>
        @endif
    </div>

    <!-- Comment Content -->
    <div class="text-white/80 text-sm mb-3">
        @if($komentar->parent_id)
            <div class="text-primary text-xs mb-1">
                ↳ Replies for {{ $komentar->parent->user->nama }}
            </div>
        @endif
        {{ $komentar->isi }}
    </div>

    <!-- Comment Actions -->
    <div class="flex items-center space-x-4 text-white/60 text-xs">
        <button onclick="replyToComment({{ $komentar->id_komentar }}, '{{ $komentar->user->nama }}')" 
                class="hover:text-primary transition-colors">
            Reply
        </button>

        @if($komentar->hasReplies())
        <button onclick="toggleReplies({{ $komentar->id_komentar }})" 
                class="hover:text-white transition-colors">
            <span class="replies-count">{{ $komentar->replies->count() }} replies</span>
        </button>
        @endif
    </div>

    <!-- Replies Container -->
    @if($komentar->hasReplies())
    <div id="replies-{{ $komentar->id_komentar }}" class="mt-3 space-y-3 border-t border-white/10 pt-3 hidden">
        @foreach($komentar->replies as $reply)
            @include('materi.partials.comment', ['komentar' => $reply, 'level' => $level + 1])
        @endforeach
    </div>
    @endif
</div>