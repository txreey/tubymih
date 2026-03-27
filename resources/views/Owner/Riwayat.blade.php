@extends('owner.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Riwayat Transaksi</h1>
            <p class="text-gray-600 mt-1">Semua transaksi yang sudah terjadi di sistem</p>
        </div>

        <!-- 3 Kotak Ringkasan -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Transaksi Selesai</p>
                <p class="text-4xl font-bold text-green-600 mt-3">{{ $totalSelesai }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Transaksi Pending/Proses</p>
                <p class="text-4xl font-bold text-red-600 mt-3">{{ $totalPending }}</p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.riwayat') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Kasir</label>
                        <input type="text" name="search_kasir" value="{{ request('search_kasir') }}"
                            placeholder="Ketik nama kasir..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Filter
                        </button>
                        <a href="{{ route('owner.riwayat') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Daftar Transaksi</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Meja</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transaksis as $index => $trx)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-5 text-sm font-medium text-gray-900">
                                    {{ $trx->tanggal->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $trx->kasir->nama ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $trx->meja->no_meja ?? '-' }}</td>
                                <td class="px-6 py-5 text-sm font-semibold text-emerald-600">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-5">
                                    <span
                                        class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full 
                                        {{ $trx->status == 'selesai' ? 'bg-green-100 text-green-800' : ($trx->status == 'proses' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <button onclick="openDetailModal({{ $trx->toJson() }})" title="Detail Transaksi"
                                        class="text-teal-600 hover:text-teal-800 transition text-xl">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center text-gray-500">
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

    <!-- Modal Detail Transaksi -->
    <div id="detailModal" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all duration-300 scale-95 opacity-0 border border-gray-300"
            id="modalContent">
            <!-- Header Toko -->
            <div class="p-5 border-b border-gray-400 text-center">
                <h2 class="text-xl font-bold text-gray-900">Tuangeun by Mimih</h2>
                <p class="text-sm text-gray-700">Jl. Raya Warung Kadu No.65</p>
            </div>

            <!-- Isi Struk -->
            <div class="p-5 text-sm font-mono leading-tight space-y-1">
                <div class="flex justify-between">
                    <span>Tanggal :</span>
                    <span id="modalTanggal">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir :</span>
                    <span id="modalKasir">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Meja :</span>
                    <span id="modalMeja">-</span>
                </div>
                <div class="flex justify-between">
                    <span>Status :</span>
                    <span id="modalStatus">-</span>
                </div>

                <div class="my-3 border-t border-dashed border-gray-500"></div>

                <div class="flex justify-between font-bold text-base">
                    <span>Total :</span>
                    <span id="modalTotal" class="text-emerald-700">-</span>
                </div>
            </div>

            <!-- Tombol -->
            <div class="px-5 py-4 bg-gray-50 border-t flex justify-end gap-3">
                <button onclick="closeModal()"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(trx) {
            document.getElementById('modalTanggal').textContent = trx.tanggal ?? '-';
            document.getElementById('modalKasir').textContent = trx.kasir?.nama ?? '-';
            document.getElementById('modalMeja').textContent = trx.meja?.no_meja ?? '-';
            document.getElementById('modalStatus').textContent = trx.status ?
                trx.status.charAt(0).toUpperCase() + trx.status.slice(1) : '-';
            document.getElementById('modalTotal').textContent =
                'Rp ' + new Intl.NumberFormat('id-ID').format(trx.total_harga ?? 0);

            document.getElementById('detailModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
                document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            const content = document.getElementById('modalContent');
            content.classList.add('scale-95', 'opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => document.getElementById('detailModal').classList.add('hidden'), 300);
        }
    </script>
@endsection
