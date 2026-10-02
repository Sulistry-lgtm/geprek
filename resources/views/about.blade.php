@extends('layouts.app')

@section('title', 'Tentang Kami - BestieGeprek')

@section('content')
    <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 48px; box-shadow: var(--shadow-md); margin-bottom: 32px;">
        <div style="text-align: center; max-width: 700px; margin: 0 auto 48px;">
            <span class="badge-role role-admin" style="font-size: 13px; margin-bottom: 12px; display: inline-block;">Tentang BestieGeprek</span>
            <h1 style="font-size: 36px; margin-bottom: 16px;">Sistem Informasi Ayam Geprek Bengkalis</h1>
            <p style="color: var(--gray-500); font-size: 16px;">BestieGeprek dirancang khusus untuk mempermudah masyarakat menemukan lokasi toko ayam geprek terbaik di wilayah Bengkalis secara cepat, terstruktur, dan aman.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center; margin-bottom: 48px;">
            <div>
                <h2 style="font-size: 24px; margin-bottom: 16px; color: var(--primary);">Visi & Misi Kami</h2>
                <p style="margin-bottom: 16px; font-size: 15px;">Kami ingin menghubungkan pecinta kuliner pedas khususnya ayam geprek di Bengkalis dengan pemilik toko kuliner lokal secara lebih efisien dan modern.</p>
                <p style="font-size: 15px;">Melalui platform ini, penjual dapat memasarkan toko, daftar menu, koordinat lokasi peta, serta nomor WhatsApp pemilik untuk kelancaran transaksi pemesanan secara langsung.</p>
            </div>
            <div style="background: linear-gradient(135deg, var(--primary-light) 0%, rgba(245,158,11,0.1) 100%); padding: 32px; border-radius: var(--border-radius-lg); border-left: 6px solid var(--primary);">
                <h3 style="margin-bottom: 12px; font-size: 18px;"><i class="fa-solid fa-shield-halved" style="color: var(--primary); margin-right: 8px;"></i> Keamanan Data Prioritas Kami</h3>
                <ul style="list-style-type: none; display: flex; flex-direction: column; gap: 10px; font-size: 14px; font-weight: 500;">
                    <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> Kontak WhatsApp terlindungi (hanya bisa diakses setelah login).</li>
                    <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> Sistem validasi password ketat dengan kombinasi huruf dan angka.</li>
                    <li><i class="fa-solid fa-check" style="color: var(--success); margin-right: 8px;"></i> Keamanan database terjamin dari serangan SQL Injection.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
