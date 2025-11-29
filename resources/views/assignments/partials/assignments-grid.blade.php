<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="assignmentsGrid">
    @forelse($assignments as $tugas)
        <a href="{{ route('kelas.assignments.show', $tugas->id_tugas) }}" 
           class="bg-gradient-to-br from-gray-700 to-purple-800 rounded-2xl border border-white/30 p-6 hover:border-primary/50 hover:-translate-y-1 transition-all duration-300 group">
            
            <!-- Class Badge -->
            <div class="mb-3">
                <span class="bg-primary/20 text-primary text-xs px-3 py-1 rounded-full">
                    {{ $tugas->kelas->nama_kelas }}
                </span>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                {{ $tugas->judul }}
            </h3>

            <!-- Description -->
            <p class="text-white/70 text-sm mb-4 line-clamp-3 leading-relaxed">
                {{ $tugas->deskripsi }}
            </p>

            <!-- Deadline -->
            <div class="flex items-center text-red-400 text-sm mb-3">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Due: {{ $tugas->deadline->format('M d, Y H:i') }}
            </div>

            <!-- Status Badge -->
            @php
                $now = now();
                $isOverdue = $tugas->deadline < $now;
                $isDueSoon = $tugas->deadline->diffInHours($now) <= 24 && !$isOverdue;
            @endphp
            
            @if($isOverdue)
                <div class="mb-3">
                    <span class="bg-red-500/20 text-red-400 text-xs px-3 py-1 rounded-full">
                        Overdue
                    </span>
                </div>
            @elseif($isDueSoon)
                <div class="mb-3">
                    <span class="bg-yellow-500/20 text-yellow-400 text-xs px-3 py-1 rounded-full">
                        Due Soon
                    </span>
                </div>
            @else
                <div class="mb-3">
                    <span class="bg-green-500/20 text-green-400 text-xs px-3 py-1 rounded-full">
                        Active
                    </span>
                </div>
            @endif

            <!-- Footer -->
            <div class="flex justify-between items-center text-xs text-white/50">
                <span>{{ $tugas->created_at->format('M d, Y') }}</span>
                <span class="group-hover:text-primary transition-colors">View →</span>
            </div>
        </a>
    @empty
        <!-- Empty State -->
        <div class="col-span-full text-center py-16">
            <div class="bg-white/5 rounded-2xl p-12 border border-white/10">
                <svg class="w-24 h-24 mx-auto text-white/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <h3 class="text-2xl font-bold text-white/60 mb-3">No Assignments Found</h3>
                <p class="text-white/40 mb-6">Try adjusting your search terms.</p>
                <button onclick="resetSearch()" class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                    Show All Assignments
                </button>
            </div>
        </div>
    @endforelse
</div>

@if($assignments->hasPages() && $assignments->count() > 0)
    <div class="mt-8 text-center text-white/60 text-sm">
        Showing {{ $assignments->firstItem() }} - {{ $assignments->lastItem() }} of {{ $assignments->total() }} assignments
    </div>
@endif