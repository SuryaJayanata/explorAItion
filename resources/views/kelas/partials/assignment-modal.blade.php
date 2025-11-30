<!-- resources/views/kelas/partials/assignment-modal.blade.php -->
<div id="assignmentModal" class="fixed inset-0 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-6 md:p-8 mx-4 max-w-md w-full shadow-2xl transform transition-all duration-300 scale-95 max-h-[90vh] overflow-y-auto">
        <button onclick="closeAssignmentModal()" class="absolute top-4 right-4 text-white/70 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Add New Assignment</h2>
        
        <form id="assignmentForm" action="{{ route('kelas.tugas.store', $kelas->id_kelas) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Error Messages Container -->
            <div id="assignmentErrors" class="hidden bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-4">
                <ul id="assignmentErrorList" class="list-disc list-inside text-sm"></ul>
            </div>

            <!-- Success Message -->
            <div id="assignmentSuccess" class="hidden bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-4">
                <span id="assignmentSuccessMessage"></span>
            </div>

            <!-- Judul -->
            <div class="mb-4">
                <label for="judul" class="block text-white mb-2">Title</label>
                <input type="text" id="judul" name="judul" 
                       class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white outline-none focus:border-primary transition-colors"
                       value="{{ old('judul') }}" required>
                <div id="judulError" class="hidden text-red-400 text-sm mt-1"></div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="deskripsi" class="block text-white mb-2">Description</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white outline-none focus:border-primary transition-colors resize-none"
                          required>{{ old('deskripsi') }}</textarea>
                <div id="deskripsiError" class="hidden text-red-400 text-sm mt-1"></div>
            </div>

            <!-- Deadline -->
            <div class="mb-6">
                <label for="deadline" class="block text-white mb-2">Deadline</label>
                <input type="datetime-local" id="deadline" name="deadline" 
                       class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white outline-none focus:border-primary transition-colors"
                       value="{{ old('deadline') }}" required>
                <div id="deadlineError" class="hidden text-red-400 text-sm mt-1"></div>
            </div>

            <!-- AUTO GRADING SECTION -->
            <div class="mb-6 bg-white/5 rounded-xl p-4 border border-white/10">
                <h3 class="text-lg font-semibold mb-3 flex items-center text-white">
                    <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Auto Grading Settings
                </h3>

                <div class="mb-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="auto_grading" id="auto_grading" value="1" 
                               class="w-4 h-4 text-primary bg-transparent border-white rounded focus:ring-primary focus:ring-2"
                               {{ old('auto_grading') ? 'checked' : '' }}>
                        <span class="text-white font-medium text-sm">Enable Auto Grading with AI</span>
                    </label>
                    <p class="text-white/60 text-xs mt-1">AI will automatically grade student answers based on answer key</p>
                </div>

                <div id="auto_grading_fields" class="space-y-3 {{ old('auto_grading') ? '' : 'hidden' }}">
                    <!-- Kunci Jawaban File -->
                    <div>
                        <label for="kunci_jawaban_file" class="block text-white text-sm mb-1">Answer Key File (PDF/TXT)</label>
                        <input type="file" id="kunci_jawaban_file" name="kunci_jawaban_file" 
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm outline-none focus:border-primary transition-colors file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary file:text-white hover:file:bg-primary/80"
                               accept=".pdf,.txt">
                        <p class="text-white/60 text-xs mt-1">Upload PDF or TXT file containing answer key</p>
                        <div id="kunci_jawaban_fileError" class="hidden text-red-400 text-xs mt-1"></div>
                    </div>

                    <!-- Kunci Jawaban Text -->
                    <div>
                        <label for="kunci_jawaban_text" class="block text-white text-sm mb-1">Or Enter Answer Key Text</label>
                        <textarea id="kunci_jawaban_text" name="kunci_jawaban_text" rows="3"
                                  class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm outline-none focus:border-primary transition-colors resize-none"
                                  placeholder="Enter answer key text here...">{{ old('kunci_jawaban_text') }}</textarea>
                        <div id="kunci_jawaban_textError" class="hidden text-red-400 text-xs mt-1"></div>
                    </div>

                    <!-- Passing Grade -->
                    <div>
                        <label for="passing_grade" class="block text-white text-sm mb-1">Passing Grade</label>
                        <input type="number" id="passing_grade" name="passing_grade" min="0" max="100" step="0.01"
                               class="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm outline-none focus:border-primary transition-colors"
                               value="{{ old('passing_grade', 70) }}">
                        <p class="text-white/60 text-xs mt-1">Minimum score to pass (0-100)</p>
                        <div id="passing_gradeError" class="hidden text-red-400 text-xs mt-1"></div>
                    </div>

                    <div class="bg-blue-500/20 border border-blue-500/30 rounded-lg p-3">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-blue-300 text-xs font-medium">Auto Grading Features</p>
                                <p class="text-blue-200 text-xs mt-1">
                                    • AI automatically grades student answers<br>
                                    • Detects meaning similarity, not just exact words<br>
                                    • Provides detailed feedback<br>
                                    • Students can appeal if there are errors
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="button" onclick="closeAssignmentModal()" 
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" id="assignmentSubmitBtn"
                        class="flex-1 bg-primary hover:bg-primary/80 text-white py-3 rounded-lg transition-colors flex items-center justify-center">
                    <span id="submitText">Add Assignment</span>
                    <div id="submitSpinner" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white ml-2"></div>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const autoGradingCheckbox = document.getElementById('auto_grading');
        const autoGradingFields = document.getElementById('auto_grading_fields');

        // Toggle auto grading fields
        if (autoGradingCheckbox && autoGradingFields) {
            autoGradingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    autoGradingFields.classList.remove('hidden');
                } else {
                    autoGradingFields.classList.add('hidden');
                }
            });

            // Trigger change event on page load in case of old input
            autoGradingCheckbox.dispatchEvent(new Event('change'));
        }

        // AJAX form submission untuk modal
        const assignmentForm = document.getElementById('assignmentForm');
        if (assignmentForm) {
            assignmentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('assignmentSubmitBtn');
                const submitText = document.getElementById('submitText');
                const submitSpinner = document.getElementById('submitSpinner');
                
                // Show loading state
                submitBtn.disabled = true;
                submitText.textContent = 'Adding...';
                submitSpinner.classList.remove('hidden');
                
                // Hide previous errors and success
                hideAllAssignmentErrors();
                hideAssignmentSuccess();
                
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
                    submitBtn.disabled = false;
                    submitText.textContent = 'Add Assignment';
                    submitSpinner.classList.add('hidden');
                    
                    if (data.success) {
                        // Show success message
                        showAssignmentSuccess(data.message);
                        
                        // Reset form
                        assignmentForm.reset();
                        
                        // Close modal and redirect after 2 seconds
                        setTimeout(() => {
                            closeAssignmentModal();
                            window.location.href = data.redirect_url || '{{ route("kelas.show", $kelas->id_kelas) }}';
                        }, 2000);
                        
                    } else if (data.errors) {
                        // Show validation errors
                        if (data.errors.judul) {
                            showAssignmentError('judul', data.errors.judul[0]);
                        }
                        if (data.errors.deskripsi) {
                            showAssignmentError('deskripsi', data.errors.deskripsi[0]);
                        }
                        if (data.errors.deadline) {
                            showAssignmentError('deadline', data.errors.deadline[0]);
                        }
                        if (data.errors.kunci_jawaban_file) {
                            showAssignmentError('kunci_jawaban_file', data.errors.kunci_jawaban_file[0]);
                        }
                        if (data.errors.kunci_jawaban_text) {
                            showAssignmentError('kunci_jawaban_text', data.errors.kunci_jawaban_text[0]);
                        }
                        if (data.errors.passing_grade) {
                            showAssignmentError('passing_grade', data.errors.passing_grade[0]);
                        }
                        
                        // Show general errors
                        if (data.message) {
                            showGeneralAssignmentErrors([data.message]);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Reset loading state
                    submitBtn.disabled = false;
                    submitText.textContent = 'Add Assignment';
                    submitSpinner.classList.add('hidden');
                    
                    showGeneralAssignmentErrors(['Terjadi kesalahan. Silakan coba lagi.']);
                });
            });
        }

        // Helper functions untuk error handling
        function hideAllAssignmentErrors() {
            const errorContainers = [
                'assignmentErrors',
                'judulError',
                'deskripsiError',
                'deadlineError',
                'kunci_jawaban_fileError',
                'kunci_jawaban_textError',
                'passing_gradeError'
            ];
            
            errorContainers.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.classList.add('hidden');
                    if (id === 'assignmentErrors') {
                        element.innerHTML = '<ul id="assignmentErrorList" class="list-disc list-inside text-sm"></ul>';
                    } else {
                        element.textContent = '';
                    }
                }
            });
        }

        function hideAssignmentSuccess() {
            const successElement = document.getElementById('assignmentSuccess');
            if (successElement) {
                successElement.classList.add('hidden');
            }
        }

        function showAssignmentError(field, message) {
            const errorElement = document.getElementById(field + 'Error');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            }
        }

        function showGeneralAssignmentErrors(messages) {
            const errorContainer = document.getElementById('assignmentErrors');
            const errorList = document.getElementById('assignmentErrorList');
            
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

        function showAssignmentSuccess(message) {
            const successElement = document.getElementById('assignmentSuccess');
            const successMessage = document.getElementById('assignmentSuccessMessage');
            
            if (successElement && successMessage) {
                successMessage.textContent = message;
                successElement.classList.remove('hidden');
            }
        }
    });
</script>
@endpush