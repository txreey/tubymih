@extends('owner.layouts.app')
@section('title', 'Log Aktivitas')
@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-gray-600 mt-1">Riwayat aktivitas semua user</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.log') }}" id="filterForm" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" id="dari_tanggal" value="{{ request('dari_tanggal') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" id="sampai_tanggal"
                            value="{{ request('sampai_tanggal') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                        <select name="role" id="filterRole"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <a href="{{ route('owner.log') }}"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-center text-sm">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-list text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Log Aktivitas</p>
                        <p class="text-xs text-gray-400 mt-1">Total: {{ $total }} aktivitas</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-14">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400 font-medium">
                                    {{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $log->waktu->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                    {{ $log->user->nama ?? 'Unknown' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ ucfirst($log->user->role ?? '-') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $log->aktivitas }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log->detail ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400">
                                    <i class="fas fa-clipboard-list text-5xl text-gray-200 mb-4 block"></i>
                                    Belum ada log aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Arrow --}}
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400">
                    @if ($logs->count() > 0)
                        Menampilkan <strong>{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</strong>
                        dari <strong>{{ $total }}</strong> aktivitas
                    @endif
                </p>
                <div class="flex items-center gap-1.5">
                    {{-- Prev --}}
                    @if ($logs->onFirstPage())
                        <button disabled
                            class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 opacity-40 cursor-not-allowed">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </button>
                    @else
                        <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}"
                            class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition">
                            <i class="fas fa-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- Current Page --}}
                    <div
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center">
                        {{ $logs->currentPage() }}
                    </div>

                    {{-- Next --}}
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}"
                            class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    @else
                        <button disabled
                            class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 opacity-40 cursor-not-allowed">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    @endif  
                </div>
            </div>
        </div>
    </div>

    <script>
        // Realtime filter 
        document.addEventListener('DOMContentLoaded', function() {
            ['dari_tanggal', 'sampai_tanggal', 'filterRole'].forEach(id => {
                document.getElementById(id)?.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });
        });
    </script>
@endsection
