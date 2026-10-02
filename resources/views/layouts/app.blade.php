<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BestieGeprek - Cari Toko Ayam Geprek Bengkalis')</title>
    <meta name="description" content="BestieGeprek adalah platform sistem informasi untuk menemukan referensi toko ayam geprek terbaik, terlezat, dan terpopuler di wilayah Bengkalis lengkap dengan daftar menu, ulasan, lokasi, dan kontak WhatsApp.">
    <!-- Font & CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="container navbar-container">
            <a href="{{ route('home') }}" class="logo" style="display: flex; align-items: center; text-decoration: none; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="BestieGeprek Logo" style="height: 48px; object-fit: contain;">
                Bestie<span>Geprek</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang</a></li>
                <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
                @auth
                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Dashboard Admin</a></li>
                    @endif
                @endauth
            </ul>

            <div class="nav-auth">
                @auth
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="display: flex; flex-direction: column; align-items: flex-end; text-align: right; line-height: 1.4;">
                            <span style="font-size: 14px; font-weight: 600; color: var(--dark); white-space: nowrap;">
                                Halo, <strong>{{ auth()->user()->name }}</strong>
                            </span>
                            <span class="badge-role role-{{ auth()->user()->role === 'super_admin' ? 'superadmin' : auth()->user()->role }}" style="font-size: 10px; padding: 2px 8px; border-radius: 20px; font-weight: 700; display: inline-block; width: fit-content; text-transform: uppercase; margin-top: 2px;">
                                {{ auth()->user()->role === 'super_admin' ? 'Super Admin' : (auth()->user()->role === 'admin' ? 'Admin' : 'User') }}
                            </span>
                        </div>
                        <a href="{{ auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() ? route('admin.profile.edit') : '#' }}" style="text-decoration: none;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 800; overflow: hidden; border: 2px solid var(--primary-light); margin-left: 4px;">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-outline-dark btn-sm" style="white-space: nowrap;">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    @if(Request::is('/') || Request::routeIs('home'))
        <main style="flex: 1; padding: 0;">
            @yield('content')
        </main>
    @else
        <main style="flex: 1; padding: 32px 0;">
            <div class="container">
                @yield('content')
            </div>
        </main>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-logo" style="margin-bottom: 16px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="BestieGeprek Logo" style="height: 40px; object-fit: contain;">
                Bestie<span>Geprek</span>
            </div>
            <p style="margin-bottom: 8px; font-weight: 500;">Temukan Kelezatan Ayam Geprek Terbaik & Terpopuler di Bengkalis! 🌶️🔥</p>
            <p>&copy; {{ date('Y') }} BestieGeprek. Dibuat dengan penuh rasa untuk para pencinta kuliner pedas.</p>
        </div>
    </footer>

    <!-- Toast Container -->
    <div class="toast-container" id="toast-container">
        @if(session('success'))
            <div class="custom-toast toast-success" id="toast-success">
                <i class="fa-solid fa-circle-check toast-icon"></i>
                <div class="toast-content">
                    <div class="toast-title">Sukses</div>
                    <div class="toast-message">{{ session('success') }}</div>
                </div>
                <button class="toast-close" onclick="closeToast('toast-success')"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"></div>
            </div>
        @endif

        @if(session('error'))
            <div class="custom-toast toast-error" id="toast-error">
                <i class="fa-solid fa-circle-xmark toast-icon"></i>
                <div class="toast-content">
                    <div class="toast-title">Error</div>
                    <div class="toast-message">{{ session('error') }}</div>
                </div>
                <button class="toast-close" onclick="closeToast('toast-error')"><i class="fa-solid fa-xmark"></i></button>
                <div class="toast-progress"></div>
            </div>
        @endif
    </div>

    <script>
        function closeToast(id) {
            var el = document.getElementById(id);
            if (el) {
                el.style.animation = 'slideOut 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                setTimeout(function() {
                    el.remove();
                }, 500);
            }
        }

        // Auto close toasts after 10 seconds
        document.addEventListener('DOMContentLoaded', function() {
            var toasts = document.querySelectorAll('.custom-toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    closeToast(toast.id);
                }, 10000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
