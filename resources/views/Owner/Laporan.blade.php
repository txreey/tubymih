@extends('owner.layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>
            <p class="text-gray-600 mt-1">Ringkasan pendapatan kasir per tanggal</p>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <form method="GET" action="{{ route('owner.laporan') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kasir</label>
                        <select name="id_kasir"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Kasir</option>
                            @foreach ($kasirs as $kasir)
                                <option value="{{ $kasir->id }}"
                                    {{ request('id_kasir') == $kasir->id ? 'selected' : '' }}>{{ $kasir->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-3 lg:col-span-2">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Cari</button>
                        <a href="{{ route('owner.laporan') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">Reset</a>
                        <button type="button" onclick="exportToExcel()"
                            class="px-6 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm text-sm flex items-center gap-2">
                            <i class="fas fa-file-excel"></i> Ekspor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">Total Penjualan</p>
                <p class="text-4xl font-bold text-blue-700 mt-3">{{ $totalPenjualan }} item</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center">
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-4xl font-bold text-emerald-600 mt-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Grafik (Ukuran sudah diperkecil) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pendapatan Harian</h3>
            <div style="height: 280px;">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900">Data Laporan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Transaksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Penjualan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporanData as $index => $row)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $row['tanggal'] }}</td>
                                <td class="px-6 py-4 text-sm">{{ $row['kasir'] }}</td>
                                <td class="px-6 py-4 text-sm text-teal-600 font-semibold">{{ $row['transaksi'] }} transaksi
                                </td>
                                <td class="px-6 py-4 text-sm text-blue-600">{{ $row['penjualan'] }} item</td>
                                <td class="px-6 py-4 text-sm font-semibold text-emerald-600">Rp
                                    {{ number_format($row['pendapatan'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    Belum ada data laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const rawData = @json($chartData);

        const labels = rawData.map(item => item.tanggal);
        const values = rawData.map(item => item.pendapatan);

        new Chart(document.getElementById('pendapatanChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.3,
                    borderWidth: 3,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp ' + (v / 1000000) + 'jt'
                        }
                    }
                }
            }
        });

        function exportToExcel() {
            Swal.fire('Ekspor', 'Fitur sedang dikembangkan...', 'info');
        }
    </script>
@endsection
