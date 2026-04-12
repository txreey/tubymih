@extends('admin.layouts.app')
@section('title', 'Kelola Menu')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Menu</h1>
            <p class="text-gray-600 mt-1">Kelola daftar menu makanan dan minuman</p>
        </div>

        {{-- Statistik Cards Baru --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Makanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-orange-600">Total Makanan</p>
                        <p class="text-3xl font-bold text-orange-700 mt-1" id="totalMakananCard">0</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-utensils text-orange-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Minuman -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600">Total Minuman</p>
                        <p class="text-3xl font-bold text-blue-700 mt-1" id="totalMinumanCard">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-coffee text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Menu Aktif -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-500">Menu Aktif</p>
                        <p class="text-3xl font-bold text-green-600 mt-1" id="aktifCard">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Menu Nonaktif -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Menu Nonaktif</p>
                        <p class="text-3xl font-bold text-gray-600 mt-1" id="nonaktifCard">0</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-ban text-gray-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Menu Habis -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-500">Menu Habis</p>
                        <p class="text-3xl font-bold text-red-600 mt-1" id="habisCard">0</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Menu</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama menu..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select id="filterKategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="">-- Semua --</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="filterStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="">-- Semua --</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="kosong">Habis</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button onclick="terapkanFilter()"
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

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div>
                    <p class="text-sm font-bold text-gray-800">Daftar Menu</p>
                    <p class="text-xs text-gray-400 mt-1" id="totalMenuLabel">Total: 0 Menu</p>
                </div>
                <button onclick="bukaModalTambah()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-xl shadow-sm hover:bg-teal-700 transition text-sm">
                    + Tambah Menu
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-14">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Nama Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Gambar</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-utensils text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data menu
            </div>

            <div id="paginationWrapper"
                class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-500" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5">
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <div id="currentPageBox"
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center">
                        1</div>
                    <button onclick="nextPage()" id="btnNext"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Menu Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data menu baru</p>
                    </div>
                </div>
                <button onclick="tutupModal('createModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="createForm" enctype="multipart/form-data" class="p-6 bg-gray-50 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-teal-600 text-sm"></i> Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="createKategoriInduk"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"
                            onchange="onKategoriIndukBerubah()">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateKategoriInduk"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-layer-group text-teal-600 text-sm"></i> Jenis <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="createKategori" name="id_kategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="" selected disabled>pilih kategori dulu</option>
                        </select>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateKategori"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-utensils text-teal-600 text-sm"></i> Nama Menu <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" id="createNama" name="nama_makanan"
                            placeholder="Contoh: Nasi Goreng Spesial"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-money-bill text-teal-600 text-sm"></i> Harga <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="number" id="createHarga" name="harga" placeholder="Contoh: 25000"
                            min="0"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateHarga"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-boxes-stacked text-teal-600 text-sm"></i> Stok <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="number" id="createStok" name="stok" placeholder="Contoh: 50" min="0"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateStok"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-image text-teal-600 text-sm"></i> Gambar Menu <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="file" id="createGambar" name="gambar" accept="image/*"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateGambar"></p>
                        <img id="createGambarPreview" src="#" alt="Preview"
                            class="mt-2 w-24 h-24 object-cover rounded-lg hidden shadow">
                    </div>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.
                        Pilih <strong>Kategori</strong> terlebih dahulu sebelum memilih Jenis.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="tutupModal('createModal')"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL HARGA --}}
    <div id="hargaModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="hargaContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div
                class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Kelola Harga</h2>
                        <p class="text-emerald-100 text-xs mt-0.5">Ubah harga jual menu</p>
                    </div>
                </div>
                <button onclick="tutupModal('hargaModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" id="hargaMenuId">
                <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                    <p class="text-xs text-gray-500">Nama Menu</p>
                    <p class="font-semibold text-gray-900" id="hargaNama">-</p>
                    <p class="text-xs text-gray-500 mt-2">Kategori</p>
                    <p class="text-sm text-gray-700" id="hargaKategori">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Harga Saat Ini</p>
                    <p class="text-2xl font-bold text-emerald-700" id="hargaSaatIni">Rp 0</p>
                </div>
                <div id="hargaSebelumnyaWrapper"
                    class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-amber-500 text-sm"></i>
                        <p class="text-xs text-amber-700 font-medium">Harga Sebelum Edit Terakhir</p>
                    </div>
                    <p class="text-xl font-bold text-amber-800" id="hargaSebelumnya">-</p>
                    <p class="text-xs text-amber-600">Nilai harga sebelum perubahan terakhir dilakukan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Baru <span
                            class="text-red-500">*</span></label>
                    <input type="number" id="inputHargaBaru" min="0" placeholder="Masukkan harga baru"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 text-lg outline-none">
                    <p class="text-red-500 text-xs mt-1 hidden" id="errHarga"></p>
                </div>
            </div>
            <div class="px-6 pb-5 flex justify-end gap-3">
                <button onclick="tutupModal('hargaModal')"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button onclick="submitHarga()"
                    class="px-7 py-2.5 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition shadow-md flex items-center gap-2 text-sm">
                    <i class="fas fa-save"></i> Simpan Harga
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL STOK --}}
    <div id="stokModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="stokContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Kelola Stok</h2>
                        <p class="text-blue-100 text-xs mt-0.5">Tambah / kurangi stok menu</p>
                    </div>
                </div>
                <button onclick="tutupModal('stokModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" id="stokMenuId">
                <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                    <p class="text-xs text-gray-500">Nama Menu</p>
                    <p class="font-semibold text-gray-900" id="stokNama">-</p>
                </div>
                <div class="flex items-center justify-between bg-blue-50 rounded-xl px-5 py-4">
                    <div>
                        <p class="text-xs text-gray-500">Stok Saat Ini</p>
                        <p class="text-3xl font-bold text-blue-700" id="stokSaatIni">0</p>
                        <p class="text-xs text-gray-400">pcs</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 text-2xl"></i>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Setelah Update</p>
                        <p class="text-3xl font-bold text-gray-800" id="stokTotal">0</p>
                        <p class="text-xs text-gray-400">pcs</p>
                    </div>
                </div>
                <div id="stokSebelumnyaWrapper"
                    class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-amber-500 text-sm"></i>
                        <p class="text-xs text-amber-700 font-medium">Stok Sebelum Edit Terakhir</p>
                    </div>
                    <p class="text-xl font-bold text-amber-800" id="stokSebelumnya">-</p>
                    <p class="text-xs text-amber-600">Nilai stok sebelum perubahan terakhir dilakukan</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok Baru <span class="text-gray-400 font-normal text-xs ml-1">(positif untuk tambah, negatif untuk
                            kurang)</span>
                    </label>
                    <input type="number" id="inputJumlahStok" placeholder="Contoh: 10 atau -5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-400 text-lg outline-none"
                        oninput="hitungTotalStok()">
                    <p class="text-red-500 text-xs mt-1 hidden" id="errStok"></p>
                </div>
            </div>
            <div class="px-6 pb-5 flex justify-end gap-3">
                <button onclick="tutupModal('stokModal')"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button onclick="submitStok()"
                    class="px-7 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md flex items-center gap-2 text-sm">
                    <i class="fas fa-save"></i> Update Stok
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let semuaMenu = @json($menus);
        let semuaKategori = @json($kategoris);
        let menuTerfilter = [...semuaMenu];

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            simpan: '{{ route('admin.menu.store') }}',
            update: (id) => `{{ url('admin/menu') }}/${id}`,
            toggleStatus: (id) => `{{ url('admin/menu') }}/${id}/toggle-status`,
            cekPesanan: (id) => `{{ url('admin/menu') }}/${id}/check-orders`,
        };

        const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
        const PER_PAGE = 5;
        let halamanAktif = 1;
        let totalHalamanGlobal = 1;

        function escapeHtml(s) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(s || ''));
            return div.innerHTML;
        }

        function updateStatistikCards() {
            const totalMakanan = semuaMenu.filter(m => m.kategori && m.kategori.nama_kategori === 'Makanan').length;
            const totalMinuman = semuaMenu.filter(m => m.kategori && m.kategori.nama_kategori === 'Minuman').length;
            const aktif = semuaMenu.filter(m => m.status === 'aktif').length;
            const nonaktif = semuaMenu.filter(m => m.status === 'nonaktif').length;
            const habis = semuaMenu.filter(m => m.status === 'kosong').length;

            document.getElementById('totalMakananCard').textContent = totalMakanan;
            document.getElementById('totalMinumanCard').textContent = totalMinuman;
            document.getElementById('aktifCard').textContent = aktif;
            document.getElementById('nonaktifCard').textContent = nonaktif;
            document.getElementById('habisCard').textContent = habis;
            document.getElementById('totalMenuLabel').textContent = `Total: ${semuaMenu.length} Menu`;
        }

        function renderHalaman() {
            const tbody = document.getElementById('menuTableBody');
            const empty = document.getElementById('emptyState');
            const wrapperPag = document.getElementById('paginationWrapper');
            const total = menuTerfilter.length;

            if (!total) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                wrapperPag.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');
            wrapperPag.classList.remove('hidden');

            const totalHalaman = Math.ceil(total / PER_PAGE);
            if (halamanAktif > totalHalaman) halamanAktif = totalHalaman;

            const mulai = (halamanAktif - 1) * PER_PAGE;
            const dataHalaman = menuTerfilter.slice(mulai, mulai + PER_PAGE);

            tbody.innerHTML = dataHalaman.map((m, i) => {
                const nomor = mulai + i + 1;
                const induk = m.kategori?.nama_kategori || '';
                const jenis = m.kategori?.jenis || '-';
                const warnaBadge = induk === 'Makanan' ? 'bg-orange-100 text-orange-700' :
                    induk === 'Minuman' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600';

                const gambar = m.gambar ?
                    `<img src="/storage/${m.gambar}" class="w-12 h-12 object-cover rounded-lg shadow-sm" onerror="this.src='https://placehold.co/48x48/e2e8f0/94a3b8?text=No+Img'">` :
                    `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><i class="fas fa-image text-lg"></i></div>`;

                let statusHtml = '';
                if (m.status === 'kosong') {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700"><i class="fas fa-times-circle"></i> Habis</span>`;
                } else if (m.status === 'aktif') {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"><i class="fas fa-circle text-[8px]"></i> Aktif</span>`;
                } else {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500"><i class="fas fa-circle text-[8px]"></i> Nonaktif</span>`;
                }

                const btnToggle = (m.status === 'kosong') ?
                    `<button title="Menu Habis - Tidak bisa toggle" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" disabled>
                        <i class="fas fa-toggle-off text-sm"></i>
                    </button>` :
                    (m.status === 'aktif' ?
                        `<button onclick="toggleStatus(${m.id})" title="Nonaktifkan Menu" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition">
                            <i class="fas fa-toggle-on text-sm"></i>
                        </button>` :
                        `<button onclick="toggleStatus(${m.id})" title="Aktifkan Menu" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 hover:bg-gray-200 transition">
                            <i class="fas fa-toggle-off text-sm"></i>
                        </button>`);

                return `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors" id="row-${m.id}">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">${nomor}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escapeHtml(m.nama_makanan)}</td>
                        <td class="px-6 py-4"><span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${warnaBadge}">${escapeHtml(jenis)}</span></td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-700" id="harga-${m.id}">${formatRupiah(m.harga)}</td>
                        <td class="px-6 py-4 text-sm font-semibold ${
                            m.stok === 0 ? 'text-red-600' : m.stok <= 3 ? 'text-orange-600' : 'text-gray-700'
                        }" id="stok-${m.id}">${m.stok}</td>
                        <td class="px-6 py-4" id="status-${m.id}">${statusHtml}</td>
                        <td class="px-6 py-4">${gambar}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="bukaModalHarga(${m.id})" title="Kelola Harga" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                    <i class="fas fa-dollar-sign text-xs"></i>
                                </button>
                                <button onclick="bukaModalStok(${m.id})" title="Kelola Stok" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                    <i class="fas fa-boxes-stacked text-xs"></i>
                                </button>
                                ${btnToggle}
                            </div>
                        </td>
                    </tr>`;
            }).join('');

            renderPagination(total, totalHalaman);
            updateStatistikCards();
        }

        function renderPagination(total, totalHalaman) {
            totalHalamanGlobal = totalHalaman;
            const info = document.getElementById('paginationInfo');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const currentPageBox = document.getElementById('currentPageBox');

            const mulai = (halamanAktif - 1) * PER_PAGE + 1;
            const sampai = Math.min(halamanAktif * PER_PAGE, total);
            info.innerHTML = `Menampilkan <strong>${mulai}-${sampai}</strong> dari <strong>${total}</strong> menu`;
            currentPageBox.textContent = halamanAktif;
            btnPrev.disabled = halamanAktif <= 1;
            btnNext.disabled = halamanAktif >= totalHalaman;
        }

        function prevPage() {
            if (halamanAktif > 1) {
                halamanAktif--;
                renderHalaman();
            }
        }

        function nextPage() {
            if (halamanAktif < totalHalamanGlobal) {
                halamanAktif++;
                renderHalaman();
            }
        }

        function terapkanFilter() {
            const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
            const induk = document.getElementById('filterKategori').value;
            const statusFilter = document.getElementById('filterStatus').value;

            menuTerfilter = semuaMenu.filter(m => {
                const cocokKeyword = !keyword || m.nama_makanan.toLowerCase().includes(keyword);
                const cocokInduk = !induk || (m.kategori?.nama_kategori === induk);

                let cocokStatus = true;
                if (statusFilter === 'aktif') cocokStatus = m.status === 'aktif';
                else if (statusFilter === 'nonaktif') cocokStatus = m.status === 'nonaktif';
                else if (statusFilter === 'kosong') cocokStatus = m.status === 'kosong';

                return cocokKeyword && cocokInduk && cocokStatus;
            });

            halamanAktif = 1;
            renderHalaman();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterStatus').value = '';
            menuTerfilter = [...semuaMenu];
            halamanAktif = 1;
            renderHalaman();
        }

        // ============================================================
        // MODAL TAMBAH
        // ============================================================
        function buatPetaKategori() {
            const peta = {
                Makanan: [],
                Minuman: []
            };
            semuaKategori.forEach(k => {
                const induk = k.nama_kategori;
                if (peta[induk]) peta[induk].push({
                    id: k.id,
                    jenis: k.jenis
                });
            });
            return peta;
        }

        function isiDropdownJenis(selectId, nilaiInduk) {
            const select = document.getElementById(selectId);
            if (!nilaiInduk) {
                select.innerHTML = '<option value="" selected disabled>pilih kategori dulu</option>';
                select.disabled = true;
                select.classList.add('bg-gray-100', 'cursor-not-allowed');
                return;
            }
            const peta = buatPetaKategori();
            const daftar = (peta[nilaiInduk] || []).sort((a, b) => a.jenis.localeCompare(b.jenis));
            select.innerHTML = '<option value="">-- Pilih Jenis --</option>' + daftar.map(k =>
                `<option value="${k.id}">${escapeHtml(k.jenis)}</option>`).join('');
            select.disabled = false;
            select.classList.remove('bg-gray-100', 'cursor-not-allowed');
        }

        function onKategoriIndukBerubah() {
            const nilai = document.getElementById('createKategoriInduk').value;
            isiDropdownJenis('createKategori', nilai);
            document.getElementById('errCreateKategoriInduk').classList.add('hidden');
            document.getElementById('errCreateKategori').classList.add('hidden');
        }

        function validasiTambah() {
            let valid = true;
            // bersihkan error (simple version)
            document.querySelectorAll('[id^="errCreate"]').forEach(el => el.classList.add('hidden'));

            if (!document.getElementById('createKategoriInduk').value) valid = false;
            if (!document.getElementById('createKategori').value) valid = false;
            if (!document.getElementById('createNama').value.trim()) valid = false;
            if (!document.getElementById('createHarga').value) valid = false;
            if (!document.getElementById('createStok').value) valid = false;
            if (!document.getElementById('createGambar').files[0]) valid = false;

            return valid;
        }

        function bukaModalTambah() {
            document.getElementById('createForm').reset();
            document.getElementById('createGambarPreview').classList.add('hidden');
            document.getElementById('createKategoriInduk').value = '';
            isiDropdownJenis('createKategori', '');
            tampilkanModal('createModal');
        }

        async function submitCreate() {
            if (!validasiTambah()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap'
                });
                return;
            }

            const form = new FormData(document.getElementById('createForm'));

            try {
                const res = await fetch(ROUTES.simpan, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: form
                });
                const data = await res.json();

                if (data.success) {
                    semuaMenu.push(data.data);
                    menuTerfilter.push(data.data);
                    renderHalaman();
                    tutupModal('createModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Menu baru ditambahkan',
                        timer: 2000,
                        toast: true
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan'
                });
            }
        }

        // ============================================================
        // MODAL HARGA
        // ============================================================
        window.bukaModalHarga = async function(id) {
            const sedangDipesan = await cekApakahDipesan(id);
            if (sedangDipesan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Diubah',
                    text: 'Menu ini sedang dipesan, harga tidak bisa diubah.'
                });
                return;
            }

            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            document.getElementById('hargaMenuId').value = m.id;
            document.getElementById('hargaNama').textContent = m.nama_makanan;
            document.getElementById('hargaKategori').textContent = m.kategori ? m.kategori.nama_kategori + ' — ' + m
                .kategori.jenis : '-';
            document.getElementById('hargaSaatIni').textContent = formatRupiah(m.harga);
            document.getElementById('inputHargaBaru').value = m.harga;

            tampilkanModal('hargaModal');
        };

        window.submitHarga = async function() {
            const id = document.getElementById('hargaMenuId').value;
            const hargaBaru = document.getElementById('inputHargaBaru').value;

            if (!hargaBaru || Number(hargaBaru) < 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Harga tidak valid'
                });
                return;
            }

            try {
                const form = new FormData();
                form.append('_method', 'PUT');
                form.append('harga', hargaBaru);

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: form
                });

                const data = await res.json();
                if (data.success) {
                    const idx = semuaMenu.findIndex(x => Number(x.id) === Number(id));
                    if (idx !== -1) semuaMenu[idx] = data.data;
                    menuTerfilter = menuTerfilter.map(m => Number(m.id) === Number(id) ? data.data : m);

                    renderHalaman();
                    tutupModal('hargaModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Harga diperbarui',
                        timer: 2000,
                        toast: true
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan'
                });
            }
        };

        // ============================================================
        // MODAL STOK
        // ============================================================
        window.bukaModalStok = async function(id) {
            const sedangDipesan = await cekApakahDipesan(id);
            if (sedangDipesan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Diubah',
                    text: 'Menu ini sedang dipesan, stok tidak bisa diubah.'
                });
                return;
            }

            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            stokSaatIni = parseInt(m.stok);
            document.getElementById('stokMenuId').value = m.id;
            document.getElementById('stokNama').textContent = m.nama_makanan;
            document.getElementById('stokSaatIni').textContent = m.stok;
            document.getElementById('stokTotal').textContent = m.stok;
            document.getElementById('inputJumlahStok').value = '';

            tampilkanModal('stokModal');
        };

        window.hitungTotalStok = function() {
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value) || 0;
            document.getElementById('stokTotal').textContent = stokSaatIni + jumlah;
        };

        window.submitStok = async function() {
            const id = document.getElementById('stokMenuId').value;
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value) || 0;

            if (jumlah === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Masukkan jumlah stok'
                });
                return;
            }

            const newStok = stokSaatIni + jumlah;
            if (newStok < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok tidak boleh negatif'
                });
                return;
            }

            try {
                const m = semuaMenu.find(x => Number(x.id) === Number(id));
                const form = new FormData();
                form.append('_method', 'PUT');
                form.append('stok', newStok);

                // Stok 0 → kosong | dari kosong ke > 0 → aktif
                if (newStok === 0) {
                    form.append('status', 'kosong');
                } else if (newStok > 0 && m && m.status === 'kosong') {
                    form.append('status', 'aktif');
                }

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: form
                });

                const data = await res.json();
                if (data.success) {
                    const idx = semuaMenu.findIndex(x => Number(x.id) === Number(id));
                    if (idx !== -1) semuaMenu[idx] = data.data;
                    menuTerfilter = menuTerfilter.map(m => Number(m.id) === Number(id) ? data.data : m);

                    renderHalaman();
                    tutupModal('stokModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: `Stok menjadi ${newStok}`,
                        timer: 2000,
                        toast: true
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat update stok'
                });
            }
        };

        // ============================================================
        // TOGGLE STATUS
        // ============================================================
        window.toggleStatus = async function(id) {
            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            if (m.status === 'kosong') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Habis',
                    text: 'Tidak bisa mengubah status menu yang stoknya habis.'
                });
                return;
            }

            // Cek dulu apakah sedang dipesan
            const sedangDipesan = await cekApakahDipesan(id);
            if (sedangDipesan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Bisa Diubah',
                    text: 'Menu ini sedang dipesan, status tidak bisa diubah.'
                });
                return;
            }

            const akanAktif = m.status === 'nonaktif';
            const label = akanAktif ? 'Aktifkan' : 'Nonaktifkan';

            const konfirm = await Swal.fire({
                title: `${label} menu?`,
                text: `Menu "${m.nama_makanan}" akan di${label.toLowerCase()}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: `Ya, ${label}!`
            });

            if (!konfirm.isConfirmed) return;

            try {
                const res = await fetch(ROUTES.toggleStatus(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        status: akanAktif ? 'aktif' : 'nonaktif'
                    })
                });

                const data = await res.json();

                if (data.success) {
                    const idx = semuaMenu.findIndex(x => Number(x.id) === Number(id));
                    if (idx !== -1) semuaMenu[idx] = data.data;
                    menuTerfilter = menuTerfilter.map(menu => Number(menu.id) === Number(id) ? data.data : menu);

                    renderHalaman();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: `Status menjadi ${data.data.status}`,
                        timer: 1800,
                        toast: true
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan'
                });
            }
        };

        // ============================================================
        // HELPER MODAL
        // ============================================================
        function tampilkanModal(id) {
            document.getElementById(id).classList.remove('hidden');
            setTimeout(() => {
                const konten = document.getElementById(id.replace('Modal', 'Content'));
                if (konten) konten.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function tutupModal(id) {
            const konten = document.getElementById(id.replace('Modal', 'Content'));
            if (konten) konten.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        async function cekApakahDipesan(id) {
            try {
                const res = await fetch(ROUTES.cekPesanan(id), {
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const data = await res.json();
                return data.has_orders === true;
            } catch {
                return false;
            }
        }

        // INIT
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', terapkanFilter);
            document.getElementById('filterKategori').addEventListener('change', terapkanFilter);
            document.getElementById('filterStatus').addEventListener('change', terapkanFilter);

            updateStatistikCards();
            renderHalaman();
        });

        // Expose ke window
        window.bukaModalTambah = bukaModalTambah;
        window.submitCreate = submitCreate;
        window.onKategoriIndukBerubah = onKategoriIndukBerubah;
        window.bukaModalHarga = bukaModalHarga;
        window.submitHarga = submitHarga;
        window.bukaModalStok = bukaModalStok;
        window.hitungTotalStok = hitungTotalStok;
        window.submitStok = submitStok;
        window.toggleStatus = toggleStatus;
        window.terapkanFilter = terapkanFilter;
        window.resetFilter = resetFilter;
        window.prevPage = prevPage;
        window.nextPage = nextPage;
        window.tutupModal = tutupModal;
    </script>
@endsection
