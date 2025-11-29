<!-- View Assignment Modal -->
<div id="viewAssignmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-2xl mx-4 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeViewAssignmentModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="mb-6">
            <h2 id="viewAssignmentTitle" class="text-2xl md:text-3xl font-bold text-shadow shadow-white/30 mb-2">Assignment Title</h2>
            <div class="flex items-center text-white/60 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="viewAssignmentDeadlineInfo">Due information</span>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3 flex items-center text-white">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Description
            </h3>
            <p id="viewAssignmentDescription" class="text-white/80 leading-relaxed bg-white/5 rounded-lg p-4 whitespace-pre-line">
                Loading description...
            </p>
        </div>

        <!-- Deadline -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3 flex items-center text-white">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Deadline
            </h3>
            <div class="bg-white/5 rounded-lg p-4">
                <p id="viewAssignmentDeadline" class="text-white/80">Loading deadline...</p>
                <p id="viewAssignmentDeadlineInfo" class="text-white/60 text-sm mt-1"></p>
            </div>
        </div>

        <!-- Student Submission Section -->
        @if(Auth::user()->isSiswa())
        <div class="bg-white/5 rounded-lg p-4">
            <h3 class="text-lg font-semibold mb-3 flex items-center text-white">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Submit Your Work
            </h3>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="file_jawaban" 
                           class="w-full text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/80"
                           required>
                    <p class="text-xs text-white/60 mt-1">Supported formats: PDF, DOC, DOCX, PPT, PPTX, TXT. Max: 10MB</p>
                </div>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                    Submit Assignment
                </button>
            </form>
        </div>
        @endif
    </div>
</div>