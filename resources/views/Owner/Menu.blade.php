@extends('owner.layouts.app')

@section('title', 'Lihat Menu')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Menu</h1>
            <p class="text-gray-600 mt-1">Semua menu yang tersedia di sistem</p>
        </div>

        {{-- Summary Cards  --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">

            {{-- Makanan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-orange-500 text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 leading-none">Makanan</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1 leading-none" id="totalMakananCard">0</p>
                    </div>
                </div>
            </div>

            {{-- Minuman --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-coffee text-blue-500 text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 leading-none">Minuman</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1 leading-none" id="totalMinumanCard">0</p>
                    </div>
                </div>
            </div>

            {{-- Aktif --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-circle-check text-green-500 text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 leading-none">Aktif</p>
                        <p class="text-2xl font-bold text-green-600 mt-1 leading-none" id="totalAktifCard">0</p>
                    </div>
                </div>
            </div>

            {{-- Nonaktif --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-ban text-gray-400 text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 leading-none">Nonaktif</p>
                        <p class="text-2xl font-bold text-gray-500 mt-1 leading-none" id="totalNonaktifCard">0</p>
                    </div>
                </div>
            </div>

            {{-- Kosong --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-box-open text-red-400 text-base"></i>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 leading-none">Stok Kosong</p>
                        <p class="text-2xl font-bold text-red-500 mt-1 leading-none" id="totalKosongCard">0</p>
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
                            <option value=""> Semua Kategori </option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="filterStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value=""> Semua Status </option>
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
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Menu</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalMenuLabel">Total: 0 Menu</p>
                    </div>
                </div>
                {{-- Indikator auto-refresh --}}
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse inline-block"></span>
                    Auto-refresh aktif
                </div>
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

            {{-- Pagination --}}
            <div id="paginationWrapper"
                class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5">
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <div id="currentPageBox"
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center">
                        1
                    </div>
                    <button onclick="nextPage()" id="btnNext"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL HARGA & STOK --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="detailContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0 max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div
                class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Detail Harga & Stok</h2>
                        <p class="text-teal-100 text-xs mt-0.5" id="detailSubtitle">Riwayat perubahan menu</p>
                    </div>
                </div>
                <button onclick="tutupModal('detailModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 overflow-y-auto space-y-6">

                {{-- Info Menu --}}
                <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                    <div id="detailGambar"
                        class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-base" id="detailNama">-</p>
                        <p class="text-sm text-gray-500 mt-0.5" id="detailKategori">-</p>
                        <div id="detailStatusBadge" class="mt-2"></div>
                    </div>
                </div>

                {{-- Harga & Stok Saat Ini --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                        <p class="text-xs text-emerald-600 font-medium mb-1">
                            <i class="fas fa-money-bill-wave mr-1"></i>Harga Saat Ini
                        </p>
                        <p class="text-2xl font-bold text-emerald-700" id="detailHargaSekarang">Rp 0</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
                        <p class="text-xs text-blue-600 font-medium mb-1">
                            <i class="fas fa-boxes-stacked mr-1"></i>Stok Saat Ini
                        </p>
                        <p class="text-2xl font-bold text-blue-700" id="detailStokSekarang">0 <span
                                class="text-sm font-normal">pcs</span></p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Riwayat Perubahan Terakhir</p>
                    <div class="flex-1 h-px bg-gray-200"></div>
                </div>

                {{-- Harga & Stok Sebelum Edit --}}
                <div class="grid grid-cols-2 gap-4">
                    <div id="hargaSlot1" class="hidden">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                            <p class="text-xs text-amber-600 font-medium mb-1">
                                <i class="fas fa-history mr-1"></i>Harga Sebelum Edit Terakhir
                            </p>
                            <p class="text-2xl font-bold text-amber-800" id="hargaSlot1Val">-</p>
                            <p class="text-xs text-amber-600 mt-2">Nilai sebelum perubahan terakhir</p>
                        </div>
                    </div>

                    <div id="stokSlot1" class="hidden">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                            <p class="text-xs text-amber-600 font-medium mb-1">
                                <i class="fas fa-history mr-1"></i>Stok Sebelum Edit Terakhir
                            </p>
                            <p class="text-2xl font-bold text-amber-800" id="stokSlot1Val">-</p>
                            <p class="text-xs text-amber-600 mt-2">Nilai sebelum perubahan terakhir</p>
                        </div>
                    </div>
                </div>

                <div id="hargaKosong"
                    class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-400 text-sm">
                    <i class="fas fa-clock text-2xl mb-2 block"></i>
                    Belum ada riwayat perubahan harga
                </div>

                <div id="stokKosong"
                    class="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-400 text-sm">
                    <i class="fas fa-clock text-2xl mb-2 block"></i>
                    Belum ada riwayat perubahan stok
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end flex-shrink-0">
                <button onclick="tutupModal('detailModal')"
                    class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-md">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        // ── Data awal ──────────────────────────────────────────
        let semuaMenu = @json($menus);
        let menuTerfilter = [...semuaMenu];
        const PER_PAGE = 5;
        let halamanAktif = 1;
        let totalHalamanGlobal = 1;

        // ── Helpers ───────────────────────────────────────────────────────
        const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);

        function escapeHtml(s) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(s || ''));
            return div.innerHTML;
        }

        // ── Cards ─────────────────────────────────────────────────────────
        function updateCards() {
            document.getElementById('totalMakananCard').textContent =
                semuaMenu.filter(m => m.kategori?.nama_kategori === 'Makanan').length;
            document.getElementById('totalMinumanCard').textContent =
                semuaMenu.filter(m => m.kategori?.nama_kategori === 'Minuman').length;
            document.getElementById('totalAktifCard').textContent =
                semuaMenu.filter(m => m.status === 'aktif' && m.stok > 0).length;
            document.getElementById('totalNonaktifCard').textContent =
                semuaMenu.filter(m => m.status === 'nonaktif' && m.stok > 0).length;
            document.getElementById('totalKosongCard').textContent =
                semuaMenu.filter(m => m.stok === 0).length;
        }

        // ── Filter ─────────────────────────────────
        function terapkanFilter() {
            const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
            const induk = document.getElementById('filterKategori').value;
            const statusFilter = document.getElementById('filterStatus').value;

            menuTerfilter = semuaMenu.filter(m => {
                const cocokKeyword = !keyword || m.nama_makanan.toLowerCase().includes(keyword);
                const cocokInduk = !induk || (m.kategori?.nama_kategori === induk);

                let cocokStatus = true;
                if (statusFilter === 'aktif') {
                    cocokStatus = m.status === 'aktif' && m.stok > 0;
                } else if (statusFilter === 'nonaktif') {
                    cocokStatus = m.status === 'nonaktif' || m.stok === 0;
                } else if (statusFilter === 'kosong') {
                    cocokStatus = m.stok === 0;
                }

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

        // ── Render tabel ──────────────────────────────────────────────────
        function renderHalaman() {
            const tbody = document.getElementById('menuTableBody');
            const empty = document.getElementById('emptyState');
            const wrapperPag = document.getElementById('paginationWrapper');
            const total = menuTerfilter.length;

            document.getElementById('totalMenuLabel').textContent = `Total: ${menuTerfilter.length} Menu`;

            if (!total) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                wrapperPag.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');
            wrapperPag.classList.remove('hidden');

            const totalHalaman = Math.ceil(total / PER_PAGE);
            totalHalamanGlobal = totalHalaman;
            if (halamanAktif > totalHalaman) halamanAktif = totalHalaman || 1;

            const mulai = (halamanAktif - 1) * PER_PAGE;
            const dataHalaman = menuTerfilter.slice(mulai, mulai + PER_PAGE);

            tbody.innerHTML = dataHalaman.map((m, i) => {
                const nomor = mulai + i + 1;
                const induk = m.kategori?.nama_kategori || '';
                const jenis = m.kategori?.jenis || '-';
                const warnaBadge = induk === 'Makanan' ?
                    'bg-orange-100 text-orange-700' :
                    induk === 'Minuman' ?
                    'bg-blue-100 text-blue-700' :
                    'bg-gray-100 text-gray-600';

                const gambar = m.gambar ?
                    `<img src="/storage/${m.gambar}" class="w-12 h-12 object-cover rounded-lg shadow-sm"
                           onerror="this.src='https://placehold.co/48x48/e2e8f0/94a3b8?text=No+Img'">` :
                    `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                           <i class="fas fa-image text-lg"></i>
                       </div>`;

                // Status badge 
                let statusHtml = '';
                if (m.stok === 0) {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500"><i class="fas fa-circle text-[8px]"></i> Kosong</span>`;
                } else if (m.status === 'aktif') {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"><i class="fas fa-circle text-[8px]"></i> Aktif</span>`;
                } else {
                    statusHtml =
                        `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500"><i class="fas fa-circle text-[8px]"></i> Nonaktif</span>`;
                }

                const warnaStok = m.stok === 0 ?
                    'text-red-600 font-bold' :
                    m.stok <= 5 ?
                    'text-orange-600 font-semibold' :
                    'text-gray-700';

                return `
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-400 font-medium">${nomor}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escapeHtml(m.nama_makanan)}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${warnaBadge}">
                            ${escapeHtml(jenis)}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-gray-700">${formatRupiah(m.harga)}</td>
                    <td class="px-6 py-4 text-sm ${warnaStok}">${m.stok} pcs</td>
                    <td class="px-6 py-4">${statusHtml}</td>
                    <td class="px-6 py-4">${gambar}</td>
                    <td class="px-6 py-4">
                        {{-- Hanya tombol detail (read-only) --}}
                        <button onclick="bukaModalDetail(${m.id})" title="Lihat Detail"
                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-100 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </button>
                    </td>
                </tr>`;
            }).join('');

            // Pagination info
            const sampai = Math.min(halamanAktif * PER_PAGE, total);
            document.getElementById('paginationInfo').innerHTML =
                `Menampilkan <strong>${mulai + 1}–${sampai}</strong> dari <strong>${total}</strong> menu`;
            document.getElementById('currentPageBox').textContent = halamanAktif;
            document.getElementById('btnPrev').disabled = (halamanAktif === 1);
            document.getElementById('btnNext').disabled = (halamanAktif === totalHalaman);
        }

        // ── Pagination ────────────────────────────────────────────────────
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

        // ── Modal helpers ─────────────────────────────────────────────────
        function tampilkanModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            modal.classList.remove('hidden');
            setTimeout(() => {
                const content = document.getElementById(id.replace('Modal', 'Content'));
                if (content) {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }
            }, 10);
        }

        function tutupModal(id) {
            const modal = document.getElementById(id);
            if (!modal) return;
            const content = document.getElementById(id.replace('Modal', 'Content'));
            if (content) {
                content.classList.remove('scale-100', 'opacity-100');
                content.classList.add('scale-95', 'opacity-0');
            }
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        // ── Modal Detail ──────────────────────────────────────────────────
        function bukaModalDetail(id) {
            const m = semuaMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;

            document.getElementById('detailSubtitle').textContent = m.nama_makanan;
            document.getElementById('detailNama').textContent = m.nama_makanan;
            document.getElementById('detailKategori').textContent = m.kategori ?
                `${m.kategori.nama_kategori} — ${m.kategori.jenis}` : '-';

            // Status badge
            const badgeEl = document.getElementById('detailStatusBadge');
            if (m.stok === 0) {
                badgeEl.innerHTML =
                    `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500"><i class="fas fa-circle text-[8px]"></i> Kosong</span>`;
            } else if (m.status === 'aktif') {
                badgeEl.innerHTML =
                    `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700"><i class="fas fa-circle text-[8px]"></i> Aktif</span>`;
            } else {
                badgeEl.innerHTML =
                    `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500"><i class="fas fa-circle text-[8px]"></i> Nonaktif</span>`;
            }

            // Gambar
            const gambarEl = document.getElementById('detailGambar');
            gambarEl.innerHTML = m.gambar ?
                `<img src="/storage/${m.gambar}" class="w-full h-full object-cover"
                       onerror="this.parentElement.innerHTML='<i class=\\'fas fa-image text-gray-400 text-xl\\'></i>'">` :
                `<i class="fas fa-image text-gray-400 text-xl"></i>`;

            document.getElementById('detailHargaSekarang').textContent = formatRupiah(m.harga);
            document.getElementById('detailStokSekarang').innerHTML =
                `${m.stok} <span class="text-sm font-normal">pcs</span>`;

            // Riwayat Harga
            const adaHarga = m.harga_sebelumnya != null;
            document.getElementById('hargaSlot1').classList.toggle('hidden', !adaHarga);
            document.getElementById('hargaKosong').classList.toggle('hidden', adaHarga);
            if (adaHarga) {
                document.getElementById('hargaSlot1Val').textContent = formatRupiah(m.harga_sebelumnya);
            }

            // Riwayat Stok
            const adaStok = m.stok_sebelumnya != null;
            document.getElementById('stokSlot1').classList.toggle('hidden', !adaStok);
            document.getElementById('stokKosong').classList.toggle('hidden', adaStok);
            if (adaStok) {
                document.getElementById('stokSlot1Val').textContent = `${m.stok_sebelumnya} pcs`;
            }

            tampilkanModal('detailModal');
        }

        async function fetchMenuTerbaru() {
            try {
                const res = await fetch('{{ route('owner.menu') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (!res.ok) return;
                const data = await res.json();

                const keyword = document.getElementById('filterSearch').value;
                const induk = document.getElementById('filterKategori').value;
                const statusFilter = document.getElementById('filterStatus').value;

                semuaMenu = data;
                updateCards();

                terapkanFilter();
            } catch (e) {
            }
        }

        // ── Init ──────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('filterSearch').addEventListener('input', terapkanFilter);
            document.getElementById('filterKategori').addEventListener('change', terapkanFilter);
            document.getElementById('filterStatus').addEventListener('change', terapkanFilter);

            updateCards();
            renderHalaman();

            setInterval(fetchMenuTerbaru, 15000);
        });

        // Expose window
        window.terapkanFilter = terapkanFilter;
        window.resetFilter = resetFilter;
        window.prevPage = prevPage;
        window.nextPage = nextPage;
        window.bukaModalDetail = bukaModalDetail;
        window.tutupModal = tutupModal;
    </script>
@endsection
