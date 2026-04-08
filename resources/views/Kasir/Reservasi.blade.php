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
            <span>Tombol <strong>Aktifkan</strong> muncul <strong>1 jam sebelum</strong> waktu reservasi. Setelah
                diaktifkan, order masuk ke Riwayat Transaksi.</span>
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
                        <option value="pending">Menunggu</option>
                        <option value="aktif">Sudah Aktif</option>
                        <option value="batal">Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
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
                                Orang</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pre-Order</th>
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

    {{-- MODAL AKTIVASI RESERVASI --}}
    <div id="aktifkanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
            {{-- Header --}}
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
                <button onclick="closeAktifkanModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-4">
                {{-- Info reservasi --}}
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-gray-500">Customer</span>
                        <div class="font-semibold text-gray-800 mt-0.5" id="modalNamaCustomer">-</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Waktu Reservasi</span>
                        <div class="font-semibold text-purple-700 mt-0.5" id="modalWaktu">-</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Meja</span>
                        <div class="font-semibold text-gray-800 mt-0.5" id="modalMeja">-</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Jumlah Orang</span>
                        <div class="font-semibold text-gray-800 mt-0.5" id="modalOrang">-</div>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-500">Catatan</span>
                        <div class="font-semibold text-gray-800 mt-0.5" id="modalCatatan">-</div>
                    </div>
                </div>

                {{-- Pre-order items --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-bold text-gray-800">📋 Pre-Order Menu</h4>
                        <button onclick="toggleTambahMenu()" id="btnToggleTambahMenu"
                            class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 font-semibold transition">
                            + Tambah Menu
                        </button>
                    </div>

                    {{-- Panel tambah menu --}}
                    <div id="tambahMenuPanel" class="hidden mb-3 border-2 border-amber-200 rounded-xl overflow-hidden">
                        <div class="p-3 bg-amber-50 border-b border-amber-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-amber-700">Pilih Menu Tambahan</span>
                            <div class="flex gap-2">
                                <input type="text" id="menuSearchInput" placeholder="Cari menu..."
                                    oninput="filterMenuTambah()"
                                    class="px-2 py-1 border border-amber-300 rounded-lg text-xs outline-none focus:ring-1 focus:ring-amber-400">
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="flex gap-2 mb-3 flex-wrap" id="menuKategoriBar"></div>
                            <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto" id="menuTambahGrid"></div>
                        </div>
                    </div>

                    {{-- Current cart items --}}
                    <div id="cartReservasiItems" class="space-y-2">
                        <div class="text-center py-6 text-gray-400 text-xs" id="cartEmpty">
                            <i class="fas fa-utensils text-2xl mb-2 block"></i>
                            Belum ada menu yang dipesan (opsional)
                        </div>
                    </div>
                </div>

                {{-- Total --}}
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Pre-Order</span>
                        <span class="text-lg font-bold text-purple-600" id="modalTotal">Rp 0</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Pembayaran dilakukan setelah pelanggan selesai makan via menu
                        <strong>Tagih</strong>
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-3 border-t border-gray-200 flex justify-between items-center shrink-0">
                <button onclick="closeAktifkanModal()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Batal</button>
                <button onclick="prosesAktifkan()" id="btnProsesAktifkan"
                    class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg transition text-sm">
                    ✅ Aktifkan & Kirim ke Dapur
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ─── Data dari server ───────────────────────────────
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

        // ─── Utilitas ───────────────────────────────────────
        function formatRp(n) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
        }

        function formatTanggal(t) {
            if (!t) return '-';
            return new Date(t).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatWaktuReservasi(tanggal, jam) {
            if (!tanggal || !jam) return '-';
            // Parse dengan benar
            const [year, month, day] = tanggal.split('-');
            const [hour, minute] = jam.split(':');
            const date = new Date(year, month - 1, day, hour, minute);

            if (isNaN(date.getTime())) return `${tanggal} ${jam}`;

            return date.toLocaleDateString('id-ID', {
                weekday: 'short',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) + ' ' + jam;
        }

        function bisaAktifkan(reservasi) {
            if (reservasi.status !== 'pending') return false;
            if (!reservasi.tanggal_reservasi || !reservasi.jam_reservasi) return false;
            const waktuReservasi = new Date(`${reservasi.tanggal_reservasi}T${reservasi.jam_reservasi}`);
            const now = new Date();
            const diffMs = waktuReservasi - now;
            return diffMs <= 60 * 60 * 1000;
        }

        function getStatusBadge(reservasi) {
            const s = reservasi.status;
            if (s === 'pending')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 text-xs font-medium">⏳ Menunggu</span>';
            if (s === 'aktif')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">✅ Aktif</span>';
            if (s === 'batal')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-medium">❌ Batal</span>';
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">-</span>';
        }

        // ─── Stats ───────────────────────────────────────────
        function updateStats() {
            document.getElementById('statTotal').textContent = allReservasi.length;
            document.getElementById('statMenunggu').textContent = allReservasi.filter(r => r.status === 'pending').length;
            document.getElementById('statAktif').textContent = allReservasi.filter(r => r.status === 'aktif').length;
            document.getElementById('statBatal').textContent = allReservasi.filter(r => r.status === 'batal').length;
        }

        // ─── Render Tabel ────────────────────────────────────
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
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredReservasi.slice(start, start + PER_PAGE);

            let html = '';
            pageData.forEach((r, i) => {
                const bisa = bisaAktifkan(r);
                const preOrderCount = (r.items || []).length;
                const preOrderTotal = (r.items || []).reduce((s, item) => s + item.harga_satuan * item.qty, 0);
                const preOrderText = preOrderCount > 0 ?
                    `${preOrderCount} item · ${formatRp(preOrderTotal)}` :
                    '<span class="text-gray-400">-</span>';

                const rowClass = (bisa && r.status === 'pending') ?
                    'bg-purple-50 hover:bg-purple-100 border-l-4 border-l-purple-400 transition' :
                    'hover:bg-gray-50 transition';

                let aksiHtml = '';
                if (r.status === 'pending') {
                    if (bisa) {
                        aksiHtml = `<button onclick="openAktifkanModal(${r.id})"
                        class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-800 font-bold text-xs animate-pulse">
                        <i class="fas fa-calendar-check"></i> Aktifkan
                    </button>`;
                    } else {
                        aksiHtml = `<span class="text-xs text-gray-400">Belum bisa diaktifkan</span>`;
                    }
                } else if (r.status === 'aktif') {
                    aksiHtml = `<span class="text-xs text-green-600 font-semibold">Sudah aktif</span>`;
                } else {
                    aksiHtml = `<span class="text-xs text-gray-400">-</span>`;
                }

                html += `
                <tr class="${rowClass}">
                    <td class="px-3 py-2 text-xs text-gray-500">${start + i + 1}</td>
                    <td class="px-3 py-2 font-mono text-xs font-semibold text-gray-900">${r.no_transaksi || '-'}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">${formatTanggal(r.created_at)}</td>
                    <td class="px-3 py-2 text-xs text-gray-700">${r.nama_kasir || '-'}</td>
                    <td class="px-3 py-2 text-xs font-medium text-gray-800">${r.nama_pelanggan || '-'}</td>
                    <td class="px-3 py-2 text-xs font-semibold text-purple-700">
                        ${formatWaktuReservasi(r.tanggal_reservasi, r.jam_reservasi)}
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-700">${r.nama_meja || '-'}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">${r.jumlah_orang || '-'} org</td>
                    <td class="px-3 py-2 text-xs">${preOrderText}</td>
                    <td class="px-3 py-2">${getStatusBadge(r)}</td>
                    <td class="px-3 py-2">${aksiHtml}</td>
                </tr>
            `;
            });

            tbody.innerHTML = html;
            document.getElementById('totalLabel').textContent = `Total: ${total} Reservasi`;

            let btnHTML = '';
            if (Math.ceil(total / PER_PAGE) > 1) {
                for (let p = 1; p <= Math.ceil(total / PER_PAGE); p++) {
                    btnHTML +=
                        `<button onclick="goPage(${p})" class="w-7 h-7 text-xs rounded ${p === currentPage ? 'bg-purple-600 text-white' : 'border border-gray-300 bg-white hover:bg-gray-50'} transition">${p}</button>`;
                }
            }
            document.getElementById('paginationButtons').innerHTML = btnHTML;
        }

        function goPage(p) {
            currentPage = p;
            renderTable();
        }

        // ─── Filter ──────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const status = document.getElementById('filterStatus').value;
            const tanggal = document.getElementById('filterTanggal').value;

            filteredReservasi = allReservasi.filter(r => {
                const matchSearch = !search ||
                    (r.no_transaksi || '').toLowerCase().includes(search) ||
                    (r.nama_pelanggan || '').toLowerCase().includes(search);
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

        // ─── Modal Aktivasi ──────────────────────────────────
        function openAktifkanModal(id) {
            const r = allReservasi.find(x => x.id === id);
            if (!r) return;

            selectedReservasiId = id;

            modalCart = (r.items || []).map(item => ({
                id: item.id_menu || item.menu_id,
                nama: item.nama,
                harga: item.harga_satuan,
                qty: item.qty,
                stok: 999,
                emoji: item.emoji || '🍽️',
                kategori: item.kategori || '-'
            }));

            document.getElementById('modalReservInfo').textContent = r.no_transaksi || '';
            document.getElementById('modalNamaCustomer').textContent = r.nama_pelanggan || '-';
            document.getElementById('modalWaktu').textContent = formatWaktuReservasi(r.tanggal_reservasi, r.jam_reservasi);
            document.getElementById('modalMeja').textContent = r.nama_meja || '-';
            document.getElementById('modalOrang').textContent = (r.jumlah_orang || '-') + ' orang';
            document.getElementById('modalCatatan').textContent = r.catatan || 'Tidak ada catatan';

            document.getElementById('tambahMenuPanel').classList.add('hidden');
            document.getElementById('menuSearchInput').value = '';
            menuSearchQuery = '';
            activeMenuKat = 'semua';

            renderModalCart();
            renderMenuTambahGrid();
            renderMenuKategoriBar();

            document.getElementById('aktifkanModal').classList.remove('hidden');
        }

        function closeAktifkanModal() {
            document.getElementById('aktifkanModal').classList.add('hidden');
            selectedReservasiId = null;
            modalCart = [];
        }

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
                    <span class="text-xl">${item.emoji}</span>
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
                modalCart[idx].qty += delta;
                if (modalCart[idx].qty <= 0) modalCart.splice(idx, 1);
            }
            renderModalCart();
        }

        function toggleTambahMenu() {
            const panel = document.getElementById('tambahMenuPanel');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                renderMenuKategoriBar();
                renderMenuTambahGrid();
            }
        }

        function renderMenuKategoriBar() {
            const kategoris = ['Semua', ...new Set(ALL_MENUS.map(m => m.kategori))];
            document.getElementById('menuKategoriBar').innerHTML = kategoris.map((k) => {
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

            document.getElementById('menuTambahGrid').innerHTML = menus.map(m => {
                const inCart = modalCart.find(c => c.id === m.id);
                const qty = inCart ? inCart.qty : 0;
                const habis = m.stok === 0;

                return `
                <div onclick="${habis ? '' : `changeModalQty(${m.id}, 1)`}"
                    class="relative border-2 ${qty > 0 ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white'} rounded-lg p-2 text-center transition cursor-pointer hover:border-amber-300 ${habis ? 'opacity-40 cursor-not-allowed' : ''}">
                    ${qty > 0 ? `<div class="absolute top-1 right-1 w-4 h-4 rounded-full bg-amber-400 text-black text-xs font-bold flex items-center justify-center">${qty}</div>` : ''}
                    <div class="text-xl mb-1">${m.emoji || '🍽️'}</div>
                    <div class="text-xs font-semibold text-gray-800 line-clamp-1">${m.nama}</div>
                    <div class="text-xs text-amber-600 font-bold">${formatRp(m.harga)}</div>
                    <div class="text-xs text-gray-400">${habis ? 'Habis' : 'Stok ' + m.stok}</div>
                </div>
            `;
            }).join('') || '<div class="col-span-3 text-center text-xs text-gray-400 py-4">Tidak ada menu</div>';
        }

        async function prosesAktifkan() {
            const btn = document.getElementById('btnProsesAktifkan');
            btn.disabled = true;
            btn.textContent = 'Mengaktifkan...';

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
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items
                    })
                });

                if (!res.ok) {
                    const e = await res.json();
                    throw new Error(e.message || 'Terjadi kesalahan');
                }
                const data = await res.json();

                if (data.success) {
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
                        <p>No. Transaksi Baru: <strong>${data.no_transaksi || '-'}</strong></p>
                        <p class="text-gray-500 mt-1">Order telah masuk ke Riwayat Transaksi.<br>Tagih pembayaran setelah pelanggan selesai.</p>
                    </div>`,
                        confirmButtonColor: '#7c3aed',
                        confirmButtonText: 'Lihat Riwayat'
                    }).then(r => {
                        if (r.isConfirmed) window.location.href = '{{ route('kasir.riwayat') }}';
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: err.message,
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                btn.disabled = false;
                btn.textContent = '✅ Aktifkan & Kirim ke Dapur';
            }
        }

        // ─── Init ─────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            document.getElementById('filterTanggal').addEventListener('change', applyFilter);
            updateStats();
            renderTable();

            setInterval(() => {
                renderTable();
            }, 60000);
        });
    </script>
@endsection
