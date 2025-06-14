<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'InstaApp')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
    @php
        $showNavbar = Auth::check() && !in_array(Route::currentRouteName(), ['login', 'register']);
    @endphp
    @if ($showNavbar)
        @include('layouts.navbar')
    @endif

    <!-- Main Content -->
    <main class="{{ $showNavbar ? 'pt-16' : '' }}">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <ul class="mt-2">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // CSRF Token Setup for AJAX
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
        
        // // Setup AJAX headers
        // fetch('/sanctum/csrf-cookie').then(() => {
        //     // CSRF token is now set
        // });
    </script>
</body>
</html>