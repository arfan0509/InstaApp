@extends('layouts.app')

@section('title', 'Profile - InstaApp')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-blue-600 h-32"></div>
            
            <div class="px-6 pb-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center -mt-16 sm:-mt-12">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 mb-4 sm:mb-0">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-purple-600 text-3xl font-bold shadow-lg border-4 border-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <!-- User Info -->
                    <div class="sm:ml-6 flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                                <p class="text-gray-600 mt-1">{{ $user->email }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Joined {{ $user->created_at->format('F Y') }}
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-0">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Posts</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $posts->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Likes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $posts->sum(function($post) { return $post->likes->count(); }) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-comments text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Comments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $posts->sum(function($post) { return $post->comments->count(); }) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Your Posts</h2>
                    <span class="text-sm text-gray-500">{{ $posts->count() }} posts</span>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($posts as $post)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-alt text-purple-600"></i>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900 truncate">{{ $post->title }}</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                                        <span class="flex items-center">
                                            <i class="fas fa-heart mr-1"></i>
                                            {{ $post->likes->count() }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-comment mr-1"></i>
                                            {{ $post->comments->count() }}
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="mt-2 text-gray-600 line-clamp-3">{{ Str::limit($post->content, 200) }}</p>
                                
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $post->created_at->diffForHumans() }}
                                    </span>
                                    
                                    <div class="flex items-center space-x-2">
                                        <a href="#" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                            View
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this post?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                        <p class="text-gray-500 mb-4">You haven't created any posts yet. Start sharing your thoughts!</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Post
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection