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
            </div>

            <!-- Stat Cards - 4 kolom (Navigation Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Card 1: Total Menu -->
                <a href="{{ route('owner.menu') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200 cursor-pointer group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Menu</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_menu']) }}</p>
                        </div>
                        <div class="text-teal-500 bg-teal-50 p-3 rounded-lg group-hover:bg-teal-100 transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card 2: Transaksi Hari Ini (Baru) -->
                <a href="{{ route('owner.riwayat') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200 cursor-pointer group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Transaksi Hari Ini</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">
                                {{ number_format($data['transaksi_hari_ini'] ?? 0) }}</p>
                        </div>
                        <div
                            class="text-orange-500 bg-orange-50 p-3 rounded-lg group-hover:bg-orange-100 transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card 3: Total Meja -->
                <a href="{{ route('owner.meja') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200 cursor-pointer group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Meja</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_meja']) }}</p>
                        </div>
                        <div class="text-blue-500 bg-blue-50 p-3 rounded-lg group-hover:bg-blue-100 transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Card 4: Total User -->
                <a href="{{ route('owner.users.index') }}"
                    class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200 cursor-pointer group">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total User</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($data['total_user']) }}</p>
                        </div>
                        <div
                            class="text-purple-500 bg-purple-50 p-3 rounded-lg group-hover:bg-purple-100 transition-colors">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM6 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </a>

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
                    <p class="text-3xl sm:text-4xl font-bold text-emerald-600 mt-4">
                        Rp {{ number_format($data['pendapatan_tahun'] ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <!-- Chart + Menu Terlaris -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Chart Pendapatan 7 Hari Terakhir (KIRI) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Pendapatan 7 Hari Terakhir</h3>
                    <div class="h-80">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>

                <!-- Menu Terlaris 7 Hari Terakhir (KANAN) - Maksimal 5, tanpa scroll -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-5">Menu Terlaris 7 Hari Terakhir</h3>
                    <div class="space-y-4">
                        @forelse ($data['menu_terlaris']->take(5) as $menu)
                            <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0">
                                <span class="text-gray-700 font-medium">{{ $menu['name'] }}</span>
                                <span class="font-semibold text-teal-600 bg-teal-50 px-3 py-1 rounded-lg">
                                    {{ number_format($menu['qty']) }} terjual
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-500">
                                Belum ada transaksi lunas dalam 7 hari terakhir.
                            </div>
                        @endforelse
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
