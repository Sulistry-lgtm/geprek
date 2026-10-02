@extends('layouts.app')

@section('title', 'Daftar Akun - BestieGeprek')

@section('content')
    <div class="form-card">
        <h2 class="form-title">Daftar Akun Pengguna</h2>
        
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="user">
            
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" required autocomplete="name" autofocus>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@gmail.com" required autocomplete="email">
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>


            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter (Huruf besar, kecil, angka & simbol)" required autocomplete="off" style="padding-right: 45px;">
                    <button type="button" onclick="togglePassword('password', 'toggle-icon-1')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 4px 8px; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="toggle-icon-1"></i>
                    </button>
                </div>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="Ulangi password" required autocomplete="off" style="padding-right: 45px;">
                    <button type="button" onclick="togglePassword('password-confirm', 'toggle-icon-2')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500); padding: 4px 8px; display: flex; align-items: center;">
                        <i class="fa-solid fa-eye" id="toggle-icon-2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 12px;">
                <i class="fa-solid fa-user-plus" style="margin-right: 6px;"></i> Registrasi Akun
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
                Daftar dengan Google
            </a>
        </form>
        
        <div class="form-footer" style="display: flex; flex-direction: column; gap: 8px;">
            <div>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
            <div style="font-size: 13px; border-top: 1px dashed var(--gray-200); padding-top: 12px; margin-top: 8px;">
                Ingin membuka toko ayam geprek sendiri? <a href="{{ route('register-toko') }}" style="font-weight: 700;">Daftar sebagai Penjual/Toko</a>
            </div>
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
