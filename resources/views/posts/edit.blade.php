@extends('layouts.app')

@section('title', 'Edit Post - InstaApp')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Post</h2>
            <form method="POST" action="{{ route('posts.update', $post) }}">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea id="content" name="content" rows="5" class="w-full px-4 py-2 border rounded-lg @error('content') border-red-500 @enderror" required>{{ old('content', $post->content) }}</textarea>
                    @error('content') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image Preview</label>
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="w-full max-h-[70vh] object-contain rounded mb-2">
                    @else
                        <span class="text-gray-500">No image uploaded.</span>
                    @endif
                </div>
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">Update Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
