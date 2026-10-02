@extends('layouts.app')

@section('title', 'Hubungi Kami - BestieGeprek')

@section('content')
    <div style="background-color: var(--white); border-radius: var(--border-radius-lg); padding: 32px; box-shadow: var(--shadow-md); margin-bottom: 24px;">
        <div style="text-align: center; max-width: 600px; margin: 0 auto 32px;">
            <span class="badge-role role-admin" style="font-size: 12px; margin-bottom: 8px; display: inline-block;">Hubungi Kami</span>
            <h1 style="font-size: 28px; margin-bottom: 12px;">Ada Pertanyaan atau Masalah?</h1>
            <p style="color: var(--gray-500); font-size: 15px;">Kami di BestieGeprek siap membantu Anda. Silakan kirimkan pertanyaan, keluhan, atau saran mengenai platform pencarian toko geprek kami.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 32px;">
            
            <!-- Contact Details -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <h2 style="font-size: 20px; margin-bottom: 4px;">Informasi Kontak</h2>
                
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: var(--border-radius-md); background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; margin-bottom: 2px;">Alamat Kantor</h4>
                        <p style="font-size: 13px; color: var(--gray-700);">Jl. Bathin Alam, Sungai Alam, Bengkalis, Riau 28711</p>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: var(--border-radius-md); background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; margin-bottom: 2px;">Email Support</h4>
                        <p style="font-size: 13px; color: var(--gray-700);"><a href="mailto:geprekbestie76@gmail.com" style="color: var(--primary); font-weight: 500;">geprekbestie76@gmail.com</a></p>
                    </div>
                </div>

                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: var(--border-radius-md); background-color: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; flex-shrink: 0;">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 15px; margin-bottom: 2px;">WhatsApp Helpdesk</h4>
                        <p style="font-size: 13px; color: var(--gray-700);"><a href="https://wa.me/6283117071309" target="_blank" style="color: var(--success); font-weight: 600;"><i class="fa-brands fa-whatsapp"></i> +62 831-1707-1309</a></p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="background-color: var(--light); padding: 24px; border-radius: var(--border-radius-lg); border: 1px solid var(--gray-200);">
                <h2 style="font-size: 20px; margin-bottom: 12px;">Kirim Pesan</h2>
                
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="name" class="form-label" style="margin-bottom: 4px;">Nama Anda</label>
                        <input type="text" id="name" name="name" class="form-control" style="padding: 8px 12px;" placeholder="Nama Lengkap" required>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label for="email" class="form-label" style="margin-bottom: 4px;">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-control" style="padding: 8px 12px;" placeholder="contoh@gmail.com" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label for="message" class="form-label" style="margin-bottom: 4px;">Pesan / Masukan</label>
                        <textarea id="message" name="message" rows="3" class="form-control" style="padding: 8px 12px;" placeholder="Tuliskan keluhan atau pertanyaan Anda..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">
                        Kirim Pesan <i class="fa-solid fa-paper-plane" style="margin-left: 6px;"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
@endsection
