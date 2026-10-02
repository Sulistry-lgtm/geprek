@extends('layouts.app')

@section('title', 'Kelola Menu - BestieGeprek')

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="admin-content" style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2>Daftar Menu Ayam Geprek</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Kelola menu makanan, harga, dan keterkaitan toko ayam geprek.</p>
            </div>
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah Menu Baru
            </a>
        </div>

        @if($menus->isEmpty())
            <div style="text-align: center; padding: 48px; background-color: var(--light); border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-bowl-food" style="font-size: 48px; color: var(--gray-300); margin-bottom: 16px;"></i>
                <h3>Belum ada data menu</h3>
                <p style="color: var(--gray-500); margin-top: 8px;">Mulai dengan menambahkan menu ayam geprek pertama Anda dengan mengklik tombol Tambah Menu Baru.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Menu</th>
                            <th>Toko Geprek</th>
                            <th>Harga</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>
                                    @if($menu->gambar)
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" style="width: 60px; height: 45px; object-fit: cover; border-radius: var(--border-radius-sm);">
                                    @else
                                        <div style="width: 60px; height: 45px; background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%); display:flex; align-items:center; justify-content:center; border-radius: var(--border-radius-sm);">
                                            <i class="fa-solid fa-bowl-food" style="color: white; font-size: 14px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $menu->nama_menu }}</strong></td>
                                <td>
                                    <span style="font-size: 14px; font-weight: 500;">{{ $menu->store->nama_toko }}</span>
                                </td>
                                <td>
                                    <span style="font-size: 15px; font-weight: 700; color: var(--primary);">
                                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 10px; border-color: var(--warning); color: var(--warning);">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu {{ $menu->nama_menu }}?')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 10px;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
