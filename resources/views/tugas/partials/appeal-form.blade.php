@if(Auth::user()->isSiswa() && $pengumpulanSaya && $pengumpulanSaya->nilai && !$pengumpulanSaya->hasPendingAppeal())
<div class="bg-white/5 rounded-xl p-6 border border-white/10 mb-6">
    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
        <svg class="w-5 h-5 mr-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        Ajukan Banding Nilai
    </h3>
    
    <form action="{{ route('kelas.tugas.appeal', [$kelas->id_kelas, $tugas->id_tugas]) }}" method="POST">
        @csrf
        <input type="hidden" name="pengumpulan_id" value="{{ $pengumpulanSaya->id_pengumpulan }}">
        
        <div class="mb-4">
            <label for="alasan_banding" class="block text-white/90 mb-2">Alasan Banding</label>
            <textarea id="alasan_banding" name="alasan_banding" rows="4" 
                      class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-yellow-400 resize-none"
                      placeholder="Jelaskan alasan Anda mengajukan banding. Berikan argumen yang jelas mengapa nilai perlu ditinjau ulang..."
                      required></textarea>
            <p class="text-white/60 text-sm mt-1">Maksimal 1000 karakter</p>
        </div>
        
        <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-lg p-4 mb-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-yellow-300 font-medium">Informasi Banding</p>
                    <p class="text-yellow-200/80 text-sm mt-1">
                        • Nilai saat ini: <strong>{{ $pengumpulanSaya->nilai->nilai }}/100</strong><br>
                        • Banding akan ditinjau oleh guru<br>
                        • Hasil review akan dikirim via notifikasi
                    </p>
                </div>
            </div>
        </div>
        
        <button type="submit" 
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Ajukan Banding
        </button>
    </form>
</div>
@endif

@if($pengumpulanSaya && $pengumpulanSaya->hasPendingAppeal())
<div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-6 mb-6">
    <div class="flex items-center">
        <svg class="w-6 h-6 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h4 class="text-blue-300 font-semibold text-lg">Banding Sedang Diproses</h4>
            <p class="text-blue-200/80 text-sm mt-1">Appeal Anda sedang ditinjau oleh guru. Anda akan mendapat notifikasi ketika ada update.</p>
        </div>
    </div>
</div>
@endif