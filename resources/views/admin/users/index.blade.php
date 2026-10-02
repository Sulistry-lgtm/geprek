@extends('layouts.app')

@section('title', 'Kelola Pengguna - BestieGeprek')

@section('content')
<div class="admin-layout">
    
    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="admin-content" style="background-color: var(--white); border-radius: var(--border-radius-lg); box-shadow: var(--shadow-sm); padding: 24px;">
        <div style="margin-bottom: 24px;">
            <h2>Manajemen Akun & Hak Akses Pengguna</h2>
            <p style="color: var(--gray-500); font-size: 14px;">Atur peran pengguna (User, Penjual, Super Admin) untuk memberikan hak akses atau hapus akun dari sistem secara langsung.</p>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Peran Saat Ini</th>
                        <th>Ubah Peran</th>
                        <th style="width: 100px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge-role role-{{ $user->role === 'super_admin' ? 'superadmin' : $user->role }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                @if($user->id === auth()->id())
                                    <span style="font-size: 13px; color: var(--gray-500); font-style: italic;">
                                        (Akun Anda Sendiri)
                                    </span>
                                @else
                                    <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-control" style="padding: 6px 12px; font-size: 13px; width: 140px; border-radius: var(--border-radius-sm);" onchange="this.form.submit()">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User (Umum)</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Toko)</option>
                                            <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Seluruh ulasan yang ditulis oleh pengguna ini juga akan dihapus.')" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 6px 10px;">
                                            <i class="fa-solid fa-user-minus"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 13px; color: var(--gray-500); font-style: italic;">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
