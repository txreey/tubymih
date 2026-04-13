@extends('kasir.layouts.app')
@section('title', 'Order Baru')
@section('content')
    <div class="flex h-[calc(100vh-64px)] bg-gray-50" id="orderApp">
        {{-- ════════════ LEFT PANEL ════════════ --}}
        <div class="flex-1 overflow-y-auto p-8 border-r border-gray-200">
            {{-- STEP BAR --}}
            <div class="flex items-center mb-8" id="stepBar"></div>

            {{-- ── SCR 1: Tipe Order ── --}}
            <div class="order-screen" id="scr1">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Mulai Order Baru</h2>
                <p class="text-sm text-gray-400 mb-6">Pilih tipe order untuk menentukan alur transaksi</p>
                <div class="grid grid-cols-2 gap-4">
                    {{-- Dine In --}}
                    <div id="cardDine" onclick="selectType('dine')"
                        class="relative border-2 border-gray-200 rounded-2xl p-7 cursor-pointer transition-all hover:border-gray-300 bg-white">
                        <div id="chkDine"
                            class="absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-400 text-black text-xs font-bold items-center justify-center hidden">
                            ✓</div>
                        <div class="text-4xl mb-4">🍽️</div>
                        <div class="text-lg font-bold text-gray-800 mb-2">Dine In</div>
                        <div class="text-xs text-gray-400 leading-relaxed">Pelanggan makan di tempat. Pilih meja terlebih
                            dahulu, lalu pesan menu. Pembayaran dilakukan <strong>setelah makan</strong>.</div>
                        <span
                            class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-600">Bayar
                            setelah makan</span>
                    </div>
                    {{-- Take Away --}}
                    <div id="cardTake" onclick="selectType('take')"
                        class="relative border-2 border-gray-200 rounded-2xl p-7 cursor-pointer transition-all hover:border-gray-300 bg-white">
                        <div id="chkTake"
                            class="absolute top-4 right-4 w-6 h-6 rounded-full bg-teal-400 text-black text-xs font-bold items-center justify-center hidden">
                            ✓</div>
                        <div class="text-4xl mb-4">🥡</div>
                        <div class="text-lg font-bold text-gray-800 mb-2">Take Away</div>
                        <div class="text-xs text-gray-400 leading-relaxed">Pesanan dibawa pulang. Pilih menu lalu kirim ke
                            dapur. Pembayaran dilakukan <strong>setelah pesanan siap</strong>.</div>
                        <span
                            class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-600">Bayar
                            setelah pesanan siap</span>
                    </div>
                </div>
            </div>

            {{-- ── SCR 2: Pilih Meja ── --}}
            <div class="order-screen hidden" id="scr2">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Pilih Meja</h2>
                <p class="text-sm text-gray-400 mb-1">Tap meja yang tersedia — bisa pilih <strong
                        class="text-gray-600">lebih dari satu</strong> untuk rombongan</p>
                <div id="selectedMejasBanner"
                    class="hidden mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap flex-1">
                        <span class="text-xs font-bold text-amber-700">✅ Meja dipilih:</span>
                        <div id="selectedMejasTags" class="flex flex-wrap gap-1.5"></div>
                    </div>
                    <span class="text-xs text-amber-600 font-semibold shrink-0" id="selectedMejasCount"></span>
                </div>
                <div class="grid grid-cols-4 gap-3 mb-5" id="mejaSummary"></div>
                <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                    <div class="flex gap-2">
                        <button
                            class="tbl-filter-btn px-4 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all"
                            onclick="filterMeja(this,'semua')">Semua</button>
                        <button
                            class="tbl-filter-btn px-4 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                            onclick="filterMeja(this,'lesehan')">🛖 Lesehan</button>
                        <button
                            class="tbl-filter-btn px-4 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                            onclick="filterMeja(this,'meja_kursi')">🪑 Meja Kursi</button>
                    </div>
                    <div class="flex items-center gap-4 text-xs text-gray-400">
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-sm border-2 border-amber-400 bg-amber-50 inline-block"></span>Dipilih</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-sm border-2 border-green-400 bg-green-50 inline-block"></span>Tersedia</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-sm border-2 border-red-300 bg-red-50 inline-block"></span>Terisi</span>
                    </div>
                </div>
                <div id="tableGrid" class="grid grid-cols-6 gap-3"></div>
            </div>

            {{-- ── SCR 3: Pilih Menu ── --}}
            <div class="order-screen hidden" id="scr3">
                <div class="flex items-start justify-between mb-1">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Pilih Menu</h2>
                        <p class="text-sm text-gray-400 mt-0.5" id="menuSub">Tambahkan item ke order</p>
                    </div>
                    <button id="btnBackToMeja" onclick="backToMeja()"
                        class="hidden items-center gap-1.5 px-4 py-2 rounded-xl border-2 border-amber-300 text-amber-600 text-xs font-semibold hover:bg-amber-50 transition-all">
                        ← Ganti Meja
                    </button>
                </div>
                <div id="selectedMejaInfoBar" class="hidden mb-4">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-700">
                        🪑 Meja: <span id="selectedMejaLabel" class="font-bold ml-1">-</span>
                    </span>
                </div>
                <div class="flex gap-2 mb-5 flex-wrap" id="menuFilterBar"></div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4" id="menuGrid"></div>
            </div>

            {{-- ── SCR 5: Konfirmasi Order ── --}}
            <div class="order-screen hidden" id="scr5">
                <div class="flex gap-6">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-1">Konfirmasi Order</h2>
                        <p class="text-sm text-gray-400 mb-5" id="paySub"></p>
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-5">
                            <div
                                class="grid grid-cols-12 px-4 py-2 bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <span class="col-span-6">Item</span>
                                <span class="col-span-2 text-center">Qty</span>
                                <span class="col-span-2 text-right">Harga</span>
                                <span class="col-span-2 text-right">Total</span>
                            </div>
                            <div id="payItemRows"></div>
                            <div class="px-4 py-3 border-t border-gray-100 space-y-1.5" id="payTotals"></div>
                        </div>
                        <div id="payActionBtns" class="grid gap-3"></div>
                    </div>
                    <div class="w-64 shrink-0">
                        <div class="bg-white border-2 border-gray-200 rounded-2xl p-5 sticky top-0">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Info Order</p>
                            <div class="space-y-3 mb-4">
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Kasir</label>
                                    <div class="text-sm font-semibold text-gray-800">{{ auth()->user()->nama ?? '-' }}
                                    </div>
                                </div>
                                <div id="payMejaInfo" class="hidden">
                                    <label class="text-xs text-gray-400 mb-1 block">Meja</label>
                                    <div class="text-sm font-semibold text-gray-800" id="payMejaVal">-</div>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-400 mb-1 block">Total Tagihan</label>
                                    <div class="text-lg font-bold text-gray-900" id="payTotalVal">Rp 0</div>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1 block">Nama Customer <span
                                            class="text-red-400">*</span></label>
                                    <input type="text" id="inputNamaCustomer" placeholder="Masukkan nama customer"
                                        oninput="validateNamaCustomer()"
                                        class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-medium text-gray-800 focus:outline-none focus:border-amber-400 transition-all">
                                    <p class="text-xs text-red-400 mt-1 hidden" id="namaCustomerError">Nama customer wajib
                                        diisi</p>
                                </div>
                            </div>
                            <div id="orderInfoBox"
                                class="rounded-xl p-3 bg-amber-50 border border-amber-200 text-xs text-amber-700 leading-relaxed">
                                🍳 Pesanan akan dikirim ke dapur. Pembayaran dilakukan <strong>setelah pelanggan
                                    selesai</strong> via menu <strong>Tagih</strong> di halaman Riwayat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SCR 6: Sukses ── --}}
            <div class="order-screen hidden" id="scr6">
                <div class="flex gap-6 items-start justify-center pt-8">
                    <div class="w-80 space-y-4">
                        <div id="successBox" class="border rounded-2xl p-6 text-center">
                            <div class="text-5xl mb-3" id="successIcon">🍳</div>
                            <div class="font-bold text-lg" id="successTitle">Sukses!</div>
                            <div class="text-sm mt-2" id="successSubText"></div>
                        </div>
                        <div id="successInfoBox" class="rounded-xl p-4 text-xs leading-relaxed hidden"></div>
                        <button onclick="resetAll()"
                            class="w-full py-3 rounded-xl border-2 border-teal-500 text-teal-600 font-bold text-sm hover:bg-teal-50 transition-all">+
                            Order Baru</button>
                        <a href="{{ route('kasir.riwayat') }}"
                            class="block w-full py-3 rounded-xl bg-teal-600 text-white font-bold text-sm hover:bg-teal-700 transition-all text-center">📋
                            Lihat Riwayat</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════ RIGHT PANEL (CART) ════════════ --}}
        <div class="w-80 flex flex-col bg-white border-l border-gray-200 shrink-0">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-gray-800">Keranjang</span>
                    <span id="cartBadge" class="px-2 py-0.5 rounded-full bg-amber-400 text-black text-xs font-bold">0
                        item</span>
                </div>
                <div class="mt-3 flex flex-wrap gap-2" id="cartMeta">
                    <span class="text-xs text-gray-400">Belum ada order</span>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-4" id="cartItems">
                <div class="h-full flex flex-col items-center justify-center text-gray-300 gap-3">
                    <span class="text-4xl">🛒</span>
                    <span class="text-sm">Keranjang kosong</span>
                </div>
            </div>
            <div id="cartCatSummary" class="hidden px-6 py-3 border-t border-gray-100 bg-gray-50">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Ringkasan Pesanan</p>
                <div id="catSummaryRows" class="space-y-1"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-400">Total</span>
                    <span class="text-xl font-bold text-gray-800" id="cartTotal">Rp 0</span>
                </div>
                <p class="text-xs text-gray-400 text-center leading-relaxed" id="flowHint">Pilih tipe order untuk memulai
                </p>
                <button id="btnNext" onclick="nextStep()" style="display:none;"
                    class="w-full py-3.5 rounded-xl font-bold text-sm transition-all mt-3">Lanjut →</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ALL_MEJAS = @json($mejas);
        const ALL_MENUS = @json($menus);

        let state = {
            type: null,
            tableIds: [],
            tableNomors: [],
            tableDetails: [],
            cart: [],
            step: 1,
            noTransaksi: null,
            namaCustomer: '',
            currentScr: '1',
        };

        // ══════════════════════════════════════════════
        // STEP BAR
        // ══════════════════════════════════════════════
        function getSteps() {
            if (state.type === 'dine_in') return ['Tipe Order', 'Pilih Meja', 'Pilih Menu', 'Konfirmasi'];
            if (state.type === 'take_away') return ['Tipe Order', 'Pilih Menu', 'Konfirmasi'];
            return ['Tipe Order'];
        }

        function scrToVisualStep(scr) {
            if (state.type === 'dine_in') {
                const map = {
                    '1': 1,
                    '2': 2,
                    '3': 3,
                    '5': 4
                };
                return map[String(scr)] || 1;
            }
            if (state.type === 'take_away') {
                const map = {
                    '1': 1,
                    '3': 2,
                    '5': 3
                };
                return map[String(scr)] || 1;
            }
            return 1;
        }

        function renderStepBar() {
            const steps = getSteps();
            const visual = scrToVisualStep(state.currentScr);
            document.getElementById('stepBar').innerHTML = steps.map((s, i) => {
                const num = i + 1;
                const isActive = num === visual;
                const isDone = num < visual;
                const numCls = isDone ? 'border-green-500 bg-green-500 text-white' :
                    isActive ? 'border-amber-400 bg-amber-400 text-black' :
                    'border-gray-300 text-gray-400';
                const labelCls = isDone ? 'text-green-500' : isActive ? 'text-gray-800' : 'text-gray-400';
                const lineCls = isDone ? 'bg-green-400' : 'bg-gray-300';
                let html = `<div class="flex items-center gap-2 text-xs font-medium ${labelCls}">
                <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all ${numCls}">
                    ${isDone ? '✓' : num}
                </div>
                <span>${s}</span>
            </div>`;
                if (i < steps.length - 1) html += `<div class="flex-1 h-px mx-3 transition-all ${lineCls}"></div>`;
                return html;
            }).join('');
        }

        // ══════════════════════════════════════════════
        // TIPE ORDER
        // ══════════════════════════════════════════════
        function selectType(t) {
            state.type = t === 'dine' ? 'dine_in' : 'take_away';
            const base = 'relative border-2 rounded-2xl p-7 cursor-pointer transition-all ';
            document.getElementById('cardDine').className = base + (t === 'dine' ? 'border-amber-400 bg-amber-50' :
                'border-gray-200 bg-white hover:border-gray-300');
            document.getElementById('cardTake').className = base + (t === 'take' ? 'border-teal-400 bg-teal-50' :
                'border-gray-200 bg-white hover:border-gray-300');
            ['Dine', 'Take'].forEach((x, i) => {
                const key = ['dine', 'take'][i];
                document.getElementById('chk' + x).className =
                    'absolute top-4 right-4 w-6 h-6 rounded-full text-xs font-bold items-center justify-center ' +
                    (t === key ? 'flex ' : 'hidden ') +
                    (x === 'Dine' ? 'bg-amber-400 text-black' : 'bg-teal-400 text-black');
            });
            const metaHtml = t === 'dine' ?
                '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>' :
                '<span class="px-2 py-1 rounded-full bg-teal-100 text-teal-600 text-xs font-semibold">🥡 Take Away</span>';
            document.getElementById('cartMeta').innerHTML = metaHtml;
            const hintMap = {
                dine: 'Pilih meja → menu → kirim dapur',
                take: 'Pilih menu → kirim dapur → bayar setelah siap',
            };
            document.getElementById('flowHint').textContent = hintMap[t];
            const btn = document.getElementById('btnNext');
            btn.style.display = 'block';
            btn.textContent = t === 'dine' ? 'Pilih Meja →' : 'Pilih Menu →';
            btn.className = 'w-full py-3.5 rounded-xl font-bold text-sm transition-all mt-3 ' +
                (t === 'dine' ? 'bg-amber-400 hover:bg-amber-500 text-black' :
                    'bg-teal-500 hover:bg-teal-600 text-white');
            renderStepBar();
        }

        // ══════════════════════════════════════════════
        // NAVIGASI
        // ══════════════════════════════════════════════
        function nextStep() {
            if (state.type === 'take_away') {
                if (state.currentScr === '1') goScreen('3');
                else if (state.currentScr === '3') goScreen('5');
            } else if (state.type === 'dine_in') {
                if (state.currentScr === '1') goScreen('2');
                else if (state.currentScr === '2') {
                    if (!state.tableIds.length) {
                        alertMeja();
                        return;
                    }
                    goScreen('3');
                } else if (state.currentScr === '3') goScreen('5');
            }
        }

        function alertMeja() {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Meja',
                text: 'Pilih minimal satu meja terlebih dahulu!',
                confirmButtonColor: '#f59e0b'
            });
        }

        function goScreen(n) {
            const nStr = String(n);
            document.querySelectorAll('.order-screen').forEach(s => s.classList.add('hidden'));
            const target = document.getElementById('scr' + nStr);
            if (target) target.classList.remove('hidden');
            state.currentScr = nStr;
            const stepMap = {
                '1': 1,
                '2': 2,
                '3': 3,
                '5': 5,
                '6': 6
            };
            state.step = stepMap[nStr] || parseInt(nStr) || 1;
            renderStepBar();
            updateNextBtn();

            if (nStr === '2') {
                renderMejaSummary();
                renderMejaGrid(activeMejaFilter);
            }
            if (nStr === '3') {
                initMenuFilters();
                renderMenuGrid('semua');
                const btnBack = document.getElementById('btnBackToMeja');
                const infoBar = document.getElementById('selectedMejaInfoBar');
                if (state.type === 'dine_in' && state.tableNomors.length) {
                    btnBack.classList.remove('hidden');
                    btnBack.classList.add('flex');
                    infoBar.classList.remove('hidden');
                    document.getElementById('selectedMejaLabel').textContent = state.tableNomors.join(', ');
                } else {
                    btnBack.classList.add('hidden');
                    btnBack.classList.remove('flex');
                    infoBar.classList.add('hidden');
                }
            }
            if (nStr === '5') renderPayment();
        }

        function backToMeja() {
            state.tableIds = [];
            state.tableNomors = [];
            state.tableDetails = [];
            document.getElementById('cartMeta').innerHTML =
                '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>';
            goScreen('2');
        }

        function updateNextBtn() {
            const btn = document.getElementById('btnNext');
            const hint = document.getElementById('flowHint');
            const base = 'w-full py-3.5 rounded-xl font-bold text-sm transition-all mt-3 ';
            const scr = state.currentScr;

            if (scr === '1') {
                btn.style.display = state.type ? 'block' : 'none';
                return;
            }
            if (scr === '5' || scr === '6') {
                btn.style.display = 'none';
                return;
            }
            if (scr === '2') {
                btn.style.display = 'block';
                btn.textContent = state.tableIds.length ? `Lanjut (${state.tableIds.length} meja) →` : 'Lanjut →';
                btn.disabled = !state.tableIds.length;
                btn.className = base +
                    'bg-amber-400 hover:bg-amber-500 text-black disabled:opacity-40 disabled:cursor-not-allowed';
                hint.textContent = state.tableIds.length ? state.tableNomors.join(', ') + ' dipilih' :
                    'Pilih minimal 1 meja';
                return;
            }
            if (scr === '3') {
                btn.style.display = 'block';
                btn.disabled = state.cart.length === 0;
                btn.textContent = 'Konfirmasi Order →';
                const color = state.type === 'dine_in' ? 'bg-amber-400 hover:bg-amber-500 text-black' :
                    'bg-teal-500 hover:bg-teal-600 text-white';
                btn.className = base + color + ' disabled:opacity-40 disabled:cursor-not-allowed';
                hint.textContent = state.cart.length ? 'Total: ' + formatRp(getTotal()) : 'Tambahkan menu';
                return;
            }
            btn.style.display = 'none';
            hint.textContent = '';
        }

        // ══════════════════════════════════════════════
        // MEJA
        // ══════════════════════════════════════════════
        function renderMejaSummary() {
            const lesehan = ALL_MEJAS.filter(m => m.tipe === 'lesehan' || m.tipe_meja === 'Lesehan');
            const kursi = ALL_MEJAS.filter(m => m.tipe === 'kursi' || m.tipe_meja === 'Meja Kursi');
            const tersedia = ALL_MEJAS.filter(m => m.status === 'tersedia').length;
            const terisi = ALL_MEJAS.filter(m => m.status === 'terisi').length;

            document.getElementById('mejaSummary').innerHTML = `
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-purple-100 flex items-center justify-center text-lg shrink-0">🛖</div>
                <div><div class="text-lg font-bold text-gray-800 leading-none">${lesehan.length}</div><div class="text-xs text-gray-400 mt-0.5">Lesehan</div></div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-lg shrink-0">🪑</div>
                <div><div class="text-lg font-bold text-gray-800 leading-none">${kursi.length}</div><div class="text-xs text-gray-400 mt-0.5">Meja Kursi</div></div>
            </div>
            <div class="bg-white border border-green-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center text-lg shrink-0">✅</div>
                <div><div class="text-lg font-bold text-green-600 leading-none">${tersedia}</div><div class="text-xs text-gray-400 mt-0.5">Tersedia</div></div>
            </div>
            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center text-lg shrink-0">🔴</div>
                <div><div class="text-lg font-bold text-red-500 leading-none">${terisi}</div><div class="text-xs text-gray-400 mt-0.5">Terisi</div></div>
            </div>`;
        }

        let activeMejaFilter = 'semua';

        function filterMeja(btn, tipe) {
            document.querySelectorAll('.tbl-filter-btn').forEach(b => {
                b.className =
                    'tbl-filter-btn px-4 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300';
            });
            btn.className =
                'tbl-filter-btn px-4 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all';
            activeMejaFilter = tipe;
            renderMejaGrid(tipe);
        }

        function getMejaTipe(meja) {
            return meja.tipe || meja.tipe_meja;
        }

        function renderMejaGrid(filter) {
            const filtered = filter === 'semua' ? ALL_MEJAS : ALL_MEJAS.filter(m => {
                const tipe = getMejaTipe(m);
                if (filter === 'lesehan') return tipe === 'Lesehan' || tipe === 'lesehan';
                if (filter === 'meja_kursi') return tipe === 'Meja Kursi' || tipe === 'kursi';
                return false;
            });

            document.getElementById('tableGrid').innerHTML = filtered.map(m => {
                const terisi = m.status === 'terisi';
                const isLesehan = getMejaTipe(m) === 'Lesehan' || getMejaTipe(m) === 'lesehan';
                const selected = state.tableIds.includes(m.id);
                const nomorMeja = m.nomor_meja || m.no_meja;

                let statusText = '',
                    statusColor = '',
                    disabledClick = false;
                if (terisi) {
                    statusText = 'Terisi';
                    statusColor = 'bg-red-100 text-red-500';
                    disabledClick = true;
                } else if (selected) {
                    statusText = 'Dipilih ✓';
                    statusColor = 'bg-amber-100 text-amber-600';
                } else {
                    statusText = 'Tersedia';
                    statusColor = 'bg-green-100 text-green-600';
                }

                const icon = isLesehan ?
                    (terisi ?
                        `<svg viewBox="0 0 48 36" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="4" y="24" width="40" height="5" rx="2" fill="#fca5a5" stroke="#ef4444" stroke-width="1.5"/><circle cx="13" cy="17" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><path d="M9 24 Q13 20 17 24" stroke="#ef4444" stroke-width="1.5" fill="none" stroke-linecap="round"/><circle cx="35" cy="17" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><path d="M31 24 Q35 20 39 24" stroke="#ef4444" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>` :
                        `<svg viewBox="0 0 48 36" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="4" y="24" width="40" height="5" rx="2" fill="#bbf7d0" stroke="#16a34a" stroke-width="1.5"/><rect x="8" y="14" width="10" height="10" rx="2" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="30" y="14" width="10" height="10" rx="2" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/></svg>`
                    ) :
                    (terisi ?
                        `<svg viewBox="0 0 48 40" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="8" y="16" width="32" height="8" rx="2" fill="#fca5a5" stroke="#ef4444" stroke-width="1.5"/><line x1="16" y1="24" x2="16" y2="34" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/><line x1="32" y1="24" x2="32" y2="34" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/><circle cx="18" cy="10" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><circle cx="30" cy="10" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/></svg>` :
                        `<svg viewBox="0 0 48 40" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="8" y="16" width="32" height="8" rx="2" fill="#bbf7d0" stroke="#16a34a" stroke-width="1.5"/><line x1="16" y1="24" x2="16" y2="34" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/><line x1="32" y1="24" x2="32" y2="34" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/><rect x="12" y="6" width="10" height="7" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="26" y="6" width="10" height="7" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="12" y="28" width="10" height="6" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="26" y="28" width="10" height="6" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/></svg>`
                    );

                const checkBadge = selected ?
                    `<div class="absolute top-1.5 left-1.5 w-5 h-5 rounded-full bg-amber-400 text-black text-xs font-bold flex items-center justify-center">✓</div>` :
                    '';
                const hasDeskripsi = m.deskripsi && m.deskripsi !== '-';
                const base = 'relative border-2 rounded-xl p-3 text-center transition-all ';
                let cls;
                if (disabledClick) cls = base + 'border-red-200 bg-red-50 opacity-60 cursor-not-allowed';
                else if (selected) cls = base + 'border-amber-400 bg-amber-50 ring-2 ring-amber-300 cursor-pointer';
                else cls = base +
                'border-gray-200 bg-white cursor-pointer hover:border-amber-400 hover:bg-amber-50';

                return `
                <div id="tbl_${m.id}" class="${cls}" onclick="${disabledClick ? '' : `toggleTable(${m.id}, '${nomorMeja}', '${getMejaTipe(m)}', '${escHtml(m.deskripsi || '')}')`}">
                    ${checkBadge}
                    ${icon}
                    <div class="text-sm font-bold text-gray-800">${nomorMeja}</div>
                    <div class="text-xs text-gray-400 mt-0.5">${isLesehan ? 'Lesehan' : 'Meja Kursi'} ${m.kapasitas} org</div>
                    ${hasDeskripsi ? `<div class="text-xs text-gray-400 mt-0.5 truncate">${escHtml(m.deskripsi).substring(0,20)}${m.deskripsi.length>20?'...':''}</div>` : ''}
                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-semibold ${statusColor}">${statusText}</span>
                </div>`;
            }).join('');
        }

        function escHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function toggleTable(id, nomor, tipe, deskripsi) {
            const meja = ALL_MEJAS.find(m => (m.nomor_meja || m.no_meja) === nomor);
            if (meja && meja.status === 'terisi') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Meja Tidak Tersedia',
                    text: 'Meja sedang terisi!',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }
            const idx = state.tableIds.indexOf(id);
            if (idx === -1) {
                state.tableIds.push(id);
                state.tableNomors.push(nomor);
                state.tableDetails.push({
                    id,
                    nomor,
                    tipe,
                    deskripsi
                });
            } else {
                state.tableIds.splice(idx, 1);
                state.tableNomors.splice(idx, 1);
                state.tableDetails.splice(idx, 1);
            }
            renderMejaGrid(activeMejaFilter);
            updateSelectedMejasBanner();
            updateNextBtn();
        }

        function updateSelectedMejasBanner() {
            const banner = document.getElementById('selectedMejasBanner');
            const tags = document.getElementById('selectedMejasTags');
            const count = document.getElementById('selectedMejasCount');
            if (!state.tableNomors.length) {
                banner.classList.add('hidden');
                return;
            }
            banner.classList.remove('hidden');
            tags.innerHTML = state.tableDetails.map(t =>
                `<span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 text-xs font-bold">${t.nomor}</span>`
            ).join('');
            count.textContent = `${state.tableNomors.length} meja`;
            const typeLabel = state.type === 'dine_in' ?
                '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>' :
                '<span class="px-2 py-1 rounded-full bg-teal-100 text-teal-600 text-xs font-semibold">🥡 Take Away</span>';
            document.getElementById('cartMeta').innerHTML = typeLabel +
                `<span class="px-2 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">🪑 ${state.tableNomors.join(', ')}</span>`;
            document.getElementById('menuSub').textContent = `Order untuk Meja ${state.tableNomors.join(', ')}`;
        }

        // ══════════════════════════════════════════════
        // MENU
        // ══════════════════════════════════════════════
        let activeFilter = 'semua';

        function initMenuFilters() {
            const kategoris = ['Semua', ...new Set(ALL_MENUS.map(m => m.kategori))];
            document.getElementById('menuFilterBar').innerHTML = kategoris.map((k, i) => {
                const val = k === 'Semua' ? 'semua' : k;
                return `<button class="filter-btn px-4 py-1.5 rounded-full border-2 ${i === 0 ? 'border-teal-500 bg-teal-500 text-white' : 'border-gray-200 text-gray-400 hover:border-gray-300'} text-xs font-semibold transition-all" onclick="filterMenu(this,'${val}')">${k}</button>`;
            }).join('');
        }

        function filterMenu(btn, cat) {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.className =
                    'filter-btn px-4 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300';
            });
            btn.className =
                'filter-btn px-4 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all';
            activeFilter = cat;
            renderMenuGrid(cat);
        }

        function renderMenuGrid(cat) {
            const filtered = cat === 'semua' ? ALL_MENUS : ALL_MENUS.filter(m => m.kategori === cat);

            document.getElementById('menuGrid').innerHTML = filtered.map(m => {
                const inCart = state.cart.find(c => c.id === m.id);
                const qtyInCart = inCart ? inCart.qty : 0;

                const stokAsli = m.stok;

                const sisaStok = stokAsli - qtyInCart; 

                // ── Tentukan status menu ──────────────────────────────────
                const isNonaktif = m.is_aktif === false;
                const isHabis = !isNonaktif && stokAsli <= 0; 
                const bisa = !isNonaktif && !isHabis;

                // ── Gambar ───────────────────────────────────────────────
                const imageHtml = m.gambar ?
                    `<img src="/storage/${m.gambar}" class="w-full h-44 object-cover rounded-t-2xl" alt="${m.nama}" onerror="this.src='https://placehold.co/400x300/e2e8f0/94a3b8?text=${encodeURIComponent(m.nama.substring(0,15))}';">` :
                    `<div class="w-full h-44 bg-gradient-to-br from-amber-50 to-orange-50 flex items-center justify-center text-7xl rounded-t-2xl">${m.emoji || '🍽️'}</div>`;

                // ── Badge stok ──────────────────────────────────────────
                let stokBadge = '';
                if (isNonaktif) {
                    stokBadge =
                        `<span class="text-xs font-semibold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Tidak Tersedia</span>`;
                } else if (isHabis) {
                    stokBadge =
                        `<span class="text-xs font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full">Stok Habis</span>`;
                } else if (sisaStok <= 0) {
                    stokBadge =
                        `<span class="text-xs font-semibold text-orange-500 bg-orange-50 px-2 py-0.5 rounded-full">Maks. di keranjang</span>`;
                } else {
                    stokBadge = `<span class="text-xs font-medium text-gray-500">Sisa ${sisaStok}</span>`;
                }

                // ── Tombol / badge qty ────────────────────────────────────
                let actionHtml = '';
                if (bisa) {
                    if (qtyInCart > 0) {
                        actionHtml =
                            `<div class="absolute top-3 right-3 bg-amber-500 text-white text-xs font-bold w-7 h-7 flex items-center justify-center rounded-full shadow">${qtyInCart}</div>`;
                    } else {
                        actionHtml =
                            `<div onclick="addToCart(${m.id}); event.stopImmediatePropagation()" class="absolute top-3 right-3 bg-amber-500 hover:bg-amber-600 w-8 h-8 flex items-center justify-center text-2xl text-white rounded-full shadow transition-all">+</div>`;
                    }
                }

                // ── nonaktif & habis ─────────────────
                const overlayHtml = (isNonaktif || isHabis) ?
                    `<div class="absolute inset-0 bg-white/60 rounded-2xl pointer-events-none"></div>` : '';

                // ── Handler klik ─────────────────────────────────────────
                let onclickAttr = '';
                if (isNonaktif) {
                    onclickAttr = `onclick="alertMenuNonaktif('${m.nama.replace(/'/g, "\\'")}')"`;
                } else if (isHabis) {
                    onclickAttr = `onclick="alertMenuHabis('${m.nama.replace(/'/g, "\\'")}')"`;
                } else {
                    onclickAttr = `onclick="addToCart(${m.id})"`;
                }

                // ── Card wrapper class ────────────────────────────────────
                const cardClass = bisa ?
                    'group relative border border-gray-200 hover:border-amber-300 bg-white rounded-2xl overflow-hidden cursor-pointer transition-all hover:shadow-md' :
                    'group relative border border-gray-200 bg-white rounded-2xl overflow-hidden cursor-pointer transition-all opacity-70';

                return `
                <div ${onclickAttr} class="${cardClass}">
                    ${overlayHtml}
                    ${imageHtml}
                    <div class="p-3">
                        <div class="text-xs text-gray-500 capitalize mb-1">${m.kategori || '-'}</div>
                        <div class="font-semibold text-gray-800 text-sm leading-tight line-clamp-2 min-h-[40px]">${m.nama}</div>
                        <div class="mt-3 text-lg font-bold ${bisa ? 'text-amber-600' : 'text-gray-400'}">${formatRp(m.harga)}</div>
                    </div>
                    <div class="px-3 pb-3 flex justify-between items-center text-xs">
                        <div>${stokBadge}</div>
                    </div>
                    ${actionHtml}
                </div>`;
            }).join('');
        }

        function alertMenuNonaktif(nama) {
            Swal.fire({
                icon: 'info',
                title: 'Menu Tidak Tersedia',
                text: `"${nama}" sedang dinonaktifkan oleh admin dan tidak bisa dipesan saat ini.`,
                confirmButtonColor: '#6b7280',
                confirmButtonText: 'Mengerti'
            });
        }

        function alertMenuHabis(nama) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Habis',
                text: `"${nama}" sedang kehabisan stok dan tidak bisa dipesan saat ini.`,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti'
            });
        }

        function warnStokHabis(nama) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Habis',
                text: `Stok "${nama}" sudah habis — tidak bisa menambah lagi.`,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti'
            });
        }

        // ══════════════════════════════════════════════
        // CART — addToCart (FIXED)
        // ══════════════════════════════════════════════
        function addToCart(id) {
            const menu = ALL_MENUS.find(m => m.id === id);
            if (!menu) return;

            // Cek nonaktif
            if (menu.is_aktif === false) {
                alertMenuNonaktif(menu.nama);
                return;
            }

            // Cek stok asli = 0
            if (menu.stok <= 0) {
                alertMenuHabis(menu.nama);
                return;
            }

            const existing = state.cart.find(c => c.id === id);
            const qtyInCart = existing ? existing.qty : 0;

            if (qtyInCart >= menu.stok) {
                warnStokHabis(menu.nama);
                return;
            }

            if (existing) {
                existing.qty++;
            } else {
                state.cart.push({
                    id: menu.id,
                    nama: menu.nama,
                    harga: menu.harga,
                    kategori: menu.kategori,
                    stok: menu.stok,
                    emoji: menu.emoji,
                    qty: 1,
                });
            }

            renderMenuGrid(activeFilter);
            renderCart();
            updateNextBtn();
        }

        // ══════════════════════════════════════════════
        // CART — render & changeQty (FIXED)
        // ══════════════════════════════════════════════
        function renderCart() {
            const totQty = state.cart.reduce((s, c) => s + c.qty, 0);
            document.getElementById('cartBadge').textContent = totQty + ' item';
            document.getElementById('cartTotal').textContent = formatRp(getTotal());
            const el = document.getElementById('cartItems');
            if (!state.cart.length) {
                el.innerHTML =
                    `<div class="h-full flex flex-col items-center justify-center text-gray-300 gap-3"><span class="text-4xl">🛒</span><span class="text-sm">Keranjang kosong</span></div>`;
                document.getElementById('cartCatSummary').classList.add('hidden');
                return;
            }
            el.innerHTML = state.cart.map(item => `
            <div class="flex items-center justify-between py-3 border-b border-gray-100 gap-3">
                <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-400 capitalize">${item.kategori}</div>
                    <div class="text-sm font-medium text-gray-800 truncate">${item.nama}</div>
                    <div class="text-xs text-gray-400">${formatRp(item.harga)} × ${item.qty} = ${formatRp(item.harga * item.qty)}</div>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <button onclick="changeQty(${item.id},-1)" class="w-6 h-6 rounded-md bg-gray-100 border border-gray-200 text-gray-600 flex items-center justify-center text-sm hover:bg-gray-200">−</button>
                    <span class="text-sm font-bold text-gray-800 w-5 text-center">${item.qty}</span>
                    <button onclick="changeQty(${item.id},1)" class="w-6 h-6 rounded-md bg-gray-100 border border-gray-200 text-gray-600 flex items-center justify-center text-sm hover:bg-gray-200">+</button>
                </div>
            </div>`).join('');
            const catMap = {};
            state.cart.forEach(i => {
                catMap[i.kategori] = (catMap[i.kategori] || 0) + i.harga * i.qty;
            });
            document.getElementById('cartCatSummary').classList.remove('hidden');
            document.getElementById('catSummaryRows').innerHTML = Object.entries(catMap).map(([cat, tot]) =>
                `<div class="flex justify-between text-xs"><span class="text-gray-500 capitalize">${cat}</span><span class="font-semibold text-gray-700">${formatRp(tot)}</span></div>`
            ).join('');
        }

        function changeQty(id, delta) {
            const idx = state.cart.findIndex(c => c.id === id);
            if (idx === -1) return;
            const menu = ALL_MENUS.find(m => m.id === id);

            if (delta > 0) {
                const stokAsli = menu ? menu.stok : 0;
                const qtyInCart = state.cart[idx].qty;
                if (qtyInCart >= stokAsli) {
                    warnStokHabis(state.cart[idx].nama);
                    return;
                }
            }

            state.cart[idx].qty += delta;
            if (state.cart[idx].qty <= 0) state.cart.splice(idx, 1);
            if (state.currentScr === '3') renderMenuGrid(activeFilter);
            renderCart();
            updateNextBtn();
        }

        // ══════════════════════════════════════════════
        // KONFIRMASI ORDER (SCR 5)
        // ══════════════════════════════════════════════
        function renderPayment() {
            const isDine = state.type === 'dine_in';
            const total = getTotal();
            const mejaLabel = state.tableNomors.join(', ') || '-';
            document.getElementById('paySub').innerHTML = isDine ?
                `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">🍽️ Dine In · Meja ${mejaLabel}</span>` :
                `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-semibold">🥡 Take Away</span>`;
            const mejaBox = document.getElementById('payMejaInfo');
            if (isDine) {
                mejaBox.classList.remove('hidden');
                document.getElementById('payMejaVal').textContent = mejaLabel;
            } else mejaBox.classList.add('hidden');
            document.getElementById('payTotalVal').textContent = formatRp(total);
            document.getElementById('inputNamaCustomer').value = '';
            document.getElementById('namaCustomerError').classList.add('hidden');
            document.getElementById('payItemRows').innerHTML = state.cart.map(c =>
                `<div class="grid grid-cols-12 px-4 py-2.5 border-b border-gray-50 text-sm">
                <span class="col-span-6 text-gray-700">${c.nama}<br><span class="text-xs text-gray-400">${formatRp(c.harga)}</span></span>
                <span class="col-span-2 text-center text-gray-500">x${c.qty}</span>
                <span class="col-span-2 text-right text-gray-500">${formatRp(c.harga)}</span>
                <span class="col-span-2 text-right font-semibold text-gray-800">${formatRp(c.harga * c.qty)}</span>
            </div>`).join('');
            document.getElementById('payTotals').innerHTML =
                `<div class="flex justify-between text-sm text-gray-500"><span>Subtotal (${state.cart.reduce((s,c)=>s+c.qty,0)} item)</span><span>${formatRp(total)}</span></div>
             <div class="flex justify-between text-base font-bold text-gray-900 pt-1"><span>TOTAL</span><span>${formatRp(total)}</span></div>`;
            document.getElementById('payActionBtns').className = 'grid grid-cols-3 gap-3';
            document.getElementById('payActionBtns').innerHTML =
                `<button onclick="batalOrder()" class="py-3 rounded-xl border-2 border-red-200 text-red-500 font-semibold text-sm hover:bg-red-50 transition-all">✕ Batal</button>
             <button onclick="tambahMenu()" class="py-3 rounded-xl border-2 border-amber-300 text-amber-600 font-semibold text-sm hover:bg-amber-50 transition-all">+ Tambah Menu</button>
             <button id="btnKirimDapur" onclick="kirimDapur()" class="py-3 rounded-xl bg-orange-500 text-white font-bold text-sm hover:bg-orange-600 transition-all">🍳 Kirim Dapur</button>`;
        }

        function validateNamaCustomer() {
            const val = document.getElementById('inputNamaCustomer').value.trim();
            const errEl = document.getElementById('namaCustomerError');
            if (!val) {
                errEl.classList.remove('hidden');
                return false;
            }
            errEl.classList.add('hidden');
            state.namaCustomer = val;
            return true;
        }

        function batalOrder() {
            Swal.fire({
                title: 'Batalkan Order?',
                text: 'Semua item di keranjang akan dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batal',
                cancelButtonText: 'Tidak'
            }).then(r => {
                if (r.isConfirmed) resetAll();
            });
        }

        function tambahMenu() {
            goScreen('3');
        }

        // ══════════════════════════════════════════════
        // KIRIM DAPUR
        // ══════════════════════════════════════════════
        function kirimDapur() {
            const nama = document.getElementById('inputNamaCustomer').value.trim();
            if (!nama) {
                document.getElementById('namaCustomerError').classList.remove('hidden');
                document.getElementById('inputNamaCustomer').focus();
                return;
            }
            state.namaCustomer = nama;
            const btn = document.getElementById('btnKirimDapur');
            btn.disabled = true;
            btn.textContent = 'Mengirim...';
            fetch('{{ route('kasir.order.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        tipe_order: state.type,
                        id_mejas: state.tableIds,
                        nama_pelanggan: state.namaCustomer,
                        bayar_nanti: true,
                        items: state.cart.map(c => ({
                            id: c.id,
                            qty: c.qty,
                            harga: c.harga,
                        })),
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        state.noTransaksi = data.no_transaksi;
                        showSukses();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                        btn.disabled = false;
                        btn.textContent = '🍳 Kirim Dapur';
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan koneksi.',
                        confirmButtonColor: '#ef4444'
                    });
                    btn.disabled = false;
                    btn.textContent = '🍳 Kirim Dapur';
                });
        }

        // ══════════════════════════════════════════════
        // SUKSES (SCR 6)
        // ══════════════════════════════════════════════
        function showSukses() {
            const isDine = state.type === 'dine_in';
            const mejaInfo = state.tableNomors.length ? ` · Meja ${state.tableNomors.join(', ')}` : '';
            document.getElementById('successBox').className =
                'bg-green-50 border border-green-200 rounded-2xl p-6 text-center';
            document.getElementById('successIcon').textContent = '🍳';
            document.getElementById('successTitle').className = 'font-bold text-green-700 text-lg';
            document.getElementById('successTitle').textContent = 'Order Terkirim ke Dapur!';
            document.getElementById('successSubText').className = 'text-sm text-green-600 mt-2';
            document.getElementById('successSubText').textContent =
                `${isDine ? '🍽️ Dine In' : '🥡 Take Away'}${mejaInfo} · ${state.namaCustomer}`;
            const infoBox = document.getElementById('successInfoBox');
            infoBox.classList.remove('hidden');
            infoBox.className = 'bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-700 leading-relaxed';
            infoBox.innerHTML = `<p class="font-bold mb-2">💡 Cara menagih setelah selesai:</p>
            <ol class="list-decimal pl-4 space-y-1">
                <li>Buka menu <strong>Riwayat Transaksi</strong></li>
                <li>Cari order <strong>${state.noTransaksi || '-'}</strong></li>
                <li>Klik tombol <strong class="text-green-700">💳 Tagih</strong></li>
                <li>Masukkan jumlah bayar & konfirmasi</li>
            </ol>`;
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('flowHint').textContent = '';
            goScreen('6');
        }

        // ══════════════════════════════════════════════
        // RESET
        // ══════════════════════════════════════════════
        function resetAll() {
            state = {
                type: null,
                tableIds: [],
                tableNomors: [],
                tableDetails: [],
                cart: [],
                step: 1,
                noTransaksi: null,
                namaCustomer: '',
                currentScr: '1',
            };
            const base = 'relative border-2 rounded-2xl p-7 cursor-pointer transition-all ';
            document.getElementById('cardDine').className = base + 'border-gray-200 bg-white hover:border-gray-300';
            document.getElementById('cardTake').className = base + 'border-gray-200 bg-white hover:border-gray-300';
            ['Dine', 'Take'].forEach(x => {
                document.getElementById('chk' + x).className = document.getElementById('chk' + x).className.replace(
                    'flex', 'hidden');
            });
            document.getElementById('cartMeta').innerHTML = '<span class="text-xs text-gray-400">Belum ada order</span>';
            document.getElementById('cartTotal').textContent = 'Rp 0';
            document.getElementById('cartBadge').textContent = '0 item';
            document.getElementById('flowHint').textContent = 'Pilih tipe order untuk memulai';
            document.getElementById('btnNext').style.display = 'none';
            renderCart();
            renderStepBar();
            document.querySelectorAll('.order-screen').forEach(s => s.classList.add('hidden'));
            document.getElementById('scr1').classList.remove('hidden');
        }

        // ══════════════════════════════════════════════
        // HELPERS
        // ══════════════════════════════════════════════
        function getTotal() {
            return state.cart.reduce((s, c) => s + c.harga * c.qty, 0);
        }

        function formatRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function getAvailableStok(menuId) {
            const menu = ALL_MENUS.find(m => m.id === menuId);
            if (!menu) return 0;
            return menu.stok;
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderStepBar();
        });
        renderStepBar();
    </script>
@endpush
