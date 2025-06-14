<!-- resources/views/posts/comment-modal.blade.php -->
<div id="commentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg w-full max-w-md max-h-[80vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <!-- Modal Content -->
            <div id="modalContent" class="flex-1 overflow-y-auto p-4">
                <!-- Comments will be loaded here -->
            </div>
            <!-- Add Comment Form (akan diisi JS) -->
            <div id="modalAddComment" class="p-4 border-t border-gray-200">
                <!-- Add comment form will be injected here by JS -->
            </div>
        </div>
    </div>
</div>
