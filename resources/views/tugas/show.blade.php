@extends('layouts.app')

@section('title', $tugas->judul . ' - eduSPACE')

@section('content')
<div class="min-h-screen px-5 py-28 md:py-32">
    <div class="max-w-6xl mx-auto">
        <!-- Navigation -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('kelas.show', $tugas->kelas->id_kelas) }}" class="inline-flex items-center text-white hover:text-primary transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Class
                </a>
            </div>
            
            @if(Auth::user()->isGuru() && $tugas->kelas->id_guru == Auth::id())
            <div class="flex space-x-3">
                <a href="{{ route('kelas.tugas.edit', [$tugas->kelas->id_kelas, $tugas->id_tugas]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                 a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Assignment
                </a>
                <form action="{{ route('kelas.tugas.destroy', [$tugas->kelas->id_kelas, $tugas->id_tugas]) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm"
                            onclick="return confirm('Delete this assignment? This action cannot be undone.')">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Assignment
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Assignment Header -->
        <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-8 mb-8 shadow-lg shadow-primary/30">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">{{ $tugas->judul }}</h1>
                    <div class="flex items-center flex-wrap gap-4 text-white/60">
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Created {{ $tugas->created_at->format('M d, Y') }}
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Class {{ $tugas->kelas->nama_kelas }}
                        </div>
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            By {{ $tugas->kelas->guru->nama }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deadline -->
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submission Deadline
                </h3>
                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-red-500/20 p-3 rounded-lg">
                                <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-lg">{{ $tugas->deadline->format('l, F d, Y') }}</p>
                                <p class="text-white/60 text-sm">Due at {{ $tugas->deadline->format('H:i') }}</p>
                                @php
                                    $now = now();
                                    $diff = $tugas->deadline->diff($now);
                                @endphp
                                @if($tugas->deadline < $now)
                                    <p class="text-red-400 text-sm font-medium mt-1">Overdue by {{ $diff->days }} days</p>
                                @else
                                    <p class="text-green-400 text-sm font-medium mt-1">
                                        {{ $diff->days }} days {{ $diff->h }} hours remaining
                                    </p>
                                @endif
                            </div>
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
                    Assignment Description
                </h3>
                <div class="bg-white/5 rounded-xl p-6 border border-white/10">
                    <p class="text-white/80 leading-relaxed text-lg whitespace-pre-line">{{ $tugas->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Student View -->
        @if(Auth::user()->isSiswa())
        @include('tugas.partials.student-view')
        @endif

        <!-- Teacher View - Student Submissions -->
        @if(Auth::user()->isGuru() && $tugas->kelas->id_guru == Auth::id())
        <div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-8 mb-8 shadow-lg shadow-primary/30">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Student Submissions
                </h2>
                <div class="text-white/60 text-sm">
                    {{ $tugas->pengumpulan->count() }} / {{ $semuaSiswa->count() }} submitted
                </div>
            </div>

            <!-- Submission Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                    <div class="text-2xl font-bold text-white">{{ $tugas->pengumpulan->count() }}</div>
                    <div class="text-white/60 text-sm">Total Submitted</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                    <div class="text-2xl font-bold text-green-400">
                        {{ $tugas->pengumpulan->where('nilai', '!=', null)->count() }}
                    </div>
                    <div class="text-white/60 text-sm">Graded</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                    <div class="text-2xl font-bold text-yellow-400">
                        {{ $tugas->pengumpulan->where('nilai', null)->count() }}
                    </div>
                    <div class="text-white/60 text-sm">Not Graded</div>
                </div>
                <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                    <div class="text-2xl font-bold text-red-400">
                        {{ $semuaSiswa->count() - $tugas->pengumpulan->count() }}
                    </div>
                    <div class="text-white/60 text-sm">Not Submitted</div>
                </div>
            </div>

            <!-- Students List -->
            <div class="space-y-4">
                @foreach($semuaSiswa as $anggota)
                @php
                    $siswa = $anggota->user;
                    $pengumpulan = $tugas->pengumpulan->where('id_user', $siswa->id_user)->first();
                    $currentNilai = $pengumpulan && $pengumpulan->nilai ? $pengumpulan->nilai->nilai : null;
                    $currentKomentar = $pengumpulan && $pengumpulan->nilai ? addslashes($pengumpulan->nilai->komentar_guru) : '';
                @endphp
                <div class="bg-white/5 rounded-xl p-6 border border-white/10 hover:bg-white/10 transition-colors">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            @if($siswa->avatar)
                                <img src="{{ asset('storage/' . $siswa->avatar) }}" 
                                     alt="Avatar" 
                                     class="w-10 h-10 rounded-full border-2 border-white/20">
                            @else
                                <div class="w-12 h-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-lg border-2 border-white/20">
                                    {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="text-white font-semibold text-lg">{{ $siswa->nama }}</h4>
                                <p class="text-white/60 text-sm">{{ $siswa->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <!-- Status Badge -->
                            @if($pengumpulan)
                                @if($pengumpulan->nilai)
                                    <div class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">
                                        Graded: {{ $pengumpulan->nilai->nilai }}/100
                                    </div>
                                @else
                                    <div class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">
                                        Submitted - Not Graded
                                    </div>
                                @endif
                            @else
                                <div class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm font-medium">
                                    Not Submitted
                                </div>
                            @endif
                            
                            <!-- Action Buttons -->
                            @if($pengumpulan)
                                <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View Work
                                </a>
                                
                                <button onclick="openGradeModal({{ $pengumpulan->id_pengumpulan }}, {{ $currentNilai ?? 'null' }}, `{{ $currentKomentar }}`)"
                                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ $pengumpulan->nilai ? 'Edit Grade' : 'Give Grade' }}
                                </button>
                            @else
                                <span class="text-white/40 text-sm">No submission yet</span>
                            @endif
                        </div>
                    </div>

                    <!-- Submission Details -->
                    @if($pengumpulan)
                    <div class="mt-4 pt-4 border-t border-white/10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-white/60">Submitted: {{ $pengumpulan->created_at->format('M d, Y H:i') }}</p>
                                @if($pengumpulan->created_at > $tugas->deadline)
                                    <p class="text-red-400 text-sm">Submitted late</p>
                                @endif
                            </div>
                            @if($pengumpulan->nilai && $pengumpulan->nilai->komentar_guru)
                            <div>
                                <p class="text-white/60">Teacher's Comment:</p>
                                <p class="text-white/80">{{ Str::limit($pengumpulan->nilai->komentar_guru, 100) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Comments Section -->
        @include('tugas.partials.comments-section')
    </div>
</div>

<!-- Grade Modal -->
@if(Auth::user()->isGuru() && $tugas->kelas->id_guru == Auth::id())
<div id="gradeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-md mx-4 relative">
        <button onclick="closeGradeModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <h2 class="text-2xl md:text-3xl font-bold mb-6 text-center text-white">Grade Submission</h2>
        
        <form id="gradeForm" method="POST">
            @csrf
            <div class="mb-6">
                <label for="nilai" class="block text-lg mb-3 text-white/90">Grade (0-100)</label>
                <input type="number" id="nilai" name="nilai" min="0" max="100" 
                       class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary"
                       placeholder="Enter grade (0-100)" required>
            </div>
            
            <div class="mb-6">
                <label for="komentar_guru" class="block text-lg mb-3 text-white/90">Comments & Feedback</label>
                <textarea id="komentar_guru" name="komentar_guru" rows="4"
                          class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary resize-none"
                          placeholder="Provide comments and feedback for the student"></textarea>
            </div>
            
            <button type="submit" 
                    class="w-full bg-primary py-4 rounded-lg text-white text-lg font-medium shadow-lg shadow-primary/30 hover:shadow-primary/50 transition-all">
                Save Grade
            </button>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // ==================== GRADE MODAL FUNCTIONS (FOR TEACHERS) ====================
    @if(Auth::user()->isGuru() && $tugas->kelas->id_guru == Auth::id())

    function openGradeModal(pengumpulanId, currentGrade, currentComment) {
        console.log('Opening grade modal for:', pengumpulanId, 'Grade:', currentGrade, 'Comment:', currentComment);
        
        const modal = document.getElementById('gradeModal');
        const form = document.getElementById('gradeForm');
        const nilaiInput = document.getElementById('nilai');
        const komentarInput = document.getElementById('komentar_guru');
        
        // Set form action
        form.action = `/kelas/{{ $tugas->kelas->id_kelas }}/tugas/{{ $tugas->id_tugas }}/pengumpulan/${pengumpulanId}/nilai`;
        
        // Set current values if editing
        if (currentGrade && currentGrade !== 'null') {
            nilaiInput.value = currentGrade;
        } else {
            nilaiInput.value = '';
        }
        
        if (currentComment && currentComment !== 'null') {
            // Unescape the comment
            komentarInput.value = currentComment.replace(/\\/g, '');
        } else {
            komentarInput.value = '';
        }
        
        // Show modal with animation
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Focus on grade input
        setTimeout(() => {
            nilaiInput.focus();
        }, 300);
    }

    function closeGradeModal() {
        const modal = document.getElementById('gradeModal');
        
        // Hide modal with animation
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = document.getElementById('gradeForm');
        if (form) {
            form.reset();
        }
    }

    // Grade form submission dengan AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const gradeForm = document.getElementById('gradeForm');
        if (gradeForm) {
            gradeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitButton = this.querySelector('button[type="submit"]');
                const originalContent = submitButton.innerHTML;
                
                // Validate grade
                const nilai = formData.get('nilai');
                if (nilai < 0 || nilai > 100) {
                    showToast('Please enter a grade between 0 and 100.', 'error');
                    return;
                }
                
                // Disable button and show loading
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Grade saved successfully!', 'success');
                        closeGradeModal();
                        // Refresh page after short delay to show updated grades
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to save grade');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to save grade. Please try again.', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalContent;
                });
            });
        }

        // Close grade modal when clicking outside
        const gradeModal = document.getElementById('gradeModal');
        if (gradeModal) {
            gradeModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeGradeModal();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeGradeModal();
            }
        });
    });

    @endif

    // ==================== TOAST NOTIFICATION SYSTEM ====================
    function showToast(message, type = 'info') {
        // Remove existing toasts
        document.querySelectorAll('.toast').forEach(toast => toast.remove());
        
        const toast = document.createElement('div');
        toast.className = `toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${getToastClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                ${getToastIcon(type)}
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
        
        // Click to dismiss
        toast.addEventListener('click', () => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
    }

    function getToastClass(type) {
        const classes = {
            success: 'bg-green-500 text-white border border-green-400',
            error: 'bg-red-500 text-white border border-red-400',
            warning: 'bg-yellow-500 text-white border border-yellow-400',
            info: 'bg-blue-500 text-white border border-blue-400'
        };
        return classes[type] || classes.info;
    }

    function getToastIcon(type) {
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`
        };
        return icons[type] || icons.info;
    }
</script>

<style>
    /* Additional custom styles */
    .comment {
        transition: all 0.3s ease;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
    
    /* Smooth scrolling for the whole page */
    html {
        scroll-behavior: smooth;
    }
    
    /* Toast animations */
    .toast {
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(189, 24, 239, 0.5);
        border-radius: 3px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(189, 24, 239, 0.7);
    }

    /* Modal animations */
    #gradeModal {
        transition: all 0.3s ease;
    }
</style>
@endpush