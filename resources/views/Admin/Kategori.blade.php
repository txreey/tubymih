@extends('admin.layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto p-6">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kategori</h1>
            <p class="text-gray-600 mt-1">Kelola kategori dan jenis untuk menu makanan & minuman</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cari Nama Kategori</label>
                        <input type="text" id="filterSearch" placeholder="Ketik nama kategori..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
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
                        <select id="filterJenis" disabled
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 text-sm">
                            <option value="">Pilih kategori dulu</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button onclick="terapkanFilter()"
                            class="flex-1 px-5 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm text-sm">Cari</button>
                        <button onclick="resetFilter()"
                            class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition shadow-sm text-sm">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-tags text-teal-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 leading-none">Daftar Kategori</p>
                        <p class="text-xs text-gray-400 mt-1" id="totalKategoriInfo">Total: 0 Kategori</p>
                    </div>
                </div>
                <button onclick="bukaModalTambah()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 text-white font-semibold rounded-xl shadow-sm hover:bg-teal-700 transition text-sm">
                    + Tambah Kategori
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/40">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-16">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Jumlah Menu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kategoriTableBody"></tbody>
                </table>
            </div>

            <div id="emptyState" class="hidden px-6 py-16 text-center text-gray-400">
                <i class="fas fa-tags text-5xl text-gray-200 mb-4 block"></i>
                Belum ada data kategori
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/40 flex items-center justify-between">
                <p class="text-xs text-gray-500" id="paginationInfo"></p>
                <div class="flex items-center gap-1.5">
                    <!-- Tombol Previous -->
                    <button onclick="prevPage()" id="btnPrev"
                        class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-300 hover:border-teal-500 hover:text-teal-600 transition disabled:opacity-40">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <!-- Kotak Angka Saat Ini -->
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

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="detailContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h2 class="text-xl font-bold">Detail Kategori</h2>
                </div>
                <button onclick="tutupModal('detailModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 bg-gray-50 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-medium mb-1">Kategori</p>
                        <p class="text-base font-bold text-gray-900" id="detailNamaKategori">-</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <p class="text-xs text-gray-400 font-medium mb-1">Jenis</p>
                        <p class="text-base font-bold text-gray-900" id="detailJenis">-</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-700 mb-2">Menu yang menggunakan jenis ini:</p>
                    <div class="bg-white border border-gray-200 rounded-xl p-4 max-h-48 overflow-y-auto">
                        <ul id="detailMenuList" class="space-y-1 text-sm text-gray-700 list-disc pl-4"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button onclick="tutupModal('detailModal')"
                    class="px-8 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition shadow-md flex items-center gap-2">
                    <i class="fas fa-check"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH --}}
    <div id="createModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden backdrop-blur-md">
        <div id="createContent"
            class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 opacity-0">
            <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Tambah Kategori</h2>
                        <p class="text-teal-100 text-xs mt-0.5">Isi data kategori baru</p>
                    </div>
                </div>
                <button onclick="tutupModal('createModal')"
                    class="w-9 h-9 bg-red-500 hover:bg-red-600 text-white flex items-center justify-center rounded-xl transition shadow-md">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 bg-gray-50 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-tag text-teal-600 text-sm"></i> Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="createNamaKategori" onchange="handleKategoriChange()"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
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
                        <input type="text" id="createJenis" placeholder="Masukkan jenis..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 focus:border-teal-400 outline-none transition text-sm">
                        <p class="text-red-500 text-xs mt-1 hidden" id="errCreateJenis"></p>
                    </div>
                </div>
                <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg flex items-start gap-3 text-xs">
                    <i class="fas fa-info-circle text-teal-600 text-base mt-0.5"></i>
                    <p class="text-teal-800">Field bertanda <span class="text-red-500 font-bold">*</span> wajib diisi. Isi
                        jenis baru untuk kategori yang dipilih.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="tutupModal('createModal')"
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

        .ab-eye {
            background: #f0fdf9;
            color: #0d9488;
        }

        .ab-eye:hover {
            background: #0d9488;
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
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let semuaKategori = @json($kategoris);
        let dataTerfilter = [...semuaKategori];
        let halamanAktif = 1;
        let totalHalamanGlobal = 1;
        const PER_PAGE = 5;

        const CSRF = '{{ csrf_token() }}';
        const ROUTES = {
            simpan: '{{ route('admin.kategori.store') }}',
            update: (id) => `{{ url('admin/kategori') }}/${id}`,
            hapus: (id) => `{{ url('admin/kategori') }}/${id}`,
        };

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(str || ''));
            return div.innerHTML;
        }

        function tampilkanModal(id) {
            document.getElementById(id).classList.remove('hidden');
            setTimeout(() => {
                const konten = document.getElementById(id.replace('Modal', 'Content'));
                konten.classList.remove('scale-95', 'opacity-0');
                konten.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function tutupModal(id) {
            const konten = document.getElementById(id.replace('Modal', 'Content'));
            konten.classList.remove('scale-100', 'opacity-100');
            konten.classList.add('scale-95', 'opacity-0');
            setTimeout(() => document.getElementById(id).classList.add('hidden'), 300);
        }

        function tampilkanError(id, pesan) {
            const el = document.getElementById(id);
            if (el) {
                el.textContent = pesan;
                el.classList.remove('hidden');
            }
        }

        function bersihkanError(prefix) {
            const kapital = prefix.charAt(0).toUpperCase() + prefix.slice(1);
            document.querySelectorAll(`[id^="err${kapital}"]`).forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function setLoading(btn, loading) {
            if (!btn) return;
            if (loading) {
                btn.disabled = true;
                btn.dataset.asli = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin text-sm"></i> Proses...';
            } else {
                btn.disabled = false;
                btn.innerHTML = btn.dataset.asli;
            }
        }

        function susunFlatList(data) {
            const map = {};
            data.forEach(k => {
                const key = k.nama_kategori || 'Lainnya';
                if (!map[key]) map[key] = [];
                map[key].push(k);
            });
            const urutan = ['Makanan', 'Minuman'];
            const keys = [...urutan.filter(k => map[k]), ...Object.keys(map).filter(k => !urutan.includes(k)).sort()];
            const flat = [];
            let noKat = 1;
            keys.forEach(nama => {
                const items = map[nama];
                items.forEach((k, idx) => {
                    flat.push({
                        ...k,
                        _noKategori: noKat,
                        _namaKategori: nama,
                        _isFirstInGroup: idx === 0,
                        _groupSize: items.length
                    });
                });
                noKat++;
            });
            return flat;
        }

        function hitungHalaman(flatList) {
            const halaman = [];
            let i = 0;
            while (i < flatList.length) {
                const halamanIni = [];
                let sisaSlot = PER_PAGE;
                while (i < flatList.length && sisaSlot > 0) {
                    const item = flatList[i];
                    if (item._isFirstInGroup) {
                        const groupSize = item._groupSize;
                        if (halamanIni.length === 0) {
                            const ambil = Math.min(groupSize, sisaSlot);
                            for (let j = 0; j < ambil; j++) halamanIni.push(flatList[i + j]);
                            sisaSlot -= ambil;
                            i += ambil;
                        } else {
                            if (groupSize > sisaSlot) break;
                            for (let j = 0; j < groupSize; j++) halamanIni.push(flatList[i + j]);
                            sisaSlot -= groupSize;
                            i += groupSize;
                        }
                    } else {
                        i++;
                    }
                }
                if (halamanIni.length > 0) halaman.push(halamanIni);
            }
            return halaman;
        }

        function renderTabel(data) {
            const tbody = document.getElementById('kategoriTableBody');
            const empty = document.getElementById('emptyState');
            const totalUnik = [...new Set(data.map(k => k.nama_kategori))].length;
            document.getElementById('totalKategoriInfo').textContent = `Total: ${totalUnik} Kategori`;

            if (!data.length) {
                tbody.innerHTML = '';
                empty.classList.remove('hidden');
                renderPagination([], 0, 0, 0);
                return;
            }
            empty.classList.add('hidden');

            const flatList = susunFlatList(data);
            const semuaHalaman = hitungHalaman(flatList);
            totalHalamanGlobal = semuaHalaman.length;
            if (halamanAktif > semuaHalaman.length) halamanAktif = semuaHalaman.length;
            if (halamanAktif < 1) halamanAktif = 1;

            const itemHalaman = semuaHalaman[halamanAktif - 1] || [];
            const itemSebelumHalaman = semuaHalaman.slice(0, halamanAktif - 1).reduce((s, h) => s + h.length, 0);

            const grupDiHalaman = {};
            itemHalaman.forEach(item => {
                if (!grupDiHalaman[item._namaKategori]) grupDiHalaman[item._namaKategori] = 0;
                grupDiHalaman[item._namaKategori]++;
            });

            const sudahRenderGrup = {};
            let rows = '';
            itemHalaman.forEach(item => {
                const id = item.id;
                const jumlah = item.menus_count ?? item.jumlah ?? 0;
                const namaGrup = item._namaKategori;
                const rowspanDiHalaman = grupDiHalaman[namaGrup];
                let cellGrup = '';
                if (!sudahRenderGrup[namaGrup]) {
                    sudahRenderGrup[namaGrup] = true;
                    cellGrup =
                        `
                        <td rowspan="${rowspanDiHalaman}" class="px-6 py-4 text-sm text-gray-400 font-medium align-middle border-r border-gray-100">${item._noKategori}</td>
                        <td rowspan="${rowspanDiHalaman}" class="px-6 py-4 text-sm font-bold text-teal-600 align-middle border-r border-gray-100">${escapeHtml(namaGrup)}</td>`;
                }
                rows += `
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors" id="row-${id}">
                        ${cellGrup}
                        <td class="px-6 py-4">
                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-teal-50 text-teal-700">${escapeHtml(item.jenis || '-')}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">${jumlah} Menu</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button onclick="bukaDetail(${id})" class="ab ab-eye" title="Detail"><i class="fas fa-eye"></i></button>
                                <button onclick="hapusKategori(${id})" class="ab ab-del" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>`;
            });
            tbody.innerHTML = rows;
            renderPagination(semuaHalaman, itemSebelumHalaman, itemHalaman.length, flatList.length);
        }

        function renderPagination(semuaHalaman, itemMulai, itemDiHalaman, totalItem) {
            const totalHalaman = semuaHalaman.length;
            const info = document.getElementById('paginationInfo');
            const btnPrev = document.getElementById('btnPrev');
            const btnNext = document.getElementById('btnNext');
            const currentPageBox = document.getElementById('currentPageBox');

            if (totalHalaman === 0) {
                info.textContent = '';
                currentPageBox.textContent = '1';
                btnPrev.disabled = true;
                btnNext.disabled = true;
                return;
            }

            info.innerHTML =
                `Menampilkan <strong>${itemMulai + 1}-${itemMulai + itemDiHalaman}</strong> dari <strong>${totalItem}</strong> jenis`;
            currentPageBox.textContent = halamanAktif;
            btnPrev.disabled = halamanAktif <= 1;
            btnNext.disabled = halamanAktif >= totalHalaman;
        }

        function prevPage() {
            if (halamanAktif > 1) {
                halamanAktif--;
                renderTabel(dataTerfilter);
            }
        }

        function nextPage() {
            if (halamanAktif < totalHalamanGlobal) {
                halamanAktif++;
                renderTabel(dataTerfilter);
            }
        }

        function terapkanFilter() {
            halamanAktif = 1;
            const keyword = document.getElementById('filterSearch').value.toLowerCase().trim();
            const kategori = document.getElementById('filterKategori').value;
            const jenis = document.getElementById('filterJenis').value;
            dataTerfilter = semuaKategori.filter(k => {
                const cocokKeyword = !keyword || k.nama_kategori.toLowerCase().includes(keyword) || (k.jenis || '')
                    .toLowerCase().includes(keyword);
                const cocokKategori = !kategori || k.nama_kategori === kategori;
                const cocokJenis = !jenis || (k.jenis || '').toLowerCase().includes(jenis.toLowerCase());
                return cocokKeyword && cocokKategori && cocokJenis;
            });
            renderTabel(dataTerfilter);
        }

        function resetFilter() {
            document.getElementById('filterSearch').value = '';
            document.getElementById('filterKategori').value = '';
            document.getElementById('filterJenis').value = '';
            document.getElementById('filterJenis').disabled = true;
            document.getElementById('filterJenis').innerHTML = '<option value="">Pilih kategori dulu</option>';
            dataTerfilter = [...semuaKategori];
            halamanAktif = 1;
            renderTabel(dataTerfilter);
        }

        function filterKategoriBerubah() {
            const kategori = document.getElementById('filterKategori').value;
            const selectJenis = document.getElementById('filterJenis');
            if (!kategori) {
                selectJenis.disabled = true;
                selectJenis.innerHTML = '<option value="">Pilih kategori dulu</option>';
                terapkanFilter();
                return;
            }
            selectJenis.disabled = false;
            selectJenis.innerHTML = '<option value=""> Semua Jenis </option>';
            [...new Set(semuaKategori.filter(k => k.nama_kategori === kategori).map(k => k.jenis))].forEach(j => {
                const opt = document.createElement('option');
                opt.value = j;
                opt.textContent = j;
                selectJenis.appendChild(opt);
            });
            terapkanFilter();
        }

        function cekDuplikasiJenis(namaKategori, jenisBaru) {
            return semuaKategori.some(k => k.nama_kategori === namaKategori && k.jenis?.toLowerCase() === jenisBaru
                .toLowerCase());
        }

        function bukaDetail(id) {
            const k = semuaKategori.find(x => Number(x.id) === Number(id));
            if (!k) return;
            document.getElementById('detailNamaKategori').textContent = k.nama_kategori || '-';
            document.getElementById('detailJenis').textContent = k.jenis || '-';
            const ul = document.getElementById('detailMenuList');
            ul.innerHTML = '';
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
            tampilkanModal('detailModal');
        }

        function bukaModalTambah() {
            document.getElementById('createNamaKategori').value = '';
            document.getElementById('createJenis').value = '';
            document.getElementById('createJenis').disabled = false;
            bersihkanError('create');
            tampilkanModal('createModal');
        }

        function handleKategoriChange() {
            const kategori = document.getElementById('createNamaKategori').value;
            const inputJenis = document.getElementById('createJenis');
            if (!kategori) {
                inputJenis.disabled = true;
                inputJenis.value = '';
                inputJenis.placeholder = 'Pilih kategori dulu';
            } else {
                inputJenis.disabled = false;
                inputJenis.placeholder = 'Masukkan jenis...';
            }
        }

        function validasiTambah() {
            let valid = true;
            bersihkanError('create');
            const kategori = document.getElementById('createNamaKategori').value;
            const jenis = document.getElementById('createJenis').value.trim();
            if (!kategori) {
                tampilkanError('errCreateNamaKategori', 'Kategori wajib dipilih.');
                valid = false;
            }
            if (!jenis) {
                tampilkanError('errCreateJenis', 'Jenis wajib diisi.');
                valid = false;
            }
            if (kategori && jenis && cekDuplikasiJenis(kategori, jenis)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplikasi Jenis!',
                    text: `Jenis "${jenis}" sudah ada di kategori ${kategori}.`,
                    confirmButtonColor: '#dc2626'
                });
                valid = false;
            }
            return valid;
        }

        async function submitCreate() {
            if (!validasiTambah()) return;
            const payload = {
                nama_kategori: document.getElementById('createNamaKategori').value,
                jenis: document.getElementById('createJenis').value.trim()
            };
            const btn = document.querySelector('#createContent button[onclick="submitCreate()"]');
            setLoading(btn, true);
            try {
                const res = await fetch(ROUTES.simpan, {
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
                    if (res.status === 422 && data.errors) {
                        Object.entries(data.errors).forEach(([field, msgs]) => {
                            if (field === 'nama_kategori') tampilkanError('errCreateNamaKategori', msgs[0]);
                            if (field === 'jenis') tampilkanError('errCreateJenis', msgs[0]);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan.'
                        });
                    }
                    return;
                }
                data.data.menus = [];
                semuaKategori.push(data.data);
                terapkanFilter();
                tutupModal('createModal');
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

        async function hapusKategori(id) {
            const konfirmasi = await Swal.fire({
                title: 'Hapus kategori ini?',
                text: 'Kategori yang masih dipakai menu tidak bisa dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });
            if (!konfirmasi.isConfirmed) return;
            try {
                const res = await fetch(ROUTES.hapus(id), {
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
                semuaKategori = semuaKategori.filter(x => Number(x.id) !== Number(id));
                terapkanFilter();
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

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterSearch').addEventListener('input', terapkanFilter);
            document.getElementById('filterKategori').addEventListener('change', filterKategoriBerubah);
            document.getElementById('filterJenis').addEventListener('change', terapkanFilter);
            renderTabel(semuaKategori);
        });

        window.bukaDetail = bukaDetail;
        window.hapusKategori = hapusKategori;
        window.terapkanFilter = terapkanFilter;
        window.resetFilter = resetFilter;
        window.bukaModalTambah = bukaModalTambah;
        window.tutupModal = tutupModal;
        window.handleKategoriChange = handleKategoriChange;
        window.submitCreate = submitCreate;
        window.prevPage = prevPage;
        window.nextPage = nextPage;
    </script>
@endsection
