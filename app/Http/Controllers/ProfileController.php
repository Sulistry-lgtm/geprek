<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $store = $user->stores()->first(); // For Store Admin
        return view('admin.profile.edit', compact('user', 'store'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];

        // Only validate password if the user is changing it
        if ($request->filled('password')) {
            $rules['password'] = [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ];
        }

        $request->validate($rules, [
            'password.min' => 'Password wajib minimal 8 karakter.',
            'password.letters' => 'Password wajib mengandung huruf.',
            'password.mixed' => 'Password wajib mengandung kombinasi huruf besar dan kecil.',
            'password.numbers' => 'Password wajib mengandung angka.',
            'password.symbols' => 'Password wajib mengandung simbol (!@#$% dll).',
        ]);

        $user->name = $request->name;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil akun berhasil diperbarui!');
    }

    public function updateStore(Request $request)
    {
        $user = Auth::user();
        $store = $user->stores()->first();

        if (!$store) {
            abort(404, 'Toko tidak ditemukan.');
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

        if ($request->hasFile('gambar')) {
            if ($store->gambar) {
                Storage::disk('public')->delete($store->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('stores', 'public');
        }

        $store->update($data);

        return redirect()->back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}
