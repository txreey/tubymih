@extends('admin.layouts.app')

@section('title', 'Laporan Pendapatan')

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
            <h1 class="text-3xl font-bold text-gray-900">Laporan Pendapatan</h1>
            <p class="text-gray-600 mt-1">Ringkasan transaksi dan pendapatan per kasir per tanggal</p>
        </div>

        {{-- Filter Box --}}
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
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
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
                        <a href="{{ route('admin.laporan') . '?' . http_build_query($exportParams) }}"
                            class="flex-1 px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Total Pendapatan Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-coins text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Total Pendapatan</p>
                        <p class="text-xs text-gray-400 mt-1">Periode yang dipilih</p>
                    </div>
                </div>
                <p class="text-3xl font-bold text-emerald-600">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chart-bar text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Rekap Per Kasir Per Tanggal</p>
                        <p class="text-xs text-gray-400 mt-1">Total: {{ $laporanData->count() }} data</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-16">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kasir</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Penjualan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporanData as $index => $row)
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-teal-600">{{ $row['tanggal'] }}</td>
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
                                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                                    <i class="fas fa-chart-bar text-5xl text-gray-200 mb-4 block"></i>
                                    Belum ada data laporan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($laporanData->count() > 0)
                        <tfoot>
                            <tr class="border-t-2 border-gray-200 bg-gray-50/40">
                                <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-700">Total Keseluruhan</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    {{ $laporanData->sum('transaksi') }} transaksi
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-700">
                                    {{ $laporanData->sum('penjualan') }} item
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-emerald-600">
                                    Rp {{ number_format($laporanData->sum('pendapatan'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            @if ($laporanData->count() === 0)
                {{-- empty state already rendered inside table --}}
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[method="GET"]');
            const dariInput = document.querySelector('input[name="dari"]');
            const sampaiInput = document.querySelector('input[name="sampai"]');

            // Validasi realtime: update min/max attribute
            function syncDateRange() {
                if (dariInput.value) {
                    sampaiInput.min = dariInput.value;
                }
                if (sampaiInput.value) {
                    dariInput.max = sampaiInput.value;
                }
            }

            dariInput.addEventListener('change', syncDateRange);
            sampaiInput.addEventListener('change', syncDateRange);
            syncDateRange(); // Init

            // Validasi sebelum submit
            form.addEventListener('submit', function(e) {
                const dari = dariInput.value;
                const sampai = sampaiInput.value;

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

                // Validasi: maksimal range 1 tahun (opsional, bisa disesuaikan)
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
        });
    </script>
@endsection
