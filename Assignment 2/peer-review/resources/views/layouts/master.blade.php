<!DOCTYPE html>
<html>
<head>
    <!-- Dynamically injects the page title from child views -->
    <title>@yield('title')</title>
    
    <meta charset="utf-8"> <!-- Defines the character encoding -->
    
    <!-- Favicon icon for the website -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.png') }}">
    
    <!-- Bootstrap CSS for responsive and modern UI components -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Custom CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">

     <!-- CSRF token for form security -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Include Alpine.js for reactive UI components -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Google Fonts (Montserrat family) for typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <!-- Navbar with branding and navigation links -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex justify-content-between" id="header-menu">
            <!-- Logo and homepage link -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" id="menu-logo" alt="Logo">
            </a>
            
            <!-- Collapsible navigation menu -->
            <div class="d-flex flex-column header-menu">
                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        
                        <!-- Conditional links for logged-in users -->
                        @if (Auth::check())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                            </li>
                            <!-- Logout form -->
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link">Logout</button>
                                </form>
                            </li>
                        @else
                            <!-- Register and Login links for unauthenticated users -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endif
                    </ul>
                </div>
                
                <!-- Logged-in user's information -->
                @if (Auth::check())
                <div class="header-text text-end">
                    @php
                        $user = Auth::user();
                    @endphp
                    <p class="mb-0">
                        <strong>Logged In:</strong> {{ $user->first_name }} {{ $user->surname }} ({{ ucfirst($user->role) }})
                    </p>
                </div>
                @endif
            </div>
        </nav>
    </header>
    
    <!-- Optional banner section, can be dynamically injected from child views -->
    @yield('banner')

    <!-- Main content area, dynamically loaded from child views -->
    <div class="container-fluid p-0">
        <main class="w-100">
            @yield('content')
        </main>
    </div>
    
    <!-- Bootstrap JS and Popper.js for handling interactive UI components -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
