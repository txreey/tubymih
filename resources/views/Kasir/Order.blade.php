{{-- resources/views/kasir/order.blade.php --}}
@extends('kasir.layouts.app')

@section('title', 'Order Baru')

@section('content')
    <div class="flex h-[calc(100vh-64px)] bg-gray-50" id="orderApp">

        {{-- ════════════ LEFT PANEL ════════════ --}}
        <div class="flex-1 overflow-y-auto p-8 border-r border-gray-200">

            {{-- STEP BAR --}}
            <div class="flex items-center mb-8" id="stepBar">
                {{-- Steps dirender via JS sesuai tipe order --}}
            </div>

            {{-- ── SCR 1: Tipe Order ── --}}
            <div class="order-screen" id="scr1">
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Mulai Order Baru</h2>
                <p class="text-sm text-gray-400 mb-6">Pilih tipe order untuk menentukan alur transaksi</p>
                <div class="grid grid-cols-2 gap-4">
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
                    <div id="cardTake" onclick="selectType('take')"
                        class="relative  border-2 border-gray-200 rounded-2xl p-7 cursor-pointer transition-all hover:border-gray-300 bg-white">
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

            {{-- ── SCR 2: Pilih Meja (hanya Dine In) ── --}}
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
                <div class="grid grid-cols-4 gap-3" id="menuGrid"></div>
            </div>

            {{-- ── SCR 4: Konfirmasi Order ── --}}
            <div class="order-screen hidden" id="scr4">
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

                    {{-- Info Order Panel --}}
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
                                {{-- Nama Customer (wajib) --}}
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 mb-1 block">
                                        Nama Customer <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" id="inputNamaCustomer" placeholder="Masukkan nama customer"
                                        oninput="validateNamaCustomer()"
                                        class="w-full border-2 border-gray-200 rounded-xl px-3 py-2 text-sm font-medium text-gray-800 focus:outline-none focus:border-amber-400 transition-all">
                                    <p class="text-xs text-red-400 mt-1 hidden" id="namaCustomerError">Nama customer wajib
                                        diisi</p>
                                </div>
                            </div>

                            {{-- Info box: berlaku untuk dine-in & take-away --}}
                            <div id="orderInfoBox"
                                class="rounded-xl p-3 bg-amber-50 border border-amber-200 text-xs text-amber-700 leading-relaxed">
                                🍳 Pesanan akan dikirim ke dapur.<br>
                                Pembayaran dilakukan <strong>setelah pelanggan selesai</strong> via menu
                                <strong>Tagih</strong> di halaman Riwayat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SCR 5: Sukses (Kirim Dapur berhasil) ── --}}
            <div class="order-screen hidden" id="scr5">
                <div class="flex gap-6 items-start justify-center pt-8">
                    <div class="w-80 space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                            <div class="text-5xl mb-3">🍳</div>
                            <div class="font-bold text-green-700 text-lg">Order Terkirim ke Dapur!</div>
                            <div class="text-sm text-green-600 mt-2" id="successSubText"></div>
                        </div>
                        <div
                            class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-700 leading-relaxed">
                            <p class="font-bold mb-2">💡 Cara menagih setelah selesai:</p>
                            <ol class="list-decimal pl-4 space-y-1">
                                <li>Buka menu <strong>Riwayat Transaksi</strong></li>
                                <li>Cari order <strong id="successNoTransaksi">-</strong></li>
                                <li>Klik tombol <strong class="text-green-700">💳 Tagih</strong></li>
                                <li>Masukkan jumlah bayar & konfirmasi</li>
                            </ol>
                        </div>
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
        // Data dari server
        const ALL_MEJAS = @json($mejas);
        const ALL_MENUS = @json($menus);

        let state = {
            type: null,
            tableIds: [],
            tableNomors: [],
            tableDetails: [], // Menyimpan detail meja (termasuk deskripsi)
            cart: [],
            step: 1,
            noTransaksi: null,
            namaCustomer: '',
        };

        // ══════════════════════════════════════════════
        // STEP BAR
        // ══════════════════════════════════════════════
        function renderStepBar() {
            const isDine = state.type === 'dine_in';
            const stepBar = document.getElementById('stepBar');

            if (!state.type) {
                stepBar.innerHTML = buildStepBarHTML([{
                        label: 'Tipe Order'
                    },
                    {
                        label: 'Pilih Meja'
                    },
                    {
                        label: 'Pilih Menu'
                    },
                    {
                        label: 'Konfirmasi'
                    },
                ], 1);
                return;
            }

            if (isDine) {
                stepBar.innerHTML = buildStepBarHTML([{
                        label: 'Tipe Order'
                    },
                    {
                        label: 'Pilih Meja'
                    },
                    {
                        label: 'Pilih Menu'
                    },
                    {
                        label: 'Konfirmasi'
                    },
                ], scrToVisualStep(state.step));
            } else {
                stepBar.innerHTML = buildStepBarHTML([{
                        label: 'Tipe Order'
                    },
                    {
                        label: 'Pilih Menu'
                    },
                    {
                        label: 'Konfirmasi'
                    },
                ], scrToVisualStep(state.step));
            }
        }

        function buildStepBarHTML(steps, currentVisual) {
            return steps.map((s, i) => {
                const num = i + 1;
                const isActive = num === currentVisual;
                const isDone = num < currentVisual;
                const numCls = isDone ?
                    'border-green-500 bg-green-500 text-white' :
                    isActive ?
                    'border-amber-400 bg-amber-400 text-black' :
                    'border-gray-300 text-gray-400';
                const labelCls = isDone ? 'text-green-500' : isActive ? 'text-gray-800' : 'text-gray-400';
                const lineCls = isDone ? 'bg-green-400' : 'bg-gray-300';

                let html = `<div class="flex items-center gap-2 text-xs font-medium ${labelCls}">
                <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center text-xs font-bold transition-all ${numCls}">${isDone ? '✓' : num}</div>
                <span>${s.label}</span>
            </div>`;
                if (i < steps.length - 1) {
                    html += `<div class="flex-1 h-px mx-3 transition-all ${lineCls}"></div>`;
                }
                return html;
            }).join('');
        }

        function scrToVisualStep(scr) {
            const isDine = state.type === 'dine_in';
            if (isDine) {
                return scr;
            } else {
                if (scr === 1) return 1;
                if (scr === 3) return 2;
                if (scr === 4) return 3;
                if (scr === 5) return 3;
                return 1;
            }
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
            document.getElementById('chkDine').className =
                'absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-400 text-black text-xs font-bold items-center justify-center ' +
                (t === 'dine' ? 'flex' : 'hidden');
            document.getElementById('chkTake').className =
                'absolute top-4 right-4 w-6 h-6 rounded-full bg-teal-400 text-black text-xs font-bold items-center justify-center ' +
                (t === 'take' ? 'flex' : 'hidden');
            document.getElementById('cartMeta').innerHTML = t === 'dine' ?
                '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>' :
                '<span class="px-2 py-1 rounded-full bg-teal-100 text-teal-600 text-xs font-semibold">🥡 Take Away</span>';
            document.getElementById('flowHint').textContent = t === 'dine' ?
                'Pilih meja → menu → kirim dapur' :
                'Pilih menu → kirim dapur → bayar setelah siap';
            const btn = document.getElementById('btnNext');
            btn.style.display = 'block';
            btn.textContent = t === 'dine' ? 'Pilih Meja →' : 'Pilih Menu →';
            btn.className = 'w-full py-3.5 rounded-xl font-bold text-sm transition-all mt-3 ' +
                (t === 'dine' ? 'bg-amber-400 hover:bg-amber-500 text-black' : 'bg-teal-500 hover:bg-teal-600 text-white');
            renderStepBar();
        }

        // ══════════════════════════════════════════════
        // NAVIGASI
        // ══════════════════════════════════════════════
        function nextStep() {
            if (state.type === 'take_away') {
                if (state.step === 1) goScreen(3);
                else if (state.step === 3) goScreen(4);
            } else {
                if (state.step === 1) goScreen(2);
                else if (state.step === 2) {
                    if (!state.tableIds.length) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Meja',
                            text: 'Pilih minimal satu meja terlebih dahulu!',
                            confirmButtonColor: '#f59e0b'
                        });
                        return;
                    }
                    goScreen(3);
                } else if (state.step === 3) goScreen(4);
            }
        }

        function goScreen(n) {
            document.querySelectorAll('.order-screen').forEach(s => s.classList.add('hidden'));
            document.getElementById('scr' + n).classList.remove('hidden');
            state.step = n;
            renderStepBar();
            updateNextBtn();
            if (n === 2) {
                renderMejaSummary();
                renderMejaGrid(activeMejaFilter);
            }
            if (n === 3) {
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
            if (n === 4) renderPayment();
        }

        function backToMeja() {
            state.tableIds = [];
            state.tableNomors = [];
            state.tableDetails = [];
            document.getElementById('cartMeta').innerHTML =
                '<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>';
            goScreen(2);
        }

        function updateNextBtn() {
            const btn = document.getElementById('btnNext');
            const hint = document.getElementById('flowHint');
            const base = 'w-full py-3.5 rounded-xl font-bold text-sm transition-all mt-3 ';
            const isDine = state.type === 'dine_in';

            if (state.step === 1) {
                btn.style.display = state.type ? 'block' : 'none';
                return;
            }
            if (state.step === 2) {
                btn.style.display = 'block';
                btn.textContent = state.tableIds.length ? `Lanjut ke Menu (${state.tableIds.length} meja) →` :
                    'Lanjut ke Menu →';
                btn.disabled = !state.tableIds.length;
                btn.className = base +
                    'bg-amber-400 hover:bg-amber-500 text-black disabled:opacity-40 disabled:cursor-not-allowed';
                hint.textContent = state.tableIds.length ? `${state.tableNomors.join(', ')} dipilih` :
                    'Pilih minimal 1 meja';
                return;
            }
            if (state.step === 3) {
                btn.style.display = 'block';
                btn.disabled = state.cart.length === 0;
                btn.textContent = 'Konfirmasi Order →';
                btn.className = base + 'disabled:opacity-40 disabled:cursor-not-allowed ' +
                    (isDine ? 'bg-amber-400 hover:bg-amber-500 text-black' : 'bg-teal-500 hover:bg-teal-600 text-white');
                hint.textContent = state.cart.length ? 'Total: ' + formatRp(getTotal()) : 'Tambahkan menu';
                return;
            }
            btn.style.display = 'none';
            hint.textContent = '';
        }

        // ══════════════════════════════════════════════
        // MEJA (MULTI-SELECT)
        // ══════════════════════════════════════════════
        function renderMejaSummary() {
            // Pastikan data meja memiliki properti yang sesuai
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
            </div>
        `;
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
            // Mendukung kedua format data (tipe atau tipe_meja)
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
                const kapasitas = m.kapasitas;
                const deskripsi = m.deskripsi || '-';

                const icon = isLesehan ?
                    (terisi ?
                        `<svg viewBox="0 0 48 36" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="4" y="24" width="40" height="5" rx="2" fill="#fca5a5" stroke="#ef4444" stroke-width="1.5"/><circle cx="13" cy="17" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><path d="M9 24 Q13 20 17 24" stroke="#ef4444" stroke-width="1.5" fill="none" stroke-linecap="round"/><circle cx="35" cy="17" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><path d="M31 24 Q35 20 39 24" stroke="#ef4444" stroke-width="1.5" fill="none" stroke-linecap="round"/></svg>` :
                        `<svg viewBox="0 0 48 36" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="4" y="24" width="40" height="5" rx="2" fill="#bbf7d0" stroke="#16a34a" stroke-width="1.5"/><rect x="8" y="14" width="10" height="10" rx="2" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="30" y="14" width="10" height="10" rx="2" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/></svg>`
                    ) :
                    (terisi ?
                        `<svg viewBox="0 0 48 40" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="8" y="16" width="32" height="8" rx="2" fill="#fca5a5" stroke="#ef4444" stroke-width="1.5"/><line x1="16" y1="24" x2="16" y2="34" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/><line x1="32" y1="24" x2="32" y2="34" stroke="#ef4444" stroke-width="1.5" stroke-linecap="round"/><circle cx="18" cy="10" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/><circle cx="30" cy="10" r="4" fill="#fca5a5" stroke="#ef4444" stroke-width="1.2"/></svg>` :
                        `<svg viewBox="0 0 48 40" class="w-10 h-8 mx-auto mb-2" fill="none"><rect x="8" y="16" width="32" height="8" rx="2" fill="#bbf7d0" stroke="#16a34a" stroke-width="1.5"/><line x1="16" y1="24" x2="16" y2="34" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/><line x1="32" y1="24" x2="32" y2="34" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round"/><rect x="12" y="6" width="10" height="7" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="26" y="6" width="10" height="7" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="12" y="28" width="10" height="6" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/><rect x="26" y="28" width="10" height="6" rx="1.5" stroke="#16a34a" stroke-width="1.5" stroke-dasharray="3 2"/></svg>`
                    );

                const base = 'relative border-2 rounded-xl p-3 text-center transition-all ';
                let cls;
                if (terisi) cls = base + 'border-red-200 bg-red-50 opacity-60 cursor-not-allowed';
                else if (selected) cls = base + 'border-amber-400 bg-amber-50 cursor-pointer ring-2 ring-amber-300';
                else cls = base +
                    'border-gray-200 bg-white cursor-pointer hover:border-amber-400 hover:bg-amber-50';

                const checkBadge = selected ?
                    `<div class="absolute top-1.5 left-1.5 w-5 h-5 rounded-full bg-amber-400 text-black text-xs font-bold flex items-center justify-center">✓</div>` :
                    '';

                // Tooltip deskripsi
                const hasDeskripsi = deskripsi && deskripsi !== '-';
                const tooltipAttr = hasDeskripsi ? `title="${escHtml(deskripsi)}"` : '';

                return `
                <div id="tbl${m.id}" class="${cls}" ${tooltipAttr} onclick="${terisi ? '' : `toggleTable(${m.id}, '${nomorMeja}', '${getMejaTipe(m)}', '${escHtml(deskripsi)}')`}">
                    ${checkBadge}
                    ${icon}
                    <div class="text-sm font-bold text-gray-800">${nomorMeja}</div>
                    <div class="text-xs text-gray-400 mt-0.5">${isLesehan ? 'Lesehan' : 'Meja Kursi'} ${kapasitas} org</div>
                    ${hasDeskripsi ? `<div class="text-xs text-gray-400 mt-0.5 truncate max-w-full">${escHtml(deskripsi).substring(0, 20)}${deskripsi.length > 20 ? '...' : ''}</div>` : ''}
                    <span class="inline-block mt-1.5 px-2 py-0.5 rounded-full text-xs font-semibold
                        ${terisi ? 'bg-red-100 text-red-500' : selected ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600'}">
                        ${terisi ? 'Terisi' : selected ? 'Dipilih ✓' : 'Tersedia'}
                    </span>
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
            const idx = state.tableIds.indexOf(id);
            if (idx === -1) {
                state.tableIds.push(id);
                state.tableNomors.push(nomor);
                state.tableDetails.push({
                    id: id,
                    nomor: nomor,
                    tipe: tipe,
                    deskripsi: deskripsi
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

            // Tampilkan tag meja dengan tooltip deskripsi
            tags.innerHTML = state.tableDetails.map(t => {
                const hasDeskripsi = t.deskripsi && t.deskripsi !== '-';
                return `<span class="px-2 py-0.5 rounded-full bg-amber-200 text-amber-800 text-xs font-bold" ${hasDeskripsi ? `title="${escHtml(t.deskripsi)}"` : ''}>
                ${t.nomor}
            </span>`;
            }).join('');

            count.textContent = `${state.tableNomors.length} meja`;
            document.getElementById('cartMeta').innerHTML =
                `<span class="px-2 py-1 rounded-full bg-amber-100 text-amber-600 text-xs font-semibold">🍽️ Dine In</span>
             <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold" title="${state.tableDetails.map(t => t.deskripsi).filter(d => d && d !== '-').join(', ')}">🪑 ${state.tableNomors.join(', ')}</span>`;
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
                const qty = inCart ? inCart.qty : 0;
                const habis = m.stok === 0;
                const maxed = qty >= m.stok;

                const stokSisa = m.stok - qty;

                let stokBadge;
                if (stokSisa <= 0) stokBadge =
                    `<span class="text-xs font-semibold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-md">Habis</span>`;
                else if (stokSisa <= 3) stokBadge =
                    `<span class="text-xs font-semibold text-orange-500 bg-orange-50 px-1.5 py-0.5 rounded-md">Sisa ${stokSisa}</span>`;
                else stokBadge = `<span class="text-xs text-gray-400">Stok ${stokSisa}</span>`;

                let addEl;
                if (habis) addEl = '';
                else if (qty > 0) addEl =
                    `<div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-amber-400 text-black text-xs font-bold flex items-center justify-center">${qty}</div>`;
                else addEl =
                    `<div class="absolute top-2 right-2 w-7 h-7 rounded-full bg-amber-400 text-black text-lg font-bold items-center justify-center opacity-0 group-hover:opacity-100 transition-all flex">+</div>`;

                const clickHandler = habis ? '' : (maxed ? `onclick="warnStok('${m.nama}', ${m.stok})"` :
                    `onclick="addToCart(${m.id})"`);

                return `
                <div ${clickHandler}
                    class="relative border-2 ${qty > 0 ? 'border-amber-400' : 'border-gray-200'} rounded-xl overflow-hidden bg-white transition-all ${habis ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer hover:border-gray-300 hover:-translate-y-0.5'} group">
                    <div class="h-16 bg-gray-50 flex items-center justify-center text-3xl">${m.emoji}</div>
                    <div class="p-2.5">
                        <div class="text-xs text-gray-400 capitalize mb-0.5 truncate">${m.kategori}</div>
                        <div class="text-xs font-semibold text-gray-800 mb-1 leading-tight line-clamp-2">${m.nama}</div>
                        <div class="text-xs font-bold text-amber-500 mb-1">${formatRp(m.harga)}</div>
                        ${stokBadge}
                    </div>
                    ${addEl}
                </div>`;
            }).join('');
        }

        function warnStok(nama, stok) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Terbatas',
                text: `${nama} hanya tersisa ${stok} porsi.`,
                confirmButtonColor: '#f59e0b'
            });
        }

        function addToCart(id) {
            const menu = ALL_MENUS.find(m => m.id === id);
            if (!menu) return;
            const existing = state.cart.find(c => c.id === id);
            if (existing && existing.qty >= menu.stok) {
                warnStok(menu.nama, menu.stok);
                return;
            }
            if (existing) existing.qty++;
            else state.cart.push({
                id: menu.id,
                nama: menu.nama,
                harga: menu.harga,
                kategori: menu.kategori,
                stok: menu.stok,
                qty: 1
            });
            renderMenuGrid(activeFilter);
            renderCart();
            updateNextBtn();
        }

        // ══════════════════════════════════════════════
        // CART
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
            document.getElementById('catSummaryRows').innerHTML = Object.entries(catMap).map(([cat, tot]) => `
            <div class="flex justify-between text-xs">
                <span class="text-gray-500 capitalize">${cat}</span>
                <span class="font-semibold text-gray-700">${formatRp(tot)}</span>
            </div>`).join('');
        }

        function changeQty(id, delta) {
            const idx = state.cart.findIndex(c => c.id === id);
            if (idx === -1) return;
            if (delta > 0 && state.cart[idx].qty >= state.cart[idx].stok) {
                warnStok(state.cart[idx].nama, state.cart[idx].stok);
                return;
            }
            state.cart[idx].qty += delta;
            if (state.cart[idx].qty <= 0) state.cart.splice(idx, 1);
            if (state.step === 3) renderMenuGrid(activeFilter);
            renderCart();
            updateNextBtn();
        }

        // ══════════════════════════════════════════════
        // KONFIRMASI ORDER (SCR 4)
        // ══════════════════════════════════════════════
        function renderPayment() {
            const isDine = state.type === 'dine_in';
            const total = getTotal();
            const mejaLabel = state.tableNomors.join(', ') || '-';
            const mejaDeskripsi = state.tableDetails.map(t => t.deskripsi).filter(d => d && d !== '-').join(', ');

            document.getElementById('paySub').innerHTML = isDine ?
                `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold" title="${mejaDeskripsi}">🍽️ Dine In · Meja ${mejaLabel}</span>` :
                `<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-semibold">🥡 Take Away</span>`;

            const mejaBox = document.getElementById('payMejaInfo');
            if (isDine) {
                mejaBox.classList.remove('hidden');
                document.getElementById('payMejaVal').innerHTML =
                    `${mejaLabel}${mejaDeskripsi ? `<span class="text-xs text-gray-400 block">${mejaDeskripsi.substring(0, 30)}${mejaDeskripsi.length > 30 ? '...' : ''}</span>` : ''}`;
            } else {
                mejaBox.classList.add('hidden');
            }

            document.getElementById('payTotalVal').textContent = formatRp(total);

            document.getElementById('inputNamaCustomer').value = '';
            document.getElementById('namaCustomerError').classList.add('hidden');

            document.getElementById('payItemRows').innerHTML = state.cart.map(c => `
            <div class="grid grid-cols-12 px-4 py-2.5 border-b border-gray-50 text-sm">
                <span class="col-span-6 text-gray-700">${c.nama}<br><span class="text-xs text-gray-400">${formatRp(c.harga)}</span></span>
                <span class="col-span-2 text-center text-gray-500">x${c.qty}</span>
                <span class="col-span-2 text-right text-gray-500">${formatRp(c.harga)}</span>
                <span class="col-span-2 text-right font-semibold text-gray-800">${formatRp(c.harga * c.qty)}</span>
            </div>`).join('');

            document.getElementById('payTotals').innerHTML =
                `
            <div class="flex justify-between text-sm text-gray-500"><span>Subtotal (${state.cart.reduce((s, c) => s + c.qty, 0)} item)</span><span>${formatRp(total)}</span></div>
            <div class="flex justify-between text-base font-bold text-gray-900 pt-1"><span>TOTAL</span><span>${formatRp(total)}</span></div>`;

            document.getElementById('payActionBtns').className = 'grid grid-cols-3 gap-3';
            document.getElementById('payActionBtns').innerHTML =
                `
            <button onclick="batalOrder()" class="py-3 rounded-xl border-2 border-red-200 text-red-500 font-semibold text-sm hover:bg-red-50 transition-all">✕ Batal</button>
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
            goScreen(3);
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
                            harga: c.harga
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

        function showSukses() {
            const isDine = state.type === 'dine_in';
            const mejaInfo = isDine ? ` · Meja ${state.tableNomors.join(', ')}` : '';
            document.getElementById('successSubText').textContent =
                `${isDine ? '🍽️ Dine In' : '🥡 Take Away'}${mejaInfo} · ${state.namaCustomer}`;
            document.getElementById('successNoTransaksi').textContent = state.noTransaksi || '-';

            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('flowHint').textContent = '';

            goScreen(5);
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
            };
            const base = 'relative border-2 rounded-2xl p-7 cursor-pointer transition-all ';
            document.getElementById('cardDine').className = base + 'border-gray-200 bg-white hover:border-gray-300';
            document.getElementById('cardTake').className = base + 'border-gray-200 bg-white hover:border-gray-300';
            document.getElementById('chkDine').className =
                'absolute top-4 right-4 w-6 h-6 rounded-full bg-amber-400 text-black text-xs font-bold items-center justify-center hidden';
            document.getElementById('chkTake').className =
                'absolute top-4 right-4 w-6 h-6 rounded-full bg-teal-400 text-black text-xs font-bold items-center justify-center hidden';
            document.getElementById('cartMeta').innerHTML = '<span class="text-xs text-gray-400">Belum ada order</span>';
            document.getElementById('cartTotal').textContent = 'Rp 0';
            document.getElementById('cartBadge').textContent = '0 item';
            document.getElementById('flowHint').textContent = 'Pilih tipe order untuk memulai';
            document.getElementById('btnNext').style.display = 'none';
            renderCart();
            renderStepBar();
            document.querySelectorAll('.order-screen').forEach(s => s.classList.add('hidden'));
            document.getElementById('scr1').classList.remove('hidden');
            state.step = 1;
        }

        function getTotal() {
            return state.cart.reduce((s, c) => s + c.harga * c.qty, 0);
        }

        function formatRp(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        // Init step bar on load
        renderStepBar();
    </script>
@endpush
