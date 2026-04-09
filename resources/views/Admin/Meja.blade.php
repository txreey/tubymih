@extends('admin.layouts.app')

@section('title', 'Kelola Meja')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Meja</h1>
            <p class="text-gray-600 mt-1">Kelola nomor meja, tipe, kapasitas dan status ketersediaan</p>
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
                            <option value="reserved">Reserved</option>
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

        {{-- TABEL DAFTAR MEJA --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-chair text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Meja</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalMeja">Total: {{ count($mejas) }} Meja</p>
                    </div>
                </div>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-xl shadow-sm hover:bg-teal-700 transition text-sm">
                    + Tambah Meja
                </button>
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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="mejaTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-chair text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data meja
            </div>

            {{-- Pagination Compact dengan Arrow --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-500" id="paginationInfo"></p>

                <div class="flex items-center gap-1.5">
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>

                    <div id="currentPageBox"
                        class="px-3 py-1 bg-white border border-teal-500 rounded-lg font-semibold text-teal-700 text-sm min-w-[36px] text-center shadow-sm">
                        1
                    </div>

                    <button onclick="nextPage()" id="btnNext"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH MEJA --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">

            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Meja Baru</h2>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-chair text-teal-600 text-sm"></i> Tipe Meja <span class="text-red-500">*</span>
                        </label>
                        <select id="createTipeMeja" onchange="generateNoMeja()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="">Pilih Tipe</option>
                            <option value="Lesehan">Lesehan</option>
                            <option value="Meja Kursi">Meja Kursi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-hashtag text-teal-600 text-sm"></i> No Meja <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="createNoMeja" readonly placeholder="Otomatis terisi"
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 rounded-lg text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNoMeja"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-users text-teal-600 text-sm"></i> Kapasitas <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="number" id="createKapasitas" placeholder="Contoh: 4"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateKapasitas"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status Awal
                        </label>
                        <input type="text" value="Tersedia" readonly
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-align-left text-teal-600 text-sm"></i> Deskripsi <span
                            class="text-red-500">*</span>
                    </label>
                    <textarea id="createDeskripsi" rows="2" placeholder="Contoh: Meja panjang dekat jendela"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>

                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.
                        Pastikan data diisi dengan benar.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT MEJA (Status fix Tersedia) --}}
    <div id="editModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="editContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">

            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Edit Meja</h2>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-6 bg-gray-50 space-y-5">
                <input type="hidden" id="editMejaId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-chair text-teal-600 text-sm"></i> Tipe Meja <span
                                class="text-red-500">*</span>
                        </label>
                        <select id="editTipeMeja" onchange="handleEditTipeChange()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                            <option value="Lesehan">Lesehan</option>
                            <option value="Meja Kursi">Meja Kursi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-hashtag text-teal-600 text-sm"></i> No Meja
                        </label>
                        <input type="text" id="editNoMeja" readonly
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed outline-none text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-users text-teal-600 text-sm"></i> Kapasitas <span
                                class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editKapasitas"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditKapasitas"></p>
                    </div>

                    <!-- Status Fix Tersedia -->
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-toggle-on text-teal-600 text-sm"></i> Status
                        </label>
                        <input type="text" id="editStatus" value="Tersedia" readonly
                            class="w-full px-4 py-2.5 border border-gray-300 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed outline-none text-sm">
                        <p class="text-xs text-gray-500 mt-1">Status otomatis diatur oleh sistem kasir</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-align-left text-teal-600 text-sm"></i> Deskripsi
                    </label>
                    <textarea id="editDeskripsi" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm"></textarea>
                </div>

                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">No Meja akan otomatis menyesuaikan jika tipe diubah. Status meja dikelola oleh
                        kasir saat ada pesanan.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition text-sm flex items-center gap-2">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button onclick="submitEdit()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .ab {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all .15s;
        }

        .ab-edit {
            background: #ede9fe;
            color: #6d28d9;
        }

        .ab-edit:hover {
            background: #6d28d9;
            color: #fff;
        }

        .ab-del {
            background: #fef2f2;
            color: #dc2626;
        }

        .ab-del:hover {
            background: #dc2626;
            color: #fff;
        }

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
        let allMeja = @json($mejas);
        let filteredMeja = [...allMeja];
        const PER_PAGE = 5;
        let currentPage = 1;
        let editOriginalTipe = '';
        let editOriginalNoMeja = '';

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            store: '{{ route('admin.meja.store') }}',
            update: (id) => `{{ url('admin/meja') }}/${id}`,
            destroy: (id) => `{{ url('admin/meja') }}/${id}`,
        };

        function escHtml(str) {
            const div = document.createElement('div');
            div.textContent = str || '';
            return div.innerHTML;
        }

        function capitalize(str) {
            return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
        }

        function generateNoMeja() {
            const tipe = document.getElementById('createTipeMeja').value;
            const noMejaInput = document.getElementById('createNoMeja');
            if (!tipe) {
                noMejaInput.value = '';
                return;
            }
            const prefix = tipe === 'Lesehan' ? 'L' : 'K';
            noMejaInput.value = getNextNoMeja(prefix, null);
        }

        function isNoMejaExist(noMeja) {
            return allMeja.some(m => m.no_meja && m.no_meja.toLowerCase() === noMeja.toLowerCase());
        }

        function getNextNoMeja(prefix, excludeId = null) {
            const usedNumbers = allMeja
                .filter(m => m.no_meja && m.no_meja.startsWith(prefix) &&
                    (excludeId === null || Number(m.id) !== Number(excludeId)))
                .map(m => parseInt(m.no_meja.replace(prefix, '').trim(), 10))
                .filter(n => !isNaN(n))
                .sort((a, b) => a - b);

            if (usedNumbers.length === 0) return prefix + '01';

            for (let i = 1; i <= usedNumbers[usedNumbers.length - 1]; i++) {
                if (!usedNumbers.includes(i)) {
                    return prefix + String(i).padStart(2, '0');
                }
            }
            const maxNum = usedNumbers[usedNumbers.length - 1];
            return prefix + String(maxNum + 1).padStart(2, '0');
        }

        function susunDataGrouped(data) {
            const lesehan = data.filter(m => m.tipe_meja === 'Lesehan').sort((a, b) => a.no_meja.localeCompare(b.no_meja));
            const kursi = data.filter(m => m.tipe_meja === 'Meja Kursi').sort((a, b) => a.no_meja.localeCompare(b.no_meja));
            return [...lesehan, ...kursi];
        }

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
                if (btnPrev) btnPrev.disabled = true;
                if (btnNext) btnNext.disabled = true;
                return;
            }

            empty.classList.add('hidden');

            const sortedData = susunDataGrouped(filteredMeja);
            const total = sortedData.length;
            const totalPages = Math.ceil(total / PER_PAGE);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            const start = (currentPage - 1) * PER_PAGE;
            const end = Math.min(start + PER_PAGE, total);
            const pageData = sortedData.slice(start, end);

            let html = '';
            pageData.forEach((meja, i) => {
                const sBadge = meja.status === 'tersedia' ? 's-tersedia' :
                    meja.status === 'terisi' ? 's-terisi' : 's-reserved';

                const canEditOrDelete = meja.status === 'tersedia';

                html += `
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
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            ${canEditOrDelete ? `
                                    <button class="ab ab-edit" onclick="window.doEdit(${meja.id})" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="ab ab-del" onclick="window.doDelete(${meja.id})" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                ` : `
                                    <span class="text-xs text-gray-400">Tidak dapat diubah</span>
                                `}
                        </div>
                    </td>
                </tr>`;
            });

            tbody.innerHTML = html;
            pgInfo.innerHTML = `Menampilkan <strong>${start + 1}-${end}</strong> dari <strong>${total}</strong> meja`;
            document.getElementById('totalMeja').textContent = `Total: ${total} Meja`;

            currentBox.textContent = currentPage;
            if (btnPrev) btnPrev.disabled = (currentPage === 1);
            if (btnNext) btnNext.disabled = (currentPage === totalPages);
        }

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

        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const tipe = document.getElementById('filterTipe').value;
            const status = document.getElementById('filterStatus').value;

            filteredMeja = allMeja.filter(m => {
                const matchSearch = !search ||
                    (m.no_meja || '').toLowerCase().includes(search) ||
                    (m.deskripsi || '').toLowerCase().includes(search);
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

        function openCreateModal() {
            document.getElementById('createNoMeja').value = '';
            document.getElementById('createTipeMeja').value = '';
            document.getElementById('createKapasitas').value = '';
            document.getElementById('createDeskripsi').value = '';
            document.getElementById('errCreateNoMeja').classList.add('hidden');
            document.getElementById('errCreateKapasitas').classList.add('hidden');
            showModal('createModal');
        }

        function showModal(modalId) {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(modalId.replace('Modal', 'Content'));
            if (!modal || !content) return;
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(modalId) {
            const content = document.getElementById(modalId.replace('Modal', 'Content'));
            if (!content) return;
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                const modal = document.getElementById(modalId);
                if (modal) modal.classList.add('hidden');
            }, 300);
        }

        async function submitCreate() {
            // ... (kode submitCreate tetap sama seperti sebelumnya)
            const noMeja = document.getElementById('createNoMeja').value.trim();
            const tipeMeja = document.getElementById('createTipeMeja').value;
            const kapasitas = document.getElementById('createKapasitas').value.trim();
            const deskripsi = document.getElementById('createDeskripsi').value.trim();

            let valid = true;

            if (!tipeMeja) {
                Swal.fire('Peringatan', 'Tipe Meja wajib dipilih!', 'warning');
                valid = false;
            }
            if (!noMeja) {
                const el = document.getElementById('errCreateNoMeja');
                el.textContent = 'No Meja wajib diisi';
                el.classList.remove('hidden');
                valid = false;
            } else if (isNoMejaExist(noMeja)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplikasi',
                    text: `Nomor "${noMeja}" sudah terdaftar.`
                });
                valid = false;
            }
            if (!kapasitas || parseInt(kapasitas) <= 0) {
                const el = document.getElementById('errCreateKapasitas');
                el.textContent = 'Kapasitas harus angka > 0';
                el.classList.remove('hidden');
                valid = false;
            }
            if (!deskripsi) {
                Swal.fire('Peringatan', 'Deskripsi wajib diisi!', 'warning');
                valid = false;
            }
            if (!valid) return;

            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            try {
                const res = await fetch(ROUTES.store, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        no_meja: noMeja,
                        tipe_meja: tipeMeja,
                        kapasitas: kapasitas,
                        deskripsi: deskripsi,
                        _token: CSRF
                    })
                });
                const data = await res.json();

                if (data.success) {
                    allMeja.push(data.data);
                    filteredMeja = [...allMeja];
                    renderTable();
                    closeModal('createModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Meja berhasil ditambahkan.',
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                }
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal terhubung ke server.'
                });
            } finally {
                btn.innerHTML = orig;
                btn.disabled = false;
            }
        }

        function openEditModal(id) {
            const meja = allMeja.find(m => Number(m.id) === Number(id));
            if (!meja) return;

            // Cegah edit jika status bukan tersedia
            if (meja.status !== 'tersedia') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Diedit',
                    text: 'Meja hanya dapat diedit jika statusnya "Tersedia".'
                });
                return;
            }

            editOriginalTipe = meja.tipe_meja || '';
            editOriginalNoMeja = meja.no_meja || '';

            document.getElementById('editMejaId').value = meja.id;
            document.getElementById('editNoMeja').value = editOriginalNoMeja;
            document.getElementById('editTipeMeja').value = editOriginalTipe;
            document.getElementById('editKapasitas').value = meja.kapasitas || '';
            document.getElementById('editDeskripsi').value = meja.deskripsi || '';
            document.getElementById('editStatus').value = 'Tersedia';

            showModal('editModal');
        }

        function handleEditTipeChange() {
            const selectedTipe = document.getElementById('editTipeMeja').value;
            const noMejaInput = document.getElementById('editNoMeja');
            const currentId = document.getElementById('editMejaId').value;

            if (!selectedTipe || selectedTipe === editOriginalTipe) {
                noMejaInput.value = editOriginalNoMeja;
                return;
            }

            const prefix = selectedTipe === 'Lesehan' ? 'L' : 'K';
            noMejaInput.value = getNextNoMeja(prefix, currentId);
        }

        async function submitEdit() {
            const id = document.getElementById('editMejaId').value;
            const payload = {
                no_meja: document.getElementById('editNoMeja').value.trim(),
                tipe_meja: document.getElementById('editTipeMeja').value,
                kapasitas: document.getElementById('editKapasitas').value.trim(),
                deskripsi: document.getElementById('editDeskripsi').value.trim(),
                status: 'tersedia',
                _token: CSRF,
                _method: 'PUT'
            };

            try {
                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();

                if (data.success) {
                    const idx = allMeja.findIndex(m => Number(m.id) === Number(id));
                    if (idx !== -1) allMeja[idx] = data.data;
                    filteredMeja = [...allMeja];
                    renderTable();
                    closeModal('editModal');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Meja berhasil diperbarui.',
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memperbarui meja.'
                });
            }
        }

        async function deleteMeja(id) {
            const meja = allMeja.find(m => Number(m.id) === Number(id));
            if (meja && meja.status !== 'tersedia') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Dihapus',
                    text: 'Meja hanya dapat dihapus jika statusnya "Tersedia".'
                });
                return;
            }

            const result = await Swal.fire({
                title: 'Hapus meja ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            try {
                const res = await fetch(ROUTES.destroy(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        _method: 'DELETE',
                        _token: CSRF
                    })
                });
                const data = await res.json();

                if (data.success) {
                    allMeja = allMeja.filter(m => Number(m.id) !== Number(id));
                    filteredMeja = [...allMeja];
                    renderTable();
                    Swal.fire({
                        icon: 'success',
                        title: 'Dihapus!',
                        text: 'Meja berhasil dihapus.',
                        timer: 2000,
                        toast: true,
                        position: 'top-end'
                    });
                }
            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal menghapus meja.'
                });
            }
        }

        // Global Functions
        window.doEdit = (id) => openEditModal(id);
        window.doDelete = (id) => deleteMeja(id);
        window.prevPage = prevPage;
        window.nextPage = nextPage;

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', applyFilter);
            document.getElementById('filterTipe').addEventListener('change', applyFilter);
            document.getElementById('filterStatus').addEventListener('change', applyFilter);
            renderTable();
        });
    </script>
@endsection
