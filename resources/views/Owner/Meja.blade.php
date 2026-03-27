@extends('owner.layouts.app')

@section('title', 'Lihat Meja')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Meja</h1>
            <p class="text-gray-600 mt-1">Semua meja yang tersedia di restoran</p>
        </div>

        <!-- 3 Kotak -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Meja</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalMeja }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Meja Tersedia</p>
                <p class="text-4xl font-bold text-green-600 mt-3">{{ $mejaTersedia }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Meja Terisi/Reserved</p>
                <p class="text-4xl font-bold text-red-600 mt-3">{{ $mejaTerisi }}</p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.meja') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari No Meja</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik no meja..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Meja</label>
                        <select name="tipe_meja"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="Lesehan" {{ request('tipe_meja') == 'Lesehan' ? 'selected' : '' }}>Lesehan
                            </option>
                            <option value="Meja Kursi" {{ request('tipe_meja') == 'Meja Kursi' ? 'selected' : '' }}>Meja
                                Kursi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved
                            </option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <a href="{{ route('owner.meja') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">Daftar Meja</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                                Meja</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tipe Meja</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kapasitas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mejas as $index => $meja)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $meja->no_meja }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $meja->tipe_meja ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $meja->kapasitas }} orang</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full 
                                        {{ $meja->status == 'tersedia' ? 'bg-green-100 text-green-800' : ($meja->status == 'terisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($meja->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-chair text-5xl text-gray-300 mb-4 block"></i>
                                    Belum ada meja
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
