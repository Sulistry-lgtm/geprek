@extends('layouts.app')

@section('title', 'Super Admin Dashboard - BestieGeprek')

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div style="flex: 1;">
        
        <!-- Welcome banner -->
        <div style="background: linear-gradient(135deg, var(--primary) 0%, #cc3300 100%); color: var(--white); border-radius: var(--border-radius-lg); padding: 32px; margin-bottom: 32px; position: relative; overflow: hidden; box-shadow: var(--shadow-md);">
            <div style="position: relative; z-index: 2; max-width: 600px;">
                <span class="badge-role role-superadmin" style="font-size: 11px; margin-bottom: 12px; display: inline-block;">
                    Super Admin
                </span>
                <h2 style="color: var(--white); font-size: 28px; margin-bottom: 8px; font-weight: 800;">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p style="color: var(--gray-300); font-size: 15px; margin-bottom: 24px; font-weight: 300; line-height: 1.6;">Pantau seluruh ekosistem BestieGeprek. Kelola data semua toko, pantau menu makanan yang terdaftar, dan kelola akun pengguna serta hak akses platform ini dalam satu panel pusat.</p>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm" style="padding: 10px 16px;">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Toko
                    </a>
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-outline-light btn-sm" style="padding: 10px 16px; border-color: rgba(255,255,255,0.3); color: var(--white); background: rgba(255,255,255,0.1);">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Menu
                    </a>
                </div>
            </div>
            <div style="position: absolute; right: -20px; bottom: -30px; font-size: 180px; color: rgba(255,255,255,0.05); transform: rotate(-15deg); pointer-events: none; z-index: 1;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 32px;">
            <div class="stat-card" style="background-color: var(--white); border-radius: var(--border-radius-md); padding: 24px; box-shadow: var(--shadow-sm); border-left: 4px solid var(--primary); display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-100); border-right: 1px solid var(--gray-100); border-bottom: 1px solid var(--gray-100);">
                <div>
                    <span style="font-size: 13px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                        Total Toko
                    </span>
                    <div class="stat-val" style="font-size: 32px; font-weight: 800; margin-top: 4px;">{{ $stats['stores'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 20px;">
                    <i class="fa-solid fa-store"></i>
                </div>
            </div>

            <div class="stat-card" style="background-color: var(--white); border-radius: var(--border-radius-md); padding: 24px; box-shadow: var(--shadow-sm); border-left: 4px solid var(--secondary); display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-100); border-right: 1px solid var(--gray-100); border-bottom: 1px solid var(--gray-100);">
                <div>
                    <span style="font-size: 13px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Menu</span>
                    <div class="stat-val" style="font-size: 32px; font-weight: 800; margin-top: 4px;">{{ $stats['menus'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: rgba(245,158,11,0.1); display: flex; align-items: center; justify-content: center; color: var(--secondary); font-size: 20px;">
                    <i class="fa-solid fa-bowl-food"></i>
                </div>
            </div>

            <div class="stat-card" style="background-color: var(--white); border-radius: var(--border-radius-md); padding: 24px; box-shadow: var(--shadow-sm); border-left: 4px solid var(--success); display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-100); border-right: 1px solid var(--gray-100); border-bottom: 1px solid var(--gray-100);">
                <div>
                    <span style="font-size: 13px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Ulasan</span>
                    <div class="stat-val" style="font-size: 32px; font-weight: 800; margin-top: 4px;">{{ $stats['reviews'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #e6f7f0; display: flex; align-items: center; justify-content: center; color: var(--success); font-size: 20px;">
                    <i class="fa-solid fa-comments"></i>
                </div>
            </div>

            <div class="stat-card" style="background-color: var(--white); border-radius: var(--border-radius-md); padding: 24px; box-shadow: var(--shadow-sm); border-left: 4px solid #3b82f6; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--gray-100); border-right: 1px solid var(--gray-100); border-bottom: 1px solid var(--gray-100);">
                <div>
                    <span style="font-size: 13px; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Total Pengguna</span>
                    <div class="stat-val" style="font-size: 32px; font-weight: 800; margin-top: 4px;">{{ $stats['users'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; border-radius: 50%; background-color: #e0f2fe; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 20px;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>

        <!-- Recent Reviews Area -->
        <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 32px; box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200);">
            <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-star-half-stroke" style="font-size: 24px; color: var(--secondary);"></i>
                <h3 style="font-size: 20px; font-weight: 700;">Daftar Ulasan & Rating Terbaru</h3>
            </div>
            
            @if($recentReviews->isEmpty())
                <div style="text-align: center; padding: 32px; background-color: var(--light); border-radius: var(--border-radius-md); border: 1px dashed var(--gray-300);">
                    <i class="fa-regular fa-comment-dots" style="font-size: 40px; color: var(--gray-300); margin-bottom: 12px;"></i>
                    <p style="color: var(--gray-500); font-size: 14px;">Belum ada ulasan atau rating dari pelanggan.</p>
                </div>
            @else
                <div class="table-responsive" style="border: none; box-shadow: none; border-radius: 0;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="padding-left: 0;">Pengguna</th>
                                <th>Toko Ayam Geprek</th>
                                <th>Rating</th>
                                <th>Ulasan / Komentar</th>
                                <th style="padding-right: 0;">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentReviews as $review)
                                <tr>
                                    <td style="padding-left: 0;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <!-- Initials avatar -->
                                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                                {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <strong style="font-size: 14px; color: var(--dark);">{{ $review->user->name }}</strong><br>
                                                <small style="color: var(--gray-500); font-size: 12px;">{{ $review->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('store.detail', $review->store->id) }}" target="_blank" style="color: var(--primary); font-weight: 600; font-size: 14px; text-decoration: underline;">
                                            {{ $review->store->nama_toko }}
                                        </a>
                                    </td>
                                    <td style="color: var(--secondary);">
                                        <div style="display: flex; gap: 2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fa-solid fa-star" style="font-size: 12px;"></i>
                                                @else
                                                    <i class="fa-regular fa-star" style="color: var(--gray-300); font-size: 12px;"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 14px; color: var(--gray-700);">
                                        {{ $review->komentar }}
                                    </td>
                                    <td style="padding-right: 0; font-size: 13px; color: var(--gray-500);">
                                        {{ $review->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
