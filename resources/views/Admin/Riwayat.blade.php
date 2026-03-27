@extends('admin.layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">
        <!-- Header Utama -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-600 mt-1">Catatan semua transaksi dari kasir</p>
        </div>

        {{-- <!-- Ringkasan Total (card terpisah, di atas) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-4xl font-bold text-emerald-700 mt-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div> --}}

        <!-- Filter Box (card terpisah, di bawah total) -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <div class="flex flex-wrap items-end gap-4">
                <!-- Input Tanggal -->
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400">
                </div>

                <!-- Input Cari Nama Kasir -->
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Kasir</label>
                    <input type="text" name="search_kasir" placeholder="Ketik nama kasir..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400">
                </div>

                <!-- Dropdown Kasir (opsional kalau mau filter dropdown) -->
                {{-- <div class="flex-1 min-w-[180px]"> --}}
                    {{-- <label class="block text-sm font-medium text-gray-700 mb-1.5">Kasir</label>
                    <select name="staff"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400">
                        <option value="">Semua Kasir</option>
                        <option value="Kasir A">Kasir A</option>
                        <option value="Kasir B">Kasir B</option>
                    </select>
                </div> --}}

                <!-- Tombol Filter & Reset (sejajar di ujung kanan) -->
                <div class="flex items-end gap-3 min-w-fit">
                    <button type="button"
                        class="px-7 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm">
                        Filter
                    </button>
                    <button type="button"
                        class="px-7 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition shadow-sm">
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Staff / Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Jumlah Barang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Total</th>
                            {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th> --}}
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dummyTransaksi as $index => $trx)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-900">{{ $trx['tanggal'] }}</td>
                                <td class="px-6 py-5 text-sm text-gray-700">{{ $trx['staff'] }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ count($trx['items']) }} item</td>
                                <td class="px-6 py-5 text-sm font-semibold text-emerald-600">
                                    Rp {{ number_format($trx['total'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-5">
                                    <button
                                        onclick="openDetailModal({{ json_encode($trx, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }})"
                                        class="text-teal-600 hover:text-teal-800 text-xl transition">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-receipt text-6xl text-gray-300 mb-4 block"></i>
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Struk Detail (tetap sama) -->
    <div id="detailModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-300"
            id="modalContent">
            <!-- Header Toko -->
            <div class="p-5 border-b border-gray-400 text-center">
                <h2 class="text-xl font-bold text-gray-900">Tuangeun by Mimih</h2>
                <p class="text-sm text-gray-700">Jl. Raya Warung Kadu</p>
                <p class="text-sm text-gray-700">No.65</p>
            </div>

            <!-- Isi Struk -->
            <div class="p-5 text-sm font-mono leading-tight">
                <div class="flex justify-between">
                    <span>Tanggal :</span>
                    <span id="modalTanggal">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Staff :</span>
                    <span id="modalStaff">-</span>
                </div>

                <div class="my-3 border-t border-dashed border-gray-500"></div>

                <div id="modalItemsContainer"></div>

                <div class="my-3 border-t border-dashed border-gray-500"></div>

                <div class="space-y-0.5">
                    <div class="flex justify-between">
                        <span>Qty/Item :</span>
                        <span id="modalQtyTotal">8/3</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jumlah Bayar :</span>
                        <span id="modalJumlahBayar">Rp 150.000</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>Total Bayar :</span>
                        <span id="modalTotal">Rp 140.000</span>
                    </div>
                    <div class="flex justify-between font-bold text-emerald-700">
                        <span>Kembalian :</span>
                        <span id="modalKembalian">Rp 10.000</span>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="px-5 py-4 bg-gray-50 border-t flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition text-sm">
                    Tutup
                </button>
                <button onclick="window.print()"
                    class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition flex items-center gap-2 text-sm">
                    <i class="fas fa-print"></i> Cetak Struk
                </button>
            </div>
        </div>
    </div>

    <!-- Script tetap sama -->
    <script>
        function openDetailModal(trx) {
            document.getElementById('modalTanggal').textContent = trx.tanggal || '-';
            document.getElementById('modalStaff').textContent = trx.staff || '-';

            const jumlahBayar = trx.total + trx.kembalian;
            document.getElementById('modalJumlahBayar').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                jumlahBayar);

            document.getElementById('modalTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total);
            document.getElementById('modalKembalian').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(trx
                .kembalian || 0);

            let totalQty = 0;
            let jenisItem = trx.items ? trx.items.length : 0;
            if (trx.items) {
                trx.items.forEach(item => totalQty += (item.qty || 0));
            }
            document.getElementById('modalQtyTotal').textContent = totalQty + '/' + jenisItem;

            let html = '';
            if (trx.items) {
                trx.items.forEach(item => {
                    const subtotal = (item.qty || 0) * (item.harga || 0);
                    html += `
                        <div class="flex justify-between py-0.5">
                            <span>${item.nama || '-'}</span>
                            <span>X${item.qty || 0}</span>
                            <span>Rp ${new Intl.NumberFormat('id-ID').format(subtotal)}</span>
                        </div>`;
                });
            }
            document.getElementById('modalItemsContainer').innerHTML = html;

            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');

            if (!modal || !content) return;

            modal.classList.remove('hidden');
            setTimeout(() => content.classList.remove('scale-95', 'opacity-0'), 10);
        }

        function closeModal() {
            const content = document.getElementById('modalContent');
            if (content) {
                content.classList.add('scale-95', 'opacity-0');
                setTimeout(() => document.getElementById('detailModal').classList.add('hidden'), 300);
            }
        }
    </script>
@endsection
