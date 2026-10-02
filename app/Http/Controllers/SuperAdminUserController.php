<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminUserController extends Controller
{
    public function index()
    {
        // Get all users, sorted by latest
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent Super Admin from changing their own role
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $request->validate([
            'role' => ['required', 'in:user,admin,super_admin'],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Peran pengguna {$user->name} berhasil diubah menjadi {$request->role}!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent Super Admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Delete user's reviews
        $user->reviews()->delete();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$user->name} berhasil dihapus dari sistem.");
    }
}
