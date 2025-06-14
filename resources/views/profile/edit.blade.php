@extends('layouts.app')

@section('title', 'Edit Profile - InstaApp')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center">
                <a href="{{ route('profile.show') }}" class="mr-4 p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
                    <p class="text-gray-600">Update your profile information and settings</p>
                </div>
            </div>
        </div>

        <!-- Profile Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Profile Information</h2>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6" enctype="multipart/form-data">
                @csrf
                <!-- Avatar Section -->
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-2 border-purple-400">
                        @else
                            <div class="w-20 h-20 bg-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900">Profile Picture</h3>
                        <input type="file" name="avatar" accept="image/*" class="mt-2 block text-sm text-gray-500">
                        @error('avatar') <div class="text-danger">{{ $message }}</div> @enderror
                        @if($user->avatar)
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="remove_avatar" value="1" class="form-checkbox text-red-500">
                                    <span class="ml-2 text-sm text-red-600">Remove current avatar</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i>
                        Full Name
                    </label>
                    <input type="text" 
                           id="name"
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200 @error('name') border-red-500 @enderror" 
                           required>
                    @error('name') 
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        Email Address
                    </label>
                    <input type="email" 
                           id="email"
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200 @error('email') border-red-500 @enderror" 
                           required>
                    @error('email') 
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.show') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
            </div>
            <form method="POST" action="{{ route('profile.updatePassword') }}" class="p-6 space-y-6">
                @csrf
                <div>
                    <label for="old_password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-gray-400"></i>
                        Current Password
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="password" 
                           id="old_password"
                           name="old_password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200 @error('old_password') border-red-500 @enderror" 
                           required>
                    @error('old_password') 
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2 text-gray-400"></i>
                        New Password
                    </label>
                    <input type="password" 
                           id="password"
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200 @error('password') border-red-500 @enderror" required>
                    @error('password') 
                        <p class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2 text-gray-400"></i>
                        Confirm New Password
                    </label>
                    <input type="password" 
                           id="password_confirmation"
                           name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200" required>
                </div>
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="submit" 
                            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection