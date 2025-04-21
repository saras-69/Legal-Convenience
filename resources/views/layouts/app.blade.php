<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Convenience - @yield('title', 'Home')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        footer {
            margin-top: auto;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('welcome') }}">Legal Convenience</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Only show Find Services for citizens and non-logged-in users -->
                    @if(!Auth::check() || Auth::user()->role === 'citizen')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('services.search') }}">Find Services</a>
                        </li>
                    @endif
                    
                    @auth
                        @if(Auth::user()->role === 'citizen')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('citizen.bookings') }}">My Bookings</a>
                            </li>
                            <!-- Only show Rewards for citizens -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('rewards.index') }}">Rewards</a>
                            </li>
                        @elseif(Auth::user()->role === 'lsp')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('lsp.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('lsp.services.index') }}">My Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('lsp.bookings') }}">Bookings</a>
                            </li>
                        @elseif(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                            </li>
                            <!-- Additional admin navigation items -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users') }}">Manage Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.verifications') }}">Verifications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.services') }}">Services</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.bookings') }}">Bookings</a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                @if(Auth::user()->role === 'lsp')
                                    <li><a class="dropdown-item" href="{{ route('lsp.profile.edit') }}">Edit LSP Profile</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Legal Convenience</h5>
                    <p>Connecting citizens with legal service providers across India.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('welcome') }}">Home</a></li>
                        <!-- Hide Find Services in footer for admin and LSP users too -->
                        @if(!Auth::check() || Auth::user()->role === 'citizen')
                            <li><a href="{{ route('services.search') }}">Find Services</a></li>
                        @endif
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <strong>Legal Convenience</strong><br>
                        LPU<br>
                        Jalandhar Punjab, India<br>
                        <i class="fas fa-envelope"></i> saketsingh102003@gamils.com<br>
                        <i class="fas fa-phone"></i> +91 8905948930
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Legal Convenience. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>