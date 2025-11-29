<!-- Comments/Discussion Section -->
<div class="bg-gradient-to-br from-gray-700 to-purple-900 rounded-2xl border border-white/30 p-8 shadow-lg shadow-primary/30">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            @if(isset($materi))
                Material Discussion
            @else
                Assignment Discussion
            @endif
        </h2>
        <div class="text-white/60 text-sm">
            <span id="commentsCount">
                @if(isset($materi))
                    {{ $materi->komentar->where('parent_id', null)->count() }}
                @else
                    {{ $tugas->komentar->where('parent_id', null)->count() }}
                @endif
            </span> comments
        </div>
    </div>

    <!-- Main Comment Form -->
    <div class="bg-white/5 rounded-xl p-6 border border-white/10 mb-6">
        <form id="mainCommentForm" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="">
            <div class="mb-4">
                <label for="main_comment" class="block text-white text-sm font-medium mb-2">Add your comment</label>
                <textarea id="main_comment" name="isi" rows="3"
                          class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white outline-none focus:border-primary resize-none"
                          placeholder="Share your thoughts, ask questions, or discuss..."
                          required maxlength="500"></textarea>
                @error('isi')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div id="mainCharCounter" class="text-white/40 text-xs text-right mt-1">0/500</div>
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-primary hover:bg-purple-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Post Comment
                </button>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-6" id="commentsContainer">
        @if(isset($materi))
            @foreach($materi->komentar->where('parent_id', null)->sortByDesc('created_at') as $komentar)
                @include('materi.partials.comment', ['komentar' => $komentar, 'level' => 0])
            @endforeach
        @else
            @foreach($tugas->komentar->where('parent_id', null)->sortByDesc('created_at') as $komentar)
                @include('tugas.partials.comment', ['komentar' => $komentar, 'level' => 0])
            @endforeach
        @endif
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-12 @if((isset($materi) && $materi->komentar->where('parent_id', null)->count() > 0) || (isset($tugas) && $tugas->komentar->where('parent_id', null)->count() > 0)) hidden @endif">
        <svg class="w-16 h-16 mx-auto text-white/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <p class="text-white/60 text-lg mb-2">No comments yet</p>
        <p class="text-white/40">Be the first to start the discussion!</p>
    </div>
</div>

@push('scripts')
<script>
    // ==================== GLOBAL VARIABLES ====================
    let currentReplyingTo = '';
    let currentReplyForm = null;
    
    // Set form action based on context
    document.addEventListener('DOMContentLoaded', function() {
        const mainForm = document.getElementById('mainCommentForm');
        @if(isset($materi))
            mainForm.action = "{{ route('kelas.materi.komentar.store', [$kelas->id_kelas, $materi->id_materi]) }}";
        @else
            mainForm.action = "{{ route('kelas.tugas.komentar.store', [$kelas->id_kelas, $tugas->id_tugas]) }}";
        @endif

        // Character counter for main comment textarea
        const mainTextarea = document.getElementById('main_comment');
        const mainCharCounter = document.getElementById('mainCharCounter');
        
        if (mainTextarea && mainCharCounter) {
            mainTextarea.addEventListener('input', function() {
                const length = this.value.length;
                mainCharCounter.textContent = `${length}/500`;
                
                if (length > 500) {
                    mainCharCounter.classList.add('text-red-400');
                } else {
                    mainCharCounter.classList.remove('text-red-400');
                }
            });
        }
    });

    // ==================== COMMENT FUNCTIONS ====================

    // Reply to comment function
    function replyToComment(commentId, userName) {
        // Close any existing reply form
        if (currentReplyForm) {
            currentReplyForm.remove();
        }
        
        currentReplyingTo = userName;
        
        // Find the comment element
        const commentElement = document.getElementById(`comment-${commentId}`);
        if (!commentElement) return;
        
        // Find or create replies container
        let repliesContainer = document.getElementById(`replies-${commentId}`);
        if (!repliesContainer) {
            repliesContainer = document.createElement('div');
            repliesContainer.id = `replies-${commentId}`;
            repliesContainer.className = 'mt-3 space-y-3 border-t border-white/10 pt-3';
            commentElement.appendChild(repliesContainer);
        }
        
        // Create reply form
        const replyFormHTML = `
            <div class="reply-form bg-white/5 rounded-lg p-4 border border-primary/30 animate-fade-in">
                <form class="reply-comment-form" data-parent-id="${commentId}">
                    @csrf
                    <input type="hidden" name="parent_id" value="${commentId}">
                    <div class="mb-3">
                        <label class="block text-white text-sm font-medium mb-2">
                            Replying to <span class="text-primary">${userName}</span>
                        </label>
                        <textarea name="isi" rows="2"
                                  class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white outline-none focus:border-primary resize-none text-sm"
                                  placeholder="Type your reply..."
                                  required maxlength="500"></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <div class="text-white/40 text-xs">
                                <span class="char-counter">0/500</span>
                            </div>
                            <div class="flex space-x-2">
                                <button type="button" onclick="cancelReply()" 
                                        class="px-3 py-1 text-white/60 hover:text-white text-sm transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-1 bg-primary hover:bg-purple-600 text-white text-sm rounded transition-colors">
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        `;
        
        repliesContainer.insertAdjacentHTML('afterbegin', replyFormHTML);
        currentReplyForm = repliesContainer.querySelector('.reply-form');
        
        // Focus textarea
        const textarea = currentReplyForm.querySelector('textarea');
        textarea.focus();
        
        // Character counter for reply textarea
        const charCounter = currentReplyForm.querySelector('.char-counter');
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCounter.textContent = `${length}/500`;
            
            if (length > 500) {
                charCounter.classList.add('text-red-400');
            } else {
                charCounter.classList.remove('text-red-400');
            }
        });
        
        // Show replies container if hidden
        if (repliesContainer.classList.contains('hidden')) {
            repliesContainer.classList.remove('hidden');
        }
        
        // Scroll to reply form
        currentReplyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Cancel reply function
    function cancelReply() {
        if (currentReplyForm) {
            currentReplyForm.remove();
            currentReplyForm = null;
            currentReplyingTo = '';
        }
    }

    // Toggle replies visibility
    function toggleReplies(commentId) {
        const repliesContainer = document.getElementById(`replies-${commentId}`);
        const toggleButton = document.querySelector(`[onclick="toggleReplies(${commentId})"]`);
        
        if (!repliesContainer) return;
        
        if (repliesContainer.classList.contains('hidden')) {
            repliesContainer.classList.remove('hidden');
            toggleButton.querySelector('.replies-count').textContent = 'Hide replies';
        } else {
            repliesContainer.classList.add('hidden');
            const replyCount = repliesContainer.querySelectorAll('.comment').length;
            toggleButton.querySelector('.replies-count').textContent = `${replyCount} replies`;
        }
    }

    // ==================== AJAX COMMENT SUBMISSION ====================

    // Main comment form submission
    document.getElementById('mainCommentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        submitCommentForm(this, false);
    });

    // Event delegation for reply forms
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('reply-comment-form')) {
            e.preventDefault();
            submitCommentForm(e.target, true);
        }
    });

    // Generic function to submit comment forms
    function submitCommentForm(form, isReply = false) {
        const formData = new FormData(form);
        const submitButton = form.querySelector('button[type="submit"]');
        const originalContent = submitButton.innerHTML;
        
        // Disable button and show loading
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${isReply ? 'Replying...' : 'Posting...'}
        `;
        
        // Determine the correct action URL
        let actionUrl;
        @if(isset($materi))
            actionUrl = "{{ route('kelas.materi.komentar.store', [$kelas->id_kelas, $materi->id_materi]) }}";
        @else
            actionUrl = "{{ route('kelas.tugas.komentar.store', [$kelas->id_kelas, $tugas->id_tugas]) }}";
        @endif
        
        fetch(actionUrl, {
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
                showToast(data.message, 'success');
                
                // Add comment to DOM
                addCommentToDOM(data.komentar, data.is_reply, data.parent_id);
                
                // Reset form
                if (isReply) {
                    // Remove reply form
                    if (currentReplyForm) {
                        currentReplyForm.remove();
                        currentReplyForm = null;
                        currentReplyingTo = '';
                    }
                } else {
                    // Reset main form
                    form.reset();
                    document.getElementById('mainCharCounter').textContent = '0/500';
                }
                
                // Update comments count
                updateCommentsCount(1);
                
                // Hide empty state if it exists
                const emptyState = document.getElementById('emptyState');
                if (emptyState) {
                    emptyState.classList.add('hidden');
                }
            } else {
                throw new Error(data.message || 'Failed to post comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to post comment. Please try again.', 'error');
        })
        .finally(() => {
            // Re-enable button
            submitButton.disabled = false;
            submitButton.innerHTML = originalContent;
        });
    }

    // Function to add comment to DOM
    function addCommentToDOM(komentar, isReply = false, parentId = null) {
        if (isReply && parentId) {
            // Add reply to existing comment
            const repliesContainer = document.getElementById(`replies-${parentId}`);
            if (repliesContainer) {
                const replyHTML = createCommentHTML(komentar, 1);
                repliesContainer.insertAdjacentHTML('afterbegin', replyHTML);
                
                // Update replies count
                const toggleButton = document.querySelector(`[onclick="toggleReplies(${parentId})"]`);
                if (toggleButton) {
                    const currentCount = parseInt(toggleButton.querySelector('.replies-count').textContent) || 0;
                    toggleButton.querySelector('.replies-count').textContent = `${currentCount + 1} replies`;
                }
                
                // Show replies container if hidden
                if (repliesContainer.classList.contains('hidden')) {
                    repliesContainer.classList.remove('hidden');
                }
                
                // Add animation to new reply
                const newReply = repliesContainer.firstElementChild;
                newReply.style.animation = 'fadeInUp 0.5s ease-out';
            }
        } else {
            // Add new comment to top of list
            const commentsContainer = document.getElementById('commentsContainer');
            const commentHTML = createCommentHTML(komentar, 0);
            commentsContainer.insertAdjacentHTML('afterbegin', commentHTML);
            
            // Add animation to new comment
            const newComment = commentsContainer.firstElementChild;
            newComment.style.animation = 'fadeInUp 0.5s ease-out';
        }
    }

    // Function to create comment HTML
    function createCommentHTML(komentar, level = 0) {
        const user = komentar.user;
        const marginClass = level > 0 ? 'ml-6' : '';
        const isGuru = user.is_guru;
        
        return `
        <div class="comment bg-white/5 rounded-lg p-4 border border-white/10 ${marginClass}" 
             id="comment-${komentar.id_komentar}">
            
            <!-- Comment Header -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center space-x-2">
                    ${user.avatar ? 
                        `<img src="${user.avatar}" class="w-8 h-8 rounded-full border border-white/20">` :
                        `<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-sm font-bold">
                            ${user.initial}
                         </div>`
                    }
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="text-white font-medium text-sm">${user.nama}</span>
                            ${isGuru ? 
                                `<span class="bg-primary/20 text-primary text-xs px-2 py-0.5 rounded">Guru</span>` : ''
                            }
                        </div>
                        <span class="text-white/50 text-xs">${komentar.created_at}</span>
                    </div>
                </div>
                
                @if(Auth::check())
                <form action="${getDeleteRoute(komentar.id_komentar)}" 
                      method="POST" class="delete-comment-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-white/40 hover:text-red-400 text-sm">Hapus</button>
                </form>
                @endif
            </div>

            <!-- Comment Content -->
            <div class="text-white/80 text-sm mb-3">
                ${komentar.parent_id ? 
                    `<div class="text-primary text-xs mb-1">
                        ↳ Replies for ${currentReplyingTo}
                    </div>` : ''
                }
                ${escapeHtml(komentar.isi)}
            </div>

            <!-- Comment Actions -->
            <div class="flex items-center space-x-4 text-white/60 text-xs">
                <button onclick="replyToComment(${komentar.id_komentar}, '${user.nama}')" 
                        class="hover:text-primary transition-colors">
                    Reply
                </button>

                ${komentar.has_replies ? `
                <button onclick="toggleReplies(${komentar.id_komentar})" 
                        class="hover:text-white transition-colors">
                    <span class="replies-count">${komentar.replies_count} replies</span>
                </button>
                ` : ''}
            </div>

            <!-- Replies Container -->
            ${komentar.has_replies ? `
            <div id="replies-${komentar.id_komentar}" class="mt-3 space-y-3 border-t border-white/10 pt-3 ${level > 0 ? '' : 'hidden'}">
                <!-- Replies will be loaded here -->
            </div>
            ` : ''}
        </div>
        `;
    }

    // Helper function to get delete route
    function getDeleteRoute(commentId) {
        @if(isset($materi))
            return "{{ route('kelas.materi.komentar.destroy', [$kelas->id_kelas, $materi->id_materi, 'COMMENT_ID']) }}".replace('COMMENT_ID', commentId);
        @else
            return "{{ route('kelas.tugas.komentar.destroy', [$kelas->id_kelas, $tugas->id_tugas, 'COMMENT_ID']) }}".replace('COMMENT_ID', commentId);
        @endif
    }

    // Helper function to escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // ==================== AJAX COMMENT DELETION ====================

    // Event delegation for delete forms
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('delete-comment-form')) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }
            
            const form = e.target;
            const commentElement = form.closest('.comment');
            const commentId = commentElement.id.replace('comment-', '');
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Comment deleted successfully!', 'success');
                    
                    // Remove comment with animation
                    commentElement.style.opacity = '0';
                    commentElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        commentElement.remove();
                        
                        // Update comments count
                        updateCommentsCount(-1);
                        
                        // Show empty state if no comments left
                        const commentsContainer = document.getElementById('commentsContainer');
                        if (commentsContainer.children.length === 0) {
                            const emptyState = document.getElementById('emptyState');
                            if (emptyState) {
                                emptyState.classList.remove('hidden');
                            }
                        }
                    }, 300);
                } else {
                    throw new Error('Failed to delete comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to delete comment. Please try again.', 'error');
            });
        }
    });

    // ==================== UTILITY FUNCTIONS ====================

    // Update comments count
    function updateCommentsCount(change) {
        const countElement = document.getElementById('commentsCount');
        if (countElement) {
            const currentCount = parseInt(countElement.textContent) || 0;
            countElement.textContent = Math.max(0, currentCount + change);
        }
    }

    // Toast notification function
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
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.remove();
        }, 5000);
        
        // Click to dismiss
        toast.addEventListener('click', () => {
            toast.remove();
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
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
    .comment {
        transition: all 0.3s ease;
    }
    
    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }
    
    /* Animation for new comments */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
    
    .comment:first-child {
        animation: fadeInUp 0.5s ease-out;
    }
</style>
@endpush