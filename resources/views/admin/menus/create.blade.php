@extends('layouts.app')

@section('title', 'Tambah Menu Baru - BestieGeprek')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
<style>
    /* Modal styles for cropper */
    .cropper-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.8); z-index: 9999;
        display: none; justify-content: center; align-items: center;
    }
    .cropper-modal-container {
        background: var(--white); padding: 20px; border-radius: var(--border-radius-lg);
        width: 90%; max-width: 800px;
    }
    .img-container {
        max-height: 60vh;
        width: 100%;
        margin-bottom: 20px;
    }
    .img-container img {
        display: block;
        max-width: 100%;
    }
    .preview-container {
        margin-top: 10px;
        display: none;
    }
    .preview-container img {
        max-width: 200px;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-300);
    }
</style>
@endsection

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content Form -->
    <div class="admin-content" style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); padding: 24px;">
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Tambah Menu Makanan Baru</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Masukkan rincian menu makanan baru yang akan ditambahkan ke sistem.</p>
            </div>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        @if($stores->isEmpty())
            <div style="background-color: var(--primary-light); padding: 20px; border-radius: var(--border-radius-md); text-align: center; border: 1px dashed var(--primary);">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 32px; color: var(--primary); margin-bottom: 12px;"></i>
                <p style="font-weight: 600;">Belum Ada Toko Terdaftar!</p>
                @if(auth()->user()->isSuperAdmin())
                    <p style="font-size: 13px; color: var(--gray-700); margin-top: 4px; margin-bottom: 16px;">Anda harus membuat setidaknya satu toko ayam geprek terlebih dahulu sebelum dapat menambahkan menu makanan.</p>
                    <a href="{{ route('admin.stores.create') }}" class="btn btn-primary btn-sm">Buat Toko Sekarang</a>
                @else
                    <p style="font-size: 13px; color: var(--gray-700); margin-top: 4px; margin-bottom: 16px;">Anda belum memiliki toko yang terdaftar atau ditugaskan. Silakan hubungi Super Admin untuk mendaftarkan atau mengaitkan toko Anda terlebih dahulu.</p>
                @endif
            </div>
        @else
            <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
                @csrf

                <div class="form-group">
                    <label for="store_id" class="form-label">Toko Ayam Geprek <span style="color:var(--danger)">*</span></label>
                    <select name="store_id" id="store_id" class="form-control" required>
                        <option value="" disabled selected>Pilih Toko...</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->nama_toko }} ({{ $store->alamat }})
                            </option>
                        @endforeach
                    </select>
                    @error('store_id')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_menu" class="form-label">Nama Menu Makanan <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="{{ old('nama_menu') }}" placeholder="Contoh: Ayam Geprek Sambal Korek Level 5" required>
                    @error('nama_menu')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="harga" class="form-label">Harga Menu (Rp) <span style="color:var(--danger)">*</span></label>
                    <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga') }}" placeholder="Contoh: 15000" min="0" required>
                    @error('harga')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="gambar" class="form-label">Foto Makanan (Opsional)</label>
                    <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                    <small style="color: var(--gray-500); display: block; margin-top: 4px;">Format file: jpeg, png, jpg, webp. Ukuran maksimum: 2MB.</small>
                    <div class="preview-container" id="preview-container">
                        <p style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Pratinjau Foto:</p>
                        <img id="preview-image" src="" alt="Preview">
                    </div>
                    @error('gambar')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Menu
                </button>
            </form>
        @endif
    </div>

</div>

<!-- Cropper Modal -->
<div class="cropper-modal-overlay" id="cropper-modal">
    <div class="cropper-modal-container">
        <h3 style="margin-bottom: 16px;">Sesuaikan Foto Menu</h3>
        <div class="img-container">
            <img id="cropper-image" src="">
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn btn-secondary" id="btn-cancel-crop">Batal</button>
            <button type="button" class="btn btn-primary" id="btn-save-crop">Simpan Potongan</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('gambar');
    const cropperModal = document.getElementById('cropper-modal');
    const cropperImage = document.getElementById('cropper-image');
    const btnCancelCrop = document.getElementById('btn-cancel-crop');
    const btnSaveCrop = document.getElementById('btn-save-crop');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    
    let cropper = null;

    fileInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            // Abaikan jika file berasal dari hasil crop (bernama cropped_image.png)
            if (files[0].name === 'cropped_image.png') return;

            const url = URL.createObjectURL(files[0]);
            cropperImage.src = url;
            cropperModal.style.display = 'flex';

            if (cropper) {
                cropper.destroy();
            }

            // Inisialisasi Cropper dengan rasio 4:3
            cropper = new Cropper(cropperImage, {
                aspectRatio: 4 / 3,
                viewMode: 1,
                autoCropArea: 1,
            });
            // Reset input agar bisa memilih file yang sama jika dibatalkan
            fileInput.value = '';
        }
    });

    btnCancelCrop.addEventListener('click', function () {
        cropperModal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });

    btnSaveCrop.addEventListener('click', function () {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 600,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        canvas.toBlob(function (blob) {
            const file = new File([blob], 'cropped_image.png', { type: 'image/png' });
            
            const dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;

            previewImage.src = URL.createObjectURL(blob);
            previewContainer.style.display = 'block';

            cropperModal.style.display = 'none';
            cropper.destroy();
            cropper = null;
        }, 'image/png');
    });
});
</script>
@endsection
