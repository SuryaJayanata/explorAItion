<div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 mb-8 shadow-lg shadow-primary/30">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
        <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        My Assignment Submission
    </h2>
    
    @php
        $isDeadlinePassed = now()->greaterThan($tugas->deadline);
        $isGraded = $pengumpulanSaya && $pengumpulanSaya->nilai;
        $canUpdate = !$isGraded && !$isDeadlinePassed;
    @endphp

    @if($pengumpulanSaya)
        <!-- Submission Status -->
        <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-green-300 font-semibold text-lg">✓ Assignment Submitted</p>
                    <p class="text-green-400 text-sm mt-1">Submitted on: {{ $pengumpulanSaya->created_at->format('d M Y H:i') }}</p>
                    @if($pengumpulanSaya->created_at > $tugas->deadline)
                        <p class="text-yellow-400 text-sm mt-1">⚠️ Submitted late</p>
                    @endif
                </div>
                <a href="{{ asset('storage/' . $pengumpulanSaya->file_jawaban) }}" 
                   target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Submitted File
                </a>
            </div>
        </div>

        <!-- Grade Section -->
        @if($isGraded)
        <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-white font-semibold text-lg flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Assignment Graded
                </h4>
                <div class="text-white font-bold text-xl">
                    {{ $pengumpulanSaya->nilai->nilai }}/100
                </div>
            </div>
            
<!-- Grade Meter -->
<div class="mb-4">
    <div class="flex justify-between text-white/80 text-sm mb-2">
        <span>Progress</span>
        <span>{{ $pengumpulanSaya->nilai->nilai }}%</span>
    </div>

    <div class="w-full bg-white/10 rounded-full h-4 shadow-inner overflow-hidden">
        <div class="h-4 rounded-full transition-all duration-500 ease-out"
             style="
                width: {{ $pengumpulanSaya->nilai->nilai }}%;
                background: linear-gradient(to right, #72297bff, #91289fff, #9b2699ff, #b538b2ff);
                box-shadow: 0 0 6px rgba(34, 197, 94, 0.25);
             ">
        </div>
    </div>
</div>



            <!-- Grade Indicators -->


            <!-- Teacher's Comments -->
            @if($pengumpulanSaya->nilai->komentar_guru)
            <div class="mt-4 p-4 bg-white/5 rounded-lg border border-white/10">
                <h5 class="text-white font-semibold mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Teacher's Feedback
                </h5>
                <p class="text-white/80 text-sm leading-relaxed">{{ $pengumpulanSaya->nilai->komentar_guru }}</p>
            </div>
            @endif

            <!-- Status Info -->
            <div class="mt-4 p-3 bg-yellow-500/20 border border-yellow-500/30 rounded-lg">
                <p class="text-yellow-300 text-sm flex items-center">
                    <svg class="w-4 h-10 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    This assignment has been graded. You can no longer update your submission.
                </p>
            </div>
        </div>
        @else
        <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-yellow-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-yellow-300 font-semibold text-lg">Waiting for Evaluation</p>
                    <p class="text-yellow-400 text-sm">Your submission is being reviewed by the teacher</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Update Submission (Only show if not graded and before deadline) -->
        @if($canUpdate)
        <div class="mt-6 p-6 bg-white/5 rounded-xl border border-white/10">
            <h4 class="text-white font-semibold text-lg mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Update Submission
            </h4>
            <form action="{{ route('kelas.tugas.pengumpulan.update', [$kelas->id_kelas, $tugas->id_tugas, $pengumpulanSaya->id_pengumpulan]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-4">
                @csrf @method('PUT')
                
                <div>
                    <label for="file_jawaban" class="block text-white text-sm font-medium mb-2">
                        Upload New File
                    </label>
                    <input type="file" 
                           id="file_jawaban" 
                           name="file_jawaban" 
                           class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80"
                           required
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar,.jpg,.jpeg,.png">
                    <p class="text-white/60 text-xs mt-2">
                        Supported formats: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR, JPG, JPEG, PNG. Max: 10MB
                    </p>
                </div>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-yellow-500/25">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Update Submission
                </button>
            </form>
        </div>
        @elseif(!$canUpdate && !$isGraded)
        <div class="mt-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
            <p class="text-red-300 text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                The submission deadline has passed. You can no longer update your submission.
            </p>
        </div>
        @endif
    @else
        <!-- New Submission -->
        @if(!$isDeadlinePassed)
        <div class="bg-white/5 rounded-xl p-6 border border-white/10">
            <h4 class="text-white font-semibold text-lg mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Submit Assignment
            </h4>
            <form action="{{ route('kelas.tugas.pengumpulan.store', [$kelas->id_kelas, $tugas->id_tugas]) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-4">
                @csrf
                
                <div>
                    <label for="file_jawaban" class="block text-white text-sm font-medium mb-2">
                        Upload Your Work
                    </label>
                    <input type="file" 
                           id="file_jawaban" 
                           name="file_jawaban" 
                           class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80"
                           required
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.zip,.rar,.jpg,.jpeg,.png">
                    <p class="text-white/60 text-xs mt-2">
                        Supported formats: PDF, DOC, DOCX, PPT, PPTX, TXT, ZIP, RAR, JPG, JPEG, PNG. Max: 10MB
                    </p>
                </div>
                
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-primary hover:bg-purple-600 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-primary/25">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Submit Assignment
                </button>
            </form>
        </div>
        @else
        <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-red-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-red-300 font-semibold text-lg">Submission Closed</p>
                    <p class="text-red-400 text-sm">The submission deadline has passed</p>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Deadline Information -->
    <div class="mt-6 p-4 bg-white/5 rounded-lg border border-white/10">
        <div class="flex items-center justify-between text-sm">
            <div class="text-white/60">
                <span class="font-semibold">Deadline:</span> 
                {{ $tugas->deadline->format('d M Y H:i') }}
            </div>
            <div class="{{ $isDeadlinePassed ? 'text-red-400' : 'text-green-400' }} font-medium">
                {{ $isDeadlinePassed ? 'Deadline Passed' : 'Active' }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Animation for progress bar */
    .progress-bar {
        transition: width 1s ease-in-out;
    }
    
    /* Pulse animation for waiting state */
    .pulse-waiting {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 0.6; }
        50% { opacity: 1; }
        100% { opacity: 0.6; }
    }
</style>
@endpush