@extends('kasir.layouts.app')

@section('title', 'Dashboard Kasir - Tuangeun by Mimih')

@section('content')
    {{-- <div class="min-h-screen bg-gray-50/60 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8"> --}}

            {{-- <!-- Header + Greeting -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Halo, Kasir!</h1>
                    <p class="text-gray-600 mt-1">Aktivitas hari ini — {{ now()->format('d F Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 font-medium">Pemasukan Hari Ini</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-600">
                        Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}
                    </p>
                </div>
            </div> --}}

            <!-- Quick Action Buttons -->
            {{-- <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('kasir.order.index') }}"
                    class="group bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl p-5 text-center shadow-md hover:shadow-lg hover:from-teal-600 hover:to-teal-700 transition-all duration-300 flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Buat Order Baru</span>
                </a>

                <a href="{{ route('kasir.riwayat') }}"
                    class="group bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl p-5 text-center shadow-md hover:shadow-lg hover:from-indigo-600 hover:to-indigo-700 transition-all duration-300 flex flex-col items-center justify-center">
                    <svg class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="font-semibold">Riwayat Transaksi</span>
                </a>

                <!-- Bisa tambah 2 tombol lagi kalau perlu, misal Split Bill atau Void -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center opacity-60 cursor-not-allowed">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a5 5 0 00-10 0v2m-2 0h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                    </svg>
                    <span class="font-medium text-gray-500">Split Bill</span>
                </div>

                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center opacity-60 cursor-not-allowed">
                    <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="font-medium text-gray-500">Void Transaksi</span>
                </div>
            </div>

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Meja Tersedia</p>
                            <p class="text-4xl font-bold text-teal-600 mt-2">
                                {{ $data['meja_tersedia'] }} <span class="text-xl font-normal text-gray-400">/
                                    {{ $data['total_meja'] }}</span>
                            </p>
                        </div>
                        <div class="text-teal-500 bg-teal-50 p-4 rounded-xl">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Transaksi Hari Ini</p>
                            <p class="text-4xl font-bold text-indigo-600 mt-2">
                                {{ number_format($data['transaksi_hari_ini']) }}</p>
                        </div>
                        <div class="text-indigo-500 bg-indigo-50 p-4 rounded-xl">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a4 4 0 00-8 0v2m-2 0h12a2 2 0 012 2v6a2 2 0 01-2 2H7a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Pemasukan Hari Ini</p>
                            <p class="text-3xl sm:text-4xl font-bold text-emerald-600 mt-2">
                                Rp {{ number_format($data['pendapatan_hari'], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-emerald-500 bg-emerald-50 p-4 rounded-xl">
                            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terakhir -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-semibold text-gray-800">Transaksi Terakhir Saya</h3>
                    <a href="{{ route('kasir.riwayat') }}"
                        class="text-sm text-teal-600 hover:text-teal-800 font-medium flex items-center gap-1">
                        Lihat Semua →
                    </a>
                </div>

                @if ($data['transaksi_terakhir']->isEmpty())
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 mb-4">Belum ada transaksi hari ini...</p>
                        <a href="{{ route('kasir.order.index') }}"
                            class="inline-block bg-teal-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-teal-700 transition">
                            Mulai Order Baru
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Meja</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Waktu</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($data['transaksi_terakhir'] as $trx)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">#{{ $trx->id }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if ($trx->tipe_order === 'dine_in')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    🍽️ Dine In
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                                    🥡 Take Away
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $trx->meja->nomor_meja ?? '-' }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $trx->status === 'selesai'
                                                ? 'bg-green-100 text-green-800'
                                                : ($trx->status === 'proses'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($trx->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $trx->created_at->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div> --}}
@endsection 
