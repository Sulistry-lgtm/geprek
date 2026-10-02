@extends('layouts.app')

@section('title', 'Kelola Toko - BestieGeprek')

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="admin-content" style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2>Daftar Toko Ayam Geprek</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Kelola informasi toko, alamat, peta lokasi, kontak whatsapp, dan cover foto.</p>
            </div>
            <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah Toko Baru
            </a>
        </div>

        @if($stores->isEmpty())
            <div style="text-align: center; padding: 48px; background-color: var(--light); border-radius: var(--border-radius-md);">
                <i class="fa-solid fa-store" style="font-size: 48px; color: var(--gray-300); margin-bottom: 16px;"></i>
                <h3>Belum ada data toko</h3>
                <p style="color: var(--gray-500); margin-top: 8px;">Mulai dengan menambahkan toko geprek pertama Anda dengan mengklik tombol Tambah Toko Baru.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Toko</th>
                            <th>Alamat</th>
                            <th>WhatsApp</th>
                            <th>Koordinat (Lat, Lng)</th>
                            <th style="width: 150px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>
                                    @if($store->gambar)
                                        <img src="{{ asset('storage/' . $store->gambar) }}" alt="{{ $store->nama_toko }}" style="width: 70px; height: 50px; object-fit: cover; border-radius: var(--border-radius-sm);">
                                    @else
                                        <div style="width: 70px; height: 50px; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display:flex; align-items:center; justify-content:center; border-radius: var(--border-radius-sm);">
                                            <i class="fa-solid fa-image" style="color: white; font-size: 16px;"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong>{{ $store->nama_toko }}</strong></td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $store->alamat }}</td>
                                <td>
                                    <span style="font-size: 14px; font-weight: 500; color: var(--success);">
                                        <i class="fa-brands fa-whatsapp"></i> {{ $store->whatsapp }}
                                    </span>
                                </td>
                                <td style="font-size: 13px; font-family: monospace;">{{ $store->latitude }}, {{ $store->longitude }}</td>
                                <td style="text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <a href="{{ route('admin.stores.edit', $store->id) }}" class="btn btn-secondary btn-sm" style="padding: 6px 10px; border-color: var(--warning); color: var(--warning);">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus toko {{ $store->nama_toko }} dan seluruh data menunya?')" style="display: inline;">
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
