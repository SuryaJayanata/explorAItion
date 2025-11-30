@if($pengumpulanSaya && $pengumpulanSaya->nilai && $pengumpulanSaya->nilai->auto_graded)
<div class="bg-gradient-to-br from-blue-900 to-purple-900 rounded-2xl border border-blue-500/30 p-6 mb-6 shadow-lg shadow-blue-500/30">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            AI Grading Result
        </h3>
        <span class="bg-blue-500/20 text-blue-400 text-sm px-3 py-1 rounded-full font-medium">
            Auto Graded
        </span>
    </div>

    <!-- Nilai -->
    <div class="bg-white/5 rounded-xl p-6 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-white font-semibold text-lg">Final Score</h4>
            <div class="text-white font-bold text-2xl {{ $pengumpulanSaya->nilai->nilai >= $tugas->passing_grade ? 'text-green-400' : 'text-red-400' }}">
                {{ $pengumpulanSaya->nilai->nilai }}/100
                @if($pengumpulanSaya->nilai->nilai >= $tugas->passing_grade)
                    <span class="text-green-400 text-sm font-normal">✓ Lulus</span>
                @else
                    <span class="text-red-400 text-sm font-normal">✗ Tidak Lulus</span>
                @endif
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-2">
            <div class="flex justify-between text-white/80 text-sm mb-2">
                <span>Progress</span>
                <span>{{ $pengumpulanSaya->nilai->nilai }}%</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-3 shadow-inner overflow-hidden">
                <div class="h-3 rounded-full transition-all duration-500 ease-out"
                     style="width: {{ $pengumpulanSaya->nilai->nilai }}%;
                            background: linear-gradient(to right, #3b82f6, #8b5cf6, #a855f7, #c084fc);
                            box-shadow: 0 0 6px rgba(59, 130, 246, 0.25);">
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Umum -->
    <div class="bg-white/5 rounded-xl p-6 mb-4">
        <h4 class="text-white font-semibold mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            AI Feedback
        </h4>
        <p class="text-white/80 leading-relaxed">{{ $pengumpulanSaya->nilai->komentar_guru }}</p>
    </div>

    <!-- Detail Analisis Per Soal -->
    @php
        $analisisData = json_decode($pengumpulanSaya->nilai->analisis_detail ?? '[]', true);
    @endphp
    @if(!empty($analisisData) && is_array($analisisData))
    <div class="bg-white/5 rounded-xl p-6 mb-4">
        <h4 class="text-white font-semibold mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Detailed Question Analysis
        </h4>
        
        <div class="space-y-4">
            @foreach($analisisData as $index => $analisis)
            <div class="border-l-4 {{ $analisis['kebenaran'] == 'benar' ? 'border-green-500 bg-green-500/10' : 'border-red-500 bg-red-500/10' }} pl-4 py-3">
                <div class="flex justify-between items-start mb-2">
                    <h5 class="text-white font-medium">{{ $analisis['soal'] ?? 'Question ' . ($index + 1) }}</h5>
                    <span class="text-xs px-2 py-1 rounded-full {{ $analisis['kebenaran'] == 'benar' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $analisis['kebenaran'] == 'benar' ? 'Correct' : 'Incorrect' }} ({{ $analisis['skor'] ?? 0 }} pts)
                    </span>
                </div>
                
                @if(isset($analisis['jawaban_siswa']))
                <div class="mb-2">
                    <p class="text-white/60 text-sm">Your Answer:</p>
                    <p class="text-white/80 text-sm">{{ $analisis['jawaban_siswa'] }}</p>
                </div>
                @endif
                
                @if(isset($analisis['feedback']))
                <div>
                    <p class="text-white/60 text-sm">Feedback:</p>
                    <p class="text-white/80 text-sm">{{ $analisis['feedback'] }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Banding Button -->
    <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <p class="text-yellow-300 font-medium">Found an error in grading?</p>
                    <p class="text-yellow-400 text-sm">You can submit an appeal to your teacher</p>
                </div>
            </div>
            <button onclick="openAppealModal()" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                Submit Appeal
            </button>
        </div>
    </div>
</div>

<!-- Appeal Modal -->
<div id="appealModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-md mx-4 relative">
        <button onclick="closeAppealModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h2 class="text-2xl font-bold mb-6 text-center text-white">Submit Grade Appeal</h2>
        
        <form id="appealForm" method="POST" action="{{ route('kelas.tugas.appeal', [$kelas->id_kelas, $tugas->id_tugas]) }}">
            @csrf
            <input type="hidden" name="pengumpulan_id" value="{{ $pengumpulanSaya->id_pengumpulan }}">
            
            <div class="mb-6">
                <label for="alasan_banding" class="block text-lg mb-3 text-white/90">Reason for Appeal</label>
                <textarea id="alasan_banding" name="alasan_banding" rows="4"
                          class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary resize-none"
                          placeholder="Explain why you think the AI grading is incorrect..." required></textarea>
                <p class="text-white/60 text-xs mt-1">Be specific about which questions you think were graded incorrectly and why.</p>
            </div>
            
            <button type="submit" 
                    class="w-full bg-yellow-500 hover:bg-yellow-600 py-3 rounded-lg text-white text-lg font-medium transition-all">
                Submit Appeal
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAppealModal() {
        document.getElementById('appealModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAppealModal() {
        document.getElementById('appealModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('appealModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAppealModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAppealModal();
        }
    });
</script>
@endpush
@endif