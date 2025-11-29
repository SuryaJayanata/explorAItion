@extends('layouts.app')

@section('title', $materi->judul . ' - eduSPACE')

@section('content')
    <div class="min-h-screen px-5 py-28 md:py-32">
        <div class="max-w-4xl mx-auto">
            <!-- Navigation -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('kelas.materials.index') }}" class="inline-flex items-center text-white hover:text-primary transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        All Materials
                    </a>
                    <span class="text-white/40">/</span>
                    <a href="{{ route('kelas.show', $materi->kelas->id_kelas) }}" class="text-white hover:text-primary transition-colors">
                        {{ $materi->kelas->nama_kelas }}
                    </a>
                </div>
                
                @if(Auth::user()->isGuru() && $materi->kelas->id_guru == Auth::id())
                <div class="flex space-x-3">
                    <a href="{{ route('kelas.materi.edit', [$materi->kelas->id_kelas, $materi->id_materi]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                    a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('kelas.materi.destroy', [$materi->kelas->id_kelas, $materi->id_materi]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this material?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
            <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-8 mb-8 shadow-lg shadow-primary/30">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">{{ $materi->judul }}</h1>
                        <div class="flex items-center flex-wrap gap-4 text-white/60">
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Posted {{ $materi->created_at->format('M d, Y') }}
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
                <div>
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
                                    <p class="text-white/60 text-sm">Uploaded {{ $materi->created_at->diffForHumans() }}</p>
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
            </div>

            <!-- Comments Section -->
            @include('materi.partials.comments-section', ['kelas' => $materi->kelas, 'materi' => $materi])
        </div>
    </div>
@endsection

@push('styles')
<style>
    .prose {
        max-width: none;
    }
    .prose p {
        margin-bottom: 0;
    }
    
    /* Style untuk modal preview */
    #previewModal {
        backdrop-filter: blur(8px);
        animation: modalFadeIn 0.3s ease-out;
    }

    #previewModal iframe {
        border: none;
        border-radius: 0.5rem;
        min-height: 600px;
    }

    .preview-container {
        max-height: 80vh;
        height: 80vh;
    }

    .preview-content {
        height: calc(100% - 120px);
        min-height: 500px;
    }

    /* Animasi untuk modal */
    @keyframes modalFadeIn {
        from { 
            opacity: 0; 
            transform: scale(0.95);
        }
        to { 
            opacity: 1; 
            transform: scale(1);
        }
    }

    /* Responsive design untuk modal */
    @media (max-width: 768px) {
        .preview-container {
            max-height: 90vh;
            height: 90vh;
            margin: 10px;
        }
        
        #previewModal iframe {
            min-height: 400px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
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

function showPreviewError(message) {
    const previewContent = document.getElementById('previewContent');
    previewContent.innerHTML = `
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-red-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <h4 class="text-2xl font-bold text-red-400 mb-4">Terjadi Error</h4>
            <p class="text-white/70 text-lg mb-6">${message}</p>
            <a href="${currentFileUrl}" download 
               class="inline-flex items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors text-base font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download File
            </a>
        </div>
    `;
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
@endpush