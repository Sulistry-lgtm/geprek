@extends('layouts.app')

@section('title', $store->nama_toko . ' - BestieGeprek')

@section('styles')
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('content')
    <!-- Store Detail Header Card -->
    <div class="detail-header">
        <div class="detail-cover">
            @if($store->gambar)
                <img src="{{ asset('storage/' . $store->gambar) }}" alt="{{ $store->nama_toko }}">
            @else
                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #2d1610 0%, #e04a22 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-store" style="font-size: 100px; color: rgba(255,255,255,0.15);"></i>
                </div>
            @endif
        </div>
        
        <div class="detail-main">
            <div>
                <span class="badge-role role-admin" style="font-size: 12px; margin-bottom: 8px; display: inline-block;">Ayam Geprek Bengkalis</span>
                <h1 class="detail-title">{{ $store->nama_toko }}</h1>
                
                <div class="store-rating-row" style="font-size: 16px;">
                    <i class="fa-solid fa-star" style="font-size: 18px;"></i>
                    <strong style="color: var(--secondary); font-size: 18px;">{{ $averageRating }}</strong>
                    <span style="color: var(--gray-500); font-weight: normal;">({{ $totalReviews }} Ulasan Pengguna)</span>
                </div>

                <div class="detail-meta">
                    <div class="detail-meta-item">
                        <i class="fa-solid fa-location-dot meta-icon"></i>
                        <div>
                            <strong>Alamat:</strong>
                            <p style="font-size: 15px; margin-top: 2px;">{{ $store->alamat }}</p>
                        </div>
                    </div>
                    
                    <div class="detail-meta-item">
                        <i class="fa-solid fa-map-location-dot meta-icon"></i>
                        <div>
                            <strong>Koordinat:</strong>
                            <p style="font-size: 14px; font-family: monospace; margin-top: 2px; color: var(--gray-500);">
                                Lat: {{ $store->latitude }}, Lng: {{ $store->longitude }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WhatsApp Masking Box (RBAC Security) -->
            <div>
                @auth
                    <div class="whatsapp-box unlocked">
                        <i class="fa-brands fa-whatsapp whatsapp-icon"></i>
                        <p style="font-weight: 600; color: var(--gray-700); font-size: 14px;">WhatsApp Pemilik Toko:</p>
                        @php
                            $waNumber = preg_replace('/[^0-9]/', '', $store->whatsapp);
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                        @endphp
                        <h3 style="margin: 4px 0;"><a href="https://wa.me/{{ $waNumber }}" target="_blank" style="color: var(--success); display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 14px;"></i> {{ $store->whatsapp }}
                        </a></h3>
                        <small style="color: var(--gray-500);">Klik untuk langsung chat dengan pemilik toko</small>
                    </div>
                @else
                    <div class="whatsapp-box">
                        <i class="fa-solid fa-lock whatsapp-icon" style="color: var(--primary);"></i>
                        <p style="font-weight: 600; color: var(--gray-700); font-size: 14px;">Hubungi Pemilik Toko:</p>
                        <p style="font-size: 12px; color: var(--gray-500); margin-bottom: 8px;">Informasi WhatsApp pemilik disembunyikan demi keamanan privasi.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="width: 100%;">
                            <i class="fa-solid fa-right-to-bracket"></i> Login untuk Melihat
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Two Column Grid -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
        
        <!-- Left Column: Menus & Reviews -->
        <div>
            <!-- Menus List Section -->
            <section class="detail-section">
                <h2 class="detail-section-title">
                    <span><i class="fa-solid fa-utensils" style="color: var(--primary); margin-right: 8px;"></i>Daftar Menu</span>
                    <span style="font-size: 14px; font-weight: normal; color: var(--gray-500);">{{ $store->menus->count() }} menu tersedia</span>
                </h2>
                
                @if($store->menus->isEmpty())
                    <div style="text-align: center; padding: 32px; background-color: var(--light); border-radius: var(--border-radius-md);">
                        <i class="fa-solid fa-circle-info" style="font-size: 36px; color: var(--gray-300); margin-bottom: 8px;"></i>
                        <p style="color: var(--gray-500);">Menu makanan belum ditambahkan untuk toko ini.</p>
                    </div>
                @else
                    <div class="menu-grid">
                        @foreach($store->menus as $menu)
                            <div class="menu-card">
                                @if($menu->gambar)
                                    <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="menu-image">
                                @else
                                    <div class="menu-image" style="background: linear-gradient(135deg, #f59e0b 0%, #ff6b4a 100%); display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-bowl-food" style="font-size: 32px; color: rgba(255,255,255,0.7);"></i>
                                    </div>
                                @endif
                                <div class="menu-info">
                                    <h3 class="menu-name">{{ $menu->nama_menu }}</h3>
                                    <div class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Reviews List Section -->
            <section class="detail-section">
                <h2 class="detail-section-title">
                    <span><i class="fa-solid fa-comments" style="color: var(--primary); margin-right: 8px;"></i>Ulasan Pengguna</span>
                    <span style="font-size: 14px; font-weight: normal; color: var(--gray-500);">{{ $totalReviews }} Ulasan</span>
                </h2>

                @if($store->reviews->isEmpty())
                    <div style="text-align: center; padding: 32px; background-color: var(--light); border-radius: var(--border-radius-md);">
                        <i class="fa-regular fa-comment" style="font-size: 36px; color: var(--gray-300); margin-bottom: 8px;"></i>
                        <p style="color: var(--gray-500);">Belum ada ulasan untuk toko ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @else
                    <div class="reviews-list">
                        @foreach($store->reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <div>
                                        <span class="reviewer-name">{{ $review->user->name }}</span>
                                        <span class="badge-role role-user" style="font-size: 10px; margin-left: 6px;">{{ $review->user->role }}</span>
                                    </div>
                                    <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="review-rating" style="margin-bottom: 8px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star" style="color: var(--gray-300);"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="review-comment">{{ $review->komentar }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>

        <!-- Right Column: Location Map & Write Review Form -->
        <div style="display: flex; flex-direction: column; gap: 32px; position: sticky; top: 104px;">
            <!-- Location Map -->
            <section class="detail-section" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-map" style="color: var(--primary);"></i> Peta Lokasi Toko
                </h3>
                <div id="map-container" style="position: relative;">
                    <div id="map" style="height: 250px; border-radius: var(--border-radius-md); border: 1px solid var(--gray-200);"></div>
                </div>
            </section>

            <!-- Write Review Form (RBAC - User role only) -->
            <section class="detail-section" style="padding: 24px;">
                <h3 style="font-size: 18px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square" style="color: var(--primary);"></i> Tulis Ulasan Anda
                </h3>
                
                @auth
                    @if(auth()->user()->isRegularUser())
                        <form action="{{ route('review.store', $store->id) }}" method="POST">
                            @csrf
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label">Rating Toko</label>
                                <div class="rating-input">
                                    <input type="radio" id="star5" name="rating" value="5" required /><label for="star5" title="Sangat Enak (5)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Enak (4)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Biasa Saja (3)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kurang Enak (2)"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Tidak Recomended (1)"><i class="fa-solid fa-star"></i></label>
                                </div>
                                @error('rating')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group" style="margin-bottom: 16px;">
                                <label for="komentar" class="form-label">Komentar / Ulasan</label>
                                <textarea name="komentar" id="komentar" rows="4" class="form-control" placeholder="Tulis pendapat Anda tentang makanan, pelayanan, atau harga di toko ini..." required></textarea>
                                @error('komentar')
                                    <span class="form-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                Kirim Ulasan <i class="fa-solid fa-paper-plane" style="margin-left: 6px;"></i>
                            </button>
                        </form>
                    @else
                        <!-- Logged in as Admin or Super Admin -->
                        <div style="background-color: var(--gray-100); border-radius: var(--border-radius-md); padding: 16px; text-align: center; border: 1px solid var(--gray-200);">
                            <i class="fa-solid fa-circle-exclamation" style="font-size: 24px; color: var(--gray-500); margin-bottom: 8px;"></i>
                            <p style="font-size: 13px; color: var(--gray-700); font-weight: 500;">Role Anda adalah <strong>{{ auth()->user()->role }}</strong>.</p>
                            <p style="font-size: 12px; color: var(--gray-500); margin-top: 4px;">Hanya akun dengan peran **User** (pengguna umum) yang diizinkan menulis ulasan.</p>
                        </div>
                    @endif
                @else
                    <!-- Not Logged In -->
                    <div style="background-color: var(--primary-light); border-radius: var(--border-radius-md); padding: 20px; text-align: center; border: 1px dashed var(--primary);">
                        <i class="fa-solid fa-user-lock" style="font-size: 28px; color: var(--primary); margin-bottom: 12px;"></i>
                        <p style="font-size: 14px; font-weight: 600; color: var(--dark);">Ingin Memberikan Ulasan?</p>
                        <p style="font-size: 12px; color: var(--gray-700); margin-top: 4px; margin-bottom: 16px;">Silakan login terlebih dahulu untuk memberikan rating dan komentar pada toko geprek ini.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="width: 100%;">
                            <i class="fa-solid fa-right-to-bracket"></i> Login Sekarang
                        </a>
                    </div>
                @endauth
            </section>
        </div>

    </div>
@endsection

@section('scripts')
    <!-- Leaflet Map JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if coordinates exist
            var lat = parseFloat('{{ $store->latitude }}');
            var lng = parseFloat('{{ $store->longitude }}');
            
            if (!isNaN(lat) && !isNaN(lng)) {
                // Initialize map
                var map = L.map('map').setView([lat, lng], 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                
                // Add marker
                var marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<strong>{{ $store->nama_toko }}</strong><br>{{ $store->alamat }}").openPopup();
                
                // Fix map render issue in flex container by forcing resize check
                setTimeout(function() {
                    map.invalidateSize();
                }, 500);
            } else {
                document.getElementById('map').innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--gray-500);">Lokasi koordinat peta tidak valid</div>';
            }
        });
    </script>
@endsection
