@extends('owner.layouts.app')

@section('title', 'Lihat Menu')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daftar Menu</h1>
            <p class="text-gray-600 mt-1">Semua menu yang tersedia di sistem</p>
        </div>

        {{-- Filter Cards (Dynamic) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Menu</p>
                <p class="text-4xl font-bold text-teal-700 mt-3" id="totalMenuCard">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Makanan</p>
                <p class="text-4xl font-bold text-orange-600 mt-3" id="totalMakananCard">0</p>
            </div>
            <div class="bg-white rounded-xl shadow border border-gray-200 p-6 text-center hover:shadow-lg transition">
                <p class="text-sm font-medium text-gray-600">Total Minuman</p>
                <p class="text-4xl font-bold text-blue-600 mt-3" id="totalMinumanCard">0</p>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Menu</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama menu..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select id="filterKategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="">-- Semua Kategori --</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis</label>
                        <select id="filterJenis"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm bg-gray-100 cursor-not-allowed"
                            disabled>
                            <option value="">pilih kategori dulu</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button onclick="terapkanFilter()"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">
                            Cari
                        </button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-sm">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-utensils text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Menu</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalMenuLabel">Total: 0 Menu</p>
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
                                Nama Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Gambar</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-utensils text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data menu
            </div>

            <div id="paginationWrapper"
                class="px-6 py-3.5 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-400" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5" id="paginationButtons"></div>
            </div>
        </div>
    </div>

    <script>
        let semuaMenu = @json($menus);
        let menuTerfilter = [...semuaMenu];

        const PER_PAGE = 5;
        let halamanAktif = 1;

        const formatRupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);

        function escapeHtml(s) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(s || ''));
            return div.innerHTML;
        }

        // Update Card Stats
        function updateCards() {
            const total = semuaMenu.length;
            const makanan = semuaMenu.filter(m => m.kategori?.nama_kategori === 'Makanan').length;
            const minuman = semuaMenu.filter(m => m.kategori?.nama_kategori === 'Minuman').length;

            document.getElementById('totalMenuCard').textContent = total;
            document.getElementById('totalMakananCard').textContent = makanan;
            document.getElementById('totalMinumanCard').textContent = minuman;
        }

        // Filter Functions (sama seperti sebelumnya)
        function resetDropdownJenis() {
            const select = document.getElementById('filterJenis');
            select.innerHTML = '<option value="">pilih kategori dulu</option>';
            select.disabled = true;
            select.classList.add('bg-gray-100', 'cursor-not-allowed');
        }

        function filterKategoriChange() {
            const kategori = document.getElementById('filterKategori').value;
            const selectJenis = document.getElementById('filterJenis');

            if (!kategori) {
                resetDropdownJenis();
                terapkanFilter();
                return;
            }

            selectJenis.disabled = false;
            selectJenis.classList.remove('bg-gray-100', 'cursor-not-allowed');

            const daftarJenis = [...new Set(
                semuaMenu.filter(m => m.kategori?.nama_kategori === kategori)
                .map(m => m.kategori.jenis)
            )];

            selectJenis.innerHTML = '<option value="">-- Semua Jenis --</option>' +
                daftarJenis.map(j => `<option value="${j}">${j}</option>`).join('');

            terapkanFilter();
        }

        function terapkanFilter() {
            const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
            const induk = document.getElementById('filterKategori').value;
            const jenis = document.getElementById('filterJenis').value;

            menuTerfilter = semuaMenu.filter(m => {
                const cocokKeyword = !keyword || m.nama_makanan.toLowerCase().includes(keyword);
                const cocokInduk = !induk || (m.kategori?.nama_kategori === induk);
                const cocokJenis = !jenis || (m.kategori?.jenis === jenis);
                return cocokKeyword && cocokInduk && cocokJenis;
            });

            halamanAktif = 1;
            renderHalaman();
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            resetDropdownJenis();
            menuTerfilter = [...semuaMenu];
            halamanAktif = 1;
            renderHalaman();
        }

        function renderHalaman() {
            // ... (kode render tabel & pagination sama seperti sebelumnya)
            const tbody = document.getElementById('menuTableBody');
            const empty = document.getElementById('emptyState');
            const wrapperPag = document.getElementById('paginationWrapper');
            const total = menuTerfilter.length;

            document.getElementById('totalMenuLabel').textContent = `Total: ${semuaMenu.length} Menu`;

            if (!total) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                wrapperPag.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');
            wrapperPag.classList.remove('hidden');

            const totalHalaman = Math.ceil(total / PER_PAGE);
            const mulai = (halamanAktif - 1) * PER_PAGE;
            const dataHalaman = menuTerfilter.slice(mulai, mulai + PER_PAGE);

            tbody.innerHTML = dataHalaman.map((m, i) => {
                const nomor = mulai + i + 1;
                const induk = m.kategori?.nama_kategori || '';
                const jenis = m.kategori?.jenis || '-';
                const warnaBadge = induk === 'Makanan' ? 'bg-orange-100 text-orange-700' :
                    'bg-blue-100 text-blue-700';

                const gambar = m.gambar ?
                    `<img src="/storage/${m.gambar}" class="w-12 h-12 object-cover rounded-lg shadow-sm" onerror="this.src='https://placehold.co/48x48/e2e8f0/94a3b8?text=No+Img'">` :
                    `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><i class="fas fa-image text-lg"></i></div>`;

                return `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400 font-medium">${nomor}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-teal-600">${escapeHtml(m.nama_makanan)}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full ${warnaBadge}">${escapeHtml(jenis)}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-700">${formatRupiah(m.harga)}</td>
                        <td class="px-6 py-4 text-sm ${m.stok <= 5 ? 'text-red-600 font-bold' : m.stok <= 15 ? 'text-amber-600 font-semibold' : 'text-gray-700'}">
                            ${m.stok} pcs
                        </td>
                        <td class="px-6 py-4">${gambar}</td>
                    </tr>
                `;
            }).join('');

            renderPagination(total, totalHalaman);
        }

        // Pagination functions (sama seperti sebelumnya)
        function renderPagination(total, totalHalaman) {
            const info = document.getElementById('paginationInfo');
            const btns = document.getElementById('paginationButtons');
            const mulai = (halamanAktif - 1) * PER_PAGE + 1;
            const sampai = Math.min(halamanAktif * PER_PAGE, total);

            info.innerHTML = `Menampilkan <strong>${mulai}-${sampai}</strong> dari <strong>${total}</strong> menu`;

            if (totalHalaman <= 1) {
                btns.innerHTML = '';
                return;
            }

            const base =
                'min-w-[30px] h-[30px] px-2 rounded-lg border text-xs font-semibold transition flex items-center justify-center cursor-pointer';
            const active = `${base} bg-teal-600 border-teal-600 text-white`;
            const normal = `${base} bg-white border-gray-200 text-gray-700 hover:border-teal-400 hover:text-teal-600`;

            let html = halamanAktif > 1 ?
                `<button onclick="keHalaman(${halamanAktif-1})" class="${normal}"><i class="fas fa-chevron-left"></i></button>` :
                `<button class="${base} bg-gray-50 text-gray-300 cursor-not-allowed" disabled><i class="fas fa-chevron-left"></i></button>`;

            for (let i = 1; i <= totalHalaman; i++) {
                if (Math.abs(i - halamanAktif) <= 1 || i === 1 || i === totalHalaman) {
                    html +=
                        `<button onclick="keHalaman(${i})" class="${i === halamanAktif ? active : normal}">${i}</button>`;
                } else if (Math.abs(i - halamanAktif) === 2) {
                    html += `<span class="px-2 text-gray-400">…</span>`;
                }
            }

            html += halamanAktif < totalHalaman ?
                `<button onclick="keHalaman(${halamanAktif+1})" class="${normal}"><i class="fas fa-chevron-right"></i></button>` :
                `<button class="${base} bg-gray-50 text-gray-300 cursor-not-allowed" disabled><i class="fas fa-chevron-right"></i></button>`;

            btns.innerHTML = html;
        }

        function keHalaman(h) {
            halamanAktif = h;
            renderHalaman();
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('filterSearch').addEventListener('input', terapkanFilter);
            document.getElementById('filterKategori').addEventListener('change', filterKategoriChange);

            updateCards();
            renderHalaman();
        });

        window.terapkanFilter = terapkanFilter;
        window.resetFilter = resetFilter;
        window.keHalaman = keHalaman;
    </script>
@endsection
