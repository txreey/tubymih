@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-600 mt-1">Catatan semua transaksi dari kasir</p>
        </div>                  

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalTransaksi ?? 0 }}</p>
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
                        <p class="text-2xl font-bold text-green-600">{{ formatRupiah($totalPendapatan ?? 0) }}</p>
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
                        <p class="text-2xl font-bold text-teal-600">
                            {{ $transaksis->where('status', 'lunas')->count() ?? 0 }}
                        </p>
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
                        <p class="text-2xl font-bold text-orange-600">
                            {{ $transaksis->where('status', 'tunggak')->count() ?? 0 }}</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Order</label>
                        <select id="filterTipe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="dine_in">Dine In</option>
                            <option value="take_away">Take Away</option>
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
                <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">
                    <i class="fas fa-sync-alt text-xs"></i> Refresh
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-10">
                                No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No.
                                Transaksi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tipe</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Meja</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Item</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
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

            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>
                <div id="paginationBtns" class="flex items-center gap-1.5"></div>
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
                <button onclick="closeModal('strukModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 bg-gray-50" id="strukContent"></div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeModal('strukModal')"
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

    <style>
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

        .pg {
            min-width: 30px;
            height: 30px;
            padding: 0 8px;
            border-radius: 7px;
            border: 1px solid #e5e7eb;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: all .15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pg:hover {
            border-color: #0d9488;
            color: #0d9488;
        }

        .pg.active {
            background: #0d9488;
            border-color: #0d9488;
            color: #fff;
        }

        .s-lunas {
            background: #d1fae5;
            color: #065f46;
        }

        .s-tunggak {
            background: #ffedd5;
            color: #9a3412;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let allTransaksi = @json($transaksis);
        let filteredTransaksi = [...allTransaksi];
        let currentPage = 1;
        const PER_PAGE = 5;

        function formatRp(angka) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka || 0);
        }

        function formatTanggal(tanggal) {
            if (!tanggal) return '-';
            return new Date(tanggal).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getTipeBadge(tipe) {
            return tipe === 'dine_in' ?
                '<span class="inline-flex px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">Dine In</span>' :
                '<span class="inline-flex px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-semibold">Take Away</span>';
        }

        function getStatusBadge(status) {
            return status === 'lunas' ?
                '<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold s-lunas">Lunas</span>' :
                '<span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold s-tunggak">Belum Bayar</span>';
        }

        function renderTable() {
            const tbody = document.getElementById('riwayatTableBody');
            const empty = document.getElementById('emptyState');

            if (!filteredTransaksi.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                document.getElementById('totalTransaksiLabel').textContent = 'Total: 0 Transaksi';
                document.getElementById('paginationInfo').textContent = '';
                document.getElementById('paginationBtns').innerHTML = '';
                return;
            }
            empty.classList.add('hidden');

            const total = filteredTransaksi.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages;
            const start = (currentPage - 1) * PER_PAGE,
                end = Math.min(start + PER_PAGE, total);
            const pageData = filteredTransaksi.slice(start, end);

            tbody.innerHTML = pageData.map((trx, i) => {
                const itemCount = trx.detail_transaksi ? trx.detail_transaksi.length : 0;
                const totalQty = trx.detail_transaksi ? trx.detail_transaksi.reduce((s, item) => s + (item.qty ||
                    0), 0) : 0;
                const aksi = trx.status === 'lunas' ?
                    `<button onclick="showStruk(${trx.id})" class="ab ab-eye" title="Struk"><i class="fas fa-receipt"></i></button>` :
                    `<span class="text-xs text-gray-300">-</span>`;
                return `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-4 text-xs text-gray-400">${start+i+1}</td>
                        <td class="px-4 py-4 font-mono text-xs font-semibold text-teal-600">${trx.no_transaksi||'-'}</td>
                        <td class="px-4 py-4 text-xs text-gray-600">${formatTanggal(trx.created_at||trx.tanggal)}</td>
                        <td class="px-4 py-4 text-xs text-gray-700">${trx.nama_kasir||'-'}</td>
                        <td class="px-4 py-4 text-xs font-medium text-gray-700">${trx.nama_pelanggan||'-'}</td>
                        <td class="px-4 py-4">${getTipeBadge(trx.tipe_order)}</td>
                        <td class="px-4 py-4 text-xs text-gray-600">${trx.nama_meja||'-'}</td>
                        <td class="px-4 py-4 text-xs text-gray-400">${itemCount} item / ${totalQty} porsi</td>
                        <td class="px-4 py-4 text-xs font-semibold text-gray-800">${formatRp(trx.total_harga)}</td>
                        <td class="px-4 py-4">${getStatusBadge(trx.status)}</td>
                        <td class="px-4 py-4">${aksi}</td>
                    </tr>`;
            }).join('');

            document.getElementById('totalTransaksiLabel').innerHTML = `Total: <strong>${total}</strong> Transaksi`;
            document.getElementById('paginationInfo').innerHTML =
                `Menampilkan <strong>${start+1}-${end}</strong> dari <strong>${total}</strong> transaksi`;

            let btns = '';
            for (let p = 1; p <= totalPages; p++) btns +=
                `<button class="pg${p===currentPage?' active':''}" onclick="goPage(${p})">${p}</button>`;
            document.getElementById('paginationBtns').innerHTML = btns;
        }

        function goPage(page) {
            currentPage = page;
            renderTable();
        }

        function applyFilter() {
            const tanggal = document.getElementById('filterTanggal').value;
            const kasir = document.getElementById('filterKasir').value.toLowerCase().trim();
            const tipe = document.getElementById('filterTipe').value;
            filteredTransaksi = allTransaksi.filter(t => {
                const matchTanggal = !tanggal || ((t.created_at && t.created_at.split(' ')[0] === tanggal) || (t
                    .tanggal && t.tanggal.split(' ')[0] === tanggal));
                const matchKasir = !kasir || (t.nama_kasir || '').toLowerCase().includes(kasir);
                const matchTipe = !tipe || t.tipe_order === tipe;
                return matchTanggal && matchKasir && matchTipe;
            });
            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterTanggal').value = '';
            document.getElementById('filterKasir').value = '';
            document.getElementById('filterTipe').value = '';
            filteredTransaksi = [...allTransaksi];
            currentPage = 1;
            renderTable();
        }

        function showStruk(id) {
            const trx = allTransaksi.find(t => t.id === id);
            if (!trx) return;
            const items = trx.detail_transaksi || trx.items || [];
            const itemsHtml = items.map(item => `
                <div class="flex justify-between text-sm py-1">
                    <span>${item.nama_menu||item.nama} x${item.qty}</span>
                    <span>${formatRp((item.harga_satuan||item.harga)*item.qty)}</span>
                </div>`).join('');
            document.getElementById('strukContent').innerHTML = `
                <div class="text-center mb-4">
                    <p class="text-sm font-bold text-gray-800">STRUK PEMBAYARAN</p>
                    <p class="text-xs text-gray-400">${formatTanggal(trx.created_at||trx.tanggal)}</p>
                </div>
                <div class="border-t border-b border-gray-100 py-3 space-y-1.5 mb-3">
                    <div class="flex justify-between text-xs"><span class="text-gray-400">No. Transaksi</span><span class="font-semibold">${trx.no_transaksi}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Kasir</span><span>${trx.nama_kasir||'-'}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Tipe</span><span>${trx.tipe_order==='dine_in'?'Dine In':'Take Away'}</span></div>
                    ${trx.nama_meja?`<div class="flex justify-between text-xs"><span class="text-gray-400">Meja</span><span>${trx.nama_meja}</span></div>`:''}
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Pelanggan</span><span>${trx.nama_pelanggan||'-'}</span></div>
                </div>
                <div class="mb-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase mb-2">Detail Pesanan</p>
                    ${itemsHtml||'<p class="text-xs text-gray-400">-</p>'}
                </div>
                <div class="border-t border-gray-100 pt-3 space-y-1.5">
                    <div class="flex justify-between text-sm font-bold"><span>TOTAL</span><span>${formatRp(trx.total_harga)}</span></div>
                    <div class="flex justify-between text-xs"><span class="text-gray-400">Bayar</span><span>${formatRp(trx.jumlah_bayar||trx.total_harga)}</span></div>
                    <div class="flex justify-between text-xs text-teal-600 font-semibold"><span>Kembalian</span><span>${formatRp(trx.kembalian||0)}</span></div>
                </div>
                <div class="text-center text-xs text-gray-400 mt-4">Terima kasih telah berkunjung!</div>`;
            document.getElementById('strukModal').classList.remove('hidden');
        }

        function printStruk() {
            const content = document.getElementById('strukContent').innerHTML;
            const w = window.open('', '_blank');
            w.document.write(
                `<html><head><title>Struk</title><style>body{font-family:'Courier New',monospace;padding:15px;max-width:300px;margin:0 auto;}.flex{display:flex;justify-content:space-between;}.border-t{border-top:1px dashed #ccc;}.border-b{border-bottom:1px dashed #ccc;}.text-center{text-align:center;}.text-xs{font-size:10px;}.text-sm{font-size:11px;}.font-bold{font-weight:bold;}.font-semibold{font-weight:600;}.py-1{padding:2px 0;}.py-3{padding:8px 0;}.pt-3{padding-top:8px;}.mb-3{margin-bottom:12px;}.mb-4{margin-bottom:16px;}.mt-4{margin-top:16px;}.space-y-1\\.5>*+*{margin-top:4px;}.text-gray-400{color:#9ca3af;}.text-gray-800{color:#1f2937;}.text-teal-600{color:#0d9488;}.uppercase{text-transform:uppercase;}</style></head><body>${content}</body></html>`
            );
            w.document.close();
            w.print();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterTanggal').addEventListener('change', applyFilter);
            document.getElementById('filterKasir').addEventListener('input', applyFilter);
            document.getElementById('filterTipe').addEventListener('change', applyFilter);
            renderTable();
        });
    </script>
@endsection

@php
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka ?? 0, 0, ',', '.');
    }
@endphp
