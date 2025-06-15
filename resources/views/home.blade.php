@extends('layouts.app')

@section('title', 'Home - InstaApp')

@section('content')
<div class="max-w-lg mx-auto px-4 py-4 bg-gray-50 min-h-screen">
    <!-- Create Post Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
        <div class="p-4">
            <div class="flex items-center space-x-3 mb-4">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                @endif
                <h3 class="text-lg font-medium text-gray-800">Create New Post</h3>
            </div>
            
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <textarea name="content" rows="3" id="postContent" 
                              class="w-full px-0 py-2 border-0 focus:outline-none focus:ring-0 resize-none text-gray-800 placeholder-gray-500" 
                              placeholder="What's on your mind?" required></textarea>
                </div>
                <div class="mb-4 relative" id="previewContainer" style="display:none;">
                    <img id="imagePreview" src="#" alt="Image Preview" class="w-full max-h-64 rounded-lg mb-2 object-cover" />
                    <button type="button" id="removeImageBtn" class="absolute top-2 left-2 bg-white bg-opacity-80 rounded-full p-1 shadow hover:bg-red-500 hover:text-white transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="cursor-pointer text-green-600 hover:text-green-700">
                            <i class="fas fa-image text-xl"></i>
                            <input type="file" name="image" accept="image/*" class="hidden" id="imageInput">
                        </label>
                    </div>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium text-sm transition duration-200">
                        Share
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Feed -->
    @forelse($posts as $post)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
            <!-- Post Header -->
            <div class="flex items-center justify-between p-4 pb-3">
                <div class="flex items-center space-x-3">
                    @if($post->user->avatar)
                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr($post->user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h4 class="font-semibold text-gray-900 text-sm">{{ $post->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                @if($post->user_id === Auth::id())
                    <div class="flex items-center space-x-2 relative">
                        <button class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="togglePostDropdown(this)">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-32 bg-white border rounded shadow z-20 dropdown-menu">
                            <a href="{{ route('posts.edit', $post->id) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Edit Post</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Yakin hapus postingan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Hapus Post</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Post Content -->
            @if($post->content)
                <div class="px-4 pb-2">
                    <p class="text-gray-900 text-sm leading-relaxed">{{ $post->content }}</p>
                </div>
            @endif

            <!-- Post Image -->
            @if($post->image)
                <div class="w-full">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" 
                         class="w-full h-auto object-cover">
                </div>
            @endif

            <!-- Post Actions -->
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-4">
                        <!-- Like Button + Count -->
                        <button onclick="toggleLike({{ $post->id }})"
                                class="flex items-center space-x-1 hover:text-gray-600 transition-colors">
                            <i class="fas fa-heart text-xl {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-700' }}"
                               id="like-icon-{{ $post->id }}"></i>
                            <span class="text-sm font-semibold text-gray-900" id="like-count-{{ $post->id }}">{{ $post->likesCount() }}</span>
                        </button>
                        <!-- Comment Button + Count -->
                        <button onclick="openCommentModal({{ $post->id }})"
                                class="flex items-center space-x-1 text-gray-700 hover:text-gray-600 transition-colors">
                            <i class="fas fa-comment text-xl"></i>
                            <span class="text-sm font-semibold text-gray-900">{{ $post->comments->count() }}</span>
                        </button>
                    </div>
                </div>

                <!-- Comments Preview -->
                @if($post->comments->count() > 0)
                    <div class="mb-3">
                        @if($post->comments->count() > 2)
                            <button onclick="openCommentModal({{ $post->id }})" class="text-gray-500 text-sm mb-2 hover:text-gray-700">
                                View all {{ $post->comments->count() }} comments
                            </button>
                        @endif
                        
                        <!-- Show last 2 comments -->
                        @foreach($post->comments->take(-2) as $comment)
                            <div class="flex items-start space-x-2 mb-1">
                                <span class="font-semibold text-sm text-gray-900">{{ $comment->user->name }}</span>
                                <span class="text-sm text-gray-900">{{ $comment->content }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Add Comment Form -->
                <div class="flex items-center space-x-3 pt-2 border-t border-gray-100">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="flex-1 flex">
                        @csrf
                        <input type="text" name="content" id="commentInput-{{ $post->id }}"
                               class="flex-1 px-0 py-2 border-0 focus:outline-none focus:ring-0 text-sm placeholder-gray-500" 
                               placeholder="Add a comment..." required>
                        <button type="submit" class="text-blue-500 hover:text-blue-600 font-medium text-sm">
                            Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-camera text-4xl text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">No posts yet</h3>
            <p class="text-gray-500">Be the first to share something!</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>

@include('posts.comment-modal')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewContainer = document.getElementById('previewContainer');
    const removeImageBtn = document.getElementById('removeImageBtn');
    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                previewContainer.style.display = 'none';
            }
        });
    }
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.src = '#';
            previewContainer.style.display = 'none';
        });
    }
});

function toggleLike(postId) {
    fetch(`/posts/${postId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const likeIcon = document.getElementById(`like-icon-${postId}`);
        const likeCount = document.getElementById(`like-count-${postId}`);
        if (data.liked) {
            likeIcon.classList.remove('text-gray-700');
            likeIcon.classList.add('text-red-500');
        } else {
            likeIcon.classList.remove('text-red-500');
            likeIcon.classList.add('text-gray-700');
        }
        if (likeCount) {
            likeCount.textContent = data.likes_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

let currentPostId = null;

function openCommentModal(postId) {
    currentPostId = postId;
    document.getElementById('commentModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load comments
    loadComments(postId);
}

function closeCommentModal() {
    document.getElementById('commentModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentPostId = null;
    location.reload(); // refresh halaman home setelah modal ditutup
}

function loadComments(postId) {
    fetch(`/posts/${postId}/comments`)
        .then(response => response.json())
        .then(data => {
            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = '';
            
            if (data.comments.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-comment text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">No comments yet</p>
                        <p class="text-gray-400 text-sm">Be the first to comment!</p>
                    </div>
                `;
                return;
            }
            
            data.comments.forEach(comment => {
                const commentElement = document.createElement('div');
                commentElement.className = 'flex items-start space-x-3 mb-4';
                const avatarHtml = comment.user.avatar 
                    ? `<img src="/storage/${comment.user.avatar}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">`
                    : `<div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                           <span class="text-white font-bold text-sm">${comment.user.name.charAt(0).toUpperCase()}</span>
                       </div>`;
                let actionHtml = '';
                if (comment.can_delete) {
                    actionHtml = `
                        <div class="relative ml-2">
                            <button class="text-gray-400 hover:text-gray-600 focus:outline-none" onclick="toggleDropdown(this)">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="hidden absolute right-0 mt-2 w-24 bg-white border rounded shadow z-10">
                                <button onclick="deleteComment(${comment.id})" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Hapus</button>
                            </div>
                        </div>
                    `;
                }
                commentElement.innerHTML = `
                    ${avatarHtml}
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-semibold text-sm text-gray-900">${comment.user.name}</span>
                            <span class="text-xs text-gray-500">${comment.created_at_human}</span>
                            ${actionHtml}
                        </div>
                        <p class="text-sm text-gray-900">${comment.content}</p>
                    </div>
                `;
                
                modalContent.appendChild(commentElement);
            });
            
            // Setelah menampilkan komentar, inject form komentar ke modal
            const modalAddComment = document.getElementById('modalAddComment');
            modalAddComment.innerHTML = `
                <form id="modalCommentForm" class="flex items-center space-x-2">
                    <input type="text" id="modalCommentInput" class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-200 text-sm" placeholder="Add a comment..." required>
                    <button type="submit" class="text-blue-500 hover:text-blue-600 font-medium text-sm">Post</button>
                </form>
            `;
            // Event submit form modal
            document.getElementById('modalCommentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const input = document.getElementById('modalCommentInput');
                const content = input.value.trim();
                if (!content || !currentPostId) return;
                fetch(`/posts/${currentPostId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        loadComments(currentPostId);
                    }
                    input.value = '';
                });
            });
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
}

// Handle modal comment form submission
// document.getElementById('modalCommentForm').addEventListener('submit', function(e) {
//     e.preventDefault();
    
//     const input = document.getElementById('modalCommentInput');
//     const content = input.value.trim();
    
//     if (!content || !currentPostId) return;
    
//     fetch(`/posts/${currentPostId}/comments`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'Accept': 'application/json', // tambahkan ini!
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//         },
//         body: JSON.stringify({ content: content })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             input.value = '';
//             loadComments(currentPostId);
//         }
//     })
//     .catch(error => {
//         console.error('Error posting comment:', error);
//     });
// });

function toggleDropdown(btn) {
    const dropdown = btn.nextElementSibling;
    if (dropdown) {
        dropdown.classList.toggle('hidden');
    }
}

function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) return;
    fetch(`/comments/${commentId}`, {
        method: 'POST', // spoofing DELETE
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadComments(currentPostId);
        }
    });
}

// Close modal when clicking outside
var commentModal = document.getElementById('commentModal');
if (commentModal) {
    commentModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCommentModal();
        }
    });
}

window.togglePostDropdown = function(btn) {
    const menu = btn.nextElementSibling;
    document.querySelectorAll('.dropdown-menu').forEach(el => {
        if (el !== menu) el.classList.add('hidden');
    });
    menu.classList.toggle('hidden');
    document.addEventListener('click', function handler(e) {
        if (!btn.parentNode.contains(e.target)) {
            menu.classList.add('hidden');
            document.removeEventListener('click', handler);
        }
    });
}
</script>
@endpush
@endsection