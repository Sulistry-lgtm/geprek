@extends('layouts.app')

@section('title', 'Login - BestieGeprek')

@section('content')
    <div class="form-card">
        <h2 class="form-title">Masuk ke BestieGeprek</h2>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@gmail.com" required autocomplete="email" autofocus>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password Anda" required autocomplete="current-password" style="padding-right: 45px;">
                    <button type="button" onclick="togglePassword('password', 'toggle-icon')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 4px 8px; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: -8px; margin-bottom: 24px;">
                <input type="checkbox" name="remember" id="remember" style="width: 16px; height: 16px; cursor: pointer;">
                <label for="remember" style="font-size: 14px; color: var(--gray-700); cursor: pointer; user-select: none;">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;">
                <i class="fa-solid fa-right-to-bracket" style="margin-right: 6px;"></i> Masuk
            </button>
            
            <div style="text-align: center; margin-top: 16px; margin-bottom: 16px;">
                <span style="color: var(--gray-500); font-size: 14px; position: relative; display: inline-block;">
                    <span style="display: inline-block; width: 40px; height: 1px; background-color: var(--gray-200); position: absolute; left: -50px; top: 50%;"></span>
                    atau
                    <span style="display: inline-block; width: 40px; height: 1px; background-color: var(--gray-200); position: absolute; right: -50px; top: 50%;"></span>
                </span>
            </div>

            <a href="{{ route('auth.google') }}" class="btn btn-secondary" style="width: 100%; padding: 12px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: 1px solid var(--gray-200); background-color: var(--white); color: var(--dark); border-radius: var(--border-radius-md);">
                <img src="https://developers.google.com/static/identity/images/g-logo.png" alt="Google Logo" style="height: 18px; object-fit: contain;">
                Masuk dengan Google
            </a>
        </form>
        
        <div class="form-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePassword(inputId, iconId) {
            var input = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (input && icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    </script>
@endsection
