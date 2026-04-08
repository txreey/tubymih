@extends('kasir.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-4 max-w-7xl mx-auto p-4">

        {{-- Header --}}
        <div>
            <h1 class="text-xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-xs text-gray-500 mt-0.5">Semua transaksi — data bersama seluruh kasir</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Total Transaksi</p>
                        <p class="text-lg font-bold text-gray-800" id="statTotal">0</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-blue-600 text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Total Pendapatan</p>
                        <p class="text-lg font-bold text-green-600" id="statPendapatan">Rp 0</p>
                    </div>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600 text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Lunas</p>
                        <p class="text-lg font-bold text-emerald-600" id="statLunas">0</p>
                    </div>
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Belum Bayar</p>
                        <p class="text-lg font-bold text-orange-600" id="statBelumBayar">0</p>
                    </div>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Legenda Warna --}}
        <div
            class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2.5 flex items-center gap-3 text-xs text-orange-700">
            <i class="fas fa-exclamation-triangle"></i>
            <span>Baris <strong>berwarna oranye</strong> = order belum dibayar lebih dari <strong>3 jam</strong> — segera
                tagih!</span>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Cari Transaksi</label>
                        <input type="text" id="filterSearch" placeholder="Ketik no transaksi / nama..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Order</label>
                        <select id="filterTipe"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="dine_in">Dine In</option>
                            <option value="take_away">Take Away</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select id="filterStatus"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="tunggak">Belum Bayar</option>
                            <option value="terlambat">⚠️ Terlambat (&gt;3 jam)</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button onclick="applyFilter()"
                            class="flex-1 px-4 py-2 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition text-sm">Cari</button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-teal-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-md font-bold text-gray-900">Riwayat Transaksi</h2>
                        <p class="text-xs text-gray-500" id="totalTransaksiLabel">Total: 0 Transaksi</p>
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
                                Transaksi</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Customer</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Meja</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Item</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTableBody" class="bg-white divide-y divide-gray-100"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-4 py-8 text-center text-gray-500">
                <i class="fas fa-receipt text-3xl text-gray-300 mb-2 block"></i>
                Belum ada transaksi hari ini
            </div>

            <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-200 flex justify-center">
                <div class="flex items-center gap-1" id="paginationButtons"></div>
            </div>
        </div>
    </div>

    {{-- MODAL TAGIH --}}
    <div id="tagihModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl max-w-md w-full mx-4">
            <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-teal-600 text-sm"></i>
                    </div>
                    <h3 class="text-md font-bold text-gray-900">Tagih Pembayaran</h3>
                </div>
                <button onclick="closeModal('tagihModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5 space-y-4" id="tagihContent"></div>
            <div class="px-5 py-3 border-t border-gray-200 flex justify-end gap-2">
                <button onclick="closeModal('tagihModal')"
                    class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Batal</button>
                <button onclick="prosesTagih()" id="btnProsesTagih"
                    class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition text-sm">Bayar</button>
            </div>
        </div>
    </div>

    {{-- MODAL STRUK --}}
    <div id="strukModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl max-w-sm w-full mx-4">
            <div class="px-5 py-3 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-receipt text-teal-600 text-sm"></i>
                    </div>
                    <h3 class="text-md font-bold text-gray-900">Struk Pembayaran</h3>
                </div>
                <button onclick="closeModal('strukModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5" id="strukContent"></div>
            <div class="px-5 py-3 border-t border-gray-200 flex justify-end gap-2">
                <button onclick="printStruk()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition text-sm">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
                <button onclick="closeModal('strukModal')"
                    class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Data dari server
        let allTransaksi = @json($transaksis);
        let filteredTransaksi = [...allTransaksi];
        let currentPage = 1;
        const PER_PAGE = 5; // Pagination 5 item per halaman

        const CSRF = '{{ csrf_token() }}';
        const BASE_URL = '{{ url('') }}';
        const THREE_HOURS_MS = 3 * 60 * 60 * 1000;

        function formatRp(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
        }

        function formatTanggal(tanggal) {
            if (!tanggal) return '-';
            const date = new Date(tanggal);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function isTerlambat(trx) {
            if (trx.status !== 'tunggak') return false;
            const created = new Date(trx.created_at || trx.tanggal);
            return (Date.now() - created.getTime()) > THREE_HOURS_MS;
        }

        function getTipeBadge(tipe) {
            if (tipe === 'dine_in')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">🍽️ Dine In</span>';
            if (tipe === 'takeaway')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-teal-100 text-teal-700 text-xs font-medium">🥡 Take Away</span>';
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">-</span>';
        }

        function getStatusBadge(trx) {
            if (trx.status === 'lunas') {
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">✅ Lunas</span>';
            }
            if (isTerlambat(trx)) {
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">⚠️ Terlambat</span>';
            }
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-xs font-medium">⏰ Belum Bayar</span>';
        }

        function updateStats() {
            const lunasCount = allTransaksi.filter(t => t.status === 'lunas').length;
            const belumBayarCount = allTransaksi.filter(t => t.status === 'tunggak').length;
            const totalPendapatan = allTransaksi
                .filter(t => t.status === 'lunas')
                .reduce((sum, t) => sum + (parseFloat(t.total_harga) || 0), 0);

            document.getElementById('statTotal').textContent = allTransaksi.length;
            document.getElementById('statPendapatan').textContent = formatRp(totalPendapatan);
            document.getElementById('statLunas').textContent = lunasCount;
            document.getElementById('statBelumBayar').textContent = belumBayarCount;
        }

        function renderTable() {
            const tbody = document.getElementById('riwayatTableBody');
            const empty = document.getElementById('emptyState');

            if (!filteredTransaksi.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                document.getElementById('totalTransaksiLabel').textContent = 'Total: 0 Transaksi';
                document.getElementById('paginationButtons').innerHTML = '';
                return;
            }

            empty.classList.add('hidden');

            const total = filteredTransaksi.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredTransaksi.slice(start, start + PER_PAGE);

            let html = '';
            pageData.forEach((trx, i) => {
                const itemCount = trx.items ? trx.items.length : 0;
                const totalQty = trx.items ? trx.items.reduce((sum, item) => sum + (item.qty || 0), 0) : 0;
                const terlambat = isTerlambat(trx);

                const rowClass = terlambat ?
                    'bg-orange-50 hover:bg-orange-100 border-l-4 border-l-orange-400' :
                    'hover:bg-gray-50';

                html += `
                    <tr class="${rowClass}">
                        <td class="px-3 py-3 text-xs text-gray-500">${start + i + 1}</td>
                        <td class="px-3 py-3 font-mono text-xs font-semibold text-gray-900">${trx.no_transaksi || '-'}</td>
                        <td class="px-3 py-3 text-xs text-gray-600">${formatTanggal(trx.created_at || trx.tanggal)}</td>
                        <td class="px-3 py-3 text-xs text-gray-700">${trx.nama_kasir || '-'}</td>
                        <td class="px-3 py-3 text-xs font-medium text-gray-700">${trx.nama_pelanggan || '-'}</td>
                        <td class="px-3 py-3">${getTipeBadge(trx.tipe_order)}</td>
                        <td class="px-3 py-3 text-xs text-gray-700">${trx.nama_meja || '-'}</td>
                        <td class="px-3 py-3 text-xs text-gray-500">${itemCount} item / ${totalQty} porsi</td>
                        <td class="px-3 py-3 text-xs font-semibold text-gray-900">${formatRp(trx.total_harga)}</td>
                        <td class="px-3 py-3">${getStatusBadge(trx)}</td>
                        <td class="px-3 py-3">
                            ${trx.status === 'lunas' 
                                ? `<button onclick="showStruk(${trx.id})" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs"><i class="fas fa-receipt"></i> Struk</button>`
                                : `<button onclick="openTagihModal(${trx.id})" class="inline-flex items-center gap-1 text-green-600 hover:text-green-700 text-xs font-medium ${terlambat ? 'animate-pulse' : ''}"><i class="fas fa-money-bill-wave"></i> Tagih</button>`
                            }
                        </td>
                    </tr>`;
            });

            tbody.innerHTML = html;
            document.getElementById('totalTransaksiLabel').textContent = `Total: ${total} Transaksi`;

            let btnHTML = '';
            if (totalPages > 1) {
                for (let p = 1; p <= totalPages; p++) {
                    btnHTML += `
                        <button onclick="goPage(${p})" 
                            class="w-7 h-7 text-xs rounded transition ${p === currentPage ? 'bg-teal-600 text-white' : 'border border-gray-300 bg-white hover:bg-gray-50'}">
                            ${p}
                        </button>`;
                }
            }
            document.getElementById('paginationButtons').innerHTML = btnHTML;
        }

        function goPage(page) {
            currentPage = page;
            renderTable();
        }

        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const tipe = document.getElementById('filterTipe').value;
            const status = document.getElementById('filterStatus').value;

            filteredTransaksi = allTransaksi.filter(t => {
                const matchSearch = !search ||
                    (t.no_transaksi || '').toLowerCase().includes(search) ||
                    (t.nama_pelanggan || '').toLowerCase().includes(search);

                const matchTipe = !tipe || t.tipe_order === tipe;

                let matchStatus = true;
                if (status === 'lunas') matchStatus = t.status === 'lunas';
                else if (status === 'tunggak') matchStatus = t.status === 'tunggak';
                else if (status === 'terlambat') matchStatus = isTerlambat(t);

                return matchSearch && matchTipe && matchStatus;
            });

            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterTipe').value = '';
            document.getElementById('filterStatus').value = '';
            filteredTransaksi = [...allTransaksi];
            currentPage = 1;
            renderTable();
        }

        function loadData() {
            window.location.reload();
        }

        // ================== MODAL TAGIH ==================
        function openTagihModal(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;
            selectedTransaksiId = id;

            const itemsHtml = (trx.items || []).map(item => `
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <span class="text-sm font-medium text-gray-800">${item.nama}</span>
                        <span class="text-xs text-gray-500 ml-2">x${item.qty}</span>
                    </div>
                    <span class="text-sm text-gray-600">${formatRp(item.harga_satuan * item.qty)}</span>
                </div>
            `).join('');

            const terlambatWarning = isTerlambat(trx) ?
                `<div class="bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 text-xs text-orange-700 flex items-center gap-2 mb-3">
                    <i class="fas fa-exclamation-triangle"></i> Order ini sudah lebih dari 3 jam belum dibayar!
                   </div>` :
                '';

            document.getElementById('tagihContent').innerHTML = `
                ${terlambatWarning}
                <div class="max-h-64 overflow-y-auto">
                    ${itemsHtml || '<p class="text-sm text-gray-400 text-center">Tidak ada item</p>'}
                </div>
                <div class="border-t border-gray-200 pt-3 space-y-2">
                    <div class="flex justify-between text-base font-bold">
                        <span>Total Tagihan</span>
                        <span class="text-green-600">${formatRp(trx.total_harga)}</span>
                    </div>
                    <input type="hidden" id="totalTagihanValue" value="${trx.total_harga}">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Bayar</label>
                        <input type="number" id="jumlahBayar" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none text-sm"
                            placeholder="Masukkan nominal" oninput="hitungKembalian()">
                    </div>
                    <div class="flex justify-between text-sm pt-1">
                        <span class="text-gray-500">Kembalian</span>
                        <span id="kembalianValue" class="font-semibold text-green-600">Rp 0</span>
                    </div>
                </div>
            `;

            document.getElementById('tagihModal').classList.remove('hidden');
        }

        function hitungKembalian() {
            const jumlahBayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
            const totalTagihan = parseFloat(document.getElementById('totalTagihanValue').value) || 0;
            const kembalianEl = document.getElementById('kembalianValue');

            const kembalian = jumlahBayar - totalTagihan;
            if (kembalian >= 0) {
                kembalianEl.textContent = formatRp(kembalian);
                kembalianEl.className = 'font-semibold text-green-600';
            } else {
                kembalianEl.textContent = formatRp(Math.abs(kembalian)) + ' (Kurang)';
                kembalianEl.className = 'font-semibold text-red-600';
            }
        }

        async function prosesTagih() {
            const jumlahBayar = document.getElementById('jumlahBayar').value;
            const trx = allTransaksi.find(t => t.id === selectedTransaksiId);

            if (!jumlahBayar || parseInt(jumlahBayar) < trx.total_harga) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nominal Kurang',
                    text: `Minimal bayar ${formatRp(trx.total_harga)}`,
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            const kembalian = parseInt(jumlahBayar) - trx.total_harga;
            const btn = document.getElementById('btnProsesTagih');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const res = await fetch(`${BASE_URL}/kasir/order/${selectedTransaksiId}/tagih`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        jumlah_bayar: parseInt(jumlahBayar),
                        kembalian: kembalian
                    })
                });

                const data = await res.json();

                if (data.success) {
                    const idx = allTransaksi.findIndex(t => t.id === selectedTransaksiId);
                    if (idx !== -1) {
                        allTransaksi[idx].status = 'lunas';
                        allTransaksi[idx].jumlah_bayar = parseInt(jumlahBayar);
                        allTransaksi[idx].kembalian = kembalian;
                    }
                    filteredTransaksi = [...allTransaksi];
                    updateStats();
                    renderTable();
                    closeModal('tagihModal');

                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        html: `<div class="text-left text-sm">
                            <p>No. Transaksi: <strong>${data.no_transaksi}</strong></p>
                            <p>Total: ${formatRp(data.total)}</p>
                            <p>Bayar: ${formatRp(data.jumlah_bayar)}</p>
                            <p class="text-green-600 font-semibold">Kembalian: ${formatRp(data.kembalian)}</p>
                        </div>`,
                        confirmButtonColor: '#10b981'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Gagal terhubung ke server.',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Bayar';
            }
        }

        function showStruk(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;

            const itemsHtml = (trx.items || []).map(item => `
                <div class="flex justify-between text-sm py-1">
                    <span>${item.nama} x${item.qty}</span>
                    <span>${formatRp(item.harga_satuan * item.qty)}</span>
                </div>
            `).join('');

            document.getElementById('strukContent').innerHTML = `
                <div class="text-center mb-3">
                    <p class="text-xs font-bold text-gray-800">STRUK PEMBAYARAN</p>
                    <p class="text-xs text-gray-400">${formatTanggal(trx.created_at || trx.tanggal)}</p>
                </div>
                <div class="border-t border-b border-gray-100 py-2 space-y-1">
                    <div class="flex justify-between text-xs"><span class="text-gray-500">No. Transaksi</span><span class="font-semibold">${trx.no_transaksi}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-500">Kasir</span><span>${trx.nama_kasir}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-500">Tipe</span><span>${trx.tipe_order === 'dine_in' ? 'Dine In' : 'Take Away'}</span></div>
                    ${trx.nama_meja ? `<div class="flex justify-between text-xs"><span class="text-gray-500">Meja</span><span>${trx.nama_meja}</span></div>` : ''}
                    <div class="flex justify-between text-xs"><span class="text-gray-500">Pelanggan</span><span>${trx.nama_pelanggan || '-'}</span></div>
                </div>
                <div class="py-2">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-1">Detail Pesanan</p>
                    ${itemsHtml || '<p class="text-xs text-gray-400">-</p>'}
                </div>
                <div class="border-t border-gray-100 pt-2 space-y-1">
                    <div class="flex justify-between text-sm font-bold"><span>TOTAL</span><span>${formatRp(trx.total_harga)}</span></div>
                    <div class="flex justify-between text-xs"><span>Bayar</span><span>${formatRp(trx.jumlah_bayar || trx.total_harga)}</span></div>
                    <div class="flex justify-between text-xs text-green-600 font-semibold"><span>Kembalian</span><span>${formatRp(trx.kembalian || 0)}</span></div>
                </div>
                <div class="text-center text-xs text-gray-400 mt-3">Terima kasih telah berkunjung!</div>
            `;
            document.getElementById('strukModal').classList.remove('hidden');
        }

        function printStruk() {
            const printContent = document.getElementById('strukContent').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html><head><title>Struk Pembayaran</title>
                <style>
                    body{font-family:'Courier New',monospace;padding:15px;max-width:300px;margin:0 auto;}
                    .text-center{text-align:center;}
                    .flex{display:flex;justify-content:space-between;}
                    .border-t,.border-b{border:1px dashed #ccc;}
                    .py-1{padding:2px 0;}
                    .text-xs{font-size:10px;}
                    .font-bold{font-weight:bold;}
                </style>
                </head><body>${printContent}</body></html>`);
            printWindow.document.close();
            printWindow.print();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Auto refresh setiap 60 detik
        setInterval(() => {
            renderTable();
        }, 60000);

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterTipe').addEventListener('change', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            updateStats();
            renderTable();
        });
    </script>
@endsection
