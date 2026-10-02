@extends('layouts.app')

@section('title', 'BestieGeprek - Cari Ayam Geprek Terbaik di Bengkalis')

@section('content')
    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-content">
            <h1>Cari & Temukan <span class="highlight-ayam">Ayam</span> <span class="highlight-geprek">Geprek</span> Terbaik di Bengkalis</h1>
            <p>Sistem Informasi BestieGeprek membantu Anda menemukan referensi tempat makan ayam geprek terpopuler, lengkap dengan daftar menu, rating, ulasan, lokasi peta, dan kontak pemilik.</p>
            
            <form action="{{ route('home') }}" method="GET" class="search-box">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--gray-500); margin-left: 16px; font-size: 18px;"></i>
                <input type="text" name="search" class="search-input" value="{{ $search }}" placeholder="Cari nama toko ayam geprek atau alamat...">
                <button type="submit" class="btn btn-primary search-btn">Cari Sekarang</button>
            </form>
        </div>
    </div>

    <!-- Main Section -->
    <div class="container" style="padding: 32px 24px 64px 24px;">
        <div class="section-title">
            <div>
                <h2>
                    @if(!empty($search))
                        Hasil Pencarian untuk "{{ $search }}"
                    @else
                        Rekomendasi Toko Ayam Geprek
                    @endif
                </h2>
                <p style="font-size: 15px; color: var(--gray-500); font-weight: normal; margin-top: 4px;">
                    @if(!empty($search))
                        Ditemukan {{ $stores->count() }} toko geprek yang cocok
                    @else
                        Daftar toko geprek terpopuler di wilayah Bengkalis
                    @endif
                </p>
            </div>
            @if(!empty($search))
                <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm">Reset</a>
            @endif
        </div>

        @if($stores->isEmpty())
            <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 48px; text-align: center; box-shadow: var(--shadow-sm);">
                <i class="fa-regular fa-face-frown" style="font-size: 64px; color: var(--gray-300); margin-bottom: 16px;"></i>
                <h3>Toko tidak ditemukan</h3>
                <p style="color: var(--gray-500); margin-top: 8px;">Maaf, kami tidak dapat menemukan toko ayam geprek dengan kata kunci "{{ $search }}".</p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-sm" style="margin-top: 16px;">Kembali ke Beranda</a>
            </div>
        @else
            <div class="store-grid">
                @foreach($stores as $store)
                    <article class="store-card">
                        <div class="store-image-wrapper">
                            @if($store->gambar)
                                <img src="{{ asset('storage/' . $store->gambar) }}" alt="{{ $store->nama_toko }}" class="store-image">
                            @else
                                <!-- Custom food gradient background if no image -->
                                <div class="store-image" style="background: linear-gradient(135deg, #e04a22 0%, #f59e0b 100%); display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-pepper-hot" style="font-size: 64px; color: rgba(255,255,255,0.8);"></i>
                                </div>
                            @endif
                            <span class="store-badge">Bengkalis</span>
                        </div>
                        
                        <div class="store-info">
                            <div class="store-rating-row">
                                <i class="fa-solid fa-star"></i>
                                <span>{{ $store->average_rating }}</span>
                                <span style="color: var(--gray-500); font-weight: normal;">({{ $store->reviews->count() }} ulasan)</span>
                            </div>
                            
                            <h3 class="store-name">{{ $store->nama_toko }}</h3>
                            
                            <p class="store-address">
                                <i class="fa-solid fa-location-dot" style="color: var(--primary); margin-right: 4px;"></i> 
                                {{ $store->alamat }}
                            </p>
                            
                            <div class="store-footer">
                                <span style="font-size: 12px; color: var(--gray-500); font-weight: 500;">
                                    <i class="fa-solid fa-utensils"></i> Ayam Geprek
                                </span>
                                <a href="{{ route('store.detail', $store->id) }}" class="btn btn-primary btn-sm">
                                    Detail Toko <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Promo / Join Store CTA Section -->
    <div class="promo-section-wrapper" style="background-color: var(--white); padding: 64px 0; border-top: 1px solid rgba(0, 0, 0, 0.05); border-bottom: 1px solid rgba(0, 0, 0, 0.05); width: 100%;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
            <div class="promo-section">
                <div class="promo-grid">
                    <div>
                        <h2 class="promo-title">Promosikan <span class="highlight-ayam">Ayam Geprek</span> Anda di <span class="highlight-bestie">BestieGeprek</span></h2>
                        <ul class="promo-list">
                            <li><i class="fa-solid fa-circle-check"></i> Lebih mudah ditemukan pelanggan di Bengkalis</li>
                            <li><i class="fa-solid fa-circle-check"></i> Naikkan pesanan lewat pencarian & rating</li>
                            <li><i class="fa-solid fa-circle-check"></i> Bergabung sekarang, dan kembangkan bisnis Anda!</li>
                        </ul>
                    </div>
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <img src="{{ asset('images/join_promo_banner.png') }}" alt="Promosikan Ayam Geprek Anda" class="promo-image">
                    </div>
                </div>
                
                <div class="promo-cards">
                    <div class="promo-card">
                        <div class="promo-card-header">
                            <div class="promo-card-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <h4 class="promo-card-title">Mudah Ditemukan</h4>
                        </div>
                        <p class="promo-card-text">Toko Anda tampil di pencarian teratas ketika pelanggan mencari ayam geprek di Bengkalis.</p>
                    </div>
                    
                    <div class="promo-card">
                        <div class="promo-card-header">
                            <div class="promo-card-icon">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                            <h4 class="promo-card-title">Tingkatkan Penjualan</h4>
                        </div>
                        <p class="promo-card-text">Semakin banyak dilihat, semakin besar peluang mendapatkan lebih banyak pesanan.</p>
                    </div>
                    
                    <div class="promo-card">
                        <div class="promo-card-header">
                            <div class="promo-card-icon">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <h4 class="promo-card-title">Kelola Toko Sendiri</h4>
                        </div>
                        <p class="promo-card-text">Update menu, harga, foto, dan promo dengan mudah melalui dashboard pemilik toko.</p>
                    </div>
                </div>
                
                <div class="promo-action">
                    <a href="{{ route('register-toko') }}" class="btn btn-primary" style="padding: 14px 32px; font-size: 16px; border-radius: 50px; display: inline-flex; align-items: center; gap: 10px;">
                        Daftarkan Toko Anda Sekarang <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
