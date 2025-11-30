@extends('layouts.app')

@section('title', $materi->judul . ' - eduSPACE')

@section('content')
    <div class="min-h-screen px-5 py-28 md:py-32">
        <div class="max-w-4xl mx-auto">
            <!-- Navigation -->
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('kelas.show', $kelas->id_kelas) }}" class="inline-flex items-center text-white hover:text-primary transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Class
                </a>
                
                @if(Auth::user()->isGuru())
                <div class="flex space-x-3">
                    <form action="{{ route('kelas.materi.edit', [$kelas->id_kelas, $materi->id_materi]) }}" method="GET">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white 
                                       px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                      m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9
                                      v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                    </form>
                    <form action="{{ route('kelas.materi.destroy', [$kelas->id_kelas, $materi->id_materi]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white 
                                       px-4 py-2 rounded-lg text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                      a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                      m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-8">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Material Header -->
            <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-8 mb-8 shadow-lg shadow-primary/30">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">{{ $materi->judul }}</h1>
                        <div class="flex items-center flex-wrap gap-4 text-white/60">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Posted {{ $materi->created_at ? $materi->created_at->format('M d, Y') : 'Date not available' }}
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                By {{ $materi->kelas->guru->nama }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Description
                    </h3>
                    <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                        <p class="text-white/80 leading-relaxed text-lg">{{ $materi->deskripsi }}</p>
                    </div>
                </div>

                <!-- File Attachment -->
                @if($materi->file)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Attached File
                    </h3>
                    <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-500/20 p-3 rounded-lg">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-semibold text-lg">{{ basename($materi->file) }}</p>
                                    <p class="text-white/60 text-sm">Uploaded {{ $materi->created_at ? $materi->created_at->diffForHumans() : 'recently' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <!-- Preview Button -->
                                <button onclick="previewFile('{{ asset('storage/' . $materi->file) }}', '{{ basename($materi->file) }}')" 
                                        class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Preview
                                </button>                                
                                <!-- Download Button -->
                                <a href="{{ asset('storage/' . $materi->file) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- AI Summary Section -->
                @if($materi->file)
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        AI Summary (Powered by Gemini)
                    </h3>
                    
                    <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    @if($materi->summary && trim($materi->summary) !== '')
                            <!-- Display existing summary -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-white">Ringkasan Materi</h4>
                                    <div class="flex space-x-2">
                                        <button onclick="copySummary()" 
                                                class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Copy Text
                                        </button>
                                        <a href="{{ route('kelas.materi.download-summary', [$kelas->id_kelas, $materi->id_materi]) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Download PDF
                                        </a>
                                        @if($materi->hasFlashcards())
                                        <button onclick="showFlashcards()" 
                                                class="inline-flex items-center px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Lihat Flashcards
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-gray-900 rounded-lg p-4 border border-gray-700">
                                    <div class="text-white/80 leading-relaxed whitespace-pre-line" id="summaryContent">
                                        {{ $materi->summary }}
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-3">
                                    <p class="text-white/60 text-xs">
                                    @if($materi->summary_generated_at)
    Generated by Gemini AI • {{ $materi->summary_generated_at->format('d M Y H:i') }}
@else
    Generated by Gemini AI
@endif
                                        @if($materi->hasFlashcards())
                                        • {{ count($materi->flashcards) }} Flashcards
                                        @endif
                                    </p>
                                    <button onclick="showGenerateOptions()" 
                                            class="text-xs text-yellow-400 hover:text-yellow-300 transition-colors">
                                        Generate Ulang
                                    </button>
                                </div>
                            </div>
                        @else
                            <!-- Generate summary button -->
                            <div class="text-center py-6">
                                <svg class="w-16 h-16 mx-auto text-purple-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <h4 class="text-xl font-bold text-white mb-2">Generate AI Summary</h4>
                                <p class="text-white/70 mb-4">Gunakan Google Gemini AI untuk membuat ringkasan otomatis dari materi ini.</p>
                                <p class="text-white/50 text-sm mb-6">Supported formats: PDF, DOC, DOCX, TXT</p>
                                <button onclick="showGenerateOptions()" 
                                        id="generateOptionsBtn"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-600 hover:to-blue-600 text-white rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Generate Summary dengan AI
                                </button>
                                <p class="text-white/40 text-xs mt-3">Menggunakan Google Gemini Pro</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Comments Section -->
            @include('materi.partials.comments-section')
        </div>
    </div>

<!-- Generate Options Modal -->
<div id="generateOptionsModal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-2xl border border-gray-600 p-6 w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-white">Generate Options</h3>
            <button onclick="hideGenerateOptions()" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div class="space-y-4">
            <div class="bg-gray-700/50 rounded-lg p-4 border border-gray-600">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" id="includeFlashcards" checked class="w-4 h-4 text-purple-600 bg-gray-800 border-gray-600 rounded focus:ring-purple-500 focus:ring-2">
                    <div>
                        <span class="text-white font-medium">Include Flashcards</span>
                        <p class="text-white/60 text-sm mt-1">Buat flashcard untuk memaksimalkan belajar</p>
                    </div>
                </label>
            </div>

            <div id="flashcardOptions" class="bg-gray-700/50 rounded-lg p-4 border border-gray-600 space-y-3">
                <label class="text-white font-medium block">Jumlah Flashcards</label>
                <div class="flex items-center space-x-3">
                    <input type="range" id="flashcardCount" min="3" max="10" value="5" 
                           class="w-full h-2 bg-gray-600 rounded-lg appearance-none cursor-pointer slider">
                    <span id="flashcardCountValue" class="text-white font-bold min-w-8 text-center bg-purple-600 px-2 py-1 rounded">5</span>
                </div>
                <p class="text-white/60 text-xs">Rekomendasi: 5-7 flashcards untuk materi ini</p>
            </div>

            <div class="flex space-x-3 pt-2">
                <button onclick="hideGenerateOptions()" 
                        class="flex-1 px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors font-medium">
                    Cancel
                </button>
                <button onclick="startGeneration()" 
                        id="confirmGenerateBtn"
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg transition-colors font-medium shadow-lg">
                    Generate Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Flashcards Modal -->
<div id="flashcardsModal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-2xl border border-gray-600 p-6 w-full max-w-2xl mx-4 max-h-[80vh] overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Flashcards</h3>
            <button onclick="hideFlashcards()" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <div id="flashcardsContainer" class="overflow-y-auto max-h-[60vh] p-2">
            <!-- Flashcards will be loaded here -->
        </div>
        
        <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-600">
            <button onclick="previousFlashcard()" 
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors font-medium">
                Previous
            </button>
            <span id="flashcardCounter" class="text-white/80 font-medium">1/5</span>
            <button onclick="nextFlashcard()" 
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors font-medium">
                Next
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentFlashcardIndex = 0;
let flashcards = [];

function showGenerateOptions() {
    document.getElementById('generateOptionsModal').classList.remove('hidden');
}

function hideGenerateOptions() {
    document.getElementById('generateOptionsModal').classList.add('hidden');
}

function updateFlashcardCount() {
    const count = document.getElementById('flashcardCount').value;
    document.getElementById('flashcardCountValue').textContent = count;
}

function startGeneration() {
    const includeFlashcards = document.getElementById('includeFlashcards').checked;
    const flashcardCount = includeFlashcards ? document.getElementById('flashcardCount').value : 0;
    
    hideGenerateOptions();
    generateSummary(includeFlashcards, flashcardCount);
}

function generateSummary(includeFlashcards = false, flashcardCount = 0) {
    const btn = document.getElementById('generateOptionsBtn') || document.querySelector('[onclick="showGenerateOptions()"]');
    const originalText = btn.innerHTML;
    
    console.log('Starting generateSummary function', { includeFlashcards, flashcardCount });
    
    // Show loading
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Generating with AI...
    `;
    
    const url = `{{ route('kelas.materi.generate-summary', [$kelas->id_kelas, $materi->id_materi]) }}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            include_flashcards: includeFlashcards,
            flashcard_count: flashcardCount
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showToast(data.message, 'success');
            console.log('Success - reloading page in 2 seconds');
            // Reload after 2 seconds to show the new summary
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            console.error('API Error:', data.message);
            showToast('Error: ' + data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        showToast('Terjadi kesalahan jaringan: ' + error.message, 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function showFlashcards() {
    // Load flashcards data dari array PHP
    @if($materi->hasFlashcards())
        flashcards = {!! json_encode($materi->flashcards) !!};
    @else
        flashcards = [];
    @endif
    
    currentFlashcardIndex = 0;
    renderFlashcards();
    document.getElementById('flashcardsModal').classList.remove('hidden');
}

function hideFlashcards() {
    document.getElementById('flashcardsModal').classList.add('hidden');
}

function renderFlashcards() {
    const container = document.getElementById('flashcardsContainer');
    const counter = document.getElementById('flashcardCounter');
    
    if (flashcards.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12 bg-gray-700/50 rounded-xl border border-gray-600">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-white/60 text-lg">Tidak ada flashcards tersedia.</p>
                <p class="text-white/40 text-sm mt-2">Generate summary dengan opsi flashcards untuk membuat flashcards.</p>
            </div>
        `;
        counter.textContent = '0/0';
        return;
    }
    
    const currentCard = flashcards[currentFlashcardIndex];
    container.innerHTML = `
        <div class="flashcard bg-gray-700 rounded-xl p-6 border-2 border-purple-500/50 cursor-pointer transform transition-all duration-300 hover:scale-[1.02] hover:border-purple-500" onclick="flipFlashcard(this)">
            <div class="flashcard-front">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-purple-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-white">Pertanyaan</h4>
                </div>
                <p class="text-white/90 text-lg text-center leading-relaxed bg-gray-600/50 p-4 rounded-lg border border-gray-500">${currentCard.pertanyaan}</p>
                <p class="text-purple-400 text-sm mt-4 text-center flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Klik untuk melihat jawaban
                </p>
            </div>
            <div class="flashcard-back hidden">
                <div class="flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-white">Jawaban</h4>
                </div>
                <p class="text-white/90 text-lg text-center leading-relaxed bg-gray-600/50 p-4 rounded-lg border border-gray-500">${currentCard.jawaban}</p>
                <p class="text-green-400 text-sm mt-4 text-center flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Klik untuk melihat pertanyaan
                </p>
            </div>
        </div>
    `;
    
    counter.textContent = `${currentFlashcardIndex + 1}/${flashcards.length}`;
}
function flipFlashcard(element) {
    element.classList.toggle('flipped');
    
    const front = element.querySelector('.flashcard-front');
    const back = element.querySelector('.flashcard-back');
    
    // Untuk efek yang lebih smooth
    setTimeout(() => {
        if (element.classList.contains('flipped')) {
            front.style.display = 'none';
            back.style.display = 'block';
        } else {
            front.style.display = 'block';
            back.style.display = 'none';
        }
    }, 300);
}

function nextFlashcard() {
    if (currentFlashcardIndex < flashcards.length - 1) {
        currentFlashcardIndex++;
        renderFlashcards();
    }
}

function previousFlashcard() {
    if (currentFlashcardIndex > 0) {
        currentFlashcardIndex--;
        renderFlashcards();
    }
}

function copySummary() {
    const summaryContent = document.getElementById('summaryContent').textContent;
    
    navigator.clipboard.writeText(summaryContent).then(function() {
        showToast('Summary copied to clipboard!', 'success');
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        showToast('Failed to copy summary', 'error');
    });
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    const toast = document.createElement('div');
    toast.className = `toast fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300`;
    toast.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
    
    // Click to dismiss
    toast.addEventListener('click', () => {
        toast.remove();
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    const flashcardCount = document.getElementById('flashcardCount');
    if (flashcardCount) {
        flashcardCount.addEventListener('input', updateFlashcardCount);
        updateFlashcardCount();
    }
});

// Preview file functions
function previewFile(fileUrl, fileName) {
    // Buat modal preview
    const modal = document.createElement('div');
    modal.id = 'previewModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    `;
    
    modal.innerHTML = `
        <div class="preview-container bg-gradient-to-br from-gray-800 to-purple-900 rounded-2xl border border-white/20 w-full max-w-6xl flex flex-col shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-white/10 flex-shrink-0">
                <h3 class="text-xl font-bold text-white flex items-center truncate">
                    <svg class="w-6 h-6 mr-3 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="truncate">Preview: ${fileName}</span>
                </h3>
                <button onclick="closePreviewModal()" class="p-2 text-white/60 hover:text-white transition-colors rounded-lg hover:bg-white/10 flex-shrink-0 ml-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="preview-content p-6 overflow-auto flex-1">
                <div id="previewContent" class="w-full h-full flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-white text-lg">Loading preview...</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-between p-4 border-t border-white/10 bg-black/20 flex-shrink-0">
                <span class="text-white/60 text-sm truncate flex-1 mr-4">${fileName}</span>
                <div class="flex gap-3">
                    <button onclick="closePreviewModal()" 
                            class="inline-flex items-center gap-2 bg-primary hover:bg-primary/80 text-white 
                   px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    // Load preview berdasarkan tipe file
    loadPreview(fileUrl, fileName);
}

function loadPreview(fileUrl, fileName) {
    const previewContent = document.getElementById('previewContent');
    const fileExtension = fileName.split('.').pop().toLowerCase();
    
    // Cek tipe file yang bisa dipreview
    const previewableTypes = {
        'pdf': 'pdf',
        'jpg': 'image', 'jpeg': 'image', 'png': 'image', 'gif': 'image',
        'txt': 'text',
        'doc': 'office', 'docx': 'office',
        'ppt': 'office', 'pptx': 'office'
    };
    
    const fileType = previewableTypes[fileExtension] || 'unknown';
    
    switch(fileType) {
        case 'pdf':
            // Preview PDF dengan iframe yang lebih tinggi
            previewContent.innerHTML = `
                <iframe src="${fileUrl}" class="w-full h-full rounded-lg border-0" style="min-height: 600px;">
                    <p class="text-white">Browser tidak mendukung preview PDF. <a href="${fileUrl}" download class="text-blue-400 hover:text-blue-300">Download file</a></p>
                </iframe>
            `;
            break;
            
        case 'image':
            // Preview gambar dengan container yang lebih tinggi
            previewContent.innerHTML = `
                <div class="w-full h-full flex items-center justify-center p-4">
                    <img src="${fileUrl}" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                </div>
            `;
            break;
            
        case 'text':
            // Preview text file dengan container scrollable yang lebih tinggi
            fetch(fileUrl)
                .then(response => response.text())
                .then(text => {
                    previewContent.innerHTML = `
                        <div class="w-full h-full bg-gray-900 rounded-lg p-6 overflow-auto">
                            <pre class="text-white font-mono text-sm whitespace-pre-wrap leading-relaxed">${text}</pre>
                        </div>
                    `;
                })
                .catch(error => {
                    showPreviewError('Gagal memuat file teks');
                });
            break;
            
        case 'office':
            // Untuk file office, tampilkan pesan dan tombol download
            previewContent.innerHTML = `
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-yellow-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h4 class="text-2xl font-bold text-white mb-4">Preview Tidak Tersedia</h4>
                    <p class="text-white/70 text-lg mb-6">File ${fileExtension.toUpperCase()} tidak dapat dipreview di browser.</p>
                    <p class="text-white/50 text-sm mb-8">Silakan download file untuk melihat kontennya.</p>
                    <a href="${fileUrl}" download 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-base font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download File
                    </a>
                </div>
            `;
            break;
            
        default:
            // File type tidak dikenal
            previewContent.innerHTML = `
                <div class="text-center py-16">
                    <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h4 class="text-2xl font-bold text-white mb-4">Format File Tidak Didukung</h4>
                    <p class="text-white/70 text-lg mb-6">File dengan ekstensi .${fileExtension} tidak dapat dipreview.</p>
                    <a href="${fileUrl}" download 
                       class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-base font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download File
                    </a>
                </div>
            `;
    }
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

// Close modal dengan ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});

// Close modal ketika klik di luar konten
document.addEventListener('click', function(e) {
    if (e.target.id === 'previewModal') {
        closePreviewModal();
    }
});
</script>

<style>
.flashcard {
    min-height: 250px;
    transition: all 0.3s ease;
    perspective: 1000px;
}

.flashcard-front, .flashcard-back {
    transition: transform 0.6s, opacity 0.3s ease;
    backface-visibility: hidden;
}

.flashcard-back {
    transform: rotateY(180deg);
}

.flashcard.flipped .flashcard-front {
    transform: rotateY(-180deg);
    opacity: 0;
}

.flashcard.flipped .flashcard-back {
    transform: rotateY(0deg);
    opacity: 1;
}

.hidden {
    display: none;
}

/* Style untuk slider */
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #8b5cf6;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #8b5cf6;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.slider::-webkit-slider-track {
    background: #4b5563;
    height: 8px;
    border-radius: 4px;
}

.slider::-moz-range-track {
    background: #4b5563;
    height: 8px;
    border-radius: 4px;
    border: none;
}
</style>
@endpush