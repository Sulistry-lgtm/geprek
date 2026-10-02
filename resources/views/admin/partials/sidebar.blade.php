<aside class="admin-sidebar" style="border-radius: var(--border-radius-lg); margin-right: 24px; height: fit-content; box-shadow: var(--shadow-sm);">
    <h3 style="color: var(--white); border-bottom: 1px solid var(--dark-light); padding-bottom: 12px; font-size: 18px;">Navigasi Utama</h3>
    <ul class="admin-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="admin-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
        </li>
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <li>
            <a href="{{ route('admin.stores.index') }}" class="admin-menu-link {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                <i class="fa-solid fa-store"></i> Kelola Toko
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('admin.menus.index') }}" class="admin-menu-link {{ request()->routeIs('admin.menus.*') ? 'active' : '' }}">
                <i class="fa-solid fa-bowl-food"></i> Kelola Menu
            </a>
        </li>
        @if(auth()->check() && auth()->user()->isSuperAdmin())
        <li>
            <a href="{{ route('admin.users.index') }}" class="admin-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i> Kelola Pengguna
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('admin.profile.edit') }}" class="admin-menu-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i> Pengaturan Profil
            </a>
        </li>
    </ul>
</aside>
