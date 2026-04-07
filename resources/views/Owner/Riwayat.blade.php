@extends('owner.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-600 mt-1">Catatan semua transaksi dari kasir</p>
        </div>

        {{-- Statistik Cards (sama persis Admin) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-800" id="totalTransaksiCard">0</p>
                    </div>
                    <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt text-blue-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600" id="totalPendapatanCard">Rp 0</p>
                    </div>
                    <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Lunas</p>
                        <p class="text-2xl font-bold text-teal-600" id="totalLunasCard">0</p>
                    </div>
                    <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-teal-600 text-lg"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Belum Bayar</p>
                        <p class="text-2xl font-bold text-orange-600" id="totalNunggakCard">0</p>
                    </div>
                    <div class="w-11 h-11 bg-orange-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600 text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                        <input type="date" id="filterTanggal"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Kasir</label>
                        <input type="text" id="filterKasir" placeholder="Ketik nama kasir..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="filterStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="lunas">Lunas</option>
                            <option value="tunggak">Belum Bayar</option>
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

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-history text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Riwayat Transaksi</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalTransaksiLabel">Total: 0 Transaksi</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-14">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No.
                                Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Item</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="riwayatTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-receipt text-5xl text-gray-200 mb-4 block"></i>
                Belum ada transaksi
            </div>

            <div id="paginationWrapper"
                class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5" id="paginationButtons"></div>
            </div>
        </div>
    </div>

    {{-- MODAL STRUK --}}
    <div id="strukModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-sm w-full mx-4">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 class="text-xl font-bold">Struk Pembayaran</h3>
                </div>
                <button onclick="closeStrukModal()"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 bg-gray-50" id="strukContent"></div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeStrukModal()"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button onclick="printStruk()"
                    class="px-8 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg transition shadow-md flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let semuaTransaksi = @json($transaksis);
        let transaksiTerfilter = [...semuaTransaksi];

        const PER_PAGE = 5;
        let halamanAktif = 1;

        const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);

        function updateCards() {
            const total = semuaTransaksi.length;
            const lunas = semuaTransaksi.filter(t => t.status === 'lunas').length;
            const nunggak = semuaTransaksi.filter(t => t.status === 'tunggak').length;
            const totalPendapatan = semuaTransaksi
                .filter(t => t.status === 'lunas')
                .reduce((sum, t) => sum + (parseFloat(t.total_harga) || 0), 0);

            document.getElementById('totalTransaksiCard').textContent = total;
            document.getElementById('totalPendapatanCard').textContent = formatRupiah(totalPendapatan);
            document.getElementById('totalLunasCard').textContent = lunas;
            document.getElementById('totalNunggakCard').textContent = nunggak;
        }

        function applyFilter() {
            const tanggal = document.getElementById('filterTanggal').value;
            const kasir = document.getElementById('filterKasir').value.toLowerCase().trim();
            const status = document.getElementById('filterStatus').value;

            transaksiTerfilter = semuaTransaksi.filter(t => {
                const matchTanggal = !tanggal || (t.tanggal && t.tanggal.split(' ')[0] === tanggal);
                const matchKasir = !kasir || (t.kasir?.nama || '').toLowerCase().includes(kasir);
                const matchStatus = !status || t.status === status;
                return matchTanggal && matchKasir && matchStatus;
            });

            halamanAktif = 1;
            renderHalaman();
        }

        function resetFilter() {
            document.getElementById('filterTanggal').value = '';
            document.getElementById('filterKasir').value = '';
            document.getElementById('filterStatus').value = '';
            transaksiTerfilter = [...semuaTransaksi];
            halamanAktif = 1;
            renderHalaman();
        }

        function renderHalaman() {
            const tbody = document.getElementById('riwayatTableBody');
            const empty = document.getElementById('emptyState');
            const wrapper = document.getElementById('paginationWrapper');
            const total = transaksiTerfilter.length;

            document.getElementById('totalTransaksiLabel').textContent = `Total: ${semuaTransaksi.length} Transaksi`;

            if (!total) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                wrapper.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');
            wrapper.classList.remove('hidden');

            const totalHalaman = Math.ceil(total / PER_PAGE);
            const mulai = (halamanAktif - 1) * PER_PAGE;
            const dataHalaman = transaksiTerfilter.slice(mulai, mulai + PER_PAGE);

            tbody.innerHTML = dataHalaman.map((trx, i) => {
                const nomor = mulai + i + 1;
                const statusClass = trx.status === 'lunas' ? 'bg-green-100 text-green-700' :
                    'bg-orange-100 text-orange-700';
                const statusText = trx.status === 'lunas' ? 'Lunas' : 'Belum Bayar';

                const aksi = trx.status === 'lunas' ?
                    `<button onclick="showStruk(${trx.id})" class="w-8 h-8 flex items-center justify-center bg-teal-50 hover:bg-teal-100 text-teal-600 rounded-lg transition" title="Lihat Struk">
                        <i class="fas fa-receipt"></i>
                     </button>` : `<span class="text-gray-300">-</span>`;

                return `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">${nomor}</td>
                        <td class="px-6 py-4 font-mono text-sm font-semibold text-teal-600">${trx.no_transaksi ?? '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">${trx.tanggal ? new Date(trx.tanggal).toLocaleString('id-ID') : '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${trx.kasir?.nama ?? '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${trx.nama_pelanggan ?? '-'}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${trx.tipe_order === 'dine_in' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700'}">
                                ${trx.tipe_order === 'dine_in' ? 'Dine In' : 'Take Away'}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">${trx.meja?.no_meja ?? '-'}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">${(trx.detail_transaksi || trx.items || []).length} item</td>
                        <td class="px-6 py-4 text-sm font-semibold text-emerald-600">${formatRupiah(trx.total_harga)}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">${statusText}</span>
                        </td>
                        <td class="px-6 py-4">${aksi}</td>
                    </tr>
                `;
            }).join('');

            renderPagination(total, totalHalaman);
        }

        function renderPagination(total, totalHalaman) {
            const info = document.getElementById('paginationInfo');
            const btns = document.getElementById('paginationButtons');
            const mulai = (halamanAktif - 1) * PER_PAGE + 1;
            const sampai = Math.min(halamanAktif * PER_PAGE, total);

            info.innerHTML = `Menampilkan <strong>${mulai}-${sampai}</strong> dari <strong>${total}</strong> transaksi`;

            if (totalHalaman <= 1) {
                btns.innerHTML = '';
                return;
            }

            let html = '';
            const base =
                'min-w-[34px] h-8 flex items-center justify-center rounded-lg border text-sm font-medium transition';
            const activeClass = `${base} bg-teal-600 border-teal-600 text-white`;
            const normalClass = `${base} bg-white border-gray-200 text-gray-700 hover:border-teal-500 hover:text-teal-600`;

            html += halamanAktif > 1 ?
                `<button onclick="keHalaman(${halamanAktif-1})" class="${normalClass}"><i class="fas fa-chevron-left"></i></button>` :
                `<button class="${base} bg-gray-100 text-gray-300 cursor-not-allowed" disabled><i class="fas fa-chevron-left"></i></button>`;

            for (let i = 1; i <= totalHalaman; i++) {
                if (i === halamanAktif) html += `<button class="${activeClass}">${i}</button>`;
                else if (i === 1 || i === totalHalaman || Math.abs(i - halamanAktif) <= 1) {
                    html += `<button onclick="keHalaman(${i})" class="${normalClass}">${i}</button>`;
                } else if (Math.abs(i - halamanAktif) === 2) {
                    html += `<span class="px-2 text-gray-400">…</span>`;
                }
            }

            html += halamanAktif < totalHalaman ?
                `<button onclick="keHalaman(${halamanAktif+1})" class="${normalClass}"><i class="fas fa-chevron-right"></i></button>` :
                `<button class="${base} bg-gray-100 text-gray-300 cursor-not-allowed" disabled><i class="fas fa-chevron-right"></i></button>`;

            btns.innerHTML = html;
        }

        function keHalaman(page) {
            halamanAktif = page;
            renderHalaman();
        }

        function showStruk(id) {
            const trx = semuaTransaksi.find(t => t.id === id);
            if (!trx) return;

            const items = trx.detail_transaksi || trx.items || [];
            const itemsHtml = items.map(item => `
                <div class="flex justify-between text-sm py-1 border-b last:border-none">
                    <span>${item.nama_menu || item.nama} ×${item.qty}</span>
                    <span>${formatRupiah((item.harga_satuan || item.harga) * item.qty)}</span>
                </div>
            `).join('');

            document.getElementById('strukContent').innerHTML = `
                <div class="text-center mb-4">
                    <p class="text-sm font-bold text-gray-800">STRUK PEMBAYARAN</p>
                    <p class="text-xs text-gray-400">${trx.tanggal ? new Date(trx.tanggal).toLocaleString('id-ID') : '-'}</p>
                </div>
                <div class="border-t border-b border-gray-100 py-3 space-y-1.5 mb-3">
                    <div class="flex justify-between text-xs"><span class="text-gray-400">No. Transaksi</span><span class="font-semibold">${trx.no_transaksi}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Kasir</span><span>${trx.kasir?.nama || '-'}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Customer</span><span>${trx.nama_pelanggan || '-'}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Tipe</span><span>${trx.tipe_order === 'dine_in' ? 'Dine In' : 'Take Away'}</span></div>
                    ${trx.meja ? `<div class="flex justify-between text-xs"><span class="text-gray-400">Meja</span><span>${trx.meja.no_meja}</span></div>` : ''}
                </div>
                <div class="mb-3">${itemsHtml}</div>
                <div class="border-t border-gray-100 pt-3">
                    <div class="flex justify-between text-sm font-bold">
                        <span>TOTAL</span>
                        <span>${formatRupiah(trx.total_harga)}</span>
                    </div>
                </div>
                <div class="text-center text-xs text-gray-400 mt-6">Terima kasih telah berkunjung!</div>
            `;
            document.getElementById('strukModal').classList.remove('hidden');
        }

        function closeStrukModal() {
            document.getElementById('strukModal').classList.add('hidden');
        }

        function printStruk() {
            window.print();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterTanggal').addEventListener('change', applyFilter);
            document.getElementById('filterKasir').addEventListener('input', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);

            updateCards();
            renderHalaman();
        });

        window.applyFilter = applyFilter;
        window.resetFilter = resetFilter;
        window.keHalaman = keHalaman;
        window.showStruk = showStruk;
        window.closeStrukModal = closeStrukModal;
        window.printStruk = printStruk;
    </script>
@endsection
