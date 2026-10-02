@extends('layouts.app')

@section('title', 'Edit Menu - BestieGeprek')

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
                <h2>Edit Menu Makanan</h2>
                <p style="color: var(--gray-500); font-size: 14px;">Perbarui data informasi menu makanan ayam geprek terpilih.</p>
            </div>
            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-dark btn-sm">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data" style="max-width: 600px;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="store_id" class="form-label">Toko Ayam Geprek <span style="color:var(--danger)">*</span></label>
                <select name="store_id" id="store_id" class="form-control" required>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ old('store_id', $menu->store_id) == $store->id ? 'selected' : '' }}>
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
                <input type="text" name="nama_menu" id="nama_menu" class="form-control" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
                @error('nama_menu')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="harga" class="form-label">Harga Menu (Rp) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="harga" id="harga" class="form-control" value="{{ old('harga', $menu->harga) }}" min="0" required>
                @error('harga')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="gambar" class="form-label">Ganti Foto Makanan (Opsional)</label>
                
                @if($menu->gambar)
                    <div style="margin-bottom: 12px;" id="current-photo-container">
                        <p style="font-size: 13px; color: var(--gray-500); margin-bottom: 4px;">Foto saat ini:</p>
                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" style="width: 120px; height: 90px; object-fit: cover; border-radius: var(--border-radius-sm); border: 1px solid var(--gray-200);">
                    </div>
                @endif

                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                <small style="color: var(--gray-500); display: block; margin-top: 4px;">Format file: jpeg, png, jpg, webp. Ukuran maksimum: 2MB. Biarkan kosong jika tidak ingin mengubah foto.</small>
                
                <div class="preview-container" id="preview-container">
                    <p style="font-size: 13px; margin-bottom: 4px; font-weight: 600;">Pratinjau Foto Baru:</p>
                    <img id="preview-image" src="" alt="Preview">
                </div>

                @error('gambar')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>
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
    const currentPhotoContainer = document.getElementById('current-photo-container');
    
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
            
            // Sembunyikan foto saat ini jika admin baru saja mengunggah foto baru
            if(currentPhotoContainer) {
                currentPhotoContainer.style.display = 'none';
            }

            cropperModal.style.display = 'none';
            cropper.destroy();
            cropper = null;
        }, 'image/png');
    });
});
</script>
@endsection
