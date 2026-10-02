<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminStoreController extends Controller
{
    public function index()
    {
        // Super Admin can see all stores. Admin (Seller) can only see their own.
        if (Auth::user()->isSuperAdmin()) {
            $stores = Store::latest()->get();
        } else {
            $stores = Store::where('user_id', Auth::id())->latest()->get();
        }

        return view('admin.stores.index', compact('stores'));
    }

    public function create()
    {
        $users = User::where('role', 'admin')->orderBy('name')->get();
        return view('admin.stores.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $data = $request->only(['nama_toko', 'alamat', 'whatsapp', 'latitude', 'longitude', 'user_id']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('stores', 'public');
        }

        Store::create($data);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        $users = User::where('role', 'admin')->orderBy('name')->get();
        return view('admin.stores.edit', compact('store', 'users'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);

        // RBAC Owner Check: Admin can only update their own stores
        if (!Auth::user()->isSuperAdmin() && $store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengelola toko ini.');
        }

        $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $data = $request->only(['nama_toko', 'alamat', 'whatsapp', 'latitude', 'longitude', 'user_id']);

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($store->gambar) {
                Storage::disk('public')->delete($store->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('stores', 'public');
        }

        $store->update($data);

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);

        // RBAC Owner Check: Admin can only delete their own stores
        if (!Auth::user()->isSuperAdmin() && $store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk menghapus toko ini.');
        }

        // Delete associated image
        if ($store->gambar) {
            Storage::disk('public')->delete($store->gambar);
        }

        // Delete menus and reviews
        $store->menus()->delete();
        $store->reviews()->delete();
        $store->delete();

        return redirect()->route('admin.stores.index')
            ->with('success', 'Toko dan seluruh data terkait berhasil dihapus!');
    }

    public function showRegistrationForm()
    {
        if (Auth::user()->stores()->count() > 0) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.stores.register');
    }

    public function registerStore(Request $request)
    {
        if (Auth::user()->stores()->count() > 0) {
            return redirect()->route('admin.dashboard');
        }

        $request->validate([
            'nama_toko' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'whatsapp' => ['required', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $data = $request->only(['nama_toko', 'alamat', 'whatsapp', 'latitude', 'longitude']);
        $data['user_id'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('stores', 'public');
        }

        Store::create($data);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Toko Anda berhasil didaftarkan! Selamat datang di Dashboard BestieGeprek.');
    }
}
