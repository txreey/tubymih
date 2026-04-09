@extends('owner.layouts.app')

@section('title', 'Laporan')

@php
    $exportParams = array_filter([
        'dari' => request('dari'),
        'sampai' => request('sampai'),
        'id_kasir' => request('id_kasir'),
        'export' => 'csv',
    ]);
@endphp

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
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
                                    {{ request('id_kasir') == $kasir->id ? 'selected' : '' }}>
                                    {{ $kasir->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Cari</button>
                        <a href="{{ route('owner.laporan') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">Reset</a>
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

        <!-- Grafik -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pendapatan Harian</h3>
            <div style="height: 280px;">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Header Tabel -->
            <div class="px-6 py-5 border-b flex items-center justify-between bg-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-emerald-600"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Data Laporan</h2>
                        <p class="text-xs text-gray-500">Total: {{ $laporanData->count() }} Laporan</p>
                    </div>
                </div>

                <!-- Button Ekspor di dalam tabel -->
                <a href="{{ route('owner.laporan') . '?' . http_build_query($exportParams) }}"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl flex items-center gap-2 transition shadow-sm"
                    title="Export ke CSV">
                    <i class="fas fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b bg-white">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">NO</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">TANGGAL</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">KASIR</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">TRANSAKSI</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">PENJUALAN</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporanData as $index => $row)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-5 text-sm font-medium">{{ $row['tanggal'] }}</td>
                                <td class="px-6 py-5 text-sm">{{ $row['kasir'] }}</td>
                                <td class="px-6 py-5 text-sm text-teal-600 font-semibold">{{ $row['transaksi'] }}x</td>
                                <td class="px-6 py-5 text-sm text-blue-600">{{ $row['penjualan'] }}x</td>
                                <td class="px-6 py-5 text-sm font-semibold text-emerald-600">
                                    Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-gray-400">
                                    Belum ada data laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t bg-gray-50 flex items-center justify-between text-sm">
                <div class="text-gray-600">
                    Menampilkan <strong>1-{{ $laporanData->count() }}</strong> dari
                    <strong>{{ $laporanData->count() }}</strong> laporan
                </div>
                <div class="flex gap-1">
                    <button class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-medium shadow-sm">1</button>
                    <button
                        class="px-4 py-2 bg-white border border-gray-300 rounded-xl font-medium hover:bg-gray-50">2</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // ============================================================
        // 📊 CHART.JS - Grafik Pendapatan
        // ============================================================
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

        // ============================================================
        // ✅ VALIDASI TANGGAL (Frontend)
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[method="GET"]');
            const dariInput = document.querySelector('input[name="dari"]');
            const sampaiInput = document.querySelector('input[name="sampai"]');

            // Sync min/max attribute realtime
            function syncDateRange() {
                if (dariInput?.value) sampaiInput.min = dariInput.value;
                if (sampaiInput?.value) dariInput.max = sampaiInput.value;
            }

            dariInput?.addEventListener('change', syncDateRange);
            sampaiInput?.addEventListener('change', syncDateRange);
            syncDateRange(); // Init

            // Validasi sebelum submit
            form?.addEventListener('submit', function(e) {
                const dari = dariInput?.value;
                const sampai = sampaiInput?.value;

                if (dari && sampai && dari > sampai) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanggal Tidak Valid',
                        text: 'Tanggal "Dari" tidak boleh melebihi tanggal "Sampai"',
                        confirmButtonColor: '#0d9488'
                    });
                    return false;
                }

                // Opsional: Batasi maksimal range 1 tahun
                if (dari && sampai) {
                    const diffDays = Math.ceil((new Date(sampai) - new Date(dari)) / (1000 * 60 * 60 * 24));
                    if (diffDays > 365) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Range Terlalu Lebar',
                            text: 'Maksimal rentang tanggal adalah 1 tahun (365 hari)',
                            confirmButtonColor: '#0d9488'
                        });
                        return false;
                    }
                }
            });

            // ============================================================
            // 📤 EXPORT CSV - Toast Notification (Opsional)
            // ============================================================
            document.querySelector('a[href*="export=csv"]')?.addEventListener('click', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Export dimulai...',
                    text: 'File CSV sedang disiapkan',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });

        // ============================================================
        // 🔄 Fallback: Jika tombol masih pakai onclick
        // ============================================================
        function exportToExcel() {
            // Redirect ke endpoint CSV export dengan parameter saat ini
            const params = new URLSearchParams(window.location.search);
            params.set('export', 'csv');
            window.location.href = window.location.pathname + '?' + params.toString();
        }
    </script>
@endsection
