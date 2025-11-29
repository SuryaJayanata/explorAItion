<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="materialsGrid">
    @forelse($materials as $materi)
        <a href="{{ route('kelas.materials.show', $materi->id_materi) }}" 
           class="bg-gradient-to-br from-gray-700 to-purple-800 rounded-2xl border border-white/30 p-6 hover:border-primary/50 hover:-translate-y-1 transition-all duration-300 group">
            
            <!-- Class Badge -->
            <div class="mb-3">
                <span class="bg-primary/20 text-primary text-xs px-3 py-1 rounded-full">
                    {{ $materi->kelas->nama_kelas }}
                </span>
            </div>

            <!-- Title -->
            <h3 class="text-xl font-bold text-white mb-3 line-clamp-2 group-hover:text-primary transition-colors">
                {{ $materi->judul }}
            </h3>

            <!-- Description -->
            <p class="text-white/70 text-sm mb-4 line-clamp-3 leading-relaxed">
                {{ $materi->deskripsi }}
            </p>

            <!-- File Indicator -->
            @if($materi->file)
                <div class="flex items-center text-blue-400 text-sm mb-3">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    File Attached
                </div>
            @endif

            <!-- Footer -->
            <div class="flex justify-between items-center text-xs text-white/50">
                <span>{{ $materi->created_at->format('M d, Y') }}</span>
                <span class="group-hover:text-primary transition-colors">View →</span>
            </div>
        </a>
    @empty
        <!-- Empty State -->
        <div class="col-span-full text-center py-16">
            <div class="bg-white/5 rounded-2xl p-12 border border-white/10">
                <svg class="w-24 h-24 mx-auto text-white/30 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-white/60 mb-3">No Materials Found</h3>
                <p class="text-white/40 mb-6">Try adjusting your search terms.</p>
                <button onclick="resetSearch()" class="bg-primary hover:bg-primary/80 text-white px-8 py-3 rounded-xl transition-colors inline-flex items-center">
                    Show All Materials
                </button>
            </div>
        </div>
    @endforelse
</div>

@if($materials->hasPages() && $materials->count() > 0)
    <div class="mt-8 text-center text-white/60 text-sm">
        Showing {{ $materials->firstItem() }} - {{ $materials->lastItem() }} of {{ $materials->total() }} materials
    </div>
@endif