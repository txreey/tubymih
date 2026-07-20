@extends('owner.layouts.app')

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
            <span>Baris <strong>berwarna oranye</strong> = order belum dibayar lebih dari <strong>3 jam</strong></span>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Cari Transaksi</label>
                        <input type="text" id="filterSearch" placeholder="Ketik no transaksi / nama..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kasir</label>
                        <select id="filterKasir"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Kasir</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tipe Order</label>
                        <select id="filterTipe"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="dine_in">Dine In</option>
                            <option value="takeaway">Take Away</option>
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
                Belum ada transaksi
            </div>

            {{-- Pagination Arrow Style --}}
            <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
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

    {{-- MODAL DETAIL PESANAN --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl max-w-xl w-full mx-4 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-teal-50">
                <div class="flex items-center gap-3">
                    <i class="fas fa-list-ul text-teal-600"></i>
                    <h3 class="font-bold text-gray-900">Detail Pesanan</h3>
                </div>
                <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-5" id="detailContent"></div>
            <div class="px-5 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button onclick="closeModal('detailModal')"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL STRUK --}}
    <div id="strukModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl max-w-sm w-full mx-4 overflow-hidden shadow-xl">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Struk Pembayaran</h3>
                <button onclick="closeModal('strukModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="px-6 py-5" id="strukContent"></div>

            <div class="flex gap-3 px-5 pb-5">
                <button onclick="closeModal('strukModal')"
                    class="flex-1 py-2.5 bg-[#7c3a2d] text-white text-sm font-medium rounded-xl hover:bg-[#6b3126] transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-times text-xs"></i> Tutup
                </button>
                <button onclick="printStruk()"
                    class="flex-1 py-2.5 bg-[#1a6b5a] text-white text-sm font-medium rounded-xl hover:bg-[#155a4a] transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-print text-xs"></i> Print
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let allTransaksi = @json($transaksis);
        let filteredTransaksi = [...allTransaksi];
        let currentPage = 1;
        const PER_PAGE = 5;

        const NAMA_RESTORAN = '{{ config('app.nama_restoran', 'Tuangeun by Mimih') }}';
        const ALAMAT_RESTORAN = '{{ config('app.alamat_restoran', 'Jl. Raya Wr. Kadu 84') }}';
        const THREE_HOURS_MS = 3 * 60 * 60 * 1000;

        // Format Rupiah
        function formatRp(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
        }

        // Format Tanggal
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

        // Format Tanggal untuk Struk 
        function formatTanggalStruk(tanggal) {
            if (!tanggal) return '-';
            const date = new Date(tanggal);
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            const hour = String(date.getHours()).padStart(2, '0');
            const min = String(date.getMinutes()).padStart(2, '0');
            return `${day}-${month}-${year} ${hour}:${min}`;
        }

        // Cek transaksi (>3 jam)
        function isTerlambat(trx) {
            if (trx.status !== 'tunggak') return false;
            const created = new Date(trx.created_at || trx.tanggal);
            return (Date.now() - created.getTime()) > THREE_HOURS_MS;
        }

        // Badge Tipe Order
        function getTipeBadge(tipe) {
            if (tipe === 'dine_in')
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">🍽️ Dine In</span>';
            if (tipe === 'takeaway')
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-teal-100 text-teal-700 text-xs font-medium">🥡 Take Away</span>';
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">-</span>';
        }

        // Badge Status Pembayaran
        function getStatusBadge(trx) {
            if (trx.status === 'lunas')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">✅ Lunas</span>';
            if (isTerlambat(trx))
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">⚠️ Terlambat</span>';
            return '<span class="inline-flex px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-xs font-medium">⏰ Belum Bayar</span>';
        }

        // Isi Dropdown Kasir
        function isiDropdownKasir() {
            const kasirSet = new Set();
            allTransaksi.forEach(t => {
                if (t.kasir?.nama) kasirSet.add(t.kasir.nama);
                if (t.nama_kasir) kasirSet.add(t.nama_kasir);
            });

            const select = document.getElementById('filterKasir');
            select.innerHTML = '<option value="">-- Semua Kasir --</option>';
            Array.from(kasirSet).sort().forEach(nama => {
                const opt = document.createElement('option');
                opt.value = nama;
                opt.textContent = nama;
                select.appendChild(opt);
            });
        }

        // Update Statistik di Header
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

        // Render Tabel
        function renderTable() {
            const tbody = document.getElementById('riwayatTableBody');
            const empty = document.getElementById('emptyState');

            if (!filteredTransaksi.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                document.getElementById('totalTransaksiLabel').textContent = 'Total: 0 Transaksi';
                return;
            }

            empty.classList.add('hidden');

            const total = filteredTransaksi.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredTransaksi.slice(start, start + PER_PAGE);

            let html = '';
            pageData.forEach((trx, i) => {
                const terlambat = isTerlambat(trx);
                const rowClass = terlambat ? 'bg-orange-50 hover:bg-orange-100 border-l-4 border-l-orange-400' :
                    'hover:bg-gray-50';

                html += `
                <tr class="${rowClass}">
                    <td class="px-3 py-3 text-xs text-gray-500">${start + i + 1}</td>
                    <td class="px-3 py-3 font-mono text-xs font-semibold text-gray-900">${trx.no_transaksi || '-'}</td>
                    <td class="px-3 py-3 text-xs text-gray-600">${formatTanggal(trx.created_at || trx.tanggal)}</td>
                    <td class="px-3 py-3 text-xs text-gray-700">${trx.kasir?.nama || trx.nama_kasir || '-'}</td>
                    <td class="px-3 py-3 text-xs font-medium text-gray-700">${trx.nama_pelanggan || '-'}</td>
                    <td class="px-3 py-3">${getTipeBadge(trx.tipe_order)}</td>
                    <td class="px-3 py-3 text-xs text-gray-700">${trx.nama_meja || '-'}</td>
                    <td class="px-3 py-3 text-xs text-gray-500">
                        <button onclick="showDetail(${trx.id})" 
                            class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-700 text-xs font-medium">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                    <td class="px-3 py-3 text-xs font-semibold text-gray-900">${formatRp(trx.total_harga)}</td>
                    <td class="px-3 py-3">${getStatusBadge(trx)}</td>
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-3">
                            ${trx.status === 'lunas' 
                                ? `<button onclick="showStruk(${trx.id})" 
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
                                            <i class="fas fa-receipt"></i> Struk
                                       </button>`
                                : `<span class="text-xs text-gray-400">—</span>`
                            }
                        </div>
                    </td>
                </tr>`;
            });

            tbody.innerHTML = html;
            document.getElementById('totalTransaksiLabel').textContent = `Total: ${total} Transaksi`;

            const sampai = Math.min(currentPage * PER_PAGE, total);
            document.getElementById('paginationInfo').innerHTML =
                `Menampilkan <strong>${start + 1}–${sampai}</strong> dari <strong>${total}</strong> transaksi`;

            document.getElementById('currentPageBox').textContent = currentPage;
            document.getElementById('btnPrev').disabled = currentPage === 1;
            document.getElementById('btnNext').disabled = currentPage === totalPages;
        }

        // Pagination
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredTransaksi.length / PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        }

        // Filter
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const kasir = document.getElementById('filterKasir').value;
            const tipe = document.getElementById('filterTipe').value;
            const status = document.getElementById('filterStatus').value;

            filteredTransaksi = allTransaksi.filter(t => {
                const matchSearch = !search ||
                    (t.no_transaksi || '').toLowerCase().includes(search) ||
                    (t.nama_pelanggan || '').toLowerCase().includes(search);

                const matchKasir = !kasir || (t.kasir?.nama === kasir || t.nama_kasir === kasir);
                const matchTipe = !tipe || t.tipe_order === tipe;

                let matchStatus = true;
                if (status === 'lunas') matchStatus = t.status === 'lunas';
                else if (status === 'tunggak') matchStatus = t.status === 'tunggak';
                else if (status === 'terlambat') matchStatus = isTerlambat(t);

                return matchSearch && matchKasir && matchTipe && matchStatus;
            });

            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKasir').value = '';
            document.getElementById('filterTipe').value = '';
            document.getElementById('filterStatus').value = '';
            filteredTransaksi = [...allTransaksi];
            currentPage = 1;
            renderTable();
        }

        function loadData() {
            window.location.reload();
        }

        // ==================== MODAL DETAIL ====================
        function showDetail(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;

            const items = trx.items || [];
            let itemsHtml = items.map(item => `
                <div class="flex items-center justify-between py-3 px-4 border-b border-gray-100 last:border-b-0 text-sm hover:bg-gray-50">
                    <div class="flex-1 font-medium text-gray-800 truncate pr-3">${item.nama || item.nama_menu || '-'}</div>
                    <div class="w-28 text-center text-gray-600">${formatRp(item.harga_satuan || item.harga)}</div>
                    <div class="w-12 text-center text-gray-700 font-medium">x${item.qty}</div>
                    <div class="w-28 text-right font-semibold text-emerald-600">${formatRp((item.harga_satuan || item.harga) * item.qty)}</div>
                </div>
            `).join('');

            if (!items.length) itemsHtml = `<p class="text-gray-400 py-8 text-center">Tidak ada item pesanan</p>`;

            document.getElementById('detailContent').innerHTML = `
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-4 text-xs bg-gray-50 p-3.5 rounded-xl">
                        <div>
                            <span class="text-gray-500">No Transaksi</span>
                            <p class="font-mono font-medium text-gray-900">${trx.no_transaksi || '-'}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">Tanggal</span>
                            <p class="text-gray-900">${formatTanggal(trx.created_at || trx.tanggal)}</p>
                        </div>
                    </div>
                    <div>
                        <p class="uppercase text-xs font-semibold text-gray-500 mb-3">DAFTAR MENU PESANAN</p>
                        <div class="bg-white border border-gray-100 rounded-2xl max-h-[320px] overflow-y-auto divide-y divide-gray-100">
                            ${itemsHtml}
                        </div>
                    </div>
                    <div class="pt-5 border-t border-gray-200 flex justify-between items-center text-xl font-bold">
                        <span class="text-gray-700">Total Pembayaran</span>
                        <span class="text-emerald-600">${formatRp(trx.total_harga)}</span>
                    </div>
                </div>`;

            document.getElementById('detailModal').classList.remove('hidden');
        }

        // ==================== MODAL STRUK ====================
        function showStruk(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;

            const items = trx.items || [];
            const totalQty = items.reduce((sum, i) => sum + parseInt(i.qty || 0), 0);
            const totalMenu = items.length;

            const itemsHtml = items.map(item => `
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <div style="flex:1;">
                        <p style="margin:0;font-size:13px;font-weight:600;">${item.nama || item.nama_menu}</p>
                        <p style="margin:2px 0 0;font-size:11px;color:#666;">${formatRp(item.harga_satuan || item.harga)} × ${item.qty}</p>
                    </div>
                    <span style="font-size:13px;white-space:nowrap;">${formatRp((item.harga_satuan || item.harga) * item.qty)}</span>
                </div>
            `).join('');

            document.getElementById('strukContent').innerHTML = `
                <div style="font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.4;">
                    <div style="text-align:center;margin-bottom:15px;">
                        <p style="font-size:16px;font-weight:700;margin:0;">${NAMA_RESTORAN}</p>
                        <p style="font-size:11px;color:#666;margin:4px 0 0;">${ALAMAT_RESTORAN}</p>
                    </div>
                    <hr style="border-top:1px dashed #aaa;margin:10px 0;">
                    <div style="margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;"><span style="color:#666;">Tanggal</span><span>${formatTanggalStruk(trx.created_at || trx.tanggal)}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:#666;">Kasir</span><span>${trx.kasir?.nama || trx.nama_kasir || '-'}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:#666;">No.</span><span style="font-weight:600;">${trx.no_transaksi}</span></div>
                    </div>
                    <hr style="border-top:1px dashed #aaa;margin:10px 0;">
                    <div style="margin-bottom:10px;">
                        ${itemsHtml || '<p style="color:#aaa;text-align:center;">Tidak ada item</p>'}
                    </div>
                    <hr style="border-top:1px dashed #aaa;margin:10px 0;">
                    <div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:#666;">Total Qty/Item</span><span>${totalQty}/${totalMenu}</span></div>
                        <div style="display:flex;justify-content:space-between;font-weight:700;"><span>Total Bayar</span><span>${formatRp(trx.total_harga)}</span></div>
                        <div style="display:flex;justify-content:space-between;"><span style="color:#666;">Kembalian</span><span>${formatRp(trx.kembalian || 0)}</span></div>
                    </div>
                    <p style="text-align:center;font-size:11px;color:#999;margin-top:20px;">Terima kasih dan selamat datang kembali</p>
                </div>
            `;

            document.getElementById('strukModal').classList.remove('hidden');
        }

        function printStruk() {
            window.print();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            isiDropdownKasir();
            updateStats();
            renderTable();

            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterKasir').addEventListener('change', applyFilter);
            document.getElementById('filterTipe').addEventListener('change', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
        });

        // Expose functions
        window.applyFilter = applyFilter;
        window.resetFilter = resetFilter;
        window.prevPage = prevPage;
        window.nextPage = nextPage;
        window.showDetail = showDetail;
        window.showStruk = showStruk;
        window.printStruk = printStruk;
        window.closeModal = closeModal;
        window.loadData = loadData;
    </script>
@endsection
