@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="min-h-screen bg-gray-50 py-6">

        <!-- TOPBAR -->
        <div class="max-w-7xl mx-auto px-6 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Beranda</h2>
                <p class="text-gray-500 text-sm mt-1">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }} — selamat datang kembali
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 space-y-10">

            {{-- NAVIGASI CEPAT YANG SEKALIGUS STATISTIK --}}
            <div>
                <h3 class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-5 px-1">Navigasi Cepat</h3>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

                    {{-- Users --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-white border border-gray-200 hover:border-emerald-400 hover:shadow transition-all rounded-2xl p-5 text-center group">
                        <div
                            class="w-10 h-10 mx-auto bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">Users</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $data['total_kasir'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Users</p>
                    </a>

                    {{-- Meja --}}
                    <a href="{{ route('admin.meja.index') }}"
                        class="bg-white border border-gray-200 hover:border-emerald-400 hover:shadow transition-all rounded-2xl p-5 text-center group">
                        <div
                            class="w-10 h-10 mx-auto bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <rect x="2" y="7" width="20" height="10" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7V5m12 2V5M6 17v2m12-2v2" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">Meja</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $data['total_meja'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Meja</p>
                    </a>

                    {{-- Kategori --}}
                    <a href="{{ route('admin.kategori.index') }}"
                        class="bg-white border border-gray-200 hover:border-emerald-400 hover:shadow transition-all rounded-2xl p-5 text-center group">
                        <div
                            class="w-10 h-10 mx-auto bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">Kategori</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $data['total_kategori'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Kategori</p>
                    </a>

                    {{-- Menu --}}
                    <a href="{{ route('admin.menu.index') }}"
                        class="bg-white border border-gray-200 hover:border-emerald-400 hover:shadow transition-all rounded-2xl p-5 text-center group">
                        <div
                            class="w-10 h-10 mx-auto bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 2a10 10 0 100 20 10 10 0 00-10-10zm0 0v20M2 12h20" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 2c3 4 3 12 0 20M12 2C9 6 9 14 12 22" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">Menu</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $data['total_menu'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Menu</p>
                    </a>

                    {{-- Riwayat Transaksi --}}
                    <a href="{{ route('admin.riwayat') }}"
                        class="bg-white border border-gray-200 hover:border-emerald-400 hover:shadow transition-all rounded-2xl p-5 text-center group">
                        <div
                            class="w-10 h-10 mx-auto bg-violet-100 text-violet-600 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                                <rect x="9" y="3" width="6" height="4" rx="1" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6M9 16h4" />
                            </svg>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">Riwayat</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $data['total_transaksi'] }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Total Transaksi</p>
                    </a>

                </div>
            </div>

            {{-- MENU TERLARIS & STATUS MEJA --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Menu Terlaris --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-7">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-emerald-500 rounded-full"></div>
                            <h3 class="font-semibold text-lg">Menu Terlaris Hari Ini</h3>
                        </div>
                        <a href="{{ route('admin.menu.index') }}" class="text-emerald-600 text-sm font-medium">Lihat
                            semua →</a>
                    </div>
                    <div class="space-y-6">
                        @forelse($data['menu_terlaris'] ?? [] as $i => $menu)
                            @php $pct = round(($menu['total'] / ($data['menu_terlaris'][0]['total'] ?? 1)) * 100); @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span
                                        class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-xs font-bold rounded-full 
                                        {{ $i == 0 ? 'bg-amber-100 text-amber-700' : ($i == 1 ? 'bg-gray-100 text-gray-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $i + 1 }}
                                    </span>
                                    <span class="text-gray-700">{{ $menu['nama_makanan'] }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-24 bg-gray-100 h-2.5 rounded-full">
                                        <div class="bg-emerald-500 h-2.5 rounded-full"
                                            style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="font-semibold w-10 text-right">{{ $menu['total'] }}x</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 py-8 text-center">Belum ada data hari ini.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Status Meja --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-7">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <h3 class="font-semibold text-lg">Status Meja</h3>
                        </div>
                        <a href="{{ route('admin.meja.index') }}" class="text-emerald-600 text-sm font-medium">Kelola
                            →</a>
                    </div>

                    <div class="grid grid-cols-5 gap-3 mb-8">
                        @foreach ($data['semua_meja'] ?? [] as $meja)
                            <div
                                class="{{ $meja->status === 'tersedia'
                                    ? 'bg-emerald-50 border-emerald-300 text-emerald-700'
                                    : 'bg-red-50 border-red-300 text-red-700' }} 
                                border-2 font-bold text-center py-4 rounded-2xl text-sm">
                                {{ $meja->no_meja }}
                            </div>
                        @endforeach
                    </div>

                    <div class="flex gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-emerald-400 rounded"></div>
                            <span>Tersedia ({{ $data['meja_tersedia'] }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-400 rounded"></div>
                            <span>Terisi ({{ $data['total_meja'] - $data['meja_tersedia'] }})</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
