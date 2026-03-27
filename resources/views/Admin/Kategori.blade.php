@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kategori</h1>
            <p class="text-gray-600 mt-1">Kelola kategori dan jenis untuk menu makanan & minuman</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Kategori</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama kategori..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select id="filterKategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis</label>
                        <select id="filterJenis"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Jenis</option>
                            {{-- diisi dinamis lewat JS saat filterKategori berubah --}}
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
                <h2 class="text-lg font-bold text-gray-900">Daftar Kategori</h2>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 transition text-sm">
                    <i class="fas fa-plus text-sm"></i> Tambah Kategori
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-12">
                                No</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Jumlah Menu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kategoriTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-500">
                <i class="fas fa-tags text-5xl text-gray-300 mb-4 block"></i>
                Belum ada data kategori
            </div>
        </div>
    </div>

    {{-- ── MODAL DETAIL ────────────────────────────────────────── --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="detailContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-tags"></i></div>
                    <h2 class="text-xl font-bold">Detail Kategori</h2>
                </div>
                <button onclick="closeModal('detailModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-4">
                {{-- Info row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Kategori</p>
                        <p class="text-base font-bold text-gray-900" id="detailNamaKategori">-</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Jenis</p>
                        <p class="text-base font-bold text-gray-900" id="detailJenis">-</p>
                    </div>
                </div>
                {{-- Menu list --}}
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Menu yang menggunakan jenis ini:</p>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 max-h-48 overflow-y-auto">
                        <ul id="detailMenuList" class="space-y-1 text-sm text-gray-700 list-disc pl-4"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-5 flex justify-end">
                <button onclick="closeModal('detailModal')"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL TAMBAH ────────────────────────────────────────── --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-plus-circle"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Kategori</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data kategori baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-tag text-teal-600 text-sm"></i> Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="createNamaKategori" onchange="updateJenisOptions('create')"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                    <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNamaKategori"></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-list text-teal-600 text-sm"></i> Jenis <span class="text-red-500">*</span>
                    </label>
                    <select id="createJenis"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                    <p class="text-red-500 text-xs mt-1 hidden" id="errCreateJenis"></p>
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

    {{-- ── MODAL EDIT ────────────────────────────────────────── --}}
    <div id="editModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="editContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-edit"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Edit Kategori</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Ubah data kategori</p>
                    </div>
                </div>
                <button onclick="closeModal('editModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 bg-gray-50 space-y-4">
                <input type="hidden" id="editKategoriId">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-tag text-teal-600 text-sm"></i> Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="editNamaKategori" onchange="updateJenisOptions('edit')"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                    </select>
                    <p class="text-red-500 text-xs mt-1 hidden" id="errEditNamaKategori"></p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">
                        <i class="fas fa-list text-teal-600 text-sm"></i> Jenis <span class="text-red-500">*</span>
                    </label>
                    <select id="editJenis"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <option value="">-- Pilih Kategori dulu --</option>
                    </select>
                    <p class="text-red-500 text-xs mt-1 hidden" id="errEditJenis"></p>
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
        // ── DATA ─────────────────────────────────────────────────────────
        let allKategori = @json($kategoris);

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            store: '{{ route('admin.kategori.store') }}',
            update: (id) => `{{ url('admin/kategori') }}/${id}`,
            destroy: (id) => `{{ url('admin/kategori') }}/${id}`,
        };

        // Mapping jenis per kategori
        const JENIS_MAP = {
            Makanan: ['Makanan Berat', 'Makanan Ringan', 'Makanan Manis', 'Makanan Gurih', 'Dessert'],
            Minuman: ['Minuman Dingin', 'Minuman Panas', 'Jus Buah', 'Kopi', 'Teh', 'Minuman Herbal'],
        };

        // ── RENDER ───────────────────────────────────────────────────────
        function renderTable(data) {
            const tbody = document.getElementById('kategoriTableBody');
            const empty = document.getElementById('emptyState');

            if (!data.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            // Group by nama_kategori, urutan: Makanan dulu, Minuman belakang
            const groups = {};
            data.forEach(k => {
                const key = k.nama_kategori || 'Lainnya';
                if (!groups[key]) groups[key] = [];
                groups[key].push(k);
            });

            // Urutkan grup: Makanan → Minuman → lainnya alphabetical
            const order = ['Makanan', 'Minuman'];
            const sortedKeys = [
                ...order.filter(k => groups[k]),
                ...Object.keys(groups).filter(k => !order.includes(k)).sort(),
            ];

            let nomor = 1;
            let rows = '';

            sortedKeys.forEach(grupNama => {
                const items = groups[grupNama];
                const rowspan = items.length;

                items.forEach((k, idx) => {
                    const id = k.id;
                    const jumlah = k.menus_count ?? k.jumlah ?? 0;

                    const grupCell = idx === 0 ? `
                <td rowspan="${rowspan}"
                    class="px-6 py-4 text-sm font-bold text-gray-900 border-r border-gray-100 text-center align-middle">
                    ${nomor}
                </td>
                <td rowspan="${rowspan}"
                    class="px-6 py-4 text-sm font-bold text-gray-900 border-r border-gray-100 align-middle">
                    ${escHtml(grupNama)}
                </td>` : '';

                    rows += `
            <tr class="hover:bg-gray-50 transition duration-150" id="row-${id}">
                ${grupCell}
                <td class="px-6 py-4">
                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                        ${escHtml(k.jenis || '-')}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 font-medium">${jumlah} menu</td>
                <td class="px-6 py-4 text-sm font-medium flex items-center gap-5">
                    <button onclick="window.doDetail(${id})" title="Detail"
                        class="text-teal-600 hover:text-teal-800 transition text-xl">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button onclick="window.doEdit(${id})" title="Edit"
                        class="text-indigo-600 hover:text-indigo-800 transition text-xl">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <button onclick="window.doDelete(${id})" title="Hapus"
                        class="text-red-600 hover:text-red-800 transition text-xl">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>`;
                });

                nomor++;
            });

            tbody.innerHTML = rows;
        }

        // ── FILTER ───────────────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const kategori = document.getElementById('filterKategori').value.toLowerCase().trim();
            const jenis = document.getElementById('filterJenis').value.toLowerCase().trim();

            const filtered = allKategori.filter(k => {
                const matchSearch = !search || k.nama_kategori.toLowerCase().includes(search) || (k.jenis || '')
                    .toLowerCase().includes(search);
                const matchKategori = !kategori || k.nama_kategori.toLowerCase() === kategori;
                const matchJenis = !jenis || (k.jenis || '').toLowerCase().includes(jenis);
                return matchSearch && matchKategori && matchJenis;
            });
            renderTable(filtered);
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterJenis').value = '';
            populateJenisFilter('');
            renderTable(allKategori);
        }

        // Saat filter kategori berubah, update dropdown jenis filter
        document.getElementById('filterKategori').addEventListener('change', function() {
            populateJenisFilter(this.value);
            applyFilter();
        });
        document.getElementById('filterSearch').addEventListener('input', applyFilter);
        document.getElementById('filterJenis').addEventListener('change', applyFilter);

        function populateJenisFilter(kategori) {
            const sel = document.getElementById('filterJenis');
            sel.innerHTML = '<option value="">Semua Jenis</option>';
            const list = JENIS_MAP[kategori] || [];
            list.forEach(j => {
                const opt = document.createElement('option');
                opt.value = j;
                opt.textContent = j;
                sel.appendChild(opt);
            });
        }

        // ── JENIS DROPDOWN DINAMIS (modal) ───────────────────────────────
        function updateJenisOptions(prefix, selectedJenis = '') {
            const katVal = document.getElementById(`${prefix}NamaKategori`).value;
            const sel = document.getElementById(`${prefix}Jenis`);
            const list = JENIS_MAP[katVal] || [];

            sel.innerHTML = '<option value="">-- Pilih Jenis --</option>';
            list.forEach(j => {
                const opt = document.createElement('option');
                opt.value = j;
                opt.textContent = j;
                if (j === selectedJenis) opt.selected = true;
                sel.appendChild(opt);
            });
        }

        // ── MODAL ────────────────────────────────────────────────────────
        function openDetailModal(id) {
            const k = allKategori.find(x => Number(x.id) === Number(id));
            if (!k) return;

            document.getElementById('detailNamaKategori').textContent = k.nama_kategori || '-';
            document.getElementById('detailJenis').textContent = k.jenis || '-';

            const ul = document.getElementById('detailMenuList');
            ul.innerHTML = '';

            // Ambil daftar nama menu dari relasi menus jika ada
            const menus = k.menus || [];
            if (menus.length) {
                menus.forEach(m => {
                    const li = document.createElement('li');
                    li.textContent = m.nama_makanan || m.nama || m;
                    ul.appendChild(li);
                });
            } else {
                ul.innerHTML = '<li class="text-gray-400 italic list-none">Belum ada menu terdaftar</li>';
            }

            showModal('detailModal');
        }

        function openCreateModal() {
            document.getElementById('createNamaKategori').value = '';
            document.getElementById('createJenis').innerHTML = '<option value="">-- Pilih Kategori dulu --</option>';
            clearErrors('create');
            showModal('createModal');
        }

        function openEditModal(id) {
            const k = allKategori.find(x => Number(x.id) === Number(id));
            if (!k) return;

            document.getElementById('editKategoriId').value = k.id;
            document.getElementById('editNamaKategori').value = k.nama_kategori || '';
            updateJenisOptions('edit', k.jenis || '');
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

        // ── VALIDASI ─────────────────────────────────────────────────────
        function validate(prefix) {
            let ok = true;
            clearErrors(prefix);
            const nama = document.getElementById(`${prefix}NamaKategori`).value;
            const jenis = document.getElementById(`${prefix}Jenis`).value;
            if (!nama) {
                showErr(`err${cap(prefix)}NamaKategori`, 'Kategori wajib dipilih.');
                ok = false;
            }
            if (!jenis) {
                showErr(`err${cap(prefix)}Jenis`, 'Jenis wajib dipilih.');
                ok = false;
            }
            return ok;
        }

        // ── SUBMIT CREATE ─────────────────────────────────────────────────
        async function submitCreate() {
            if (!validate('create')) return;

            const payload = {
                nama_kategori: document.getElementById('createNamaKategori').value,
                jenis: document.getElementById('createJenis').value,
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

                // inject menus kosong untuk state lokal
                data.data.menus = [];
                allKategori.push(data.data);
                renderTable(allKategori);
                closeModal('createModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kategori berhasil ditambahkan.',
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

        // ── SUBMIT EDIT ───────────────────────────────────────────────────
        async function submitEdit() {
            if (!validate('edit')) return;

            const id = document.getElementById('editKategoriId').value;
            const payload = {
                nama_kategori: document.getElementById('editNamaKategori').value,
                jenis: document.getElementById('editJenis').value,
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

                const idx = allKategori.findIndex(x => Number(x.id) === Number(id));
                if (idx !== -1) {
                    data.data.menus = allKategori[idx].menus || [];
                    allKategori[idx] = data.data;
                }
                applyFilter();
                closeModal('editModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Kategori berhasil diperbarui.',
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

        // ── DELETE ────────────────────────────────────────────────────────
        async function deleteKategori(id) {
            const result = await Swal.fire({
                title: 'Hapus kategori ini?',
                text: 'Kategori yang masih dipakai menu tidak bisa dihapus!',
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

                allKategori = allKategori.filter(x => Number(x.id) !== Number(id));
                applyFilter();
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Kategori berhasil dihapus.',
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

        // ── UTILS ─────────────────────────────────────────────────────────
        window.doDetail = (id) => openDetailModal(id);
        window.doEdit = (id) => openEditModal(id);
        window.doDelete = (id) => deleteKategori(id);

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
                nama_kategori: `err${cap(prefix)}NamaKategori`,
                jenis: `err${cap(prefix)}Jenis`,
            };
            Object.entries(errors).forEach(([f, msgs]) => {
                if (map[f]) showErr(map[f], msgs[0]);
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

        // ── INIT ──────────────────────────────────────────────────────────
        renderTable(allKategori);
    </script>
@endsection
