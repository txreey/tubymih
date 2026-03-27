@extends('owner.layouts.app')

@section('title', 'Kelola User')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">
        <!-- Header Utama -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola User</h1>
            <p class="text-gray-600 mt-1">Kelola akun kasir, admin, dan owner</p>
        </div>

        <!-- Ringkasan Total -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">Total User</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalUser }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">User Aktif</p>
                <p class="text-4xl font-bold text-green-600 mt-3">{{ $totalAktif }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">User Nonaktif</p>
                <p class="text-4xl font-bold text-red-600 mt-3">{{ $totalNonaktif }}</p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.users.index') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama / Username</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama atau username..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <select name="role"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <a href="{{ route('owner.users.index') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">
                            Reset
                        </a>
                        <button type="button" onclick="alert('Export lagi dikerjain!')"
                            class="flex-1 px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-sm">
                            Export
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card Tabel -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Daftar User</h2>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 transition text-sm">
                    <i class="fas fa-plus text-sm"></i> Tambah User
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Username</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->username }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full 
                                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role == 'owner' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full 
                                        {{ $user->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium flex items-center gap-5">
                                    <button onclick="openEditModal({{ $user->toJson() }})" title="Edit User"
                                        class="text-indigo-600 hover:text-indigo-800 transition text-xl">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button onclick="openDetailModal({{ $user->toJson() }})" title="Detail User"
                                        class="text-teal-600 hover:text-teal-800 transition text-xl">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('owner.users.toggleStatus', $user->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" {{ $user->status == 'aktif' ? 'checked' : '' }}
                                                class="sr-only peer" onchange="this.form.submit()">
                                            <div
                                                class="w-10 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600">
                                            </div>
                                        </label>
                                    </form>
                                    <form action="{{ route('owner.users.destroy', $user->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin hapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus User"
                                            class="text-red-600 hover:text-red-800 transition text-xl">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-users-slash text-5xl text-gray-300 mb-4 block"></i>
                                    Belum ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal"
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="detailContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl backdrop-blur-sm">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Detail User</h2>
                        <p class="text-teal-100 text-xs mt-1 opacity-90">Informasi lengkap pengguna</p>
                    </div>
                </div>
                <button onclick="closeModal('detailModal')"
                    class="text-white/80 hover:text-white transition text-2xl p-2 rounded-full hover:bg-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-8 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailNama">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-at"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Username</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailUsername">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Role</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailRole">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-toggle-on"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailStatus">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">No HP</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailNoHp">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Alamat</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailAlamat">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600 text-xl flex-shrink-0">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Password</p>
                            <p class="text-lg font-semibold text-gray-900">••••••••••••</p>
                            <p class="text-xs text-gray-500 mt-1 italic">Password disembunyikan untuk keamanan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button onclick="closeModal('detailModal')"
                    class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-md hover:shadow-lg flex items-center gap-2">
                    <i class="fas fa-check"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div id="createModal"
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="createContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah User Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5 opacity-90">Isi data user baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="text-white/80 hover:text-white transition text-xl p-1.5 rounded-full hover:bg-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('owner.users.store') }}" method="POST" class="p-6 bg-gray-50 space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" required placeholder="Contoh: John Doe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-at text-teal-600 text-sm"></i> Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" required placeholder="Contoh: johndoe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-lock text-teal-600 text-sm"></i> Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-phone text-teal-600 text-sm"></i> No HP
                        </label>
                        <input type="text" name="no_hp" placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                </div>
                <div class="mt-5">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat
                    </label>
                    <textarea name="alamat" rows="2" placeholder="Contoh: Jl. Sudirman No. 45"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span>
                        </label>
                        <select name="role" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir" selected>Kasir</option>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">
                        Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi. Password kosongkan jika
                        tidak ingin mengubah.
                    </p>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editModal"
        class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="editContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Edit User</h2>
                        <p class="text-teal-100 text-xs mt-0.5 opacity-90">Ubah data user</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="text-white/80 hover:text-white transition text-xl p-1.5 rounded-full hover:bg-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editForm" method="POST" class="p-6 bg-gray-50 space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_id" id="editUserId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="editNama" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-at text-teal-600 text-sm"></i> Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="username" id="editUsername" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-lock text-teal-600 text-sm"></i> Password
                            <span class="text-gray-500 text-xs ml-2">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password" id="editPassword"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-phone text-teal-600 text-sm"></i> No HP
                        </label>
                        <input type="text" name="no_hp" id="editNoHp"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                </div>
                <div class="mt-5">
                    <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat
                    </label>
                    <textarea name="alamat" id="editAlamat" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span>
                        </label>
                        <select name="role" id="editRole" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5 flex items-center gap-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span>
                        </label>
                        <select name="status" id="editStatus" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">
                        Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi. Password kosongkan jika
                        tidak ingin mengubah.
                    </p>
                </div>
                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDetailModal(user) {
            document.getElementById('detailNama').textContent = user.nama || '-';
            document.getElementById('detailUsername').textContent = user.username || '-';
            document.getElementById('detailRole').textContent = user.role ? user.role.charAt(0).toUpperCase() + user.role
                .slice(1) : '-';
            document.getElementById('detailStatus').textContent = user.status ? user.status.charAt(0).toUpperCase() + user
                .status.slice(1) : '-';
            document.getElementById('detailNoHp').textContent = user.no_hp || '-';
            document.getElementById('detailAlamat').textContent = user.alamat || '-';
            document.getElementById('detailModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('detailContent').classList.remove('scale-95', 'opacity-0');
                document.getElementById('detailContent').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('createContent').classList.remove('scale-95', 'opacity-0');
                document.getElementById('createContent').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function openEditModal(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editNama').value = user.nama || '';
            document.getElementById('editUsername').value = user.username || '';
            document.getElementById('editNoHp').value = user.no_hp || '';
            document.getElementById('editAlamat').value = user.alamat || '';
            document.getElementById('editRole').value = user.role || 'kasir';
            document.getElementById('editStatus').value = user.status || 'aktif';
            document.getElementById('editForm').action = `/owner/users/${user.id}`;
            document.getElementById('editModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('editContent').classList.remove('scale-95', 'opacity-0');
                document.getElementById('editContent').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(modalId) {
            const contentId = modalId.replace('Modal', 'Content');
            document.getElementById(contentId).classList.remove('scale-100', 'opacity-100');
            document.getElementById(contentId).classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                document.getElementById(modalId).classList.add('hidden');
            }, 300);
        }
    </script>
@endsection
