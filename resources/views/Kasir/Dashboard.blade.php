@extends('kasir.layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
    <div class="min-h-screen bg-gray-50/60 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

            <!-- Header + Greeting -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Halo, {{ Auth::user()->nama }}!</h1>
                    <p class="text-gray-600 mt-1">Aktivitas hari ini — {{ now()->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="grid grid-cols-2 sm:grid-cols-2 gap-4"> 
                <a href="{{ route('kasir.order.index') }}"
                    class="group bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <svg class="w-10 h-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold text-lg">Buat Order Baru</span>
                </a>

                <a href="{{ route('kasir.riwayat') }}"
                    class="group bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-2xl p-6 text-center shadow-md hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center">
                    <svg class="w-10 h-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-semibold text-lg">Riwayat Transaksi</span>
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- 1. Transaksi Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Transaksi Hari Ini</p>
                            <p class="text-4xl font-bold text-indigo-600 mt-3">
                                {{ number_format($data['transaksi_hari_ini']) }}
                            </p>
                        </div>
                        <div class="text-indigo-500 bg-indigo-50 p-4 rounded-2xl">
                            <i class="fas fa-receipt text-4xl"></i>
                        </div>
                    </div>
                </div>

                <!-- 2. Meja Tersedia -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Meja Tersedia</p>
                            <p class="text-4xl font-bold text-teal-600 mt-3">
                                {{ $data['meja_tersedia'] }}
                                <span class="text-xl font-normal text-gray-400">/ {{ $data['total_meja'] }}</span>
                            </p>
                        </div>
                        <div class="text-teal-500 bg-teal-50 p-4 rounded-2xl">
                            <i class="fas fa-chair text-4xl"></i>
                        </div>
                    </div>
                </div>

                <!-- 3. Pemasukan Hari Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pemasukan Hari Ini</p>
                            <p class="text-3xl font-bold text-emerald-600 mt-2">
                                Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-emerald-500 bg-emerald-50 p-4 rounded-2xl">
                            <i class="fas fa-wallet text-4xl"></i>
                        </div>
                    </div>
                </div>

                <!-- 4. Pending Tagihan -->
                <a href="{{ route('kasir.riwayat') }}?status=tunggak"
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow block">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Pending Tagihan</p>
                            <p class="text-4xl font-bold text-red-600 mt-3">
                                {{ number_format($data['pending_tagihan']) }}
                            </p>
                        </div>
                        <div class="text-red-500 bg-red-50 p-4 rounded-2xl">
                            <i class="fas fa-hand-holding-dollar text-4xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-red-600 mt-4 font-medium flex items-center gap-1">
                        <span>Klik untuk tagih</span>
                        <i class="fas fa-arrow-right"></i>
                    </p>
                </a>
            </div>

            <!-- Transaksi Terakhir -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Transaksi Terakhir</h3>
                    <a href="{{ route('kasir.riwayat') }}"
                        class="text-teal-600 hover:text-teal-700 font-medium flex items-center gap-1">
                        Lihat Semua <span class="text-xl leading-none">→</span>
                    </a>
                </div>

                @if ($data['transaksi_terakhir']->isEmpty())
                    <div class="text-center py-16 bg-gray-50 rounded-2xl">
                        <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada transaksi hari ini</p>
                        <a href="{{ route('kasir.order.index') }}"
                            class="mt-6 inline-block bg-teal-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-teal-700">
                            Buat Order Baru
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No. Transaksi</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kasir</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meja</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Item</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($data['transaksi_terakhir'] as $index => $trx)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $trx->no_transaksi }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $trx->kasir_nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                                {{ $trx->tipe_order == 'Dine in' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700' }}">
                                                {{ $trx->tipe_order }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $trx->meja }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $trx->item_text }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-3 py-1 text-xs font-medium rounded-full
                                                {{ $trx->status_color == 'green' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $trx->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
