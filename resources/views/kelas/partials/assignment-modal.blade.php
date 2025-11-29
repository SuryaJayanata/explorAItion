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
        
        <form id="assignmentForm" action="{{ route('kelas.tugas.store', $kelas->id_kelas) }}" method="POST">
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