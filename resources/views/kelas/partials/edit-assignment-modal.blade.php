<!-- Edit Assignment Modal -->
<div id="editAssignmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-gradient-to-b from-gray-700 to-purple-900 rounded-2xl border border-white/30 backdrop-blur-md p-6 md:p-8 w-full max-w-2xl mx-4 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeEditAssignmentModal()" class="absolute top-4 right-4 text-white hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div id="editAssignmentModalContent">
            <!-- Content will be loaded via AJAX -->
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"></div>
                <p class="text-white/60 mt-4">Loading edit form...</p>
            </div>
        </div>
    </div>
</div>