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
                    <textarea name="content" rows="3" 
                              class="w-full px-0 py-2 border-0 focus:outline-none focus:ring-0 resize-none text-gray-800 placeholder-gray-500" 
                              placeholder="What's on your mind?" required></textarea>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="cursor-pointer text-green-600 hover:text-green-700">
                            <i class="fas fa-image text-xl"></i>
                            <input type="file" name="image" accept="image/*" class="hidden">
                        </label>
                        <i class="fas fa-smile text-xl text-yellow-500"></i>
                        <i class="fas fa-map-marker-alt text-xl text-red-500"></i>
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
                    <div class="flex items-center space-x-2">
                        <button class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
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
                        <!-- Like Button -->
                        <button onclick="toggleLike({{ $post->id }})" 
                                class="flex items-center space-x-1 hover:text-gray-600 transition-colors">
                            <i class="fas fa-heart text-xl {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-700' }}" 
                               id="like-icon-{{ $post->id }}"></i>
                        </button>
                        
                        <!-- Comment Button -->
                        <button onclick="openCommentModal({{ $post->id }})" 
                                class="flex items-center space-x-1 text-gray-700 hover:text-gray-600 transition-colors">
                            <i class="fas fa-comment text-xl"></i>
                        </button>
                        
                        <!-- Share Button -->
                        <button class="flex items-center space-x-1 text-gray-700 hover:text-gray-600 transition-colors">
                            <i class="fas fa-paper-plane text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Bookmark -->
                    <button class="text-gray-700 hover:text-gray-600 transition-colors">
                        <i class="fas fa-bookmark text-xl"></i>
                    </button>
                </div>

                <!-- Like Count -->
                @if($post->likesCount() > 0)
                    <div class="mb-2">
                        <span class="font-semibold text-sm text-gray-900" id="like-count-{{ $post->id }}">
                            {{ $post->likesCount() }} {{ $post->likesCount() === 1 ? 'like' : 'likes' }}
                        </span>
                    </div>
                @endif

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
                        <input type="text" name="content" 
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

<!-- Comment Modal -->
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
            
            <!-- Add Comment Form in Modal -->
            <div class="border-t border-gray-200 p-4">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <span class="text-white font-bold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <form id="modalCommentForm" class="flex-1 flex">
                        <input type="text" id="modalCommentInput" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" 
                               placeholder="Add a comment..." required>
                        <button type="submit" class="ml-2 text-blue-500 hover:text-blue-600 font-medium text-sm px-3">
                            Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPostId = null;

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
            if (data.likes_count > 0) {
                likeCount.textContent = `${data.likes_count} ${data.likes_count === 1 ? 'like' : 'likes'}`;
            } else {
                likeCount.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

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
                
                commentElement.innerHTML = `
                    ${avatarHtml}
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-semibold text-sm text-gray-900">${comment.user.name}</span>
                            <span class="text-xs text-gray-500">${comment.created_at_human}</span>
                        </div>
                        <p class="text-sm text-gray-900">${comment.content}</p>
                    </div>
                `;
                
                modalContent.appendChild(commentElement);
            });
        })
        .catch(error => {
            console.error('Error loading comments:', error);
        });
}

// Handle modal comment form submission
document.getElementById('modalCommentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('modalCommentInput');
    const content = input.value.trim();
    
    if (!content || !currentPostId) return;
    
    fetch(`/posts/${currentPostId}/comments`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            loadComments(currentPostId);
        }
    })
    .catch(error => {
        console.error('Error posting comment:', error);
    });
});

// Close modal when clicking outside
document.getElementById('commentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCommentModal();
    }
});
</script>
@endsection