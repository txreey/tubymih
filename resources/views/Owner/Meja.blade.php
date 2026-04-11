@extends('owner.layouts.app')

@section('title', 'Lihat Meja')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Meja</h1>
            <p class="text-gray-600 mt-1">Semua meja yang tersedia di restoran</p>
        </div>

        {{-- Summary Cards — dihitung dinamis dari JS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Meja</p>
                <p class="text-4xl font-bold text-teal-700 mt-3" id="summaryTotal">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Meja Tersedia</p>
                <p class="text-4xl font-bold text-green-600 mt-3" id="summaryTersedia">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Meja Terisi / Reserved</p>
                <p class="text-4xl font-bold text-red-600 mt-3" id="summaryTerisi">0</p>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari No Meja</label>
                        <input type="text" id="filterSearch" placeholder="Ketik no meja..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tipe Meja</label>
                        <select id="filterTipe"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="Lesehan">Lesehan</option>
                            <option value="Meja Kursi">Meja Kursi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <select id="filterStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Status</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button onclick="applyFilter()"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Cari</button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-sm">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chair text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Meja</p>
                        <p class="text-xs text-gray-400 mt-1" id="mejaCountLabel">Memuat data...</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-12">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">No
                                Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Tipe Meja</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kapasitas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody id="mejaTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-chair text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data meja
            </div>

            {{-- Pagination Arrow --}}
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>

                <div class="flex items-center gap-1.5">
                    <!-- Tombol Previous -->
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>

                    <!-- Kotak Angka Halaman Saat Ini -->
                    <div id="currentPageBox"
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center">
                        1
                    </div>

                    <!-- Tombol Next -->
                    <button onclick="nextPage()" id="btnNext"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <style>
        .s-tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .s-terisi {
            background: #fee2e2;
            color: #b91c1c;
        }

        .s-reserved {
            background: #fef3c7;
            color: #92400e;
        }
    </style>

    <script>
        // ─── STATE ───────────────────────────────────────────────────────
        let allMeja = @json($mejas);
        let filteredMeja = [...allMeja];
        const PER_PAGE = 5;
        let currentPage = 1;

        // ─── SUMMARY ─────────────────────────────────────────────────────
        function updateSummary() {
            document.getElementById('summaryTotal').textContent = allMeja.length;
            document.getElementById('summaryTersedia').textContent = allMeja.filter(m => m.status === 'tersedia').length;
            document.getElementById('summaryTerisi').textContent = allMeja.filter(m => m.status === 'terisi' || m.status ===
                'reserved').length;
        }

        // ─── UTILS ───────────────────────────────────────────────────────
        function escHtml(str) {
            const d = document.createElement('div');
            d.textContent = str || '';
            return d.innerHTML;
        }

        function capitalize(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
        }

        function susunDataGrouped(data) {
            const lesehan = data.filter(m => m.tipe_meja === 'Lesehan').sort((a, b) => a.no_meja.localeCompare(b.no_meja));
            const kursi = data.filter(m => m.tipe_meja === 'Meja Kursi').sort((a, b) => a.no_meja.localeCompare(b.no_meja));
            return [...lesehan, ...kursi];
        }

        // ─── RENDER TABLE ────────────────────────────────────────────────
        function renderTable() {
            const tbody = document.getElementById('mejaTableBody');
            const empty = document.getElementById('emptyState');
            const pgInfo = document.getElementById('paginationInfo');
            const currentBox = document.getElementById('currentPageBox');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');

            if (!filteredMeja.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                pgInfo.textContent = '';
                currentBox.textContent = '1';
                btnPrev.disabled = true;
                btnNext.disabled = true;
                document.getElementById('mejaCountLabel').textContent = 'Total: 0 meja';
                return;
            }

            empty.classList.add('hidden');

            const sorted = susunDataGrouped(filteredMeja);
            const total = sorted.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            const start = (currentPage - 1) * PER_PAGE;
            const end = Math.min(start + PER_PAGE, total);
            const pageData = sorted.slice(start, end);

            tbody.innerHTML = pageData.map((meja, i) => {
                const sBadge = meja.status === 'tersedia' ? 's-tersedia' :
                    meja.status === 'terisi' ? 's-terisi' :
                    's-reserved';
                return `
                <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-400 font-medium">${start + i + 1}</td>
                    <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escHtml(meja.no_meja)}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${escHtml(meja.tipe_meja || '-')}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">${meja.kapasitas} orang</td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">${escHtml(meja.deskripsi || '-')}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${sBadge}">
                            ${capitalize(meja.status)}
                        </span>
                    </td>
                </tr>`;
            }).join('');

            pgInfo.innerHTML = `Menampilkan <strong>${start + 1}–${end}</strong> dari <strong>${total}</strong> meja`;
            currentBox.textContent = currentPage;
            document.getElementById('mejaCountLabel').textContent = `Total: ${allMeja.length} meja`;

            // Enable / Disable tombol panah
            btnPrev.disabled = (currentPage === 1);
            btnNext.disabled = (currentPage === totalPages);
        }

        // ─── NAVIGASI PANAH ──────────────────────────────────────────────
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        }

        function nextPage() {
            const totalPages = Math.ceil(filteredMeja.length / PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        }

        // ─── FILTER ──────────────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const tipe = document.getElementById('filterTipe').value;
            const status = document.getElementById('filterStatus').value;
            filteredMeja = allMeja.filter(m => {
                const matchSearch = !search || (m.no_meja || '').toLowerCase().includes(search) || (m.deskripsi ||
                    '').toLowerCase().includes(search);
                return matchSearch && (!tipe || m.tipe_meja === tipe) && (!status || m.status === status);
            });
            currentPage = 1;
            renderTable();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterTipe').value = '';
            document.getElementById('filterStatus').value = '';
            filteredMeja = [...allMeja];
            currentPage = 1;
            renderTable();
        }

        // ─── INIT ────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterTipe').addEventListener('change', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            updateSummary();
            renderTable();
        });

        window.prevPage = prevPage;
        window.nextPage = nextPage;
    </script>
@endsection
