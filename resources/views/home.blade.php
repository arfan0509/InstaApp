@extends('layouts.app')

@section('title', 'Home - InstaApp')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <!-- Create Post Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Create New Post</h3>
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <textarea name="content" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none" 
                          placeholder="What's on your mind?" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-image mr-1"></i> Add Image (Optional)
                </label>
                <input type="file" name="image" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Post
                </button>
            </div>
        </form>
    </div>

    <!-- Posts Feed -->
    @forelse($posts as $post)
        <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
            <!-- Post Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($post->user->avatar)
                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-purple-400">
                        @else
                            <span class="inline-flex items-center justify-center w-10 h-10 bg-purple-500 text-white rounded-full text-lg font-bold">
                                {{ strtoupper(substr($post->user->name, 0, 1)) }}
                            </span>
                        @endif
                        <div class="ml-3">
                            <h4 class="font-semibold text-gray-800">{{ $post->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($post->user_id === Auth::id())
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('posts.edit', $post) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Post Content -->
            <div class="p-4">
                <p class="text-gray-800 mb-3">{{ $post->content }}</p>
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="Post image" 
                         class="w-full h-auto rounded-lg mb-3">
                @endif
            </div>

            <!-- Post Actions -->
            <div class="px-4 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-4">
                        <!-- Like Button -->
                        <button onclick="toggleLike({{ $post->id }})" 
                                class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                            <i class="fas fa-heart {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : '' }}" 
                               id="like-icon-{{ $post->id }}"></i>
                            <span id="like-count-{{ $post->id }}">{{ $post->likesCount() }}</span>
                        </button>
                        <!-- Comment Count -->
                        <span class="flex items-center space-x-1 text-gray-600">
                            <i class="fas fa-comment"></i>
                            <span>{{ $post->commentsCount() }}</span>
                        </span>
                    </div>
                </div>

                <!-- Comments Section -->
                @if($post->comments->count() > 0)
                    <div class="space-y-2 mb-4">
                        @foreach($post->comments as $comment)
                            <div class="flex items-start space-x-2 bg-gray-50 rounded-lg p-2">
                                @if($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border-2 border-purple-400">
                                @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-500 text-white rounded-full text-base font-bold">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </span>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-medium text-sm text-gray-800">{{ $comment->user->name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if($comment->user_id === Auth::id() || $post->user_id === Auth::id())
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Add Comment Form -->
                <form action="{{ route('comments.store', $post) }}" method="POST" class="flex space-x-2">
                    @csrf
                    <input type="text" name="content" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                           placeholder="Write a comment..." required>
                    <button type="submit" 
                            class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
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

<script>
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
            likeIcon.classList.add('text-red-500');
        } else {
            likeIcon.classList.remove('text-red-500');
        }
        
        likeCount.textContent = data.likes_count;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection