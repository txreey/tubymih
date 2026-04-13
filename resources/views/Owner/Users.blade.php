@extends('owner.layouts.app')

@section('title', 'Kelola User')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola User</h1>
            <p class="text-gray-600 mt-1">Kelola akun kasir dan admin</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">Total User</p>
                <p class="text-4xl font-bold text-teal-700 mt-3" id="summaryTotal">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">User Aktif</p>
                <p class="text-4xl font-bold text-green-600 mt-3" id="summaryAktif">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center transition hover:shadow-lg">
                <p class="text-sm font-medium text-gray-600">User Nonaktif</p>
                <p class="text-4xl font-bold text-red-600 mt-3" id="summaryNonaktif">0</p>
            </div>
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
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Cari</button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-sm">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar User</p>
                        <p class="text-xs text-gray-400 mt-1" id="userCountLabel">Memuat data...</p>
                    </div>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-xl shadow-sm hover:bg-teal-700 transition text-sm">
                    <i class="fas fa-plus text-sm"></i> Tambah User
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-16">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Nama Lengkap</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Username</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-users-slash text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data user
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>

                <div class="flex items-center gap-1.5">
                    <!-- Tombol Previous -->
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>

                    <!-- Kotak Angka -->
                    <div id="currentPageBox"
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center">
                        1
                    </div>

                    <!-- Tombol Next -->
                    <button onclick="nextPage()" id="btnNext"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="detailContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-user-circle"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Detail User</h2>
                        <p class="text-teal-100 text-xs mt-0.5 opacity-90">Informasi lengkap pengguna</p>
                    </div>
                </div>
                <button onclick="closeModal('detailModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"><span
                                id="detailNama">-</span></div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-at text-teal-600 text-sm"></i> Username</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"><span
                                id="detailUsername">-</span></div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-lock text-teal-600 text-sm"></i> Password</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700">
                            ••••••••••••</div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-phone text-teal-600 text-sm"></i> No HP</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"><span
                                id="detailNoHp">-</span></div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat</label>
                    <div
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 min-h-[52px]">
                        <span id="detailAlamat">-</span></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user-shield text-teal-600 text-sm"></i> Role</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"><span
                                id="detailRole">-</span></div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-toggle-on text-teal-600 text-sm"></i> Status</label>
                        <div class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-700"><span
                                id="detailStatus">-</span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button onclick="closeModal('detailModal')"
                    class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-md flex items-center gap-2">
                    <i class="fas fa-check"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="createContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-user-plus"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah User Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5 opacity-90">Isi data user baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="createNama" placeholder="Contoh: John Doe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-at text-teal-600 text-sm"></i> Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="createUsername" placeholder="Contoh: johndoe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateUsername"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-lock text-teal-600 text-sm"></i> Password <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" id="createPassword" placeholder="Minimal 6 karakter"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm pr-10">
                            <button type="button" onclick="togglePass('createPassword')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i
                                    class="fas fa-eye text-sm"></i></button>
                        </div>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreatePassword"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-phone text-teal-600 text-sm"></i> No Handphone</label>
                        <input type="text" id="createNoHp" placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNoHp"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat</label>
                    <textarea id="createAlamat" rows="2" placeholder="Contoh: Jl. Sudirman No. 45"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span></label>
                        <select id="createRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span></label>
                        <select id="createStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2"><i
                            class="fas fa-times"></i> Batal</button>
                    <button type="button" onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm"><i
                            class="fas fa-save text-sm"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0"
            id="editContent">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-user-edit"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Edit User</h2>
                        <p class="text-teal-100 text-xs mt-0.5 opacity-90">Ubah data user</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md"><i
                        class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-5">
                <input type="hidden" id="editUserId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user text-teal-600 text-sm"></i> Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editNama"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-at text-teal-600 text-sm"></i> Username <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editUsername"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditUsername"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-lock text-teal-600 text-sm"></i> Password <span
                                class="text-gray-400 text-xs ml-1">(kosongkan jika tidak diubah)</span></label>
                        <div class="relative">
                            <input type="password" id="editPassword" placeholder="Kosongkan jika tidak diubah"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm pr-10">
                            <button type="button" onclick="togglePass('editPassword')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"><i
                                    class="fas fa-eye text-sm"></i></button>
                        </div>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditPassword"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-phone text-teal-600 text-sm"></i> No HP</label>
                        <input type="text" id="editNoHp"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditNoHp"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-map-marker-alt text-teal-600 text-sm"></i> Alamat</label>
                    <textarea id="editAlamat" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-user-shield text-teal-600 text-sm"></i> Role <span
                                class="text-red-500">*</span></label>
                        <select id="editRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="kasir">Kasir</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span></label>
                        <select id="editStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.
                        Kosongkan password jika tidak ingin mengubah.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2"><i
                            class="fas fa-times"></i> Batal</button>
                    <button type="button" onclick="submitEdit()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm"><i
                            class="fas fa-save text-sm"></i> Update</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .ts-wrap {
            position: relative;
            display: inline-block;
            width: 36px;
            height: 20px;
            vertical-align: middle;
        }

        .ts-wrap input {
            display: none;
        }

        .ts-slider {
            position: absolute;
            inset: 0;
            background: #d1d5db;
            border-radius: 20px;
            cursor: pointer;
            transition: .2s;
        }

        .ts-slider::before {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            left: 3px;
            top: 3px;
            background: #fff;
            border-radius: 50%;
            transition: .2s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        }

        .ts-wrap input:checked+.ts-slider {
            background: #0d9488;
        }

        .ts-wrap input:checked+.ts-slider::before {
            transform: translateX(16px);
        }

        .ab {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all .15s;
        }

        .ab-eye {
            background: #f0fdf9;
            color: #0d9488;
        }

        .ab-eye:hover {
            background: #0d9488;
            color: #fff;
        }

        .ab-edit {
            background: #ede9fe;
            color: #6d28d9;
        }

        .ab-edit:hover {
            background: #6d28d9;
            color: #fff;
        }

        .ab-del {
            background: #fef2f2;
            color: #dc2626;
        }

        .ab-del:hover {
            background: #dc2626;
            color: #fff;
        }

        .s-aktif {
            background: #d1fae5;
            color: #065f46;
        }

        .s-nonaktif {
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>

    <script>
        // ─── DATA & KONSTANTA ───────────────────────────────────────────────
        let allUsers = @json($users);
        let filteredUsers = [...allUsers];
        const CSRF = '{{ csrf_token() }}';
        const PER_PAGE = 5;
        let currentPage = 1;

        const ROUTES = {
            store: '{{ route('owner.users.store') }}',
            update: (id) => `{{ url('owner/users') }}/${id}`,
            destroy: (id) => `{{ url('owner/users') }}/${id}`,
            toggleStatus: (id) => `{{ url('owner/users') }}/${id}/toggle-status`,
        };

        // ─── SUMMARY ─────────────────────────────────────────────────────
        function updateSummary() {
            document.getElementById('summaryTotal').textContent = allUsers.length;
            document.getElementById('summaryAktif').textContent = allUsers.filter(u => u.status === 'aktif').length;
            document.getElementById('summaryNonaktif').textContent = allUsers.filter(u => u.status === 'nonaktif').length;
        }

        // ─── RENDER ──────────────────────────────────────────────────────
        function renderTable() {
            const tbody = document.getElementById('userTableBody');
            const empty = document.getElementById('emptyState');
            const pgInfo = document.getElementById('paginationInfo');
            const currentBox = document.getElementById('currentPageBox');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');

            if (!filteredUsers.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                pgInfo.textContent = '';
                currentBox.textContent = '1';
                btnPrev.disabled = true;
                btnNext.disabled = true;
                document.getElementById('userCountLabel').textContent = 'Total: 0 user';
                return;
            }

            empty.classList.add('hidden');

            const total = filteredUsers.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            const start = (currentPage - 1) * PER_PAGE;
            const end = Math.min(start + PER_PAGE, total);
            const pageUsers = filteredUsers.slice(start, end);

            tbody.innerHTML = pageUsers.map((user, i) => {
                const checked = user.status === 'aktif' ? 'checked' : '';
                const sBadge = user.status === 'aktif' ? 's-aktif' : 's-nonaktif';
                const roleBadgeClass = user.role === 'admin' ?
                    'bg-purple-100 text-purple-800' :
                    'bg-teal-100 text-teal-800';

                return `
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors" id="row-${user.id}">
                    <td class="px-6 py-4 text-sm text-gray-400 font-medium">${start + i + 1}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escHtml(user.nama)}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${escHtml(user.username)}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full ${roleBadgeClass}">
                            ${capitalize(user.role)}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span id="badge-status-${user.id}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${sBadge}">
                            ${capitalize(user.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <label class="ts-wrap" title="Toggle Status">
                                <input type="checkbox" ${checked} onchange="window.doToggle(${user.id},this)">
                                <span class="ts-slider"></span>
                            </label>
                            <button class="ab ab-eye"  onclick="window.doDetail(${user.id})" title="Detail"><i class="fas fa-eye"></i></button>
                            <button class="ab ab-edit" onclick="window.doEdit(${user.id})"   title="Edit"><i class="fas fa-pencil-alt"></i></button>
                            <button class="ab ab-del"  onclick="window.doDelete(${user.id})" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                        </div>
                    </td>
                </tr>`;
            }).join('');

            // Update info & kotak halaman
            pgInfo.innerHTML = `Menampilkan <strong>${start + 1}–${end}</strong> dari <strong>${total}</strong> user`;
            currentBox.textContent = currentPage;
            document.getElementById('userCountLabel').textContent = `Total: ${allUsers.length} user`;

            // Enable / Disable tombol panah
            btnPrev.disabled = (currentPage === 1);
            btnNext.disabled = (currentPage === totalPages);
        }

        // ─── NAVIGASI PANAH ──────────────────────────────────────────────
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredUsers.length / PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        }

        // ─── FILTER ──────────────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase();
            const role = document.getElementById('filterRole').value;
            const status = document.getElementById('filterStatus').value;
            filteredUsers = allUsers.filter(u =>
                (!search || u.nama.toLowerCase().includes(search) || u.username.toLowerCase().includes(search)) &&
                (!role || u.role === role) &&
                (!status || u.status === status)
            );
            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterRole').value = '';
            document.getElementById('filterStatus').value = '';
            filteredUsers = [...allUsers];
            currentPage = 1;
            renderTable();
        }

        function exportData() {
            Swal.fire({
                icon: 'info',
                title: 'Info',
                text: 'Fitur export sedang dalam pengerjaan!'
            });
        }

        // ─── INIT ────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterRole').addEventListener('change', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            updateSummary();
            renderTable();
        });

        // Expose ke HTML
        window.prevPage = prevPage;
        window.nextPage = nextPage;
        window.doDetail = (id) => openDetailModal(id);
        window.doEdit = (id) => openEditModal(id);
        window.doDelete = (id) => deleteUser(id);
        window.doToggle = (id, el) => toggleStatus(id, el);

        // ─── MODAL ───────────────────────────────────────────────────────
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            setTimeout(() => {
                const c = document.getElementById(id.replace('Modal', 'Content'));
                c.classList.remove('scale-95', 'opacity-0');
                c.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const c = document.getElementById(id.replace('Modal', 'Content'));
            c.classList.remove('scale-100', 'opacity-100');
            c.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        function openDetailModal(userId) {
            const user = allUsers.find(u => Number(u.id) === Number(userId));
            if (!user) return;
            document.getElementById('detailNama').textContent = user.nama || '-';
            document.getElementById('detailUsername').textContent = user.username || '-';
            document.getElementById('detailRole').textContent = capitalize(user.role || '-');
            document.getElementById('detailStatus').textContent = capitalize(user.status || '-');
            document.getElementById('detailNoHp').textContent = user.no_hp || '-';
            document.getElementById('detailAlamat').textContent = user.alamat || '-';
            showModal('detailModal');
        }

        function openCreateModal() {
            ['createNama', 'createUsername', 'createPassword', 'createNoHp', 'createAlamat']
            .forEach(id => document.getElementById(id).value = '');
            document.getElementById('createRole').value = 'kasir';
            document.getElementById('createStatus').value = 'aktif';
            clearErrors('create');
            showModal('createModal');
        }

        function openEditModal(userId) {
            const user = allUsers.find(u => Number(u.id) === Number(userId));
            if (!user) return;
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

        // ─── VALIDASI ────────────────────────────────────────────────────
        function showError(elId, msg) {
            const el = document.getElementById(elId);
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function clearErrors(prefix) {
            document.querySelectorAll(`[id^="err${capitalize(prefix)}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function validateCreate() {
            clearErrors('create');
            let ok = true;
            const nama = document.getElementById('createNama').value.trim();
            const uname = document.getElementById('createUsername').value.trim();
            const pass = document.getElementById('createPassword').value;
            const hp = document.getElementById('createNoHp').value.trim();
            if (!nama) {
                showError('errCreateNama', 'Nama lengkap wajib diisi.');
                ok = false;
            } else if (nama.length > 100) {
                showError('errCreateNama', 'Nama maksimal 100 karakter.');
                ok = false;
            }
            if (!uname) {
                showError('errCreateUsername', 'Username wajib diisi.');
                ok = false;
            } else if (!/^[a-zA-Z0-9._-]+$/.test(uname)) {
                showError('errCreateUsername', 'Username hanya huruf, angka, titik, underscore, strip.');
                ok = false;
            }
            if (!pass) {
                showError('errCreatePassword', 'Password wajib diisi.');
                ok = false;
            } else if (pass.length < 6) {
                showError('errCreatePassword', 'Password minimal 6 karakter.');
                ok = false;
            }
            if (hp && !/^[0-9\+\-\s]{7,20}$/.test(hp)) {
                showError('errCreateNoHp', 'Format nomor HP tidak valid.');
                ok = false;
            }
            return ok;
        }

        function validateEdit() {
            clearErrors('edit');
            let ok = true;
            const nama = document.getElementById('editNama').value.trim();
            const uname = document.getElementById('editUsername').value.trim();
            const pass = document.getElementById('editPassword').value;
            const hp = document.getElementById('editNoHp').value.trim();
            if (!nama) {
                showError('errEditNama', 'Nama lengkap wajib diisi.');
                ok = false;
            } else if (nama.length > 100) {
                showError('errEditNama', 'Nama maksimal 100 karakter.');
                ok = false;
            }
            if (!uname) {
                showError('errEditUsername', 'Username wajib diisi.');
                ok = false;
            } else if (!/^[a-zA-Z0-9._-]+$/.test(uname)) {
                showError('errEditUsername', 'Username hanya huruf, angka, titik, underscore, strip.');
                ok = false;
            }
            if (pass && pass.length < 6) {
                showError('errEditPassword', 'Password minimal 6 karakter.');
                ok = false;
            }
            if (hp && !/^[0-9\+\-\s]{7,20}$/.test(hp)) {
                showError('errEditNoHp', 'Format nomor HP tidak valid.');
                ok = false;
            }
            return ok;
        }

        // ─── SUBMIT CREATE ───────────────────────────────────────────────
        async function submitCreate() {
            if (!validateCreate()) return;
            const payload = {
                nama: document.getElementById('createNama').value.trim(),
                username: document.getElementById('createUsername').value.trim(),
                password: document.getElementById('createPassword').value,
                no_hp: document.getElementById('createNoHp').value.trim(),
                alamat: document.getElementById('createAlamat').value.trim(),
                role: document.getElementById('createRole').value,
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
                    if (res.status === 422 && data.errors) handleLaravelErrors(data.errors, 'create');
                    else Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }
                allUsers.push(data.user);
                filteredUsers = [...allUsers];
                updateSummary();
                renderTable();
                closeModal('createModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'User baru berhasil ditambahkan.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ─── SUBMIT EDIT ─────────────────────────────────────────────────
        async function submitEdit() {
            if (!validateEdit()) return;
            const userId = document.getElementById('editUserId').value;
            const password = document.getElementById('editPassword').value;
            const payload = {
                nama: document.getElementById('editNama').value.trim(),
                username: document.getElementById('editUsername').value.trim(),
                no_hp: document.getElementById('editNoHp').value.trim(),
                alamat: document.getElementById('editAlamat').value.trim(),
                role: document.getElementById('editRole').value,
                status: document.getElementById('editStatus').value,
                _token: CSRF,
                _method: 'PUT',
            };
            if (password) payload.password = password;
            const btn = document.querySelector('#editContent button[onclick="submitEdit()"]');
            setLoading(btn, true);
            try {
                const res = await fetch(ROUTES.update(userId), {
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
                    if (res.status === 422 && data.errors) handleLaravelErrors(data.errors, 'edit');
                    else Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }
                const idx = allUsers.findIndex(u => Number(u.id) === Number(userId));
                if (idx !== -1) allUsers[idx] = data.user;
                applyFilter();
                updateSummary();
                closeModal('editModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data user berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ─── DELETE ──────────────────────────────────────────────────────
        async function deleteUser(userId) {
            const result = await Swal.fire({
                title: 'Hapus user ini?',
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
                allUsers = allUsers.filter(u => Number(u.id) !== Number(userId));
                applyFilter();
                updateSummary();
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'User berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            }
        }

        // ─── TOGGLE STATUS ───────────────────────────────────────────────
        async function toggleStatus(userId, checkbox) {
            const orig = !checkbox.checked;
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
                    checkbox.checked = orig;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }
                const idx = allUsers.findIndex(u => Number(u.id) === Number(userId));
                if (idx !== -1) {
                    allUsers[idx].status = checkbox.checked ? 'aktif' : 'nonaktif';
                    const badge = document.getElementById(`badge-status-${userId}`);
                    if (badge) {
                        const isAktif = allUsers[idx].status === 'aktif';
                        badge.textContent = isAktif ? 'Aktif' : 'Nonaktif';
                        badge.className =
                            `inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${isAktif ? 's-aktif' : 's-nonaktif'}`;
                    }
                    updateSummary();
                }
                Swal.fire({
                    icon: 'success',
                    title: 'Status diperbarui!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } catch {
                checkbox.checked = orig;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            }
        }

        // ─── LARAVEL ERRORS ──────────────────────────────────────────────
        function handleLaravelErrors(errors, prefix) {
            const map = {
                nama: 'errNama',
                username: 'errUsername',
                password: 'errPassword',
                no_hp: 'errNoHp'
            };
            Object.entries(errors).forEach(([f, msgs]) => {
                const key = `err${capitalize(prefix)}${capitalize(map[f]?.replace('err','') || '')}`;
                if (document.getElementById(key)) showError(key, msgs[0]);
            });
        }

        // ─── UTILS ───────────────────────────────────────────────────────
        function capitalize(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function togglePass(id) {
            const i = document.getElementById(id);
            i.type = i.type === 'password' ? 'text' : 'password';
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
    </script>
@endsection
