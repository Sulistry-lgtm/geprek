<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    public function index()
    {
        // Super Admin gets all menus. Admin (Seller) gets only menus of stores they own.
        if (Auth::user()->isSuperAdmin()) {
            $menus = Menu::with('store')->latest()->get();
        } else {
            $menus = Menu::whereHas('store', function ($query) {
                $query->where('user_id', Auth::id());
            })->with('store')->latest()->get();
        }

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        // Restrict list of stores to those owned by the current Admin
        if (Auth::user()->isSuperAdmin()) {
            $stores = Store::orderBy('nama_toko')->get();
        } else {
            $stores = Store::where('user_id', Auth::id())->orderBy('nama_toko')->get();
        }

        return view('admin.menus.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $store = Store::findOrFail($request->store_id);

        // RBAC Owner Check: Admin can only add menus to their own stores
        if (!Auth::user()->isSuperAdmin() && $store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk menambahkan menu di toko ini.');
        }

        $data = $request->only(['store_id', 'nama_menu', 'harga']);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);

        // RBAC Owner Check: Admin can only edit menus of their own stores
        if (!Auth::user()->isSuperAdmin() && $menu->store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengelola menu ini.');
        }

        // Restrict list of stores
        if (Auth::user()->isSuperAdmin()) {
            $stores = Store::orderBy('nama_toko')->get();
        } else {
            $stores = Store::where('user_id', Auth::id())->orderBy('nama_toko')->get();
        }

        return view('admin.menus.edit', compact('menu', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // RBAC Owner Check: Admin can only update menus of their own stores
        if (!Auth::user()->isSuperAdmin() && $menu->store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk mengelola menu ini.');
        }

        $request->validate([
            'store_id' => ['required', 'exists:stores,id'],
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $newStore = Store::findOrFail($request->store_id);

        // RBAC Owner Check for the newly selected store
        if (!Auth::user()->isSuperAdmin() && $newStore->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk toko baru terpilih.');
        }

        $data = $request->only(['store_id', 'nama_menu', 'harga']);

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // RBAC Owner Check: Admin can only delete menus of their own stores
        if (!Auth::user()->isSuperAdmin() && $menu->store->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang untuk menghapus menu ini.');
        }

        // Delete image
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus!');
    }
}
