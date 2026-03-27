@extends('admin.layouts.app')

@section('title', 'Laporan Pendapatan')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Laporan Pendapatan</h1>
            <p class="text-gray-600 mt-1">Ringkasan transaksi dan pendapatan per kasir per tanggal</p>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('admin.laporan') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
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
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.laporan') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">
                            Reset
                        </a>
                        <a href="{{ route('admin.laporan') }}?{{ http_build_query(array_merge(request()->all(), ['export' => 'csv'])) }}"
                            class="flex-1 px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center text-sm">
                            Export
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Pendapatan</p>
                <p class="text-4xl font-bold text-emerald-600 mt-1">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600 text-2xl">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Rekap Per Kasir Per Tanggal</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Transaksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Penjualan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($laporanData as $index => $row)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $row['tanggal'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $row['kasir'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-receipt text-teal-500 text-xs"></i>
                                        {{ $row['transaksi'] }} transaksi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fas fa-box text-blue-500 text-xs"></i>
                                        {{ $row['penjualan'] }} item
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-emerald-600">
                                    Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-chart-bar text-5xl text-gray-300 mb-4 block"></i>
                                    Belum ada data laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($laporanData->count() > 0)
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-700">Total
                                    Keseluruhan</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    {{ $laporanData->sum('transaksi') }} transaksi
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    {{ $laporanData->sum('penjualan') }} item
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-emerald-700">
                                    Rp {{ number_format($laporanData->sum('pendapatan'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
