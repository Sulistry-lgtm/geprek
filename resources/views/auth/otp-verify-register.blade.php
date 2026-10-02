@extends('layouts.app')

@section('title', 'Verifikasi Akun - BestieGeprek')

@section('content')
    <div class="form-card">

        <h2 class="form-title">Verifikasi Email Anda</h2>
        <p style="text-align: center; color: var(--gray-500); font-size: 14px; margin-top: -16px; margin-bottom: 24px; line-height: 1.5;">
            Masukkan 6 digit kode verifikasi OTP yang telah kami kirimkan ke email Google Anda.
        </p>
        
        <form action="{{ route('register.otp.verify') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 24px;">
                <label for="otp_code" class="form-label" style="text-align: center; display: block;">Kode OTP (6 Digit)</label>
                <input type="text" name="otp_code" id="otp_code" class="form-control @error('otp_code') is-invalid @enderror" placeholder="123456" maxlength="6" required autofocus autocomplete="off" style="text-align: center; font-size: 24px; letter-spacing: 8px; font-weight: 700; height: 56px;">
                @error('otp_code')
                    <span class="form-error" style="text-align: center; display: block; margin-top: 8px;">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 16px;">
                Verifikasi & Aktifkan Akun <i class="fa-solid fa-user-check" style="margin-left: 6px;"></i>
            </button>
        </form>
        
        <div class="form-footer" style="margin-top: 24px; border-top: 1px dashed var(--gray-200); padding-top: 16px; display: flex; flex-direction: column; gap: 10px;">
            <form action="{{ route('register.otp.resend') }}" method="POST" id="resend-form" style="margin: 0; text-align: center;">
                @csrf
                <span style="color: var(--gray-500); font-size: 13px;">Belum menerima kode?</span>
                <button type="submit" style="background: none; border: none; padding: 0; color: var(--primary); font-weight: 600; font-size: 13px; cursor: pointer; text-decoration: underline;">
                    Kirim Ulang OTP
                </button>
            </form>
            <a href="{{ route('login') }}" style="font-weight: 600; text-align: center; display: block; font-size: 13px; margin-top: 4px;"><i class="fa-solid fa-arrow-left" style="margin-right: 4px;"></i> Kembali ke Halaman Utama</a>
        </div>
    </div>
@endsection
