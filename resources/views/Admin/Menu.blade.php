@extends('admin.layouts.app')
@section('title', 'Kelola Menu')

@section('content')
    <div class="space-y-5 max-w-7xl mx-auto p-1">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Menu</h1>
            <p class="text-gray-600 mt-1">Kelola daftar menu makanan dan minuman</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Menu</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama menu..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select id="filterKategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }} - {{ $kat->jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-3 lg:col-span-2">
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
                <h2 class="text-lg font-bold text-gray-900">Daftar Menu</h2>
                <button onclick="openCreateModal()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg shadow hover:bg-teal-700 transition text-sm">
                    <i class="fas fa-plus text-sm"></i> Tambah Menu
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Gambar</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Menu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Stok</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-500">
                <i class="fas fa-utensils text-5xl text-gray-300 mb-4 block"></i>
                Belum ada data menu
            </div>
        </div>
    </div>

    {{-- ── MODAL TAMBAH ──────────────────────────────────────────────── --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-plus-circle"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Menu Baru</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data menu baru</p>
                    </div>
                </div>
                <button onclick="closeModal('createModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <form id="createForm" enctype="multipart/form-data" class="p-6 bg-gray-50 space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-tag text-teal-600 text-sm"></i> Kategori <span
                                class="text-red-500">*</span></label>
                        <select id="createKategori" name="id_kategori"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }} - {{ $kat->jenis }}</option>
                            @endforeach
                        </select>
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateKategori"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-utensils text-teal-600 text-sm"></i> Nama Menu <span
                                class="text-red-500">*</span></label>
                        <input type="text" id="createNama" name="nama_makanan" placeholder="Contoh: Nasi Goreng Spesial"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateNama"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-money-bill text-teal-600 text-sm"></i> Harga <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="createHarga" name="harga" placeholder="Contoh: 25000" min="0"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateHarga"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                                class="fas fa-boxes-stacked text-teal-600 text-sm"></i> Stok <span
                                class="text-red-500">*</span></label>
                        <input type="number" id="createStok" name="stok" placeholder="Contoh: 50" min="0"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateStok"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5"><i
                            class="fas fa-image text-teal-600 text-sm"></i> Gambar Menu</label>
                    <input type="file" id="createGambar" name="gambar" accept="image/*"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none transition text-sm">
                    {{-- preview --}}
                    <img id="createGambarPreview" src="#" alt="Preview"
                        class="mt-2 w-24 h-24 object-cover rounded-lg hidden shadow">
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-3 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-6 py-2.5 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                    <button type="button" onclick="submitCreate()"
                        class="px-8 py-2.5 bg-teal-600 text-white font-semibold rounded-lg hover:bg-teal-700 transition shadow-md flex items-center gap-2 text-sm">
                        <i class="fas fa-save text-sm"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── MODAL HARGA ───────────────────────────────────────────────── --}}
    <div id="hargaModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="hargaContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div
                class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-money-bill-wave"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Kelola Harga</h2>
                        <p class="text-emerald-100 text-xs mt-0.5">Ubah harga jual menu</p>
                    </div>
                </div>
                <button onclick="closeModal('hargaModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" id="hargaMenuId">
                <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                    <p class="text-xs text-gray-500">Nama Menu</p>
                    <p class="font-semibold text-gray-900" id="hargaNama">-</p>
                    <p class="text-xs text-gray-500 mt-2">Kategori</p>
                    <p class="text-sm text-gray-700" id="hargaKategori">-</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Harga Saat Ini</p>
                    <p class="text-2xl font-bold text-emerald-700" id="hargaSaatIni">Rp 0</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Harga Baru <span
                            class="text-red-500">*</span></label>
                    <input type="number" id="inputHargaBaru" min="0" placeholder="Masukkan harga baru"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-400 text-lg outline-none">
                    <p class="text-red-500 text-xs mt-1 hidden" id="errHarga"></p>
                </div>
            </div>
            <div class="px-6 pb-5 flex justify-end gap-3">
                <button onclick="closeModal('hargaModal')"
                    class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                <button onclick="submitHarga()"
                    class="px-7 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-save"></i> Simpan Harga
                </button>
            </div>
        </div>
    </div>

    {{-- ── MODAL STOK ────────────────────────────────────────────────── --}}
    <div id="stokModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="stokContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl"><i
                            class="fas fa-boxes-stacked"></i></div>
                    <div>
                        <h2 class="text-xl font-bold">Kelola Stok</h2>
                        <p class="text-blue-100 text-xs mt-0.5">Tambah / kurangi stok menu</p>
                    </div>
                </div>
                <button onclick="closeModal('stokModal')"
                    class="text-white/80 hover:text-white text-xl p-1.5 rounded-full hover:bg-white/10"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="p-6 space-y-5">
                <input type="hidden" id="stokMenuId">
                <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                    <p class="text-xs text-gray-500">Nama Menu</p>
                    <p class="font-semibold text-gray-900" id="stokNama">-</p>
                </div>
                <div class="flex items-center justify-between bg-blue-50 rounded-xl px-5 py-4">
                    <div>
                        <p class="text-xs text-gray-500">Stok Saat Ini</p>
                        <p class="text-3xl font-bold text-blue-700" id="stokSaatIni">0</p>
                        <p class="text-xs text-gray-400">pcs</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 text-2xl"></i>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Setelah Update</p>
                        <p class="text-3xl font-bold text-gray-800" id="stokTotal">0</p>
                        <p class="text-xs text-gray-400">pcs</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok Baru
                        <span class="text-gray-400 font-normal text-xs ml-1">(tulis angka positif untuk tambah, negatif
                            untuk kurang)</span>
                    </label>
                    <input type="number" id="inputJumlahStok" placeholder="Contoh: 10 atau -5"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 text-lg outline-none"
                        oninput="hitungTotalStok()">
                    <p class="text-red-500 text-xs mt-1 hidden" id="errStok"></p>
                </div>
            </div>
            <div class="px-6 pb-5 flex justify-end gap-3">
                <button onclick="closeModal('stokModal')"
                    class="px-6 py-2.5 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition text-sm">Batal</button>
                <button onclick="submitStok()"
                    class="px-7 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2 text-sm font-semibold">
                    <i class="fas fa-save"></i> Update Stok
                </button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ── DATA ──────────────────────────────────────────────────────────
        let allMenu = @json($menus);

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            store: '{{ route('admin.menu.store') }}',
            update: (id) => `{{ url('admin/menu') }}/${id}`,
            destroy: (id) => `{{ url('admin/menu') }}/${id}`,
        };

        const rupiah = (n) => 'Rp ' + new Intl.NumberFormat('id-ID').format(n || 0);

        // ── RENDER ────────────────────────────────────────────────────────
        function renderTable(data) {
            const tbody = document.getElementById('menuTableBody');
            const empty = document.getElementById('emptyState');

            if (!data.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                return;
            }
            empty.classList.add('hidden');

            tbody.innerHTML = data.map((m, idx) => {
                const id = m.id;
                const kat = m.kategori ? `${m.kategori.nama_kategori} - ${m.kategori.jenis || ''}` : '-';
                const gambar = m.gambar ?
                    `<img src="/storage/${m.gambar}" class="w-14 h-14 object-cover rounded-lg shadow-sm" onerror="this.src='https://placehold.co/56x56/e2e8f0/94a3b8?text=No+Img'">` :
                    `<div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400"><i class="fas fa-image text-xl"></i></div>`;
                const stokClass = m.stok <= 5 ? 'text-red-600 font-bold' : m.stok <= 15 ?
                    'text-amber-600 font-semibold' : 'text-gray-700';

                return `
        <tr class="hover:bg-gray-50 transition duration-150" id="row-${id}">
            <td class="px-6 py-4 text-sm text-gray-600">${idx + 1}</td>
            <td class="px-6 py-4">${gambar}</td>
            <td class="px-6 py-4 text-sm font-semibold text-gray-900">${escHtml(m.nama_makanan)}</td>
            <td class="px-6 py-4">
                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-teal-100 text-teal-800">
                    ${escHtml(kat)}
                </span>
            </td>
            <td class="px-6 py-4 text-sm font-semibold text-gray-800" id="harga-${id}">${rupiah(m.harga)}</td>
            <td class="px-6 py-4 text-sm ${stokClass}" id="stok-${id}">${m.stok} pcs</td>
            <td class="px-6 py-4 text-sm font-medium flex items-center gap-4">
                <button onclick="window.doHarga(${id})" title="Kelola Harga"
                    class="text-emerald-600 hover:text-emerald-800 transition text-xl">
                    <i class="fas fa-money-bill-wave"></i>
                </button>
                <button onclick="window.doStok(${id})" title="Kelola Stok"
                    class="text-blue-600 hover:text-blue-800 transition text-xl">
                    <i class="fas fa-boxes-stacked"></i>
                </button>
                <button onclick="window.doDelete(${id})" title="Hapus Menu"
                    class="text-red-600 hover:text-red-800 transition text-xl">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>`;
            }).join('');
        }

        // ── FILTER ────────────────────────────────────────────────────────
        function applyFilter() {
            const search = document.getElementById('filterSearch').value.toLowerCase().trim();
            const katId = document.getElementById('filterKategori').value;

            const filtered = allMenu.filter(m => {
                const matchSearch = !search || m.nama_makanan.toLowerCase().includes(search);
                const matchKat = !katId || String(m.id_kategori) === katId;
                return matchSearch && matchKat;
            });
            renderTable(filtered);
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            renderTable(allMenu);
        }

        document.getElementById('filterSearch').addEventListener('input', applyFilter);
        document.getElementById('filterKategori').addEventListener('change', applyFilter);

        // ── MODAL HELPERS ─────────────────────────────────────────────────
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

        // ── PREVIEW GAMBAR ────────────────────────────────────────────────
        document.getElementById('createGambar').addEventListener('change', function() {
            previewImg(this, 'createGambarPreview');
        });

        function previewImg(input, previewId) {
            const prev = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    prev.src = e.target.result;
                    prev.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // ── OPEN MODALS ───────────────────────────────────────────────────
        function openCreateModal() {
            document.getElementById('createForm').reset();
            document.getElementById('createGambarPreview').classList.add('hidden');
            clearErrors('create');
            // reset semua border input ke normal
            ['createKategori', 'createNama', 'createHarga', 'createStok'].forEach(id => setBorder(id, false));
            showModal('createModal');
        }



        function openHargaModal(id) {
            const m = allMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;
            const kat = m.kategori ? `${m.kategori.nama_kategori} - ${m.kategori.jenis || ''}` : '-';
            document.getElementById('hargaMenuId').value = m.id;
            document.getElementById('hargaNama').textContent = m.nama_makanan;
            document.getElementById('hargaKategori').textContent = kat;
            document.getElementById('hargaSaatIni').textContent = rupiah(m.harga);
            document.getElementById('inputHargaBaru').value = m.harga;
            document.getElementById('errHarga').classList.add('hidden');
            showModal('hargaModal');
        }

        function openStokModal(id) {
            const m = allMenu.find(x => Number(x.id) === Number(id));
            if (!m) return;
            document.getElementById('stokMenuId').value = m.id;
            document.getElementById('stokNama').textContent = m.nama_makanan;
            document.getElementById('stokSaatIni').textContent = m.stok;
            document.getElementById('stokTotal').textContent = m.stok;
            document.getElementById('inputJumlahStok').value = '';
            document.getElementById('errStok').classList.add('hidden');
            window._currentStok = m.stok;
            showModal('stokModal');
        }

        function hitungTotalStok() {
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value) || 0;
            const total = window._currentStok + jumlah;
            const el = document.getElementById('stokTotal');
            el.textContent = total;
            el.className = total < 0 ? 'text-3xl font-bold text-red-600' : total > window._currentStok ?
                'text-3xl font-bold text-green-600' : 'text-3xl font-bold text-gray-800';
        }

        // ── VALIDASI ──────────────────────────────────────────────────────
        function validateCreate() {
            let ok = true;
            clearErrors('create');
            resetBorders('create');

            const kategori = document.getElementById('createKategori').value;
            const nama = document.getElementById('createNama').value.trim();
            const harga = document.getElementById('createHarga').value;
            const stok = document.getElementById('createStok').value;

            if (!kategori) {
                showErr('errCreateKategori', 'Kategori wajib dipilih.');
                setBorder('createKategori', true);
                ok = false;
            }
            if (!nama) {
                showErr('errCreateNama', 'Nama menu wajib diisi.');
                setBorder('createNama', true);
                ok = false;
            }
            if (harga === '' || harga === null || Number(harga) < 0) {
                showErr('errCreateHarga', 'Harga wajib diisi dan tidak boleh negatif.');
                setBorder('createHarga', true);
                ok = false;
            }
            if (stok === '' || stok === null || Number(stok) < 0) {
                showErr('errCreateStok', 'Stok wajib diisi dan tidak boleh negatif.');
                setBorder('createStok', true);
                ok = false;
            }
            return ok;
        }


        // ── SUBMIT CREATE ─────────────────────────────────────────────────
        async function submitCreate() {
            if (!validateCreate()) return;

            const form = new FormData();
            form.append('id_kategori', document.getElementById('createKategori').value);
            form.append('nama_makanan', document.getElementById('createNama').value.trim());
            form.append('harga', document.getElementById('createHarga').value);
            form.append('stok', document.getElementById('createStok').value);
            const gambarFile = document.getElementById('createGambar').files[0];
            if (gambarFile) form.append('gambar', gambarFile);

            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            setLoading(btn, true);

            try {
                const res = await fetch(ROUTES.store, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
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

                allMenu.push(data.data);
                renderTable(allMenu);
                closeModal('createModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Menu berhasil ditambahkan.',
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



        // ── SUBMIT HARGA ──────────────────────────────────────────────────
        async function submitHarga() {
            const id = document.getElementById('hargaMenuId').value;
            const harga = document.getElementById('inputHargaBaru').value;
            document.getElementById('errHarga').classList.add('hidden');

            if (!harga || Number(harga) < 0) {
                showErr('errHarga', 'Harga wajib diisi dan tidak boleh negatif.');
                setBorder('inputHargaBaru', true);
                return;
            }
            setBorder('inputHargaBaru', false);

            const btn = document.querySelector('#hargaContent button[onclick="submitHarga()"]');
            setLoading(btn, true);

            try {
                const form = new FormData();
                form.append('harga', harga);
                form.append('_method', 'PUT');
                // ambil data menu yg lain supaya update tidak hapus field lain
                const m = allMenu.find(x => Number(x.id) === Number(id));
                form.append('id_kategori', m.id_kategori);
                form.append('nama_makanan', m.nama_makanan);
                form.append('stok', m.stok);

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                    return;
                }

                const idx = allMenu.findIndex(x => Number(x.id) === Number(id));
                if (idx !== -1) allMenu[idx] = data.data;
                // update cell harga langsung tanpa re-render seluruh tabel
                const cell = document.getElementById(`harga-${id}`);
                if (cell) cell.textContent = rupiah(data.data.harga);
                closeModal('hargaModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Harga berhasil diperbarui.',
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

        // ── SUBMIT STOK ───────────────────────────────────────────────────
        async function submitStok() {
            const id = document.getElementById('stokMenuId').value;
            const jumlah = parseInt(document.getElementById('inputJumlahStok').value);
            document.getElementById('errStok').classList.add('hidden');

            if (isNaN(jumlah) || jumlah === 0) {
                showErr('errStok', 'Masukkan angka penambahan atau pengurangan (tidak boleh 0).');
                setBorder('inputJumlahStok', true);
                return;
            }

            const newStok = window._currentStok + jumlah;
            if (newStok < 0) {
                showErr('errStok',
                    `Stok tidak cukup. Stok saat ini ${window._currentStok}, maksimal dikurangi ${window._currentStok}.`
                    );
                setBorder('inputJumlahStok', true);
                return;
            }
            setBorder('inputJumlahStok', false);

            const btn = document.querySelector('#stokContent button[onclick="submitStok()"]');
            setLoading(btn, true);

            try {
                const m = allMenu.find(x => Number(x.id) === Number(id));
                const form = new FormData();
                form.append('stok', newStok);
                form.append('_method', 'PUT');
                form.append('id_kategori', m.id_kategori);
                form.append('nama_makanan', m.nama_makanan);
                form.append('harga', m.harga);

                const res = await fetch(ROUTES.update(id), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: form,
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                    return;
                }

                const idx = allMenu.findIndex(x => Number(x.id) === Number(id));
                if (idx !== -1) allMenu[idx] = data.data;
                // update cell stok langsung
                const cell = document.getElementById(`stok-${id}`);
                if (cell) {
                    cell.textContent = `${newStok} pcs`;
                    cell.className = newStok <= 5 ? 'px-6 py-4 text-sm text-red-600 font-bold' : newStok <= 15 ?
                        'px-6 py-4 text-sm text-amber-600 font-semibold' : 'px-6 py-4 text-sm text-gray-700';
                }
                closeModal('stokModal');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: `Stok diperbarui menjadi ${newStok} pcs.`,
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
        async function deleteMenu(id) {
            const result = await Swal.fire({
                title: 'Hapus menu ini?',
                text: 'Data tidak bisa dikembalikan!',
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
                        text: data.message
                    });
                    return;
                }

                allMenu = allMenu.filter(x => Number(x.id) !== Number(id));
                applyFilter();
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: 'Menu berhasil dihapus.',
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

        window.doHarga = (id) => openHargaModal(id);
        window.doStok = (id) => openStokModal(id);
        window.doDelete = (id) => deleteMenu(id);

        function escHtml(s) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(s || ''));
            return d.innerHTML;
        }

        function showErr(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function clearErrors(prefix) {
            const cap = prefix.charAt(0).toUpperCase() + prefix.slice(1);
            document.querySelectorAll(`[id^="err${cap}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            resetBorders(prefix);
        }

        function setBorder(inputId, isError) {
            const el = document.getElementById(inputId);
            if (!el) return;
            if (isError) {
                el.classList.add('border-red-400', 'focus:ring-red-400');
                el.classList.remove('border-gray-300');
            } else {
                el.classList.remove('border-red-400', 'focus:ring-red-400');
                el.classList.add('border-gray-300');
            }
        }

        function resetBorders(prefix) {
            const map = {
                create: ['createKategori', 'createNama', 'createHarga', 'createStok'],
                harga: ['inputHargaBaru'],
                stok: ['inputJumlahStok'],
            };
            (map[prefix] || []).forEach(id => setBorder(id, false));
        }

        function handleLaravelErrors(errors, prefix) {
            const map = {
                id_kategori: 'errCreateKategori',
                nama_makanan: 'errCreateNama',
                harga: 'errCreateHarga',
                stok: 'errCreateStok'
            };
            if (prefix === 'edit') {
                map.id_kategori = 'errEditKategori';
                map.nama_makanan = 'errEditNama';
            }
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
        renderTable(allMenu);
    </script>
@endsection
