@extends('owner.layouts.app')

@section('title', 'Lihat Menu')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Menu</h1>
            <p class="text-gray-600 mt-1">Semua menu yang tersedia di sistem</p>
        </div>

        <!-- 3 Kotak -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Menu</p>
                <p class="text-4xl font-bold text-teal-700 mt-3">{{ $totalMenu }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Makanan</p>
                <p class="text-4xl font-bold text-orange-600 mt-3">{{ $totalMakanan }}</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Minuman</p>
                <p class="text-4xl font-bold text-blue-600 mt-3">{{ $totalMinuman }}</p>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.menu') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Menu</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama menu..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select name="kategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Kategori</option>
                            <option value="1" {{ request('kategori') == '1' ? 'selected' : '' }}>Makanan</option>
                            <option value="2" {{ request('kategori') == '2' ? 'selected' : '' }}>Minuman</option>
                        </select>
                    </div>
                    <div></div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <a href="{{ route('owner.menu') }}"
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
                <h2 class="text-lg font-bold text-gray-900">Daftar Menu</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Menu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Gambar</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($menus as $index => $menu)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $menu->nama_makanan }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                                        {{ $menu->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-600">
                                    Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $menu->stok }} pcs</td>
                                <td class="px-6 py-4">
                                    @if ($menu->gambar)
                                        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama_makanan }}"
                                            class="w-16 h-16 object-cover rounded-md shadow-sm">
                                    @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-md flex items-center justify-center">
                                            <i class="fas fa-utensils text-gray-300 text-xl"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-utensils text-5xl text-gray-300 mb-4 block"></i>
                                    Belum ada menu
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
