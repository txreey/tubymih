@extends('owner.layouts.app')

@section('title', 'Dashboard Owner - Tuangeun by Mimih')

@section('content')
    <div class="min-h-screen bg-gray-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Header + Greeting + Pendapatan Hari Ini -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard Owner</h1>
                    <p class="text-gray-600 mt-1">Ringkasan operasional Tuangeun by Mimih — {{ now()->format('d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-600">
                        Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Stat Cards - 4 kolom -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Menu</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_menu']) }}</p>
                        </div>
                        <div class="text-teal-500 bg-teal-50 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kasir Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_kasir']) }}</p>
                        </div>
                        <div class="text-green-500 bg-green-50 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Meja</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_meja']) }}</p>
                        </div>
                        <div class="text-blue-500 bg-blue-50 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">User Aktif</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_user_aktif']) }}
                            </p>
                        </div>
                        <div class="text-purple-500 bg-purple-50 p-3 rounded-lg">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM6 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl shadow-sm border border-emerald-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Pendapatan Hari Ini</h3>
                    <p class="text-3xl sm:text-4xl font-bold text-emerald-600 mt-4">
                        Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl shadow-sm border border-emerald-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Pendapatan Bulan Ini</h3>
                    <p class="text-3xl sm:text-4xl font-bold text-emerald-600 mt-4">
                        Rp {{ number_format($data['pendapatan_bulan'], 0, ',', '.') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl shadow-sm border border-emerald-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800">Pendapatan Tahun Ini</h3>
                    <p class="text-3xl sm:text-4xl font-bold text-emerald-600 mt-4">Rp 285.400.000</p>
                </div>
            </div>

            <!-- Chart + Menu Terlaris -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Chart -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Pendapatan 7 Hari Terakhir</h3>
                    <div class="h-80">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>

                <!-- Menu Terlaris (dummy) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Menu Terlaris Minggu Ini</h3>
                    <div class="space-y-4">
                        @foreach ([['name' => 'Nasi Goreng Spesial', 'qty' => 142], ['name' => 'Mie Ayam Bakso', 'qty' => 98], ['name' => 'Ayam Bakar Kecap', 'qty' => 85], ['name' => 'Es Teh Manis', 'qty' => 210], ['name' => 'Jus Mangga', 'qty' => 76], ['name' => 'Sate Ayam Madura', 'qty' => 64]] as $menu)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                <span class="text-gray-700">{{ $menu['name'] }}</span>
                                <span class="font-semibold text-teal-600">{{ number_format($menu['qty']) }} terjual</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('transaksiChart');
            if (!ctx) return;

            const labels = @json($data['transaksi_7hari']->pluck('tanggal')->map(fn($d) => $d->format('d M')));
            const values = @json($data['transaksi_7hari']->pluck('total'));

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: values,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            },
                            callbacks: {
                                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => 'Rp ' + value.toLocaleString('id-ID', {
                                    notation: 'compact'
                                })
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
