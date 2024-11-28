<!DOCTYPE html>
<html>
<head>
    <!-- Dynamically injects the page title -->
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <!-- FavIcon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- JS and CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <!-- Google Fonts - Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <!-- Navbar with branding and navigation links -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light" id="header-menu">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/Star Agents - Logo.png') }}" id="menu-logo" alt="Logo">
            </a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('add-agency') }}">Agencies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('add-agent') }}">New Agent</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    
    <!-- Full Width Banner Section -->
    @yield('banner')

    <!-- Main content section -->
    <main class="container">
        @yield('content')
    </main>
    
    <!-- Bootstrap JS and Popper.js for dynamic UI components -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
