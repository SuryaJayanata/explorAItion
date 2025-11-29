<!-- View Material Modal -->
<div id="viewMaterialModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-2xl mx-4 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeViewMaterialModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="mb-6">
            <h2 id="viewMaterialTitle" class="text-2xl md:text-3xl font-bold text-shadow shadow-white/30 mb-2">Material Title</h2>
            <div class="flex items-center text-white/60 text-sm">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="viewMaterialDate">Posted on Jan 1, 2024</span>
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
            <p id="viewMaterialDescription" class="text-white/80 leading-relaxed bg-white/5 rounded-lg p-4">
                Loading description...
            </p>
        </div>

        <!-- File Attachment -->
        <div id="viewMaterialFile" class="hidden">
            <h3 class="text-lg font-semibold mb-3 flex items-center text-white">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Attached File
            </h3>
            <div class="bg-white/5 rounded-lg p-4">
                <!-- File content will be loaded here -->
            </div>
        </div>
    </div>
</div>