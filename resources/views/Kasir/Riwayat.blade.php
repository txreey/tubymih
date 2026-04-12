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
                Belum ada transaksi hari ini
            </div>

            <div id="paginationWrapper" class="px-4 py-3 border-t border-gray-200 flex justify-center">
                <div class="flex items-center gap-1" id="paginationButtons"></div>
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
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition">Tutup</button>
            </div>
        </div>
    </div>

    {{-- MODAL TAGIH --}}
    <div id="tagihModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl max-w-sm w-full mx-4 overflow-hidden shadow-xl">
            <div class="px-6 py-4 text-center border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-900">Tagihan Pembayaran</h3>
                <p class="text-xs text-gray-400 mt-0.5" id="tagihSubHeader"></p>
            </div>

            <div class="px-6 pt-4 pb-2" id="tagihItemList"></div>

            <div class="border-t border-dashed border-gray-200 mx-6 my-3"></div>

            <div class="px-6 pb-5 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Tagihan</span>
                    <span class="text-sm font-bold text-gray-900" id="tagihTotalLabel">Rp 0</span>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Jumlah Bayar (Cash)</label>
                    <input type="number" id="jumlahBayar"
                        class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm font-semibold text-gray-800 text-right focus:outline-none focus:border-teal-400 transition"
                        placeholder="0" oninput="hitungKembalian()">
                    <input type="hidden" id="totalTagihanValue">
                </div>

                <div class="bg-teal-50 border border-teal-100 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-teal-600 mb-0.5">Kembalian</p>
                    <p class="text-lg font-bold" id="kembalianValue" style="color:#0d9488;">Rp 0</p>
                </div>

                <div id="terlambatWarning"
                    class="hidden bg-orange-50 border border-orange-200 rounded-lg px-3 py-2 text-xs text-orange-700 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Order ini sudah lebih dari 3 jam belum dibayar!
                </div>
            </div>

            <div class="px-6 pb-5 flex gap-3">
                <button onclick="closeModal('tagihModal')"
                    class="flex-1 py-2.5 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition flex items-center justify-center gap-2">
                    <i class="fas fa-times text-xs"></i> Batal
                </button>
                <button onclick="prosesTagih()" id="btnProsesTagih"
                    class="flex-1 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm transition flex items-center justify-center gap-2">
                    <i class="fas fa-check text-xs"></i> Konfirmasi bayar
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
        let selectedTransaksiId = null;
        let currentStrukTrx = null;
        let currentPage = 1;
        const PER_PAGE = 5;

        const BASE_URL = '{{ url('') }}';
        const NAMA_RESTORAN = '{{ config('app.nama_restoran', 'Tuangeun by Mimih') }}';
        const ALAMAT_RESTORAN = '{{ config('app.alamat_restoran', 'Jl. Raya Nr Kadu 04') }}';
        const THREE_HOURS_MS = 3 * 60 * 60 * 1000;

        // CSRF TOKEN (PERBAIKAN UTAMA)
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            if (trx.status === 'lunas')
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">✅ Lunas</span>';
            if (isTerlambat(trx))
                return '<span class="inline-flex px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-xs font-bold">⚠️ Terlambat</span>';
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
            if (currentPage > totalPages) currentPage = totalPages || 1;

            const start = (currentPage - 1) * PER_PAGE;
            const pageData = filteredTransaksi.slice(start, start + PER_PAGE);

            let html = '';
            pageData.forEach((trx, i) => {
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
                    <td class="px-3 py-3 text-xs text-gray-500">
                        <button onclick="showDetail(${trx.id})" class="inline-flex items-center gap-1 text-purple-600 hover:text-purple-700 text-xs font-medium">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                    <td class="px-3 py-3 text-xs font-semibold text-gray-900">${formatRp(trx.total_harga)}</td>
                    <td class="px-3 py-3">${getStatusBadge(trx)}</td>
                    <td class="px-3 py-3">
                        <div class="flex flex-wrap gap-3">
                            ${trx.status === 'lunas' ? 
                                `<button onclick="showStruk(${trx.id})" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 text-xs font-medium">
                                        <i class="fas fa-receipt"></i> Struk
                                     </button>` : 
                                `<button onclick="openTagihModal(${trx.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold transition ${terlambat ? 'animate-pulse' : ''}">
                                        <i class="fas fa-money-bill-wave text-xs"></i> Tagih
                                     </button>`
                            }
                        </div>
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

        // MODAL TAGIH
        function openTagihModal(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;
            selectedTransaksiId = id;

            let subParts = [];
            if (trx.tipe_order === 'dine_in') subParts.push('Dine In');
            else if (trx.tipe_order === 'takeaway') subParts.push('Take Away');
            if (trx.nama_meja) subParts.push('Meja ' + trx.nama_meja);
            document.getElementById('tagihSubHeader').textContent = subParts.join(' · ');

            const itemsHtml = (trx.items || []).map(item => `
                <div class="flex justify-between items-baseline py-1.5">
                    <span class="text-sm text-gray-700">${item.nama} <span class="text-xs text-gray-400">x${item.qty}</span></span>
                    <span class="text-sm font-medium text-gray-800">${formatRp(item.harga_satuan * item.qty)}</span>
                </div>
            `).join('');

            document.getElementById('tagihItemList').innerHTML = itemsHtml ||
                '<p class="text-xs text-gray-400 text-center py-2">Tidak ada item</p>';

            document.getElementById('tagihTotalLabel').textContent = formatRp(trx.total_harga);
            document.getElementById('totalTagihanValue').value = trx.total_harga;

            document.getElementById('jumlahBayar').value = '';
            const kemEl = document.getElementById('kembalianValue');
            kemEl.textContent = 'Rp 0';
            kemEl.style.color = '#0d9488';

            document.getElementById('terlambatWarning').classList.toggle('hidden', !isTerlambat(trx));

            document.getElementById('tagihModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('jumlahBayar').focus(), 100);
        }

        function hitungKembalian() {
            const jumlahBayar = parseFloat(document.getElementById('jumlahBayar').value) || 0;
            const totalTagihan = parseFloat(document.getElementById('totalTagihanValue').value) || 0;
            const kembalianEl = document.getElementById('kembalianValue');
            const kembalian = jumlahBayar - totalTagihan;

            if (kembalian >= 0) {
                kembalianEl.textContent = formatRp(kembalian);
                kembalianEl.style.color = '#0d9488';
            } else {
                kembalianEl.textContent = '− ' + formatRp(Math.abs(kembalian));
                kembalianEl.style.color = '#dc2626';
            }
        }

        async function prosesTagih() {
            const jumlahBayarStr = document.getElementById('jumlahBayar').value.trim();
            const trx = allTransaksi.find(t => t.id === selectedTransaksiId);
            if (!trx) return;

            const jumlahBayar = parseInt(jumlahBayarStr);
            if (!jumlahBayar || jumlahBayar < trx.total_harga) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nominal Kurang',
                    text: `Minimal bayar ${formatRp(trx.total_harga)}`,
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            const kembalian = jumlahBayar - trx.total_harga;
            const btn = document.getElementById('btnProsesTagih');
            const originalBtnText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const res = await fetch(`${BASE_URL}/kasir/order/${selectedTransaksiId}/tagih`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        jumlah_bayar: jumlahBayar,
                        kembalian: kembalian
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    const idx = allTransaksi.findIndex(t => t.id === selectedTransaksiId);
                    if (idx !== -1) {
                        allTransaksi[idx].status = 'lunas';
                        allTransaksi[idx].jumlah_bayar = jumlahBayar;
                        allTransaksi[idx].kembalian = kembalian;
                    }
                    filteredTransaksi = [...allTransaksi];
                    updateStats();
                    renderTable();
                    closeModal('tagihModal');
                    showStruk(selectedTransaksiId);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan saat memproses pembayaran',
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal terhubung ke server. Silakan coba lagi.',
                    confirmButtonColor: '#ef4444'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
        }

        // MODAL STRUK
        function showStruk(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;
            currentStrukTrx = trx;

            const totalQty = (trx.items || []).reduce((s, i) => s + parseInt(i.qty || 0), 0);
            const totalMenu = (trx.items || []).length;

            const itemsHtml = (trx.items || []).map(item => `
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                    <div style="flex:1;padding-right:8px;">
                        <p style="margin:0;font-size:12px;font-weight:600;color:#111;">${item.nama}</p>
                        <p style="margin:1px 0 0;font-size:11px;color:#888;">${formatRp(item.harga_satuan)} x${item.qty}</p>
                    </div>
                    <span style="font-size:12px;color:#333;white-space:nowrap;">${formatRp(item.harga_satuan * item.qty)}</span>
                </div>
            `).join('');

            const mejaHtml = trx.nama_meja ?
                `<div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                       <span style="color:#888;font-size:12px;">Meja</span>
                       <span style="font-size:12px;">${trx.nama_meja}</span>
                   </div>` : '';

            document.getElementById('strukContent').innerHTML = `
                <div style="font-family:'Courier New',Courier,monospace;">
                    <div style="text-align:center;margin-bottom:14px;">
                        <p style="margin:0;font-size:16px;font-weight:700;color:#111;letter-spacing:0.3px;">${NAMA_RESTORAN}</p>
                        <p style="margin:3px 0 0;font-size:11px;color:#888;">${ALAMAT_RESTORAN}</p>
                    </div>
                    <hr style="border:none;border-top:1px dashed #ccc;margin:10px 0;">
                    <div style="margin-bottom:10px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                            <span style="color:#888;font-size:12px;">Tanggal</span>
                            <span style="font-size:12px;">${formatTanggalStruk(trx.created_at || trx.tanggal)}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                            <span style="color:#888;font-size:12px;">Kasir</span>
                            <span style="font-size:12px;">${trx.nama_kasir || '-'}</span>
                        </div>
                        ${mejaHtml}
                        <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
                            <span style="color:#888;font-size:12px;">No.</span>
                            <span style="font-size:11px;font-weight:600;">${trx.no_transaksi}</span>
                        </div>
                    </div>
                    <hr style="border:none;border-top:1px dashed #ccc;margin:10px 0;">
                    <div style="margin-bottom:10px;">
                        ${itemsHtml || '<p style="font-size:12px;color:#aaa;text-align:center;">Tidak ada item</p>'}
                    </div>
                    <hr style="border:none;border-top:1px dashed #ccc;margin:10px 0;">
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:12px;color:#888;">Qty/Item</span>
                            <span style="font-size:12px;">${totalQty}/${totalMenu}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:12px;color:#888;">Jumlah Bayar</span>
                            <span style="font-size:12px;">${formatRp(trx.total_harga)}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span style="font-size:13px;font-weight:700;color:#111;">Total Bayar</span>
                            <span style="font-size:13px;font-weight:700;color:#111;">${formatRp(trx.jumlah_bayar || trx.total_harga)}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="font-size:12px;color:#888;">Kembalian</span>
                            <span style="font-size:12px;">${formatRp(trx.kembalian || 0)}</span>
                        </div>
                    </div>
                    <p style="text-align:center;font-size:11px;color:#999;margin-top:16px;">
                        Terima kasih dan selamat datang kembali
                    </p>
                </div>
            `;

            document.getElementById('strukModal').classList.remove('hidden');
        }

        function printStruk() {
            if (!currentStrukTrx) return;
            const trx = currentStrukTrx;

            const totalQty = (trx.items || []).reduce((s, i) => s + parseInt(i.qty || 0), 0);
            const totalMenu = (trx.items || []).length;

            const itemsHtmlPrint = (trx.items || []).map(item => `
                <div class="item-row">
                    <div class="item-left">
                        <span class="item-name">${item.nama}</span><br>
                        <span class="item-sub">${formatRp(item.harga_satuan)} x${item.qty}</span>
                    </div>
                    <span class="item-price">${formatRp(item.harga_satuan * item.qty)}</span>
                </div>
            `).join('');

            const mejaRowPrint = trx.nama_meja ?
                `<div class="row"><span class="label">Meja</span><span>${trx.nama_meja}</span></div>` : '';

            const printWindow = window.open('', '_blank', 'width=420,height=700');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html lang="id">
                <head>
                    <meta charset="UTF-8">
                    <title>Struk - ${trx.no_transaksi}</title>
                    <style>
                        * { margin:0;padding:0;box-sizing:border-box; }
                        body {
                            font-family:'Courier New',Courier,monospace;
                            font-size:12px;color:#111;background:white;
                            width:300px;margin:0 auto;padding:20px 16px;
                        }
                        .header { text-align:center;margin-bottom:14px; }
                        .header .store-name { font-size:15px;font-weight:700;letter-spacing:0.3px; }
                        .header .store-addr { font-size:11px;color:#666;margin-top:3px; }
                        hr { border:none;border-top:1px dashed #aaa;margin:10px 0; }
                        .row { display:flex;justify-content:space-between;margin-bottom:3px; }
                        .label { color:#777; }
                        .item-row { display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px; }
                        .item-left { flex:1;padding-right:8px; }
                        .item-name { font-weight:600; }
                        .item-sub { font-size:11px;color:#777; }
                        .item-price { white-space:nowrap; }
                        .total-row { display:flex;justify-content:space-between;margin-bottom:4px; }
                        .total-row.bold { font-weight:700;font-size:13px; }
                        .footer { text-align:center;font-size:11px;color:#999;margin-top:16px; }
                        @media print {
                            @page { margin:0;size:80mm auto; }
                            body { padding:12px 10px;width:80mm; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <p class="store-name">${NAMA_RESTORAN}</p>
                        <p class="store-addr">${ALAMAT_RESTORAN}</p>
                    </div>
                    <hr>
                    <div class="row"><span class="label">Tanggal</span><span>${formatTanggalStruk(trx.created_at || trx.tanggal)}</span></div>
                    <div class="row"><span class="label">Kasir</span><span>${trx.nama_kasir || '-'}</span></div>
                    ${mejaRowPrint}
                    <div class="row"><span class="label">No.</span><span>${trx.no_transaksi}</span></div>
                    <hr>
                    ${itemsHtmlPrint || '<p style="color:#aaa;text-align:center;">Tidak ada item</p>'}
                    <hr>
                    <div class="total-row"><span class="label">Qty/Item</span><span>${totalQty}/${totalMenu}</span></div>
                    <div class="total-row"><span class="label">Jumlah Bayar</span><span>${formatRp(trx.total_harga)}</span></div>
                    <div class="total-row bold"><span>Total Bayar</span><span>${formatRp(trx.jumlah_bayar || trx.total_harga)}</span></div>
                    <div class="total-row"><span class="label">Kembalian</span><span>${formatRp(trx.kembalian || 0)}</span></div>
                    <p class="footer">Terima kasih dan selamat datang kembali</p>
                    <script>
                        window.onload = function () { setTimeout(function () { window.print(); }, 300); };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // MODAL DETAIL
        function showDetail(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) {
                Swal.fire('Error', 'Transaksi tidak ditemukan', 'error');
                return;
            }

            const items = trx.items || [];
            let itemsHtml = items.map(item => {
                const nama = item.nama || '-';
                const qty = parseInt(item.qty) || 0;
                const hargaSatuan = parseFloat(item.harga_satuan) || 0;
                const subtotal = qty * hargaSatuan;
                return `
                <div class="flex items-center justify-between py-3 px-4 border-b border-gray-100 last:border-b-0 text-sm hover:bg-gray-50">
                    <div class="flex-1 font-medium text-gray-800 truncate pr-3">${nama}</div>
                    <div class="w-28 text-center text-gray-600">${formatRp(hargaSatuan)}</div>
                    <div class="w-12 text-center text-gray-700 font-medium">x${qty}</div>
                    <div class="w-28 text-right font-semibold text-emerald-600">${formatRp(subtotal)}</div>
                </div>`;
            }).join('');

            if (!items.length) {
                itemsHtml = `<p class="text-gray-400 py-8 text-center">Tidak ada item pesanan</p>`;
            }

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
                        <div class="bg-white border border-gray-100 rounded-2xl max-h-[320px] overflow-y-auto divide-y divide-gray-100 custom-scroll">
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

            const scrollStyle = document.createElement('style');
            scrollStyle.innerHTML = `
                .custom-scroll::-webkit-scrollbar { width:6px; }
                .custom-scroll::-webkit-scrollbar-thumb { background-color:#cbd5e1;border-radius:20px; }
                .custom-scroll::-webkit-scrollbar-thumb:hover { background-color:#94a3b8; }
            `;
            document.head.appendChild(scrollStyle);
        });
    </script>
@endsection
