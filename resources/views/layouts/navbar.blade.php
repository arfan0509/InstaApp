<!-- Navigation -->
<nav class="bg-white border-b border-gray-200 fixed w-full top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-purple-600 hover:text-purple-700">
                    InstaApp
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-700 hover:text-purple-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Mobile Menu Dropdown -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-4 top-16 bg-white rounded-lg shadow-lg border border-gray-200 py-2 w-48">
                        <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 {{ request()->routeIs('home') ? 'bg-purple-50 text-purple-600' : '' }}">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-purple-50 hover:text-purple-600 {{ request()->routeIs('profile.show') ? 'bg-purple-50 text-purple-600' : '' }}">
                            <i class="fas fa-user mr-2"></i>
                            Profile
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Desktop User Avatar with Dropdown -->
                <div class="hidden md:block relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center focus:outline-none">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-semibold hover:bg-purple-600 transition-colors duration-200">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>
                    
                    <!-- Desktop Profile Dropdown -->
                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 top-12 bg-white rounded-lg shadow-lg border border-gray-200 py-2 w-56 z-50">
                        
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                <i class="fas fa-user mr-3 text-gray-400"></i>
                                Profile
                            </a>
                            
                            @if(Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600">
                                <i class="fas fa-cog mr-3 text-gray-400"></i>
                                Settings
                            </a>
                            @endif
                            
                          
                        </div>
                        
                        <hr class="my-1">
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-3 text-red-400"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>