@extends('layouts.app')

@section('title', 'Tambah Toko Baru - BestieGeprek')

@section('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content Form -->
    <div class="admin-content" style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); padding: 24px;">
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Tambah Toko Ayam Geprek Baru</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Masukkan rincian informasi toko ayam geprek yang akan ditambahkan ke sistem.</p>
            </div>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.stores.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 700px;">
            @csrf

            <div class="form-group">
                <label for="user_id" class="form-label">Admin Penjual (Pemilik Toko) <span style="color:var(--danger)">*</span></label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="" disabled selected>Pilih Admin Penjual...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="nama_toko" class="form-label">Nama Toko Ayam Geprek <span style="color:var(--danger)">*</span></label>
                <input type="text" name="nama_toko" id="nama_toko" class="form-control" value="{{ old('nama_toko') }}" placeholder="Contoh: Ayam Geprek BestieGeprek Bengkalis" required>
                @error('nama_toko')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label">Alamat Lengkap Toko <span style="color:var(--danger)">*</span></label>
                <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Contoh: Jl. Ahmad Yani, Kelurahan Senggoro, Kecamatan Bengkalis" required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="whatsapp" class="form-label">Nomor WhatsApp Pemilik <span style="color:var(--danger)">*</span></label>
                <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="{{ old('whatsapp') }}" placeholder="Contoh: 081234567890 (Gunakan format 08 atau 62)" required>
                @error('whatsapp')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Hidden coordinates inputs -->
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}" required>
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}" required>

            <!-- Map Picker & Address Search -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span>Pilih Lokasi Toko di Peta <span style="color:var(--danger)">*</span></span>
                    <button type="button" id="btn-gps" class="btn btn-outline-dark btn-sm" style="padding: 6px 12px; font-size: 13px; border-color: var(--primary); color: var(--primary); background: transparent;">
                        <i class="fa-solid fa-location-crosshairs"></i> Gunakan GPS Saya
                    </button>
                </label>
                
                <div style="display: flex; gap: 8px; margin-bottom: 12px;">
                    <input type="text" id="map-search" class="form-control" style="font-size: 14px;" placeholder="Ketik nama jalan atau tempat di Bengkalis untuk mencari (misal: Senggoro)...">
                    <button type="button" id="btn-search" class="btn btn-primary btn-sm" style="padding: 8px 16px; white-space: nowrap;">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi
                    </button>
                </div>
                
                <div id="map" style="height: 320px; border-radius: var(--border-radius-md); border: 1px solid var(--gray-200); margin-bottom: 8px; z-index: 1;"></div>
                <span id="coord-info" style="font-size: 13px; color: var(--gray-500); font-family: monospace; display: block; margin-top: 4px;">
                    Koordinat Terpilih: Belum ditentukan. (Klik pada peta atau gunakan GPS untuk menetapkan lokasi)
                </span>
                @error('latitude')
                    <span class="form-error" style="display:block; margin-top: 4px;">{{ $message }}</span>
                @enderror
                @error('longitude')
                    <span class="form-error" style="display:block; margin-top: 4px;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="gambar" class="form-label">Cover Gambar Toko (Opsional)</label>
                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <small style="color: var(--gray-500); display: block; margin-top: 4px;">Format file: jpeg, png, jpg, webp. Ukuran maksimum: 2MB.</small>
                @error('gambar')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Toko
            </button>
        </form>
    </div>

</div>
@endsection

@section('scripts')
    <!-- Leaflet Map JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Default location Bengkalis
            var defaultLat = 1.482937;
            var defaultLng = 102.112836;
            
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');
            var coordInfo = document.getElementById('coord-info');
            
            var existingLat = parseFloat(latInput.value);
            var existingLng = parseFloat(lngInput.value);
            
            var startLat = !isNaN(existingLat) ? existingLat : defaultLat;
            var startLng = !isNaN(existingLng) ? existingLng : defaultLng;
            
            // Initialize map
            var map = L.map('map').setView([startLat, startLng], 14);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Initialize marker
            var marker = L.marker([startLat, startLng], { draggable: true }).addTo(map);
            
            function updateInputs(lat, lng) {
                latInput.value = parseFloat(lat).toFixed(6);
                lngInput.value = parseFloat(lng).toFixed(6);
                coordInfo.textContent = 'Koordinat Terpilih: ' + parseFloat(lat).toFixed(6) + ', ' + parseFloat(lng).toFixed(6);
            }
            
            // Set initial marker text if values exist
            if (!isNaN(existingLat) && !isNaN(existingLng)) {
                updateInputs(existingLat, existingLng);
            } else {
                updateInputs(defaultLat, defaultLng);
            }
            
            // Drag marker event
            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });
            
            // Click map event
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });
            
            // Geolocation / GPS
            var gpsBtn = document.getElementById('btn-gps');
            if (gpsBtn) {
                gpsBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (navigator.geolocation) {
                        gpsBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari Lokasi...';
                        gpsBtn.disabled = true;
                        
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var lat = position.coords.latitude;
                            var lng = position.coords.longitude;
                            
                            map.setView([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            updateInputs(lat, lng);
                            
                            gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Gunakan GPS Saya';
                            gpsBtn.disabled = false;
                        }, function(error) {
                            alert('Gagal mendapatkan lokasi GPS: ' + error.message);
                            gpsBtn.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i> Gunakan GPS Saya';
                            gpsBtn.disabled = false;
                        }, { enableHighAccuracy: true });
                    } else {
                        alert('Browser Anda tidak mendukung layanan GPS.');
                    }
                });
            }
            
            // Nominatim Address Search
            var searchBtn = document.getElementById('btn-search');
            var searchInput = document.getElementById('map-search');
            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var query = searchInput.value.trim();
                    if (!query) return;
                    
                    if (!query.toLowerCase().includes('bengkalis')) {
                        query += ' Bengkalis';
                    }
                    
                    searchBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
                    searchBtn.disabled = true;
                    
                    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                        .then(function(response) { return response.json(); })
                        .then(function(data) {
                            if (data && data.length > 0) {
                                var lat = parseFloat(data[0].lat);
                                var lng = parseFloat(data[0].lon);
                                map.setView([lat, lng], 16);
                                marker.setLatLng([lat, lng]);
                                updateInputs(lat, lng);
                            } else {
                                alert('Lokasi tidak ditemukan. Silakan masukkan nama jalan atau tempat yang lebih spesifik.');
                            }
                            searchBtn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi';
                            searchBtn.disabled = false;
                        })
                        .catch(function(error) {
                            alert('Terjadi kesalahan koneksi saat mencari lokasi.');
                            searchBtn.innerHTML = '<i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi';
                            searchBtn.disabled = false;
                        });
                });
                
                // Trigger search on Enter key
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchBtn.click();
                    }
                });
            }
            
            // Force leaflet map render fix
            setTimeout(function() {
                map.invalidateSize();
            }, 500);
        });
    </script>
@endsection
