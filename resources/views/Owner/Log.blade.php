@extends('owner.layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-gray-600 mt-1">Riwayat aktivitas semua user</p>
        </div>

        <!-- Filter Box -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <form method="GET" action="{{ route('owner.log') }}" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari User / Aksi</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama user atau aksi..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Role User</label>
                        <select name="role"
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
                        <a href="{{ route('owner.log') }}?{{ http_build_query(array_merge(request()->all(), ['export' => 'csv'])) }}"
                            class="flex-1 px-5 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm text-center text-sm">
                            Export
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Log -->
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Daftar Log Aktivitas</h2>
                <span class="text-sm text-gray-500">{{ count($logs) }} entri ditemukan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $index => $log)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                                    {{ $log['waktu']->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $log['user'] }}</div>
                                    @if (!empty($log['role']))
                                        <div class="mt-0.5">
                                            <span
                                                class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full
                                                {{ $log['role'] == 'admin' ? 'bg-purple-100 text-purple-700' : ($log['role'] == 'owner' ? 'bg-amber-100 text-amber-700' : 'bg-teal-100 text-teal-700') }}">
                                                {{ ucfirst($log['role']) }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $log['aksi'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $log['detail'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-history text-5xl text-gray-300 mb-4 block"></i>
                                    Belum ada log aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
