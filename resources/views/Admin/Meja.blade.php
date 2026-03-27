@extends('admin.layouts.app')

@section('title', 'Kelola Meja')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Meja</h1>
            <p class="text-gray-600 mt-1">Kelola nomor meja, tipe, kapasitas dan status ketersediaan</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari No Meja</label>
                        <input type="text" id="filterSearch" placeholder="Ketik no meja..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
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
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition text-sm">Cari</button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-sm">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Daftar Meja</h2>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 transition text-sm">
                    <i class="fas fa-plus text-sm"></i> Tambah Meja
                </button>
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
                                Tipe</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kapasitas</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="mejaTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-500">
                <i class="fas fa-chair text-5xl text-gray-300 mb-4 block"></i>
                Belum ada data meja
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-plus-circle"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Meja Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data meja baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-hashtag text-teal-600 text-sm"></i> No Meja <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="createNoMeja" placeholder="Contoh: A01"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNoMeja"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-utensils text-teal-600 text-sm"></i> Tipe Meja</label>
                        <input type="text" id="createTipeMeja" placeholder="Contoh: Lesehan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-users text-teal-600 text-sm"></i> Kapasitas <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="createKapasitas" placeholder="Contoh: 4"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateKapasitas"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span></label>
                        <select id="createStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                            <option value="reserved">Reserved</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-align-left text-teal-600 text-sm"></i> Deskripsi</label>
                    <textarea id="createDeskripsi" rows="2" placeholder="Contoh: Meja VIP dekat jendela"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-3 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                    <button onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="editContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-edit"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Edit Meja</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Ubah data meja</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-4">
                <input type="hidden" id="editMejaId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-hashtag text-teal-600 text-sm"></i> No Meja <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editNoMeja"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditNoMeja"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-utensils text-teal-600 text-sm"></i> Tipe Meja</label>
                        <input type="text" id="editTipeMeja"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-users text-teal-600 text-sm"></i> Kapasitas <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="editKapasitas"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errEditKapasitas"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-toggle-on text-teal-600 text-sm"></i> Status <span
                                class="text-red-500">*</span></label>
                        <select id="editStatus"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="tersedia">Tersedia</option>
                            <option value="terisi">Terisi</option>
                            <option value="reserved">Reserved</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-align-left text-teal-600 text-sm"></i> Deskripsi</label>
                    <textarea id="editDeskripsi" rows="2"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm"></textarea>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-3 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="closeModal('editModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                    <button onclick="submitEdit()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Update
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let allMeja = @json($mejas);

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            store: '{{ route('admin.meja.store') }}',
            update: (id) => `{{ url('admin/meja') }}/${id}`,
            destroy: (id) => `{{ url('admin/meja') }}/${id}`,
        };

        // ── RENDER ──────────────────────────────────────
        function renderTable(data) {
            const tbody = document.getElementById('mejaTableBody');
            const empty = document.getElementById('emptyState');

            if (!data.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            tbody.innerHTML = data.map((meja, idx) => {
                const id = meja.id;
                const statusBadge = meja.status === 'tersedia' ? 'bg-green-100 text-green-800' :
                    meja.status === 'terisi' ? 'bg-red-100 text-red-800' :
                    'bg-yellow-100 text-yellow-800';
                const deskripsi = meja.deskripsi ?
                    `<span class="text-gray-700">${escHtml(meja.deskripsi)}</span>` :
                    `<span class="text-gray-400 italic">-</span>`;

                return `
        <tr class="hover:bg-gray-50 transition duration-150" id="row-${id}">
            <td class="px-6 py-4 text-sm text-gray-600">${idx + 1}</td>
            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${escHtml(meja.no_meja)}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${escHtml(meja.tipe_meja || '-')}</td>
            <td class="px-6 py-4 text-sm text-gray-600">${meja.kapasitas} orang</td>
            <td class="px-6 py-4 text-sm max-w-xs truncate">${deskripsi}</td>
            <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-0.5 text-xs font-medium rounded-full ${statusBadge}">
                    ${capitalize(meja.status)}
                </span>
            </td>
            <td class="px-6 py-4 text-sm font-medium flex items-center gap-5">
                <button onclick="window.doEdit(${id})" title="Edit Meja"
                    class="text-indigo-600 hover:text-indigo-800 transition text-xl">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <button onclick="window.doDelete(${id})" title="Hapus Meja"
                    class="text-red-600 hover:text-red-800 transition text-xl">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>`;
            }).join('');
        }

        // ── FILTER ──────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const tipe = document.getElementById('filterTipe').value.toLowerCase().trim();
            const status = document.getElementById('filterStatus').value.toLowerCase().trim();

            const filtered = allMeja.filter(m => {
                const noMeja = (m.no_meja || '').toLowerCase();
                const tipeMeja = (m.tipe_meja || '').toLowerCase();
                const deskrip = (m.deskripsi || '').toLowerCase();
                const statusVal = (m.status || '').toLowerCase();

                const matchSearch = !search || noMeja.includes(search) || deskrip.includes(search);
                const matchTipe = !tipe || tipeMeja.includes(tipe);
                const matchStatus = !status || statusVal === status;

                return matchSearch && matchTipe && matchStatus;
            });
            renderTable(filtered);
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterTipe').value = '';
            document.getElementById('filterStatus').value = '';
            renderTable(allMeja);
        }

        document.getElementById('filterSearch').addEventListener('input', applyFilter);
        document.getElementById('filterTipe').addEventListener('change', applyFilter);
        document.getElementById('filterStatus').addEventListener('change', applyFilter);

        // ── MODAL ───────────────────────────────────────
        function openCreateModal() {
            ['createNoMeja', 'createTipeMeja', 'createKapasitas', 'createDeskripsi'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('createStatus').value = 'tersedia';
            clearErrors('create');
            showModal('createModal');
        }

        function openEditModal(id) {
            const meja = allMeja.find(m => Number(m.id) === Number(id));
            if (!meja) return;

            document.getElementById('editMejaId').value = meja.id;
            document.getElementById('editNoMeja').value = meja.no_meja || '';
            document.getElementById('editTipeMeja').value = meja.tipe_meja || '';
            document.getElementById('editKapasitas').value = meja.kapasitas || '';
            document.getElementById('editStatus').value = meja.status || 'tersedia';
            document.getElementById('editDeskripsi').value = meja.deskripsi || '';
            clearErrors('edit');
            showModal('editModal');
        }

        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
            setTimeout(() => {
                const c = document.getElementById(id.replace('Modal', 'Content'));
                c.classList.remove('scale-95', 'opacity-0');
                c.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal(id) {
            const c = document.getElementById(id.replace('Modal', 'Content'));
            c.classList.remove('scale-100', 'opacity-100');
            c.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        // ── VALIDASI ─────────────────────────────────────
        function validate(prefix) {
            let ok = true;
            clearErrors(prefix);

            const noMeja = document.getElementById(`${prefix}NoMeja`).value.trim();
            const kapasitas = document.getElementById(`${prefix}Kapasitas`).value.trim();

            if (!noMeja) {
                showErr(`err${cap(prefix)}NoMeja`, 'No meja wajib diisi.');
                ok = false;
            } else if (noMeja.length > 20) {
                showErr(`err${cap(prefix)}NoMeja`, 'No meja maks 20 karakter.');
                ok = false;
            }

            if (!kapasitas) {
                showErr(`err${cap(prefix)}Kapasitas`, 'Kapasitas wajib diisi.');
                ok = false;
            } else if (!/^\d+$/.test(kapasitas)) {
                showErr(`err${cap(prefix)}Kapasitas`, 'Kapasitas harus angka.');
                ok = false;
            } else if (parseInt(kapasitas) < 1) {
                showErr(`err${cap(prefix)}Kapasitas`, 'Kapasitas minimal 1.');
                ok = false;
            }

            return ok;
        }

        // ── SUBMIT CREATE ────────────────────────────────
        async function submitCreate() {
            if (!validate('create')) return;

            const payload = {
                no_meja: document.getElementById('createNoMeja').value.trim(),
                tipe_meja: document.getElementById('createTipeMeja').value.trim(),
                kapasitas: document.getElementById('createKapasitas').value.trim(),
                status: document.getElementById('createStatus').value,
                deskripsi: document.getElementById('createDeskripsi').value.trim(),
            };

            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            setLoading(btn, true);

            try {
                const res = await fetch(ROUTES.store, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    if (res.status === 422 && data.errors) handleLaravelErrors(data.errors, 'create');
                    else Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }

                allMeja.push(data.data);
                renderTable(allMeja);
                closeModal('createModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Meja berhasil ditambahkan.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });

            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ── SUBMIT EDIT ──────────────────────────────────
        async function submitEdit() {
            if (!validate('edit')) return;

            const id = document.getElementById('editMejaId').value;
            const payload = {
                no_meja: document.getElementById('editNoMeja').value.trim(),
                tipe_meja: document.getElementById('editTipeMeja').value.trim(),
                kapasitas: document.getElementById('editKapasitas').value.trim(),
                status: document.getElementById('editStatus').value,
                deskripsi: document.getElementById('editDeskripsi').value.trim(),
                _method: 'PUT',
            };

            const btn = document.querySelector('#editContent button[onclick="submitEdit()"]');
            setLoading(btn, true);

            try {
                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    if (res.status === 422 && data.errors) handleLaravelErrors(data.errors, 'edit');
                    else Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }

                const idx = allMeja.findIndex(m => Number(m.id) === Number(id));
                if (idx !== -1) allMeja[idx] = data.data;
                applyFilter();
                closeModal('editModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Meja berhasil diperbarui.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });

            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            } finally {
                setLoading(btn, false);
            }
        }

        // ── DELETE ────────────────────────────────────────
        async function deleteMeja(id) {
            const result = await Swal.fire({
                title: 'Hapus meja ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;

            try {
                const res = await fetch(ROUTES.destroy(id), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    }),
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                    return;
                }

                allMeja = allMeja.filter(m => Number(m.id) !== Number(id));
                applyFilter();
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Meja berhasil dihapus.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });

            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tidak bisa terhubung ke server.'
                });
            }
        }

        // ── UTILS ─────────────────────────────────────────
        window.doEdit = (id) => openEditModal(id);
        window.doDelete = (id) => deleteMeja(id);

        function capitalize(s) {
            return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
        }

        function cap(s) {
            return s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str || ''));
            return d.innerHTML;
        }

        function showErr(elId, msg) {
            const el = document.getElementById(elId);
            if (!el) return;
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function clearErrors(prefix) {
            document.querySelectorAll(`[id^="err${cap(prefix)}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function handleLaravelErrors(errors, prefix) {
            const map = {
                no_meja: `err${cap(prefix)}NoMeja`,
                kapasitas: `err${cap(prefix)}Kapasitas`
            };
            Object.entries(errors).forEach(([field, msgs]) => {
                if (map[field]) showErr(map[field], msgs[0]);
            });
        }

        function setLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                btn.disabled = true;
                btn.dataset.orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i> Proses...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.orig;
            }
        }

        // ── INIT ──────────────────────────────────────────
        renderTable(allMeja);
    </script>
@endsection
