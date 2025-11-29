@extends('layouts.app')

@section('title', $kelas->nama_kelas . ' - eduSPACE')

@section('content')
    <!-- Container utama untuk pusatkan konten -->
    <div id="main-content" class="min-h-screen flex flex-col items-center justify-start px-5 py-28 md:py-32 transition-all duration-300">
        <!-- Container untuk batas lebar maksimal -->
        <div class="w-full max-w-6xl mx-auto">
            <!-- Class Detail Container -->
            <div class="class-detail-container w-full bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30  p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30 mb-8">
                <!-- Back Button -->
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-white hover:text-primary transition-colors mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Class Header -->
                <div class="class-header mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-shadow shadow-white/30">{{ $kelas->nama_kelas }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <span class="bg-primary/20 text-primary text-sm px-3 py-1 rounded-full">
                            {{ Auth::user()->isGuru() ? 'Teacher' : 'Student' }}
                        </span>
                        
                        <!-- Hanya guru yang bisa melihat kode kelas -->
                        @if(Auth::user()->isGuru())
                        <span class="bg-purple-500/20 text-purple-400 text-sm px-3 py-1 rounded-full">
                            Code: {{ $kelas->kode_kelas }}
                        </span>
                        @endif
                        
                        <span class="bg-blue-500/20 text-blue-400 text-sm px-3 py-1 rounded-full">
                            {{ $anggota->count() }} Members
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-4 mb-8">
                    @if(Auth::user()->isGuru())
                        <!-- Edit Class Button -->
                        <a href="{{ route('kelas.edit', $kelas->id_kelas) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Class
                        </a>

                        <!-- Delete Class Button -->
                        <form action="{{ route('kelas.destroy', $kelas->id_kelas) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this class? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Class
                            </button>
                        </form>

                        <!-- Copy Class Code Button (Hanya untuk guru) -->
                        <button onclick="copyClassCode()" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Copy Class Code
                        </button>
                    @else
                        <!-- Leave Class Button -->
                        <form action="{{ route('kelas.leave', $kelas->id_kelas) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to leave this class?')">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Leave Class
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Materials Section -->
                <div class="bg-white/5 rounded-lg p-6 mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Class Materials ({{ $kelas->materi->count() }})
                        </h3>
                        @if(Auth::user()->isGuru())
                            <button onclick="openMaterialModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Material
                            </button>
                        @endif
                    </div>

                    @if($kelas->materi->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($kelas->materi as $materi)
                                <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-lg line-clamp-2">{{ $materi->judul }}</h4>
                                        @if(Auth::user()->isGuru())
                                            <div class="flex space-x-1">
                                                <button onclick="editMaterial({{ $materi->id_materi }})" class="text-yellow-400 hover:text-yellow-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('kelas.materi.destroy', [$kelas->id_kelas, $materi->id_materi]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this material?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-white/70 text-sm mb-3 line-clamp-2">{{ $materi->deskripsi }}</p>
                                    @if($materi->file)
                                        <div class="flex items-center text-blue-400 text-sm mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <a href="{{ asset('storage/' . $materi->file) }}" target="_blank" class="hover:underline">Download File</a>
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-center text-xs text-white/50">
                                        <span>{{ $materi->created_at->format('M d, Y') }}</span>
                                        <button onclick="viewMaterial({{ $materi->id_materi }})" class="text-primary hover:text-purple-300">View Details</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-white/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-white/60">No materials available yet.</p>
                            @if(Auth::user()->isGuru())
                                <p class="text-white/40 text-sm mt-1">Add your first material to get started!</p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Assignments Section -->
                <div class="bg-white/5 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold flex items-center">
                            <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Assignments ({{ $kelas->tugas->count() }})
                        </h3>
                        @if(Auth::user()->isGuru())
                            <button onclick="openAssignmentModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Assignment
                            </button>
                        @endif
                    </div>

                    @if($kelas->tugas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($kelas->tugas as $tugas)
                                <div class="bg-white/5 rounded-lg p-4 hover:bg-white/10 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-lg line-clamp-2">{{ $tugas->judul }}</h4>
                                        @if(Auth::user()->isGuru())
                                            <div class="flex space-x-1">
                                                <button onclick="editAssignment({{ $tugas->id_tugas }})" class="text-yellow-400 hover:text-yellow-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('kelas.tugas.destroy', [$kelas->id_kelas, $tugas->id_tugas]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this assignment?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-white/70 text-sm mb-3 line-clamp-2">{{ $tugas->deskripsi }}</p>
                                    <div class="flex items-center text-red-400 text-sm mb-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Due: {{ $tugas->deadline->format('M d, Y H:i') }}
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-white/50">
                                        <span>{{ $tugas->created_at->format('M d, Y') }}</span>
                                        <button onclick="viewAssignment({{ $tugas->id_tugas }})" class="text-primary hover:text-purple-300">View Details</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto text-white/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-white/60">No assignments yet.</p>
                            @if(Auth::user()->isGuru())
                                <p class="text-white/40 text-sm mt-1">Create your first assignment to get started!</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Class Members Section -->
            <div class="class-members w-full bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 md:p-8 relative z-10 shadow-lg shadow-primary/30">
                <h3 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Class Members ({{ $anggota->count() }})
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($anggota as $member)
                        <div class="flex items-center p-3 bg-white/5 rounded-lg hover:bg-white/10 transition-colors">
                            @if($member->user->avatar)
                                <img src="{{ asset('storage/' . $member->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full mr-3">
                            @else
                                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($member->user->nama, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium">{{ $member->user->nama }}</p>
                                <p class="text-sm text-white/60">
                                    {{ $member->user->id_user == $kelas->id_guru ? 'Teacher' : 'Student' }}
                                </p>
                            </div>
                            @if($member->user->id_user == $kelas->id_guru)
                                <span class="bg-primary/20 text-primary text-xs px-2 py-1 rounded">Owner</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @if(Auth::user()->isGuru())
        @include('kelas.partials.material-modal')
        @include('kelas.partials.assignment-modal')
    @endif
@endsection

@push('styles')
<style>
    /* Style untuk backdrop blur */
    .backdrop-blur-effect {
        filter: blur(8px);
        pointer-events: none;
        user-select: none;
    }
    
    /* Transition untuk smooth blur effect */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyClassCode() {
        // Hanya guru yang bisa copy kode kelas
        @if(Auth::user()->isGuru())
            const classCode = '{{ $kelas->kode_kelas }}';
            navigator.clipboard.writeText(classCode).then(() => {
                alert('Class code copied to clipboard: ' + classCode);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        @else
            alert('Access denied. Only teachers can copy class code.');
        @endif
    }

    // Material Modal Functions
    function openMaterialModal() {
        // Hanya guru yang bisa buka modal material
        @if(Auth::user()->isGuru())
            const modal = document.getElementById('materialModal');
            const mainContent = document.getElementById('main-content');
            
            if (modal && mainContent) {
                // Reset form dan error messages
                resetMaterialForm();
                
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                
                // Tambah blur effect ke background
                mainContent.classList.add('backdrop-blur-effect', 'scale-95');
                document.body.style.overflow = 'hidden';
            }
        @else
            alert('Access denied. Only teachers can add materials.');
        @endif
    }

    function closeMaterialModal() {
        const modal = document.getElementById('materialModal');
        const mainContent = document.getElementById('main-content');
        
        if (modal && mainContent) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            // Hapus blur effect dari background
            mainContent.classList.remove('backdrop-blur-effect', 'scale-95');
            document.body.style.overflow = 'auto';
        }
    }

    // Reset material form
    function resetMaterialForm() {
        const form = document.getElementById('materialForm');
        if (form) {
            form.reset();
        }
        
        // Hide all error messages
        hideAllMaterialErrors();
        hideMaterialSuccess();
    }

    // Hide all error messages
    function hideAllMaterialErrors() {
        const errorContainers = [
            'materialErrors',
            'judulError',
            'deskripsiError',
            'fileError'
        ];
        
        errorContainers.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.classList.add('hidden');
                if (id === 'materialErrors') {
                    element.innerHTML = '<ul id="materialErrorList" class="list-disc list-inside text-sm"></ul>';
                } else {
                    element.textContent = '';
                }
            }
        });
    }

    // Hide success message
    function hideMaterialSuccess() {
        const successElement = document.getElementById('materialSuccess');
        if (successElement) {
            successElement.classList.add('hidden');
        }
    }

    // Show specific error
    function showMaterialError(field, message) {
        const errorElement = document.getElementById(field + 'Error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    // Show general errors
    function showGeneralMaterialErrors(messages) {
        const errorContainer = document.getElementById('materialErrors');
        const errorList = document.getElementById('materialErrorList');
        
        if (errorContainer && errorList) {
            errorList.innerHTML = '';
            messages.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
            errorContainer.classList.remove('hidden');
        }
    }

    // Show success message
    function showMaterialSuccess(message) {
        const successElement = document.getElementById('materialSuccess');
        const successMessage = document.getElementById('materialSuccessMessage');
        
        if (successElement && successMessage) {
            successMessage.textContent = message;
            successElement.classList.remove('hidden');
        }
    }

    // Handle material form submission dengan AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const materialForm = document.getElementById('materialForm');
        if (materialForm) {
            materialForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Hanya guru yang bisa submit material
                @if(Auth::user()->isGuru())
                    const materialSubmitBtn = document.getElementById('materialSubmitBtn');
                    const submitText = document.getElementById('submitText');
                    const submitSpinner = document.getElementById('submitSpinner');
                    
                    // Show loading state
                    if (materialSubmitBtn && submitText && submitSpinner) {
                        materialSubmitBtn.disabled = true;
                        submitText.textContent = 'Adding...';
                        submitSpinner.classList.remove('hidden');
                    }
                    
                    // Hide previous errors and success
                    hideAllMaterialErrors();
                    hideMaterialSuccess();
                    
                    // Create FormData
                    const formData = new FormData(this);
                    
                    // AJAX request
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset loading state
                        if (materialSubmitBtn && submitText && submitSpinner) {
                            materialSubmitBtn.disabled = false;
                            submitText.textContent = 'Add Material';
                            submitSpinner.classList.add('hidden');
                        }
                        
                        if (data.success) {
                            // Show success message
                            showMaterialSuccess(data.message);
                            
                            // Reset form
                            materialForm.reset();
                            
                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 2000);
                            
                        } else if (data.errors) {
                            // Show validation errors
                            if (data.errors.judul) {
                                showMaterialError('judul', data.errors.judul[0]);
                            }
                            if (data.errors.deskripsi) {
                                showMaterialError('deskripsi', data.errors.deskripsi[0]);
                            }
                            if (data.errors.file) {
                                showMaterialError('file', data.errors.file[0]);
                            }
                            
                            // Show general errors
                            if (data.message) {
                                showGeneralMaterialErrors([data.message]);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        // Reset loading state
                        if (materialSubmitBtn && submitText && submitSpinner) {
                            materialSubmitBtn.disabled = false;
                            submitText.textContent = 'Add Material';
                            submitSpinner.classList.add('hidden');
                        }
                        
                        showGeneralMaterialErrors(['Terjadi kesalahan. Silakan coba lagi.']);
                    });
                @else
                    alert('Access denied. Only teachers can add materials.');
                @endif
            });
        }
    });

    // Assignment Modal Functions
    function openAssignmentModal() {
        // Hanya guru yang bisa buka modal assignment
        @if(Auth::user()->isGuru())
            const modal = document.getElementById('assignmentModal');
            const mainContent = document.getElementById('main-content');
            
            if (modal && mainContent) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100');
                
                // Tambah blur effect ke background
                mainContent.classList.add('backdrop-blur-effect', 'scale-95');
                document.body.style.overflow = 'hidden';
            }
        @else
            alert('Access denied. Only teachers can add assignments.');
        @endif
    }

    function closeAssignmentModal() {
        const modal = document.getElementById('assignmentModal');
        const mainContent = document.getElementById('main-content');
        
        if (modal && mainContent) {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            
            // Hapus blur effect dari background
            mainContent.classList.remove('backdrop-blur-effect', 'scale-95');
            document.body.style.overflow = 'auto';
        }
    }

    // Close modals when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const materialModal = document.getElementById('materialModal');
        const assignmentModal = document.getElementById('assignmentModal');

        if (materialModal) {
            materialModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMaterialModal();
                }
            });
        }

        if (assignmentModal) {
            assignmentModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAssignmentModal();
                }
            });
        }

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMaterialModal();
                closeAssignmentModal();
            }
        });
    });

    function viewMaterial(materialId) {
        window.location.href = '{{ route("kelas.materi.show", [$kelas->id_kelas, ":materialId"]) }}'.replace(':materialId', materialId);
    }

    function viewAssignment(assignmentId) {
        window.location.href = '{{ route("kelas.tugas.show", [$kelas->id_kelas, ":assignmentId"]) }}'.replace(':assignmentId', assignmentId);
    }

    function editMaterial(materialId) {
        // Hanya guru yang bisa edit material
        @if(Auth::user()->isGuru())
            window.location.href = '{{ route("kelas.materi.edit", [$kelas->id_kelas, ":materialId"]) }}'.replace(':materialId', materialId);
        @else
            alert('Access denied. Only teachers can edit materials.');
        @endif
    }

    function editAssignment(assignmentId) {
        // Hanya guru yang bisa edit assignment
        @if(Auth::user()->isGuru())
            window.location.href = '{{ route("kelas.tugas.edit", [$kelas->id_kelas, ":assignmentId"]) }}'.replace(':assignmentId', assignmentId);
        @else
            alert('Access denied. Only teachers can edit assignments.');
        @endif
    }
</script>
@endpush