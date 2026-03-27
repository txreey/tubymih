@extends('admin.layouts.app')

@section('title', 'Kelola User')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola User</h1>
            <p class="text-gray-600 mt-1">Kelola akun kasir, admin, dan owner</p>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama / Username</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama atau username..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <select id="filterRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="filterStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button onclick="applyFilter()"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-sm">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
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
                    <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                        {{-- Diisi oleh JS --}}
                    </tbody>
                </table>
            </div>

            {{-- Empty state --}}
            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-500">
                <i class="fas fa-users-slash text-5xl text-gray-300 mb-4 block"></i>
                Belum ada data user
            </div>
        </div>
    </div>

    {{-- ============================
     MODAL DETAIL
=============================== --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="detailContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-8 py-6 text-white flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Detail User</h2>
                        <p class="text-teal-100 text-xs mt-1">Informasi lengkap pengguna</p>
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
                                <i class="fas fa-user"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailNama">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-at"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Username</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailUsername">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-user-shield"></i></div>
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
                                <i class="fas fa-toggle-on"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailStatus">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-phone"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">No HP</p>
                                <p class="text-lg font-semibold text-gray-900" id="detailNoHp">-</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl flex-shrink-0">
                                <i class="fas fa-map-marker-alt"></i></div>
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
                            <i class="fas fa-lock"></i></div>
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
                    class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-md flex items-center gap-2">
                    <i class="fas fa-check"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ============================
     MODAL TAMBAH USER
=============================== --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="createContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-user-plus"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah User Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data kasir baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="text-white/80 hover:text-white transition text-xl p-1.5 rounded-full hover:bg-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" id="createNama" placeholder="Contoh: John Doe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-at text-teal-600 text-sm"></i> Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="createUsername" placeholder="Contoh: johndoe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateUsername"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-lock text-teal-600 text-sm"></i> Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="createPassword" placeholder="Minimal 6 karakter"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm pr-10">
                            <button type="button" onclick="togglePass('createPassword')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreatePassword"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-phone text-teal-600 text-sm"></i> No HP
                        </label>
                        <input type="text" id="createNoHp" placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNoHp"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat
                    </label>
                    <textarea id="createAlamat" rows="2" placeholder="Contoh: Jl. Sudirman No. 45"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="createRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="createStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.
                        Hanya role kasir yang dapat ditambah oleh admin.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="button" onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================
     MODAL EDIT USER
=============================== --}}
    <div id="editModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="editContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-user-edit"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Edit User</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Ubah data kasir</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="text-white/80 hover:text-white transition text-xl p-1.5 rounded-full hover:bg-white/10">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 space-y-5">
                <input type="hidden" id="editUserId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editNama"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-at text-teal-600 text-sm"></i> Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editUsername"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditUsername"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-lock text-teal-600 text-sm"></i> Password
                            <span class="text-gray-500 text-xs ml-1">(kosongkan jika tidak diubah)</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="editPassword" placeholder="Kosongkan jika tidak diubah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm pr-10">
                            <button type="button" onclick="togglePass('editPassword')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditPassword"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-phone text-teal-600 text-sm"></i> No HP
                        </label>
                        <input type="text" id="editNoHp"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditNoHp"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat
                    </label>
                    <textarea id="editAlamat" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="editRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="editStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.
                        Password kosongkan jika tidak ingin mengubah.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="button" onclick="submitEdit()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ─────────────────────────────────────────────
        // STATE: simpan semua user di memori (real-time)
        // ─────────────────────────────────────────────
        let allUsers = @json($users);

        // CSRF token untuk semua request
        const CSRF = '{{ csrf_token() }}';

        // Base URL routes
        const ROUTES = {
            store: '{{ route('admin.users.store') }}',
            update: (id) => `{{ url('admin/users') }}/${id}`,
            destroy: (id) => `{{ url('admin/users') }}/${id}`,
            toggleStatus: (id) => `{{ url('admin/users') }}/${id}/toggle-status`,
        };

        // ─────────────────────────────────────────────
        // RENDER TABLE
        // ─────────────────────────────────────────────
        function renderTable(users) {
            const tbody = document.getElementById('userTableBody');
            const empty = document.getElementById('emptyState');

            if (!users.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }

            empty.classList.add('hidden');
            tbody.innerHTML = users.map((user, idx) => {
                const id = user.id;
                const isKasir = user.role === 'kasir';
                const roleBadge = user.role === 'admin' ? 'bg-purple-100 text-purple-800' :
                    user.role === 'owner' ? 'bg-amber-100 text-amber-800' :
                    'bg-teal-100 text-teal-800';
                const statusBadge = user.status === 'aktif' ? 'bg-green-100 text-green-800' :
                    'bg-red-100 text-red-800';
                const checked = user.status === 'aktif' ? 'checked' : '';

                const kasirActions = isKasir ?
                    `<button onclick="window.doEdit(${id})" title="Edit"
                   class="text-indigo-600 hover:text-indigo-800 transition text-xl">
                   <i class="fas fa-pencil-alt"></i>
               </button>
               <label class="relative inline-flex items-center cursor-pointer" title="Toggle Status">
                   <input type="checkbox" ${checked} onchange="window.doToggle(${id}, this)"
                       class="sr-only peer">
                   <div class="w-10 h-5 bg-gray-300 peer-focus:ring-2 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-teal-600"></div>
               </label>
               <button onclick="window.doDelete(${id})" title="Hapus"
                   class="text-red-600 hover:text-red-800 transition text-xl">
                   <i class="fas fa-trash-alt"></i>
               </button>` :
                    '<span class="text-xs text-gray-400 italic">View only</span>';

                return `
        <tr class="hover:bg-gray-50 transition duration-150" id="row-${id}">
            <td class="px-6 py-4 text-sm text-gray-600">${idx + 1}</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">${escHtml(user.nama)}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${escHtml(user.username)}</td>
            <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full ${roleBadge}">
                    ${capitalize(user.role)}
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full ${statusBadge}">
                    ${capitalize(user.status)}
                </span>
            </td>
            <td class="px-6 py-4 text-sm font-medium flex items-center gap-4">
                <button onclick="window.doDetail(${id})" title="Detail"
                    class="text-teal-600 hover:text-teal-800 transition text-xl">
                    <i class="fas fa-eye"></i>
                </button>
                ${kasirActions}
            </td>
        </tr>`;
            }).join('');
        }

        // ─────────────────────────────────────────────
        // FILTER (client-side, real-time)
        // ─────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase();
            const status = document.getElementById('filterStatus').value;
            const role = document.getElementById('filterRole').value;

            const filtered = allUsers.filter(u => {
                const matchSearch = !search ||
                    u.nama.toLowerCase().includes(search) ||
                    u.username.toLowerCase().includes(search);
                const matchStatus = !status || u.status === status;
                const matchRole = !role || u.role === role;
                return matchSearch && matchStatus && matchRole;
            });

            renderTable(filtered);
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterRole').value = '';
            renderTable(allUsers);
        }

        // Live saat ngetik/pilih
        document.getElementById('filterSearch').addEventListener('input', applyFilter);
        document.getElementById('filterStatus').addEventListener('change', applyFilter);
        document.getElementById('filterRole').addEventListener('change', applyFilter);

        // ─────────────────────────────────────────────
        // MODAL HELPERS
        // ─────────────────────────────────────────────
        function openDetailModal(userId) {
            const uid = Number(userId);
            const user = allUsers.find(u => Number(u.id) === uid);
            if (!user) {
                console.warn('openDetailModal: user tidak ditemukan, id=', userId, allUsers);
                return;
            }

            document.getElementById('detailNama').textContent = user.nama || '-';
            document.getElementById('detailUsername').textContent = user.username || '-';
            document.getElementById('detailRole').textContent = capitalize(user.role || '-');
            document.getElementById('detailStatus').textContent = capitalize(user.status || '-');
            document.getElementById('detailNoHp').textContent = user.no_hp || '-';
            document.getElementById('detailAlamat').textContent = user.alamat || '-';

            showModal('detailModal');
        }

        function openCreateModal() {
            // Reset form
            ['createNama', 'createUsername', 'createPassword', 'createNoHp', 'createAlamat'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('createRole').value = 'kasir';
            document.getElementById('createStatus').value = 'aktif';
            clearErrors('create');
            showModal('createModal');
        }

        function openEditModal(userId) {
            const uid = Number(userId);
            const user = allUsers.find(u => Number(u.id) === uid);
            if (!user) {
                console.warn('openEditModal: user tidak ditemukan, id=', userId);
                return;
            }

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editNama').value = user.nama || '';
            document.getElementById('editUsername').value = user.username || '';
            document.getElementById('editPassword').value = '';
            document.getElementById('editNoHp').value = user.no_hp || '';
            document.getElementById('editAlamat').value = user.alamat || '';
            document.getElementById('editRole').value = user.role || 'kasir';
            document.getElementById('editStatus').value = user.status || 'aktif';

            clearErrors('edit');
            showModal('editModal');
        }

        function showModal(id) {
            const modal = document.getElementById(id);
            const content = document.getElementById(id.replace('Modal', 'Content'));
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const content = document.getElementById(id.replace('Modal', 'Content'));
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        // ─────────────────────────────────────────────
        // VALIDASI CLIENT-SIDE
        // ─────────────────────────────────────────────
        function validateCreate() {
            let valid = true;
            clearErrors('create');

            const nama = document.getElementById('createNama').value.trim();
            const username = document.getElementById('createUsername').value.trim();
            const password = document.getElementById('createPassword').value;
            const noHp = document.getElementById('createNoHp').value.trim();

            if (!nama) {
                showError('errCreateNama', 'Nama lengkap wajib diisi.');
                valid = false;
            } else if (nama.length > 100) {
                showError('errCreateNama', 'Nama maksimal 100 karakter.');
                valid = false;
            }

            if (!username) {
                showError('errCreateUsername', 'Username wajib diisi.');
                valid = false;
            } else if (username.length > 100) {
                showError('errCreateUsername', 'Username maksimal 100 karakter.');
                valid = false;
            } else if (!/^[a-zA-Z0-9._-]+$/.test(username)) {
                showError('errCreateUsername', 'Username hanya boleh huruf, angka, titik, underscore, strip.');
                valid = false;
            }

            if (!password) {
                showError('errCreatePassword', 'Password wajib diisi.');
                valid = false;
            } else if (password.length < 6) {
                showError('errCreatePassword', 'Password minimal 6 karakter.');
                valid = false;
            }

            if (noHp && !/^[0-9\+\-\s]{7,20}$/.test(noHp)) {
                showError('errCreateNoHp', 'Format nomor HP tidak valid.');
                valid = false;
            }

            return valid;
        }

        function validateEdit() {
            let valid = true;
            clearErrors('edit');

            const nama = document.getElementById('editNama').value.trim();
            const username = document.getElementById('editUsername').value.trim();
            const password = document.getElementById('editPassword').value;
            const noHp = document.getElementById('editNoHp').value.trim();

            if (!nama) {
                showError('errEditNama', 'Nama lengkap wajib diisi.');
                valid = false;
            } else if (nama.length > 100) {
                showError('errEditNama', 'Nama maksimal 100 karakter.');
                valid = false;
            }

            if (!username) {
                showError('errEditUsername', 'Username wajib diisi.');
                valid = false;
            } else if (username.length > 100) {
                showError('errEditUsername', 'Username maksimal 100 karakter.');
                valid = false;
            } else if (!/^[a-zA-Z0-9._-]+$/.test(username)) {
                showError('errEditUsername', 'Username hanya boleh huruf, angka, titik, underscore, strip.');
                valid = false;
            }

            if (password && password.length < 6) {
                showError('errEditPassword', 'Password minimal 6 karakter.');
                valid = false;
            }

            if (noHp && !/^[0-9\+\-\s]{7,20}$/.test(noHp)) {
                showError('errEditNoHp', 'Format nomor HP tidak valid.');
                valid = false;
            }

            return valid;
        }

        function showError(elId, msg) {
            const el = document.getElementById(elId);
            el.textContent = msg;
            el.classList.remove('hidden');
            // Highlight input border merah
            const input = el.previousElementSibling?.tagName === 'INPUT' || el.previousElementSibling?.tagName ===
                'TEXTAREA' ?
                el.previousElementSibling :
                el.previousElementSibling?.querySelector('input');
            if (input) input.classList.add('border-red-400');
        }

        function clearErrors(prefix) {
            document.querySelectorAll(`[id^="err${capitalize(prefix)}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            // Reset border semua input di modal
            const modal = document.getElementById(`${prefix}Modal`) || document.getElementById(`${prefix}Content`);
            if (modal) modal.querySelectorAll('input, textarea').forEach(el => el.classList.remove('border-red-400'));
        }

        // ─────────────────────────────────────────────
        // SUBMIT CREATE
        // ─────────────────────────────────────────────
        async function submitCreate() {
            if (!validateCreate()) return;

            const payload = {
                nama: document.getElementById('createNama').value.trim(),
                username: document.getElementById('createUsername').value.trim(),
                password: document.getElementById('createPassword').value,
                no_hp: document.getElementById('createNoHp').value.trim(),
                alamat: document.getElementById('createAlamat').value.trim(),
                role: 'kasir',
                status: document.getElementById('createStatus').value,
                _token: CSRF,
            };

            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            setLoading(btn, true);

            try {
                const res = await fetch(ROUTES.store, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    // Handle Laravel validation errors (422)
                    if (res.status === 422 && data.errors) {
                        handleLaravelErrors(data.errors, 'create');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan.'
                        });
                    }
                    return;
                }

                // ✅ Tambah ke state & render
                allUsers.push(data.user);
                renderTable(allUsers);
                closeModal('createModal');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kasir baru berhasil ditambahkan.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ─────────────────────────────────────────────
        // SUBMIT EDIT
        // ─────────────────────────────────────────────
        async function submitEdit() {
            if (!validateEdit()) return;

            const userId = document.getElementById('editUserId').value;
            const password = document.getElementById('editPassword').value;

            const payload = {
                nama: document.getElementById('editNama').value.trim(),
                username: document.getElementById('editUsername').value.trim(),
                no_hp: document.getElementById('editNoHp').value.trim(),
                alamat: document.getElementById('editAlamat').value.trim(),
                role: 'kasir',
                status: document.getElementById('editStatus').value,
                _token: CSRF,
                _method: 'PUT',
            };

            if (password) payload.password = password;

            const btn = document.querySelector('#editContent button[onclick="submitEdit()"]');
            setLoading(btn, true);

            try {
                const res = await fetch(ROUTES.update(userId), {
                    method: 'POST', // Laravel pakai _method:PUT
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    if (res.status === 422 && data.errors) {
                        handleLaravelErrors(data.errors, 'edit');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan.'
                        });
                    }
                    return;
                }

                // ✅ Update state & render
                const idx = allUsers.findIndex(u => Number(u.id) === Number(userId));
                if (idx !== -1) allUsers[idx] = data.user;
                applyFilter(); // render ulang dengan filter yang aktif
                closeModal('editModal');

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data kasir berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ─────────────────────────────────────────────
        // DELETE
        // ─────────────────────────────────────────────
        async function deleteUser(userId) {
            const result = await Swal.fire({
                title: 'Hapus kasir ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(ROUTES.destroy(userId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        _method: 'DELETE',
                        _token: CSRF
                    }),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }

                // ✅ Hapus dari state & render
                allUsers = allUsers.filter(u => Number(u.id) !== Number(userId));
                applyFilter();

                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Kasir berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            }
        }

        // ─────────────────────────────────────────────
        // TOGGLE STATUS
        // ─────────────────────────────────────────────
        async function toggleStatus(userId, checkbox) {
            const originalChecked = !checkbox.checked; // sebelum diubah

            try {
                const res = await fetch(ROUTES.toggleStatus(userId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        _token: CSRF
                    }),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    checkbox.checked = originalChecked; // rollback
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }

                // ✅ Update state
                const idx = allUsers.findIndex(u => Number(u.id) === Number(userId));
                if (idx !== -1) {
                    allUsers[idx].status = checkbox.checked ? 'aktif' : 'nonaktif';
                    // Update badge status di baris ybs tanpa render ulang seluruh tabel
                    const row = document.getElementById(`row-${userId}`);
                    const badge = row?.querySelector('td:nth-child(5) span');
                    if (badge) {
                        const isAktif = allUsers[idx].status === 'aktif';
                        badge.textContent = isAktif ? 'Aktif' : 'Nonaktif';
                        badge.className =
                            `inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full ${isAktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Status diperbarui!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });

            } catch (err) {
                checkbox.checked = originalChecked; // rollback
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            }
        }

        // ─────────────────────────────────────────────
        // HANDLE LARAVEL VALIDATION ERRORS
        // ─────────────────────────────────────────────
        function handleLaravelErrors(errors, prefix) {
            const map = {
                nama: `err${capitalize(prefix)}Nama`,
                username: `err${capitalize(prefix)}Username`,
                password: `err${capitalize(prefix)}Password`,
                no_hp: `err${capitalize(prefix)}NoHp`,
            };

            Object.entries(errors).forEach(([field, messages]) => {
                if (map[field]) showError(map[field], messages[0]);
            });
        }

        // ─────────────────────────────────────────────
        // UTILS
        // ─────────────────────────────────────────────
        function capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function escHtml(str) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str || ''));
            return div.innerHTML;
        }

        function togglePass(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        function setLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                btn.disabled = true;
                btn.dataset.orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i> Proses...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.orig;
            }
        }

        // ─────────────────────────────────────────────
        // EVENT DELEGATION — satu listener untuk semua aksi di tabel
        // pakai data-action + data-id biar id gak ketukar
        // ─────────────────────────────────────────────
        // window.do* — dipanggil langsung dari onclick di renderTable
        window.doDetail = (id) => openDetailModal(id);
        window.doEdit = (id) => openEditModal(id);
        window.doDelete = (id) => deleteUser(id);
        window.doToggle = (id, el) => toggleStatus(id, el);

        // ─────────────────────────────────────────────
        // INIT — render tabel saat halaman pertama load
        // ─────────────────────────────────────────────
        renderTable(allUsers);
    </script>
@endsection
