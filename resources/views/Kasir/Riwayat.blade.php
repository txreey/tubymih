{{-- resources/views/kasir/riwayat.blade.php --}}
@extends('kasir.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
                <p class="text-sm text-gray-400 mt-0.5">Transaksi hari ini · {{ now()->format('d M Y') }}</p>
            </div>
            <a href="{{ route('kasir.order.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-sm hover:bg-teal-700 transition-all">
                <i class="fas fa-plus text-xs"></i> Order Baru
            </a>
        </div>

        {{-- Filter Bar --}}
        <div class="flex items-center gap-3 mb-5 flex-wrap">
            <div class="flex gap-2 items-center">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipe</span>
                <div class="flex gap-1.5">
                    <button
                        class="rw-filter-tipe px-3 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all"
                        data-val="semua" onclick="filterRiwayat(this,'tipe')">Semua</button>
                    <button
                        class="rw-filter-tipe px-3 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                        data-val="dine_in" onclick="filterRiwayat(this,'tipe')">🍽️ Dine In</button>
                    <button
                        class="rw-filter-tipe px-3 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                        data-val="take_away" onclick="filterRiwayat(this,'tipe')">🥡 Take Away</button>
                </div>
            </div>
            <div class="w-px h-5 bg-gray-200"></div>
            <div class="flex gap-2 items-center">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</span>
                <div class="flex gap-1.5">
                    <button
                        class="rw-filter-status px-3 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all"
                        data-val="semua" onclick="filterRiwayat(this,'status')">Semua</button>
                    <button
                        class="rw-filter-status px-3 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                        data-val="tunggak" onclick="filterRiwayat(this,'status')">🟠 Belum Bayar</button>
                    <button
                        class="rw-filter-status px-3 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300"
                        data-val="lunas" onclick="filterRiwayat(this,'status')">🟢 Lunas</button>
                </div>
            </div>
            <div class="ml-auto text-xs text-gray-400" id="filterCount"></div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Total Transaksi</div>
                <div class="text-3xl font-bold text-gray-800">{{ $jumlahTransaksi }}</div>
                <div class="text-xs text-gray-400 mt-1">transaksi hari ini</div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Total Pendapatan</div>
                <div class="text-3xl font-bold text-teal-600">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</div>
                <div class="text-xs text-gray-400 mt-1">dari transaksi lunas</div>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Rata-rata</div>
                <div class="text-3xl font-bold text-amber-500">
                    Rp {{ $jumlahTransaksi > 0 ? number_format($totalHariIni / $jumlahTransaksi, 0, ',', '.') : 0 }}
                </div>
                <div class="text-xs text-gray-400 mt-1">per transaksi</div>
            </div>
        </div>

        {{-- Tabel Transaksi --}}
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-bold text-gray-800">Daftar Transaksi</h2>
                <span class="text-xs text-gray-400">{{ $jumlahTransaksi }} transaksi</span>
            </div>

            @if ($transaksis->isEmpty())
                <div class="py-16 text-center text-gray-300">
                    <div class="text-5xl mb-3">🧾</div>
                    <div class="text-sm">Belum ada transaksi hari ini</div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="text-xs font-bold uppercase tracking-wider text-gray-400 bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-3 text-left">No. Transaksi</th>
                                <th class="px-4 py-3 text-left">Kasir</th>
                                <th class="px-4 py-3 text-left">Customer</th>
                                <th class="px-4 py-3 text-left">Tipe</th>
                                <th class="px-4 py-3 text-left">Meja</th>
                                <th class="px-4 py-3 text-left">Item</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 text-center">Waktu</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($transaksis as $t)
                                <tr class="rw-row hover:bg-gray-50 transition-colors {{ $t->status === 'tunggak' ? 'bg-orange-50/40' : '' }}"
                                    data-tipe="{{ $t->tipe_order }}" data-status="{{ $t->status }}">

                                    {{-- No. Transaksi --}}
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-xs font-semibold text-gray-700">{{ $t->no_transaksi }}
                                        </div>
                                    </td>

                                    {{-- Kasir --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 text-xs font-bold flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($t->nama_kasir ?? '-', 0, 1)) }}
                                            </div>
                                            <span
                                                class="text-sm font-medium text-gray-700">{{ $t->nama_kasir ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Customer --}}
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-gray-700">{{ $t->nama_pelanggan ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- Tipe --}}
                                    <td class="px-4 py-4">
                                        @if ($t->tipe_order === 'dine_in')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-semibold">
                                                🍽️ Dine In
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-100 text-teal-700 text-xs font-semibold">
                                                🥡 Take Away
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Meja --}}
                                    <td class="px-4 py-4 text-sm text-gray-600">
                                        {{ $t->nama_meja ?? '-' }}
                                    </td>

                                    {{-- Item --}}
                                    <td class="px-4 py-4">
                                        <div class="text-xs text-gray-500 space-y-0.5">
                                            @foreach ($t->items->take(2) as $item)
                                                <div>{{ $item->nama }} ×{{ $item->qty }}</div>
                                            @endforeach
                                            @if ($t->items->count() > 2)
                                                <div class="text-gray-400">+{{ $t->items->count() - 2 }} lainnya</div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Total --}}
                                    <td class="px-4 py-4 text-right font-semibold text-gray-800 text-sm">
                                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-4 py-4 text-center text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y H:i') }}
                                    </td>

                                    {{-- Status badge --}}
                                    <td class="px-4 py-4 text-center">
                                        @if ($t->status === 'tunggak')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-orange-100 text-orange-600 text-xs font-semibold">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse inline-block"></span>
                                                Belum Bayar
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                                Lunas
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-4 text-center">
                                        @if ($t->status === 'tunggak')
                                            <button onclick="bukaTagih({{ $t->id }})"
                                                class="px-4 py-1.5 rounded-lg bg-orange-500 text-white text-xs font-bold hover:bg-orange-600 transition-all shadow-sm">
                                                💰 Tagih
                                            </button>
                                        @else
                                            <button onclick="lihatStruk({{ $t->id }})"
                                                class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:border-teal-400 hover:text-teal-600 hover:bg-teal-50 transition-all">
                                                🧾 Struk
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Struk --}}
    <div id="modalStruk" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-80 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Detail Struk</h3>
                <button onclick="tutupModal('modalStruk')"
                    class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>
            <div id="modalStrukContent" class="font-mono text-xs"></div>
            <div class="mt-4 flex gap-2">
                <button onclick="cetakModal()"
                    class="flex-1 py-2.5 rounded-xl bg-gray-800 text-white font-bold text-sm hover:bg-gray-900 transition-all">
                    🖨️ Cetak
                </button>
                <button onclick="tutupModal('modalStruk')"
                    class="flex-1 py-2.5 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Tagih --}}
    <div id="modalTagih" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-96 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Tagih Pembayaran</h3>
                    <p class="text-xs text-gray-400" id="tagihSub">-</p>
                </div>
                <button onclick="tutupModal('modalTagih')"
                    class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
            </div>

            {{-- Ringkasan item --}}
            <div class="bg-gray-50 rounded-xl p-3 mb-4 text-xs" id="tagihItems"></div>

            {{-- Total --}}
            <div class="flex justify-between items-center mb-4 px-1">
                <span class="text-sm text-gray-500 font-medium">Total Tagihan</span>
                <span class="text-xl font-bold text-gray-900" id="tagihTotal">Rp 0</span>
            </div>

            {{-- Input bayar --}}
            <div class="mb-3">
                <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Jumlah Bayar (Cash)</label>
                <input type="text" id="tagihInputBayar" placeholder="0" oninput="hitungTagihKembalian(this.value)"
                    class="w-full border-2 border-gray-200 rounded-xl px-3 py-2.5 text-right text-base font-bold text-gray-800 focus:outline-none focus:border-teal-400 transition-all">
                <div class="grid grid-cols-3 gap-1.5 mt-2" id="tagihQuickAmounts"></div>
            </div>

            {{-- Kembalian --}}
            <div class="rounded-xl p-3 text-center mb-4 bg-gray-50 border border-gray-200" id="tagihKembalianBox">
                <div class="text-xs text-gray-400 mb-1">Kembalian</div>
                <div class="text-lg font-bold text-gray-400" id="tagihKembalianVal">-</div>
            </div>

            {{-- Action --}}
            <div class="flex gap-2">
                <button onclick="tutupModal('modalTagih')"
                    class="flex-1 py-2.5 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all">
                    Batal
                </button>
                <button id="btnTagihBayar" onclick="prosesTagih()" disabled
                    class="flex-1 py-2.5 rounded-xl bg-gray-300 text-white font-bold text-sm cursor-not-allowed transition-all">
                    ✓ Konfirmasi Bayar
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Struk Tagih (setelah tagih berhasil) --}}
    <div id="modalStrukTagih" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl w-80 p-6">
            <div class="text-center mb-4">
                <div class="text-4xl mb-2">✅</div>
                <div class="font-bold text-green-700">Pembayaran Berhasil!</div>
                <div class="text-xs text-gray-400 mt-1" id="tagihSuccessSub">Meja sudah dibebaskan</div>
            </div>
            <div id="modalStrukTagihContent" class="font-mono text-xs mb-4"></div>
            <div class="flex gap-2">
                <button onclick="cetakTagih()"
                    class="flex-1 py-2.5 rounded-xl bg-gray-800 text-white font-bold text-sm hover:bg-gray-900 transition-all">
                    🖨️ Cetak
                </button>
                <button onclick="tutupTagihDanRefresh()"
                    class="flex-1 py-2.5 rounded-xl border-2 border-teal-500 text-teal-600 font-semibold text-sm hover:bg-teal-50 transition-all">
                    Selesai
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const TRANSAKSI_DATA = @json($transaksis);
        let activeTagihId = null;
        let tagihStrukData = null;
        let filterTipe = 'semua';
        let filterStatus = 'semua';

        // ── Filter ──
        function filterRiwayat(btn, group) {
            document.querySelectorAll('.rw-filter-' + group).forEach(b => {
                b.className = 'rw-filter-' + group +
                    ' px-3 py-1.5 rounded-full border-2 border-gray-200 text-gray-400 text-xs font-semibold transition-all hover:border-gray-300';
            });
            btn.className = 'rw-filter-' + group +
                ' px-3 py-1.5 rounded-full border-2 border-teal-500 bg-teal-500 text-white text-xs font-semibold transition-all';

            if (group === 'tipe') filterTipe = btn.dataset.val;
            if (group === 'status') filterStatus = btn.dataset.val;
            applyFilter();
        }

        function applyFilter() {
            const rows = document.querySelectorAll('.rw-row');
            let visible = 0;
            rows.forEach(row => {
                const tipeOk = filterTipe === 'semua' || row.dataset.tipe === filterTipe;
                const statusOk = filterStatus === 'semua' || row.dataset.status === filterStatus;
                const show = tipeOk && statusOk;
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            const countEl = document.getElementById('filterCount');
            if (countEl) countEl.textContent = visible + ' transaksi ditampilkan';
        }

        document.addEventListener('DOMContentLoaded', applyFilter);

        // ── Modal helpers ──
        function bukaModal(id) {
            const el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
        }

        function tutupModal(id) {
            const el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        }

        // ── Lihat Struk (transaksi lunas) ──
        function lihatStruk(id) {
            const t = TRANSAKSI_DATA.find(x => x.id == id);
            if (!t) return;
            document.getElementById('modalStrukContent').innerHTML = buatStrukHTML(t, t.jumlah_bayar, t.kembalian);
            bukaModal('modalStruk');
        }

        function buatStrukHTML(t, jumlahBayar, kembalian, namaKasir) {
            const tgl = t.tanggal ? t.tanggal.substring(0, 10).split('-').reverse().join('-') : '-';
            const itemRows = (t.items || []).map(i =>
                `<div style="display:flex;justify-content:space-between;margin-bottom:4px">
                    <span>${i.nama}<br><span style="color:#999">${formatRp(i.harga_satuan)} x${i.qty}</span></span>
                    <span>${formatRp(i.harga_satuan * i.qty)}</span>
                </div>`).join('');
            const mejaOrType = t.tipe_order === 'dine_in' ? (t.nama_meja || '-') : 'Take Away';
            return `
        <div style="text-align:center;margin-bottom:12px">
            <div style="font-weight:bold;font-size:14px">Tuangeun by Mimih</div>
            <div style="color:#666;font-size:11px">Jl. Raya Warung Kadu No. 65</div>
            <hr style="border:none;border-top:1px dashed #ccc;margin:8px 0">
        </div>
        <div style="margin-bottom:8px">
            <div style="display:flex;justify-content:space-between"><span>Tanggal</span><span>${tgl}</span></div>
            <div style="display:flex;justify-content:space-between"><span>Staff</span><span>${namaKasir || t.nama_kasir || '-'}</span></div>
            <div style="display:flex;justify-content:space-between"><span>Customer</span><span>${t.nama_pelanggan || '-'}</span></div>
            <div style="display:flex;justify-content:space-between"><span>${t.tipe_order === 'dine_in' ? 'Meja' : 'Tipe'}</span><span>${mejaOrType}</span></div>
            <div style="display:flex;justify-content:space-between"><span>No.</span><span>${t.no_transaksi}</span></div>
        </div>
        <hr style="border:none;border-top:1px dashed #ccc;margin:8px 0">
        ${itemRows}
        <hr style="border:none;border-top:1px dashed #ccc;margin:8px 0">
        <div style="display:flex;justify-content:space-between"><span>Jumlah Bayar</span><span>${formatRp(jumlahBayar)}</span></div>
        <div style="display:flex;justify-content:space-between;font-weight:bold"><span>Total Bayar</span><span>${formatRp(t.total_harga)}</span></div>
        <div style="display:flex;justify-content:space-between"><span>Kembalian</span><span>${formatRp(kembalian)}</span></div>
        <hr style="border:none;border-top:1px dashed #ccc;margin:8px 0">
        <div style="text-align:center;color:#999">Terima kasih sudah berkunjung 🙏</div>`;
        }

        function cetakModal() {
            cetak(document.getElementById('modalStrukContent').innerHTML);
        }

        // ── Tagih ──
        function bukaTagih(id) {
            const t = TRANSAKSI_DATA.find(x => x.id == id);
            if (!t) return;
            activeTagihId = id;

            const isTakeAway = t.tipe_order === 'take_away';
            document.getElementById('tagihSub').textContent = isTakeAway ?
                `🥡 Take Away · ${t.nama_pelanggan || '-'}` :
                `🍽️ Dine In · Meja ${t.nama_meja || '-'} · ${t.nama_pelanggan || '-'}`;

            document.getElementById('tagihTotal').textContent = formatRp(t.total_harga);

            document.getElementById('tagihItems').innerHTML = (t.items || []).map(i =>
                `<div class="flex justify-between py-0.5">
                    <span class="text-gray-600">${i.nama} ×${i.qty}</span>
                    <span class="font-semibold text-gray-800">${formatRp(i.harga_satuan * i.qty)}</span>
                </div>`).join('');

            const tot = t.total_harga;
            const opts = [...new Set([tot, roundUp(tot, 5000), roundUp(tot, 10000), roundUp(tot, 50000)])].slice(0, 3);
            document.getElementById('tagihQuickAmounts').innerHTML = opts.map(a =>
                `<button onclick="setTagihPay(${a})"
                    class="py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:border-teal-400 hover:text-teal-600 hover:bg-teal-50 transition-all">
                    ${formatRp(a)}
                </button>`).join('');

            document.getElementById('tagihInputBayar').value = '';
            hitungTagihKembalian('');
            bukaModal('modalTagih');
        }

        function hitungTagihKembalian(val) {
            const bayar = parseInt(val.replace(/\D/g, '')) || 0;
            const t = TRANSAKSI_DATA.find(x => x.id == activeTagihId);
            if (!t) return;
            const total = t.total_harga;
            const box = document.getElementById('tagihKembalianBox');
            const valEl = document.getElementById('tagihKembalianVal');
            const btn = document.getElementById('btnTagihBayar');

            document.getElementById('tagihInputBayar').value = bayar > 0 ? bayar.toLocaleString('id-ID') : '';

            if (bayar === 0) {
                box.className = 'rounded-xl p-3 text-center mb-4 bg-gray-50 border border-gray-200';
                valEl.className = 'text-lg font-bold text-gray-400';
                valEl.textContent = '-';
                btn.disabled = true;
                btn.className =
                    'flex-1 py-2.5 rounded-xl bg-gray-300 text-white font-bold text-sm cursor-not-allowed transition-all';
                return;
            }
            const kembalian = bayar - total;
            if (kembalian < 0) {
                box.className = 'rounded-xl p-3 text-center mb-4 bg-red-50 border border-red-200';
                valEl.className = 'text-lg font-bold text-red-500';
                valEl.textContent = 'Kurang ' + formatRp(Math.abs(kembalian));
                btn.disabled = true;
                btn.className =
                    'flex-1 py-2.5 rounded-xl bg-gray-300 text-white font-bold text-sm cursor-not-allowed transition-all';
            } else {
                box.className = 'rounded-xl p-3 text-center mb-4 bg-green-50 border border-green-200';
                valEl.className = 'text-lg font-bold text-green-600';
                valEl.textContent = formatRp(kembalian);
                btn.disabled = false;
                btn.className =
                    'flex-1 py-2.5 rounded-xl bg-teal-600 text-white font-bold text-sm hover:bg-teal-700 transition-all';
            }
        }

        function setTagihPay(amount) {
            hitungTagihKembalian(amount.toString());
        }

        function prosesTagih() {
            const t = TRANSAKSI_DATA.find(x => x.id == activeTagihId);
            const bayarStr = document.getElementById('tagihInputBayar').value.replace(/\D/g, '');
            const bayar = parseInt(bayarStr) || 0;
            const kembalian = bayar - t.total_harga;
            const btn = document.getElementById('btnTagihBayar');

            btn.disabled = true;
            btn.textContent = 'Memproses...';

            fetch(`/kasir/order/${activeTagihId}/tagih`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        jumlah_bayar: bayar,
                        kembalian: kembalian
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        tutupModal('modalTagih');
                        tagihStrukData = data;

                        // Subtitle sukses: sesuaikan dengan tipe order
                        const isTakeAway = t.tipe_order === 'take_away';
                        document.getElementById('tagihSuccessSub').textContent = isTakeAway ?
                            'Pesanan siap diambil pelanggan' :
                            'Meja sudah dibebaskan';

                        document.getElementById('modalStrukTagihContent').innerHTML = buatStrukHTML({
                            ...t,
                            no_transaksi: data.no_transaksi,
                            tanggal: data.tanggal,
                            items: data.items.map(i => ({
                                nama: i.nama,
                                qty: i.qty,
                                harga_satuan: i.harga
                            })),
                            nama_pelanggan: t.nama_pelanggan,
                            nama_meja: t.nama_meja,
                            tipe_order: t.tipe_order,
                        }, data.jumlah_bayar, data.kembalian, '{{ auth()->user()->nama ?? '-' }}');
                        bukaModal('modalStrukTagih');
                    } else {
                        alert('Gagal: ' + data.message);
                        btn.disabled = false;
                        btn.textContent = '✓ Konfirmasi Bayar';
                    }
                })
                .catch(() => {
                    alert('Terjadi kesalahan koneksi.');
                    btn.disabled = false;
                    btn.textContent = '✓ Konfirmasi Bayar';
                });
        }

        function cetakTagih() {
            cetak(document.getElementById('modalStrukTagihContent').innerHTML);
        }

        function tutupTagihDanRefresh() {
            tutupModal('modalStrukTagih');
            window.location.reload();
        }

        // ── Helpers ──
        function cetak(html) {
            const win = window.open('', '_blank', 'width=400,height=600');
            win.document.write(`<html><head><title>Struk</title>
        <style>body{font-family:monospace;font-size:12px;padding:20px;max-width:280px;margin:0 auto}</style>
        </head><body>${html}</body></html>`);
            win.document.close();
            win.focus();
            win.print();
            win.close();
        }

        function roundUp(amount, step) {
            return Math.ceil(amount / step) * step;
        }

        function formatRp(n) {
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        }
    </script>
@endpush
