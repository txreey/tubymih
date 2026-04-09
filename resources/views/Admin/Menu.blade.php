@extends('admin.layouts.app')
@section('title', 'Kelola Menu')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Menu</h1>
            <p class="text-gray-600 mt-1">Kelola daftar menu makanan dan minuman</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis</label>
                        <select id="filterJenis"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="">pilih kategori dulu</option>
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
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Menu</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalMenuLabel">Total: 0 Menu</p>
                    </div>
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

            {{-- Pagination dengan Arrow + Satu Kotak Angka --}}
            <div id="paginationWrapper"
                class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-500" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5">
                    <!-- Tombol Previous -->
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <!-- Kotak Angka Saat Ini -->
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

                {{-- Harga Sebelum Edit Terakhir --}}
                <div id="hargaSebelumnyaWrapper"
                    class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-amber-500 text-sm"></i>
                        <p class="text-xs text-amber-700 font-medium">Harga Sebelum Edit Terakhir</p>
                    </div>
                    <p class="text-xl font-bold text-amber-800" id="hargaSebelumnya">-</p>
                    <p class="text-xs text-amber-600">Nilai harga sebelum perubahan terakhir dilakukan</p>
                </div>

                {{-- Harga Sebelumnya Lagi --}}
                <div id="hargaSebelumnyaLagiWrapper"
                    class="hidden bg-orange-50 border border-orange-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-orange-500 text-sm"></i>
                        <p class="text-xs text-orange-700 font-medium">Harga Sebelumnya Lagi</p>
                    </div>
                    <p class="text-xl font-bold text-orange-800" id="hargaSebelumnyaLagi">-</p>
                    <p class="text-xs text-orange-600">Nilai harga 2 edit sebelumnya</p>
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

                {{-- Stok Sebelum Edit Terakhir --}}
                <div id="stokSebelumnyaWrapper"
                    class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-amber-500 text-sm"></i>
                        <p class="text-xs text-amber-700 font-medium">Stok Sebelum Edit Terakhir</p>
                    </div>
                    <p class="text-xl font-bold text-amber-800" id="stokSebelumnya">-</p>
                    <p class="text-xs text-amber-600">Nilai stok sebelum perubahan terakhir dilakukan</p>
                </div>

                {{-- Stok Sebelumnya Lagi --}}
                <div id="stokSebelumnyaLagiWrapper"
                    class="hidden bg-orange-50 border border-orange-200 rounded-xl p-4 space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-history text-orange-500 text-sm"></i>
                        <p class="text-xs text-orange-700 font-medium">Stok Sebelumnya Lagi</p>
                    </div>
                    <p class="text-xl font-bold text-orange-800" id="stokSebelumnyaLagi">-</p>
                    <p class="text-xs text-orange-600">Nilai stok 2 edit sebelumnya</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok Baru
                        <span class="text-gray-400 font-normal text-xs ml-1">(positif untuk tambah, negatif untuk
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
        // ============================================================
        // DATA
        // ============================================================
        let semuaMenu = @json($menus);
        let semuaKategori = @json($kategoris);
        let menuTerfilter = [...semuaMenu];

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            simpan: '{{ route('admin.menu.store') }}',
            update: (id) => `{{ url('admin/menu') }}/${id}`,
            hapus: (id) => `{{ url('admin/menu') }}/${id}`,
            cekPesanan: (id) => `{{ url('admin/menu') }}/${id}/check-orders`,
        };

        const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
        const PER_PAGE = 5;
        let halamanAktif = 1;

        // ============================================================
        // FUNGSI BANTUAN
        // ============================================================
        function escapeHtml(s) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(s || ''));
            return div.innerHTML;
        }

        function tampilkanModal(id) {
            document.getElementById(id).classList.remove('hidden');
            setTimeout(() => {
                const konten = document.getElementById(id.replace('Modal', 'Content'));
                konten.classList.remove('scale-95', 'opacity-0');
                konten.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function tutupModal(id) {
            const konten = document.getElementById(id.replace('Modal', 'Content'));
            konten.classList.remove('scale-100', 'opacity-100');
            konten.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        function setLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                btn.disabled = true;
                btn.dataset.original = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i> Proses...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.original;
            }
        }

        function tampilkanError(id, pesan) {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = pesan;
                el.classList.remove('hidden');
            }
        }

        function setBorderError(id, isError) {
            const el = document.getElementById(id);
            if (!el) return;
            if (isError) {
                el.classList.add('border-red-400');
                el.classList.remove('border-gray-300');
            } else {
                el.classList.remove('border-red-400');
                el.classList.add('border-gray-300');
            }
        }

        function bersihkanError(prefix) {
            const kapital = prefix.charAt(0).toUpperCase() + prefix.slice(1);
            document.querySelectorAll(`[id^="err${kapital}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function resetBorderError(prefix) {
            const daftarField = {
                create: ['createKategoriInduk', 'createKategori', 'createNama', 'createHarga', 'createStok',
                    'createGambar'
                ],
                harga: ['inputHargaBaru'],
                stok: ['inputJumlahStok'],
            };
            (daftarField[prefix] || []).forEach(id => setBorderError(id, false));
        }

        // ============================================================
        // HISTORY 2 LEVEL (last + previous)
        // ============================================================
        function getHistory(type, menuId) {
            const key = `${type}_history_${menuId}`;
            const data = localStorage.getItem(key);
            return data ? JSON.parse(data) : {
                editCount: 0,
                lastValue: null,
                prevValue: null
            };
        }

        function saveHistory(type, menuId, newLastValue, oldLastValue) {
            const key = `${type}_history_${menuId}`;
            const history = getHistory(type, menuId);

            localStorage.setItem(key, JSON.stringify({
                editCount: history.editCount + 1,
                lastValue: newLastValue,
                prevValue: oldLastValue !== undefined ? oldLastValue : history.lastValue
            }));
        }

        // ============================================================
        // FILTER
        // ============================================================
        function resetDropdownJenis() {
            const selectJenis = document.getElementById('filterJenis');
            selectJenis.innerHTML = '<option value="">pilih kategori dulu</option>';
            selectJenis.disabled = true;
            selectJenis.classList.add('bg-gray-100', 'cursor-not-allowed');
        }

        function filterKategoriChange() {
            const kategori = document.getElementById('filterKategori').value;
            const selectJenis = document.getElementById('filterJenis');

            if (!kategori) {
                resetDropdownJenis();
                terapkanFilter();
                return;
            }

            selectJenis.disabled = false;
            selectJenis.classList.remove('bg-gray-100', 'cursor-not-allowed');

            const daftarJenis = [...new Set(
                semuaMenu.filter(m => m.kategori?.nama_kategori === kategori).map(m => m.kategori.jenis)
            )];

            selectJenis.innerHTML = '<option value="">-- Semua Jenis --</option>' +
                daftarJenis.map(j => `<option value="${j}">${j}</option>`).join('');

            terapkanFilter();
        }

        function terapkanFilter() {
            const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
            const induk = document.getElementById('filterKategori').value;
            const jenis = document.getElementById('filterJenis').value;

            menuTerfilter = semuaMenu.filter(m => {
                const cocokKeyword = !keyword || m.nama_makanan.toLowerCase().includes(keyword);
                const cocokInduk = !induk || (m.kategori?.nama_kategori === induk);
                const cocokJenis = !jenis || (m.kategori?.jenis === jenis);
                return cocokKeyword && cocokInduk && cocokJenis;
            });

            halamanAktif = 1;
            renderHalaman();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            resetDropdownJenis();
            menuTerfilter = [...semuaMenu];
            halamanAktif = 1;
            renderHalaman();
        }

        // ============================================================
        // RENDER TABEL
        // ============================================================
        function renderHalaman() {
            const tbody = document.getElementById('menuTableBody');
            const empty = document.getElementById('emptyState');
            const wrapperPag = document.getElementById('paginationWrapper');
            const total = menuTerfilter.length;

            document.getElementById('totalMenuLabel').textContent = `Total: ${semuaMenu.length} Menu`;

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
                    `<img src="/storage/${m.gambar}" class="w-12 h-12 object-cover rounded-lg shadow-sm"
                           onerror="this.src='https://placehold.co/48x48/e2e8f0/94a3b8?text=No+Img'">` :
                    `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                           <i class="fas fa-image text-lg"></i>
                       </div>`;

                const warnaStok = m.stok <= 5 ? 'text-red-600 font-bold' :
                    m.stok <= 15 ? 'text-amber-600 font-semibold' : 'text-gray-700';

                return `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors" id="row-${m.id}">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">${nomor}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escapeHtml(m.nama_makanan)}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${warnaBadge}">${escapeHtml(jenis)}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-700" id="harga-${m.id}">${formatRupiah(m.harga)}</td>
                        <td class="px-6 py-4 text-sm ${warnaStok}" id="stok-${m.id}">${m.stok}</td>
                        <td class="px-6 py-4">${gambar}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="bukaModalHarga(${m.id})" title="Kelola Harga"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                    <i class="fas fa-dollar-sign text-xs"></i>
                                </button>
                                <button onclick="bukaModalStok(${m.id})" title="Kelola Stok"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                    <i class="fas fa-boxes-stacked text-xs"></i>
                                </button>
                                <button onclick="hapusMenu(${m.id})" title="Hapus Menu"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            renderPagination(total, totalHalaman);
        }

        let totalHalamanGlobal = 1;

        function renderPagination(total, totalHalaman) {
            totalHalamanGlobal = totalHalaman;
            const info = document.getElementById('paginationInfo');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const currentPageBox = document.getElementById('currentPageBox');

            if (!total) {
                document.getElementById('paginationWrapper').classList.add('hidden');
                return;
            }
            document.getElementById('paginationWrapper').classList.remove('hidden');

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
                if (peta[induk] && !peta[induk].find(x => x.id === k.id)) {
                    peta[induk].push({
                        id: k.id,
                        jenis: k.jenis
                    });
                }
            });
            return peta;
        }

        function isiDropdownJenis(selectId, nilaiInduk) {
            const select = document.getElementById(selectId);
            if (!nilaiInduk) {
                select.innerHTML = '<option value="" selected disabled>pilih kategori dulu</option>';
                select.disabled = true;
                select.classList.add('bg-gray-100', 'cursor-not-allowed');
                select.classList.remove('bg-white');
                return;
            }
            const peta = buatPetaKategori();
            const daftar = (peta[nilaiInduk] || []).sort((a, b) => a.jenis.localeCompare(b.jenis));
            select.innerHTML = '<option value="">-- Pilih Jenis --</option>' +
                daftar.map(k => `<option value="${k.id}">${escapeHtml(k.jenis)}</option>`).join('');
            select.disabled = false;
            select.classList.remove('bg-gray-100', 'cursor-not-allowed');
            select.classList.add('bg-white');
        }

        function onKategoriIndukBerubah() {
            const nilai = document.getElementById('createKategoriInduk').value;
            isiDropdownJenis('createKategori', nilai);
            document.getElementById('errCreateKategoriInduk').classList.add('hidden');
            document.getElementById('errCreateKategori').classList.add('hidden');
            setBorderError('createKategori', false);
        }

        function validasiTambah() {
            let valid = true;
            bersihkanError('create');
            resetBorderError('create');

            const induk = document.getElementById('createKategoriInduk').value;
            const katId = document.getElementById('createKategori').value;
            const nama = document.getElementById('createNama').value.trim();
            const harga = document.getElementById('createHarga').value;
            const stok = document.getElementById('createStok').value;
            const fileGambar = document.getElementById('createGambar').files[0];

            if (!induk) {
                tampilkanError('errCreateKategoriInduk', 'Kategori wajib dipilih.');
                setBorderError('createKategoriInduk', true);
                valid = false;
            }
            if (!katId) {
                tampilkanError('errCreateKategori', 'Jenis wajib dipilih.');
                setBorderError('createKategori', true);
                valid = false;
            }
            if (!nama) {
                tampilkanError('errCreateNama', 'Nama menu wajib diisi.');
                setBorderError('createNama', true);
                valid = false;
            }
            if (harga === '' || Number(harga) < 0) {
                tampilkanError('errCreateHarga', 'Harga wajib diisi dan tidak boleh negatif.');
                setBorderError('createHarga', true);
                valid = false;
            }
            if (stok === '' || Number(stok) < 0) {
                tampilkanError('errCreateStok', 'Stok wajib diisi dan tidak boleh negatif.');
                setBorderError('createStok', true);
                valid = false;
            }
            if (!fileGambar) {
                tampilkanError('errCreateGambar', 'Gambar menu wajib diunggah.');
                setBorderError('createGambar', true);
                valid = false;
            }
            return valid;
        }

        function bukaModalTambah() {
            document.getElementById('createForm').reset();
            document.getElementById('createGambarPreview').classList.add('hidden');
            document.getElementById('createKategoriInduk').value = '';
            isiDropdownJenis('createKategori', '');
            bersihkanError('create');
            resetBorderError('create');
            tampilkanModal('createModal');
        }

        async function submitCreate() {
            if (!validasiTambah()) return;

            const form = new FormData();
            form.append('id_kategori', document.getElementById('createKategori').value);
            form.append('nama_makanan', document.getElementById('createNama').value.trim());
            form.append('harga', document.getElementById('createHarga').value);
            form.append('stok', document.getElementById('createStok').value);

            const fileGambar = document.getElementById('createGambar').files[0];
            if (fileGambar) form.append('gambar', fileGambar);

            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            setLoading(btn, true);
            try {
                const res = await fetch(ROUTES.simpan, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    if (res.status === 422 && data.errors) {
                        const mapError = {
                            id_kategori: 'errCreateKategori',
                            nama_makanan: 'errCreateNama',
                            harga: 'errCreateHarga',
                            stok: 'errCreateStok',
                            gambar: 'errCreateGambar',
                        };
                        Object.entries(data.errors).forEach(([field, msgs]) => {
                            if (mapError[field]) tampilkanError(mapError[field], msgs[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan.'
                        });
                    }
                    return;
                }

                const menuBaru = data.data;
                semuaMenu.push(menuBaru);

                const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
                const induk = document.getElementById('filterKategori').value;
                const jenis = document.getElementById('filterJenis').value;

                const cocokFilter = (!keyword || menuBaru.nama_makanan.toLowerCase().includes(keyword)) &&
                    (!induk || (menuBaru.kategori?.nama_kategori === induk)) &&
                    (!jenis || (menuBaru.kategori?.jenis === jenis));

                if (cocokFilter) menuTerfilter.push(menuBaru);

                halamanAktif = Math.ceil(menuTerfilter.length / PER_PAGE) || 1;
                renderHalaman();

                setTimeout(() => {
                    const newRow = document.getElementById(`row-${menuBaru.id}`);
                    if (newRow) {
                        newRow.classList.add('bg-teal-50', 'ring-2', 'ring-teal-300');
                        setTimeout(() => newRow.classList.remove('bg-teal-50', 'ring-2', 'ring-teal-300'),
                        3000);
                    }
                }, 300);

                tutupModal('createModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Menu baru berhasil ditambahkan.',
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

        // ============================================================
        // MODAL HARGA
        // ============================================================
        function bukaModalHarga(id) {
            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            document.getElementById('hargaMenuId').value = m.id;
            document.getElementById('hargaNama').textContent = m.nama_makanan;
            document.getElementById('hargaKategori').textContent = m.kategori ?
                `${m.kategori.nama_kategori} — ${m.kategori.jenis}` : '-';
            document.getElementById('hargaSaatIni').textContent = formatRupiah(m.harga);
            document.getElementById('inputHargaBaru').value = m.harga;
            document.getElementById('errHarga').classList.add('hidden');
            setBorderError('inputHargaBaru', false);

            const history = getHistory('harga', id);
            const wrapper1 = document.getElementById('hargaSebelumnyaWrapper');
            const wrapper2 = document.getElementById('hargaSebelumnyaLagiWrapper');

            if (history.editCount >= 1 && history.lastValue !== null) {
                document.getElementById('hargaSebelumnya').textContent = formatRupiah(history.lastValue);
                wrapper1.classList.remove('hidden');
            } else {
                wrapper1.classList.add('hidden');
            }

            if (history.editCount >= 2 && history.prevValue !== null) {
                document.getElementById('hargaSebelumnyaLagi').textContent = formatRupiah(history.prevValue);
                wrapper2.classList.remove('hidden');
            } else {
                wrapper2.classList.add('hidden');
            }

            tampilkanModal('hargaModal');
        }

        async function submitHarga() {
            const id = document.getElementById('hargaMenuId').value;
            const hargaBaru = document.getElementById('inputHargaBaru').value;

            if (!hargaBaru || Number(hargaBaru) < 0) {
                tampilkanError('errHarga', 'Harga wajib diisi dan tidak boleh negatif.');
                setBorderError('inputHargaBaru', true);
                return;
            }
            setBorderError('inputHargaBaru', false);

            const btn = document.querySelector('#hargaContent button[onclick="submitHarga()"]');
            setLoading(btn, true);
            try {
                const m = semuaMenu.find(x => Number(x.id) === Number(id));
                const hargaLama = m.harga;
                const history = getHistory('harga', id);

                const form = new FormData();
                form.append('harga', hargaBaru);
                form.append('_method', 'PUT');
                form.append('id_kategori', m.id_kategori);
                form.append('nama_makanan', m.nama_makanan);
                form.append('stok', m.stok);

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                    return;
                }

                saveHistory('harga', id, hargaLama, history.lastValue);

                const idx = semuaMenu.findIndex(x => Number(x.id) === Number(id));
                if (idx !== -1) semuaMenu[idx] = data.data;
                const fidx = menuTerfilter.findIndex(x => Number(x.id) === Number(id));
                if (fidx !== -1) menuTerfilter[fidx] = data.data;

                const cell = document.getElementById(`harga-${id}`);
                if (cell) cell.textContent = formatRupiah(data.data.harga);

                tutupModal('hargaModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Harga berhasil diperbarui.',
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

        // ============================================================
        // MODAL STOK
        // ============================================================
        let stokSaatIni = 0;

        function bukaModalStok(id) {
            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            stokSaatIni = m.stok;
            document.getElementById('stokMenuId').value = m.id;
            document.getElementById('stokNama').textContent = m.nama_makanan;
            document.getElementById('stokSaatIni').textContent = m.stok;
            document.getElementById('stokTotal').textContent = m.stok;
            document.getElementById('inputJumlahStok').value = '';
            document.getElementById('errStok').classList.add('hidden');
            setBorderError('inputJumlahStok', false);

            const history = getHistory('stok', id);
            const wrapper1 = document.getElementById('stokSebelumnyaWrapper');

            if (history.editCount >= 1 && history.lastValue !== null) {
                document.getElementById('stokSebelumnya').textContent = history.lastValue;
                wrapper1.classList.remove('hidden');
            } else {
                wrapper1.classList.add('hidden');
            }

            tampilkanModal('stokModal');
        }

        function hitungTotalStok() {
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value) || 0;
            const total = stokSaatIni + jumlah;
            const el = document.getElementById('stokTotal');
            el.textContent = total;
            el.className = total < 0 ? 'text-3xl font-bold text-red-600' :
                total > stokSaatIni ? 'text-3xl font-bold text-green-600' :
                'text-3xl font-bold text-gray-800';
        }

        async function submitStok() {
            const id = document.getElementById('stokMenuId').value;
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value);
            document.getElementById('errStok').classList.add('hidden');

            if (isNaN(jumlah) || jumlah === 0) {
                tampilkanError('errStok', 'Masukkan angka penambahan atau pengurangan (tidak boleh 0).');
                setBorderError('inputJumlahStok', true);
                return;
            }
            const newStok = stokSaatIni + jumlah;
            if (newStok < 0) {
                tampilkanError('errStok',
                    `Stok tidak cukup. Stok saat ini ${stokSaatIni}, maksimal dikurangi ${stokSaatIni}.`);
                setBorderError('inputJumlahStok', true);
                return;
            }
            setBorderError('inputJumlahStok', false);

            const btn = document.querySelector('#stokContent button[onclick="submitStok()"]');
            setLoading(btn, true);
            try {
                const m = semuaMenu.find(x => Number(x.id) === Number(id));
                const stokLama = stokSaatIni;
                const history = getHistory('stok', id);

                const form = new FormData();
                form.append('stok', newStok);
                form.append('_method', 'PUT');
                form.append('id_kategori', m.id_kategori);
                form.append('nama_makanan', m.nama_makanan);
                form.append('harga', m.harga);

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                    return;
                }

                saveHistory('stok', id, stokLama, history.lastValue);

                const idx = semuaMenu.findIndex(x => Number(x.id) === Number(id));
                if (idx !== -1) semuaMenu[idx] = data.data;
                const fidx = menuTerfilter.findIndex(x => Number(x.id) === Number(id));
                if (fidx !== -1) menuTerfilter[fidx] = data.data;

                const cell = document.getElementById(`stok-${id}`);
                if (cell) {
                    cell.textContent = newStok;
                    cell.className =
                        `px-6 py-4 text-sm ${newStok <= 5 ? 'text-red-600 font-bold' : newStok <= 15 ? 'text-amber-600 font-semibold' : 'text-gray-700'}`;
                }

                tutupModal('stokModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Stok diperbarui menjadi ${newStok} pcs.`,
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

        // ============================================================
        // HAPUS
        // ============================================================
        async function hapusMenu(id) {
            try {
                const cekRes = await fetch(ROUTES.cekPesanan(id), {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const cekData = await cekRes.json();
                if (cekData.has_orders) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Dapat Dihapus!',
                        text: 'Menu ini sedang dipesan oleh pembeli dan tidak dapat dihapus.',
                        confirmButtonColor: '#dc2626'
                    });
                    return;
                }
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal mengecek status pesanan menu.'
                });
                return;
            }

            const konfirmasi = await Swal.fire({
                title: 'Hapus menu ini?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });
            if (!konfirmasi.isConfirmed) return;

            try {
                const res = await fetch(ROUTES.hapus(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                    return;
                }

                semuaMenu = semuaMenu.filter(x => Number(x.id) !== Number(id));
                menuTerfilter = menuTerfilter.filter(x => Number(x.id) !== Number(id));
                renderHalaman();
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Menu berhasil dihapus.',
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

        // ============================================================
        // EVENT LISTENERS & INIT
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', terapkanFilter);
            document.getElementById('filterKategori').addEventListener('change', filterKategoriChange);
            document.getElementById('createGambar').addEventListener('change', function() {
                const preview = document.getElementById('createGambarPreview');
                const errGambar = document.getElementById('errCreateGambar');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(this.files[0]);
                    errGambar.classList.add('hidden');
                    setBorderError('createGambar', false);
                } else {
                    preview.classList.add('hidden');
                }
            });
            renderHalaman();
        });

        // Expose fungsi ke global scope
        window.bukaModalHarga = bukaModalHarga;
        window.bukaModalStok = bukaModalStok;
        window.hapusMenu = hapusMenu;
        window.terapkanFilter = terapkanFilter;
        window.resetFilter = resetFilter;
        window.prevPage = prevPage;
        window.nextPage = nextPage;
        window.hitungTotalStok = hitungTotalStok;
        window.tutupModal = tutupModal;
        window.submitCreate = submitCreate;
        window.submitHarga = submitHarga;
        window.submitStok = submitStok;
        window.onKategoriIndukBerubah = onKategoriIndukBerubah;
        window.bukaModalTambah = bukaModalTambah;
    </script>
@endsection
