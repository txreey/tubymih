@extends('kasir.layouts.app')
@section('title', 'Reservasi')
@section('content')
    <div class="space-y-4 max-w-7xl mx-auto p-4">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Data Reservasi</h1>
                <p class="text-xs text-gray-500 mt-0.5">Semua reservasi — data permanen, tidak terhapus walau beda hari/akun
                </p>
            </div>
            <a href="{{ route('kasir.order.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-plus"></i> Buat Reservasi Baru
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <p class="text-xs text-gray-400 mb-0.5">Total Reservasi</p>
                <p class="text-lg font-bold text-gray-800" id="statTotal">-</p>
            </div>
            <div class="bg-white rounded-lg border border-purple-200 p-3 shadow-sm">
                <p class="text-xs text-gray-400 mb-0.5">Menunggu</p>
                <p class="text-lg font-bold text-purple-600" id="statMenunggu">-</p>
            </div>
            <div class="bg-white rounded-lg border border-green-200 p-3 shadow-sm">
                <p class="text-xs text-gray-400 mb-0.5">Sudah Aktif</p>
                <p class="text-lg font-bold text-green-600" id="statAktif">-</p>
            </div>
            <div class="bg-white rounded-lg border border-red-200 p-3 shadow-sm">
                <p class="text-xs text-gray-400 mb-0.5">Dibatalkan</p>
                <p class="text-lg font-bold text-red-500" id="statBatal">-</p>
            </div>
        </div>

        {{-- Info banner --}}
        <div
            class="bg-purple-50 border border-purple-200 rounded-lg px-4 py-2.5 flex items-center gap-3 text-xs text-purple-700">
            <i class="fas fa-info-circle"></i>
            <span>Klik <strong>Aktifkan</strong> saat customer tiba untuk mengkonfirmasi reservasi dan mengirim order ke
                dapur.</span>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" id="filterSearch" placeholder="Nama customer / no reservasi..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select id="filterStatus"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 outline-none transition text-sm">
                        <option value="">Semua Status</option>
                        <option value="reservasi">Menunggu</option>
                        <option value="aktif">Sudah Aktif</option>
                        <option value="batal">Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Reservasi</label>
                    <input type="date" id="filterTanggal"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-400 outline-none transition text-sm">
                </div>
                <div class="flex items-end gap-2">
                    <button onclick="applyFilter()"
                        class="flex-1 px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition text-sm">Cari</button>
                    <button onclick="resetFilter()"
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Reset</button>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-md font-bold text-gray-900">Daftar Reservasi</h2>
                        <p class="text-xs text-gray-500" id="totalLabel">Total: 0 Reservasi</p>
                    </div>
                </div>
                <button onclick="loadData()"
                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-xs">
                    <i class="fas fa-sync-alt text-xs"></i> Refresh
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                No</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No.
                                Reservasi</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Dibuat</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Customer</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Waktu Reservasi</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Meja</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pre-Order</th>

                            {{-- Kolom Status Baru --}}
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>

                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="reservasiTableBody" class="bg-white divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="emptyState" class="hidden px-4 py-8 text-center text-gray-500">
                <i class="fas fa-calendar text-3xl text-gray-300 mb-2 block"></i>
                Belum ada data reservasi
            </div>
            <div class="px-4 py-3 border-t border-gray-200 flex justify-center">
                <div class="flex items-center gap-1" id="paginationButtons"></div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         MODAL AKTIVASI RESERVASI
    ═══════════════════════════════════════════════ --}}
    <div id="aktifkanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">

            {{-- Header Modal --}}
            <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-md font-bold text-gray-900">Aktifkan Reservasi</h3>
                        <p class="text-xs text-gray-400" id="modalReservInfo">-</p>
                    </div>
                </div>
                <button onclick="closeAktifkanModal()"
                    class="text-gray-400 hover:text-gray-600 text-lg leading-none">×</button>
            </div>

            {{-- Body Modal --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-4">

                {{-- Ringkasan data reservasi --}}
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500 block mb-0.5">Customer</span>
                        <div class="font-semibold text-gray-800" id="modalNamaCustomer">-</div>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Waktu Reservasi</span>
                        <div class="font-semibold text-purple-700" id="modalWaktu">-</div>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Meja</span>
                        <div class="font-semibold text-gray-800" id="modalMeja">-</div>
                    </div>
                    <div class="col-span-2" id="modalCatatanRow">
                        <span class="text-gray-500 block mb-0.5">Catatan</span>
                        <div class="font-semibold text-gray-800" id="modalCatatan">-</div>
                    </div>
                </div>

                {{-- Pre-order items & tambah menu --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-bold text-gray-800">📋 Rincian Menu</h4>
                        <button onclick="toggleTambahMenu()" id="btnToggleTambahMenu"
                            class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold transition">
                            + Tambah Menu
                        </button>
                    </div>

                    {{-- Panel tambah menu --}}
                    <div id="tambahMenuPanel" class="hidden mb-3 border-2 border-amber-200 rounded-xl overflow-hidden">
                        <div class="p-3 bg-amber-50 border-b border-amber-200 flex items-center justify-between gap-2">
                            <span class="text-xs font-bold text-amber-700 shrink-0">Pilih Menu Tambahan</span>
                            <input type="text" id="menuSearchInput" placeholder="Cari menu..."
                                oninput="filterMenuTambah()"
                                class="px-2 py-1 border border-amber-300 rounded-lg text-xs outline-none focus:ring-1 focus:ring-amber-400 min-w-0 flex-1 max-w-xs">
                        </div>
                        <div class="p-3">
                            <div class="flex gap-2 mb-3 flex-wrap" id="menuKategoriBar"></div>
                            <div class="grid grid-cols-3 gap-2 max-h-52 overflow-y-auto pr-1" id="menuTambahGrid"></div>
                        </div>
                    </div>

                    {{-- Cart items --}}
                    <div id="cartReservasiItems" class="space-y-2">
                        <div class="text-center py-6 text-gray-400 text-xs" id="cartEmpty">
                            <i class="fas fa-utensils text-2xl mb-2 block"></i>
                            Tidak ada pre-order — customer bisa pesan saat tiba
                        </div>
                    </div>
                </div>

                {{-- Total --}}
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Tagihan</span>
                        <span class="text-lg font-bold text-purple-600" id="modalTotal">Rp 0</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Pembayaran dilakukan setelah pelanggan selesai makan via tombol
                        <strong>Tagih</strong> di Riwayat.
                    </p>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="px-5 py-3 border-t border-gray-200 flex justify-between items-center shrink-0 gap-3">
                <button onclick="konfirmasiBatal()"
                    class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 font-medium rounded-lg hover:bg-red-100 transition text-sm">
                    ✕ Batalkan Reservasi
                </button>
                <div class="flex gap-2">
                    <button onclick="closeAktifkanModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                        Tutup
                    </button>
                    <button onclick="prosesAktifkan()" id="btnProsesAktifkan"
                        class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-check"></i> Aktifkan & Lanjut
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── Data dari server ──────────────────────────────────────
        let allReservasi = @json($reservasis);
        const ALL_MENUS = @json($menus);
        let filteredReservasi = [...allReservasi];
        let currentPage = 1;
        const PER_PAGE = 10;

        // State modal aktivasi
        let selectedReservasiId = null;
        let modalCart = [];
        let activeMenuKat = 'semua';
        let menuSearchQuery = '';

        const CSRF = '{{ csrf_token() }}';
        const BASE_URL = '{{ url('') }}';

        // ─── Utilitas ──────────────────────────────────────────────
        function formatRp(n) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);
        }

        function formatTanggalDibuat(t) {
            if (!t) return '-';
            return new Date(t).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        /**
         * Format waktu reservasi — sama persis dengan format input order:
         * tanggal_reservasi = "YYYY-MM-DD", jam_reservasi = "HH:MM"
         * Output: "DD/MM/YYYY HH:MM"
         */
        function formatWaktuReservasi(tanggal, jam) {
            if (!tanggal) return '-';
            const [y, m, d] = tanggal.split('-');
            const jamStr = jam ? jam.substring(0, 5) : '';
            return `${d}/${m}/${y}${jamStr ? ' ' + jamStr : ''}`;
        }

        // ─── Stats ────────────────────────────────────────────────
        function updateStats() {
            document.getElementById('statTotal').textContent = allReservasi.length;
            document.getElementById('statMenunggu').textContent = allReservasi.filter(r => r.status === 'reservasi').length;
            document.getElementById('statAktif').textContent = allReservasi.filter(r => r.status === 'aktif').length;
            document.getElementById('statBatal').textContent = allReservasi.filter(r => r.status === 'batal').length;
        }

        // Helper untuk badge status
        function getStatusBadge(status) {
            if (status === 'reservasi') {
                return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                    <i class="fas fa-clock mr-1"></i> Menunggu
                </span>`;
            } else if (status === 'aktif') {
                return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                    <i class="fas fa-check-circle mr-1"></i> Sudah Aktif
                </span>`;
            } else if (status === 'batal') {
                return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                    <i class="fas fa-times-circle mr-1"></i> Dibatalkan
                </span>`;
            }
            return `<span class="text-xs text-gray-400">-</span>`;
        }

        // ─── Render Tabel ─────────────────────────────────────────
        // ─── Render Tabel ─────────────────────────────────────────
        function renderTable() {
            const tbody = document.getElementById('reservasiTableBody');
            const empty = document.getElementById('emptyState');

            if (!filteredReservasi.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                document.getElementById('totalLabel').textContent = 'Total: 0 Reservasi';
                document.getElementById('paginationButtons').innerHTML = '';
                return;
            }

            empty.classList.add('hidden');
            const total = filteredReservasi.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages || 1;
            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredReservasi.slice(start, start + PER_PAGE);

            let html = '';

            pageData.forEach((r, i) => {
                const preOrderItems = r.items || [];
                const preOrderCount = preOrderItems.length;
                const preOrderTotal = preOrderItems.reduce((s, item) =>
                    s + (item.harga_satuan || 0) * (item.qty || 0), 0);

                const preOrderText = preOrderCount > 0 ?
                    `<span class="font-semibold">${preOrderCount} item</span> <span class="text-gray-400">· ${formatRp(preOrderTotal)}</span>` :
                    '<span class="text-gray-400">-</span>';

                // Highlight baris berdasarkan status
                let rowClass = 'hover:bg-gray-50 transition';
                if (r.status === 'reservasi') {
                    rowClass = 'bg-purple-50 hover:bg-purple-100 border-l-4 border-l-purple-400 transition';
                } else if (r.status === 'batal') {
                    rowClass = 'bg-red-50 hover:bg-red-100 border-l-4 border-l-red-400 transition opacity-75';
                }

                // Tombol Aksi
                let aksiHtml = '';
                if (r.status === 'reservasi') {
                    aksiHtml = `
                <div class="flex items-center gap-2">
                    <button onclick="openAktifkanModal(${r.id})"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-purple-600 text-white font-semibold text-xs hover:bg-purple-700 transition">
                        <i class="fas fa-calendar-check text-xs"></i> Aktifkan
                    </button>
                    <button onclick="konfirmasiBatalDariTabel(${r.id})"
                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 border border-red-200 text-red-600 font-semibold text-xs hover:bg-red-100 transition">
                        <i class="fas fa-times text-xs"></i> Batal
                    </button>
                </div>`;
                } else if (r.status === 'aktif') {
                    aksiHtml = `<span class="inline-flex items-center gap-1 text-xs text-green-600 font-semibold">
                            <i class="fas fa-check-circle"></i> Sudah aktif
                        </span>`;
                } else {
                    aksiHtml = `<span class="text-xs text-gray-400">-</span>`;
                }

                html += `
            <tr class="${rowClass}">
                <td class="px-3 py-2.5 text-xs text-gray-500">${start + i + 1}</td>
                <td class="px-3 py-2.5 font-mono text-xs font-semibold text-gray-900">${r.no_transaksi || '-'}</td>
                <td class="px-3 py-2.5 text-xs text-gray-500">${formatTanggalDibuat(r.created_at)}</td>
                <td class="px-3 py-2.5 text-xs text-gray-700">${r.nama_kasir || '-'}</td>
                <td class="px-3 py-2.5 text-xs font-medium text-gray-800">${r.nama_pelanggan || '-'}</td>
                <td class="px-3 py-2.5 text-xs font-semibold text-purple-700 whitespace-nowrap">
                    📅 ${formatWaktuReservasi(r.tanggal_reservasi, r.jam_reservasi)}
                </td>
                <td class="px-3 py-2.5 text-xs text-gray-700">${r.nama_meja || '-'}</td>
                <td class="px-3 py-2.5 text-xs">${preOrderText}</td>
                
                <!-- Kolom Status -->
                <td class="px-3 py-2.5">
                    ${getStatusBadge(r.status)}
                </td>
                
                <td class="px-3 py-2.5">${aksiHtml}</td>
            </tr>
        `;
            });

            tbody.innerHTML = html;
            document.getElementById('totalLabel').textContent = `Total: ${total} Reservasi`;

            // Pagination
            let btnHTML = '';
            if (totalPages > 1) {
                for (let p = 1; p <= totalPages; p++) {
                    btnHTML += `
                <button onclick="goPage(${p})" 
                    class="w-7 h-7 text-xs rounded ${p === currentPage ? 'bg-purple-600 text-white' : 'border border-gray-300 bg-white hover:bg-gray-50'} transition">
                    ${p}
                </button>`;
                }
            }
            document.getElementById('paginationButtons').innerHTML = btnHTML;
        }

        function goPage(p) {
            currentPage = p;
            renderTable();
        }

        // ─── Filter ───────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const status = document.getElementById('filterStatus').value;
            const tanggal = document.getElementById('filterTanggal').value;

            filteredReservasi = allReservasi.filter(r => {
                const matchSearch = !search || (r.no_transaksi || '').toLowerCase().includes(search) || (r
                    .nama_pelanggan || '').toLowerCase().includes(search);
                const matchStatus = !status || r.status === status;
                const matchTanggal = !tanggal || r.tanggal_reservasi === tanggal;
                return matchSearch && matchStatus && matchTanggal;
            });
            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterStatus').value = '';
            document.getElementById('filterTanggal').value = '';
            filteredReservasi = [...allReservasi];
            currentPage = 1;
            renderTable();
        }

        function loadData() {
            window.location.reload();
        }

        // ─── Modal Aktivasi ───────────────────────────────────────
        function openAktifkanModal(id) {
            const r = allReservasi.find(x => x.id === id);
            if (!r) return;

            selectedReservasiId = id;

            // Inisialisasi cart dari pre-order yang sudah ada
            modalCart = (r.items || []).map(item => ({
                id: item.id_menu || item.menu_id,
                nama: item.nama,
                harga: item.harga_satuan,
                qty: item.qty,
                stok: item.stok ?? 999,
                emoji: item.emoji || '🍽️',
                kategori: item.kategori || '-',
            }));

            // Isi info header modal
            document.getElementById('modalReservInfo').textContent = r.no_transaksi || '';
            document.getElementById('modalNamaCustomer').textContent = r.nama_pelanggan || '-';
            document.getElementById('modalWaktu').textContent = '📅 ' + formatWaktuReservasi(r.tanggal_reservasi, r
                .jam_reservasi);
            document.getElementById('modalMeja').textContent = r.nama_meja || '-';
            document.getElementById('modalCatatan').textContent = r.catatan || 'Tidak ada catatan';

            // Reset panel tambah menu
            document.getElementById('tambahMenuPanel').classList.add('hidden');
            document.getElementById('menuSearchInput').value = '';
            menuSearchQuery = '';
            activeMenuKat = 'semua';

            renderModalCart();
            renderMenuKategoriBar();
            renderMenuTambahGrid();

            document.getElementById('aktifkanModal').classList.remove('hidden');
        }

        function closeAktifkanModal() {
            document.getElementById('aktifkanModal').classList.add('hidden');
            selectedReservasiId = null;
            modalCart = [];
        }

        // ─── Cart Modal ───────────────────────────────────────────
        function renderModalCart() {
            const container = document.getElementById('cartReservasiItems');
            const emptyEl = document.getElementById('cartEmpty');
            const totalEl = document.getElementById('modalTotal');

            const total = modalCart.reduce((s, c) => s + c.harga * c.qty, 0);
            totalEl.textContent = formatRp(total);

            if (!modalCart.length) {
                container.innerHTML = '';
                emptyEl.style.display = 'block';
                return;
            }

            emptyEl.style.display = 'none';
            container.innerHTML = modalCart.map(item => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <span class="text-xl shrink-0">${item.emoji}</span>
                        <div class="min-w-0">
                            <div class="text-xs font-semibold text-gray-800 truncate">${item.nama}</div>
                            <div class="text-xs text-amber-600 font-bold">${formatRp(item.harga)} × ${item.qty} = ${formatRp(item.harga * item.qty)}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                        <button onclick="changeModalQty(${item.id}, -1)"
                            class="w-6 h-6 rounded-md bg-white border border-gray-300 text-gray-600 flex items-center justify-center text-sm hover:bg-gray-100">−</button>
                        <span class="text-sm font-bold text-gray-800 w-5 text-center">${item.qty}</span>
                        <button onclick="changeModalQty(${item.id}, 1)"
                            class="w-6 h-6 rounded-md bg-white border border-gray-300 text-gray-600 flex items-center justify-center text-sm hover:bg-gray-100">+</button>
                    </div>
                </div>
            `).join('');
        }

        function changeModalQty(menuId, delta) {
            const idx = modalCart.findIndex(c => c.id === menuId);
            if (idx === -1 && delta > 0) {
                const menu = ALL_MENUS.find(m => m.id === menuId);
                if (!menu) return;
                if (menu.stok <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Habis',
                        text: `${menu.nama} sudah habis.`,
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }
                modalCart.push({
                    id: menu.id,
                    nama: menu.nama,
                    harga: menu.harga,
                    qty: 1,
                    stok: menu.stok,
                    emoji: menu.emoji || '🍽️',
                    kategori: menu.kategori
                });
            } else if (idx !== -1) {
                if (delta > 0 && modalCart[idx].qty >= modalCart[idx].stok) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Terbatas',
                        text: `Stok ${modalCart[idx].nama} hanya ${modalCart[idx].stok} porsi.`,
                        confirmButtonColor: '#f59e0b'
                    });
                    return;
                }
                modalCart[idx].qty += delta;
                if (modalCart[idx].qty <= 0) modalCart.splice(idx, 1);
            }
            renderModalCart();
            renderMenuTambahGrid(); // update badge qty di grid
        }

        // ─── Panel Tambah Menu ────────────────────────────────────
        function toggleTambahMenu() {
            const panel = document.getElementById('tambahMenuPanel');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                renderMenuKategoriBar();
                renderMenuTambahGrid();
            }
        }

        function renderMenuKategoriBar() {
            const kategoris = ['Semua', ...new Set(ALL_MENUS.map(m => m.kategori).filter(Boolean))];
            document.getElementById('menuKategoriBar').innerHTML = kategoris.map(k => {
                const val = k === 'Semua' ? 'semua' : k;
                const active = val === activeMenuKat;
                return `<button onclick="setMenuKat('${val}')"
                    class="px-3 py-1 rounded-full border text-xs font-semibold transition
                    ${active ? 'bg-teal-500 border-teal-500 text-white' : 'border-gray-300 text-gray-500 hover:border-gray-400'}">${k}</button>`;
            }).join('');
        }

        function setMenuKat(kat) {
            activeMenuKat = kat;
            renderMenuKategoriBar();
            renderMenuTambahGrid();
        }

        function filterMenuTambah() {
            menuSearchQuery = document.getElementById('menuSearchInput').value.toLowerCase();
            renderMenuTambahGrid();
        }

        function renderMenuTambahGrid() {
            let menus = activeMenuKat === 'semua' ? ALL_MENUS : ALL_MENUS.filter(m => m.kategori === activeMenuKat);
            if (menuSearchQuery) menus = menus.filter(m => m.nama.toLowerCase().includes(menuSearchQuery));

            const grid = document.getElementById('menuTambahGrid');
            if (!menus.length) {
                grid.innerHTML = '<div class="col-span-3 text-center text-xs text-gray-400 py-4">Tidak ada menu</div>';
                return;
            }

            grid.innerHTML = menus.map(m => {
                const inCart = modalCart.find(c => c.id === m.id);
                const qty = inCart ? inCart.qty : 0;
                const habis = m.stok === 0;
                return `
                    <div onclick="${habis ? '' : `changeModalQty(${m.id}, 1)`}"
                        class="relative border-2 ${qty > 0 ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white'} rounded-lg p-2 text-center transition
                        ${habis ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:border-amber-300 hover:-translate-y-0.5'}">
                        ${qty > 0 ? `<div class="absolute top-1 right-1 w-4 h-4 rounded-full bg-amber-400 text-black text-xs font-bold flex items-center justify-center leading-none">${qty}</div>` : ''}
                        <div class="text-xl mb-1">${m.emoji || '🍽️'}</div>
                        <div class="text-xs font-semibold text-gray-800 line-clamp-1">${m.nama}</div>
                        <div class="text-xs text-amber-600 font-bold">${formatRp(m.harga)}</div>
                        <div class="text-xs text-gray-400">${habis ? 'Habis' : 'Stok ' + m.stok}</div>
                    </div>
                `;
            }).join('');
        }

        // ─── Proses Aktifkan ──────────────────────────────────────
        async function prosesAktifkan() {
            const btn = document.getElementById('btnProsesAktifkan');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengaktifkan...';

            const items = modalCart.map(c => ({
                id: c.id,
                qty: c.qty,
                harga: c.harga
            }));

            try {
                const res = await fetch(`${BASE_URL}/kasir/reservasi/${selectedReservasiId}/aktifkan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        items
                    }),
                });

                const data = await res.json();

                if (!res.ok || !data.success) throw new Error(data.message || 'Terjadi kesalahan');

                // Update data lokal
                const idx = allReservasi.findIndex(r => r.id === selectedReservasiId);
                if (idx !== -1) allReservasi[idx].status = 'aktif';
                filteredReservasi = [...allReservasi];
                updateStats();
                renderTable();
                closeAktifkanModal();

                Swal.fire({
                    icon: 'success',
                    title: 'Reservasi Diaktifkan!',
                    html: `<div class="text-left text-sm">
                        <p>No. Transaksi: <strong>${data.no_transaksi || '-'}</strong></p>
                        <p class="text-gray-500 mt-1">Order masuk ke Riwayat — meja sudah terisi, stok menu berkurang.</p>
                    </div>`,
                    confirmButtonColor: '#7c3aed',
                    confirmButtonText: 'Lihat Riwayat',
                    showCancelButton: true,
                    cancelButtonText: 'Tutup',
                }).then(r => {
                    if (r.isConfirmed) window.location.href = '{{ route('kasir.riwayat') }}';
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message,
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Aktifkan & Lanjut';
            }
        }

        // ─── Batalkan Reservasi ───────────────────────────────────
        // Dari dalam modal
        function konfirmasiBatal() {
            if (!selectedReservasiId) return;
            batalReservasi(selectedReservasiId, true);
        }

        // Dari tombol Batal di tabel (tanpa buka modal)
        function konfirmasiBatalDariTabel(id) {
            batalReservasi(id, false);
        }

        // ─── Batalkan Reservasi ───────────────────────────────────
        async function batalReservasi(id, tutupModal) {
            const r = allReservasi.find(x => x.id === id);
            if (!r) return;

            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Reservasi?',
                html: `<div class="text-sm text-left">
            <p>Reservasi <strong>${r.no_transaksi || '#' + id}</strong> atas nama <strong>${r.nama_pelanggan}</strong> akan dibatalkan.</p>
            <p class="text-gray-500 mt-1">Meja akan dikembalikan ke status <strong>Tersedia</strong>.</p>
        </div>`,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
            }).then(async result => {
                if (!result.isConfirmed) return;

                try {
                    const res = await fetch(`${BASE_URL}/kasir/order/${id}/batal`, {
                        method: 'POST', // Ubah ke POST
                        headers: {
                            'X-CSRF-TOKEN': CSRF,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE' // Spoofing method DELETE
                        })
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) {
                        throw new Error(data.message || 'Terjadi kesalahan saat membatalkan');
                    }

                    // Update UI lokal
                    const idx = allReservasi.findIndex(x => x.id === id);
                    if (idx !== -1) allReservasi[idx].status = 'batal';
                    filteredReservasi = [...allReservasi];
                    updateStats();
                    renderTable();
                    if (tutupModal) closeAktifkanModal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Reservasi Dibatalkan',
                        text: 'Meja sudah dikembalikan ke status Tersedia.',
                        confirmButtonColor: '#7c3aed'
                    });

                } catch (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: err.message,
                        confirmButtonColor: '#ef4444'
                    });
                }
            });
        }

        // ─── Init ─────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            document.getElementById('filterTanggal').addEventListener('change', applyFilter);
            updateStats();
            renderTable();
            // Auto-refresh setiap 60 detik
            setInterval(renderTable, 60_000);
        });
    </script>
@endsection
