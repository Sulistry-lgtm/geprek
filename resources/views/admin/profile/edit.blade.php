@extends('layouts.app')

@section('title', 'Pengaturan Profil - BestieGeprek')

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div style="flex: 1; display: flex; flex-direction: column; gap: 32px;">
        
        <!-- Header -->
        <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 32px; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); display: flex; align-items: center; gap: 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 32px; font-weight: 800; overflow: hidden; border: 3px solid var(--primary-light);">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h2 style="font-size: 24px; margin-bottom: 4px;">Pengaturan Profil</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Kelola informasi pribadi akun Anda{{ $store ? ' dan data toko' : '' }}.</p>
            </div>
        </div>

        <!-- Akun Settings -->
        <div style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--gray-200); background-color: #faf8f5;">
                <h3 style="font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-user" style="color: var(--primary);"></i> Profil Akun
                </h3>
            </div>
            
            <form action="{{ route('admin.profile.updateAccount') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
                @csrf
                @method('PUT')

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="avatar" class="form-label">Foto Profil (Avatar)</label>
                    <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                    <small style="color: var(--gray-500); display: block; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah foto profil.</small>
                    @error('avatar')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="email" class="form-label">Alamat Email <span style="color: var(--gray-500); font-weight: normal;">(Tidak dapat diubah)</span></label>
                    <input type="email" id="email" class="form-control" value="{{ $user->email }}" disabled style="background-color: var(--gray-100); color: var(--gray-500); cursor: not-allowed;">
                </div>

                <hr style="border: 0; border-top: 1px dashed var(--gray-200); margin: 32px 0;">
                <h4 style="margin-bottom: 16px; font-size: 16px; color: var(--danger);"><i class="fa-solid fa-lock"></i> Ubah Password</h4>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Min. 8 karakter (huruf besar, kecil, angka, simbol)" autocomplete="new-password">
                    <small style="color: var(--gray-500); display: block; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah password.</small>
                    @error('password')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" style="margin-bottom: 32px;">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan Akun
                </button>
            </form>
        </div>

        <!-- Toko Settings (Only if User is an Admin and has a store) -->
        @if($store)
        <div style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); overflow: hidden;">
            <div style="padding: 24px; border-bottom: 1px solid var(--gray-200); background-color: #faf8f5;">
                <h3 style="font-size: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-store" style="color: var(--secondary);"></i> Profil Toko
                </h3>
            </div>
            
            <form action="{{ route('admin.profile.updateStore') }}" method="POST" enctype="multipart/form-data" style="padding: 24px;">
                @csrf
                @method('PUT')

                <!-- Store fields layout similar to create/edit -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="nama_toko" class="form-label">Nama Toko</label>
                            <input type="text" name="nama_toko" id="nama_toko" class="form-control" value="{{ old('nama_toko', $store->nama_toko) }}" required>
                            @error('nama_toko')<span class="form-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="whatsapp" class="form-label">Nomor WhatsApp</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ old('whatsapp', $store->whatsapp) }}" required>
                            @error('whatsapp')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="gambar" class="form-label">Foto Profil Toko</label>
                            <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                            @if($store->gambar)
                                <div style="margin-top: 12px;">
                                    <img src="{{ asset('storage/' . $store->gambar) }}" alt="Current Store Image" style="width: 120px; height: 120px; object-fit: cover; border-radius: var(--border-radius-sm); border: 2px solid var(--gray-200);">
                                </div>
                            @endif
                            <small style="color: var(--gray-500); display: block; margin-top: 6px;">Biarkan kosong jika tidak ingin mengubah foto toko.</small>
                            @error('gambar')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <div class="form-group" style="margin-bottom: 20px;">
                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="4" required>{{ old('alamat', $store->alamat) }}</textarea>
                            @error('alamat')<span class="form-error">{{ $message }}</span>@enderror
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $store->latitude) }}" required>
                                @error('latitude')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 20px;">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" value="{{ old('longitude', $store->longitude) }}" required>
                                @error('longitude')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 16px; border-top: 1px solid var(--gray-200); padding-top: 24px;">
                    <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan Toko
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection
