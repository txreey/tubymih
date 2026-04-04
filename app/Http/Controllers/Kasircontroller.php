<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // Nilai enum sesuai migration yang ada
    // ══════════════════════════════════════════════════════════════
    const STATUS_TUNGGAK = 'tunggak';
    const STATUS_LUNAS   = 'lunas';

    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard()
    {
        $idKasir = Auth::id();
        $today   = today();

        $transaksiHariIni = Transaksi::where('id_kasir', $idKasir)
            ->whereDate('tanggal', $today)
            ->count();

        $pendapatanHari = Transaksi::where('status', self::STATUS_LUNAS)
            ->whereDate('tanggal', $today)
            ->sum('total_harga');

        $mejaTersedia = Meja::where('status', 'tersedia')->count();
        $totalMeja    = Meja::count();

        $transaksiTerakhir = Transaksi::with('meja')
            ->where('id_kasir', $idKasir)
            ->whereDate('tanggal', $today)
            ->latest()
            ->take(5)
            ->get();

        $data = compact('transaksiHariIni', 'pendapatanHari', 'mejaTersedia', 'totalMeja', 'transaksiTerakhir');
        return view('kasir.dashboard', compact('data'));
    }

    // ==========================================
    // HALAMAN ORDER UTAMA
    // ==========================================
    public function orderIndex()
    {
        // Ambil semua data meja lengkap dengan deskripsi
        $mejas = Meja::orderByRaw("FIELD(status, 'tersedia', 'terisi')")
            ->orderBy('no_meja')
            ->get()
            ->map(fn($m) => (object)[
                'id'         => $m->id,
                'nomor_meja' => $m->no_meja,
                'kapasitas'  => $m->kapasitas,
                'tipe'       => $this->normalizeTipeMeja($m->tipe_meja),
                'tipe_meja'  => $m->tipe_meja,
                'status'     => $m->status,
                'deskripsi'  => $m->deskripsi ?? '-',
            ]);

        $menus = Menu::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('nama_makanan')
            ->get()
            ->map(fn($m) => (object)[
                'id'       => $m->id,
                'nama'     => $m->nama_makanan,
                'harga'    => (int) $m->harga,
                'kategori' => $m->kategori->nama_kategori ?? 'Lainnya',
                'emoji'    => $m->emoji ?? '🍽️',
                'stok'     => (int) $m->stok,
                'tersedia' => $m->stok > 0,
            ]);

        return view('kasir.order', compact('mejas', 'menus'));
    }

    /**
     * Normalisasi tipe meja untuk frontend
     * - 'Lesehan' -> 'lesehan'
     * - 'Meja Kursi' -> 'kursi'
     */
    private function normalizeTipeMeja($tipe)
    {
        $tipe = trim($tipe ?? '');

        if (stripos($tipe, 'lesehan') !== false) {
            return 'lesehan';
        }

        if (stripos($tipe, 'kursi') !== false || stripos($tipe, 'meja kursi') !== false) {
            return 'kursi';
        }

        return 'kursi';
    }

    // ==========================================
    // SIMPAN TRANSAKSI BARU (Kirim Dapur)
    // Support multi-meja untuk dine-in (disimpan sebagai string atau JSON jika perlu)
    // ==========================================
    public function orderStore(Request $request)
    {
        $request->validate([
            'tipe_order'     => 'required|in:dine_in,take_away',
            'id_mejas'       => 'required_if:tipe_order,dine_in|nullable|array',
            'id_mejas.*'     => 'integer|exists:meja,id',
            'nama_pelanggan' => 'required|string|max:100',
            'bayar_nanti'    => 'boolean',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|integer',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.harga'  => 'required|integer|min:0',
        ]);

        $isDineIn = $request->tipe_order === 'dine_in';
        $idMejas  = $request->input('id_mejas', []);

        DB::beginTransaction();

        try {
            $total       = collect($request->items)->sum(fn($i) => $i['harga'] * $i['qty']);
            $noTransaksi = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            // Simpan meja pertama sebagai id_meja utama
            $idMejaUtama = $isDineIn && !empty($idMejas) ? $idMejas[0] : null;

            // Optional: Simpan daftar semua meja sebagai JSON (jika perlu)
            $allMejasJson = $isDineIn && !empty($idMejas) ? json_encode($idMejas) : null;

            $transaksi = Transaksi::create([
                'id_kasir'        => Auth::id(),
                'id_meja'         => $idMejaUtama,
                'no_transaksi'    => $noTransaksi,
                'jenis_pemesanan' => $request->tipe_order === 'dine_in' ? 'dine_in' : 'takeaway',
                'nama_pelanggan'  => $request->nama_pelanggan,
                'tanggal'         => now(),
                'total_harga'     => $total,
                'jumlah_bayar'    => 0,
                'kembalian'       => 0,
                'status'          => self::STATUS_TUNGGAK,
                // Jika ada kolom untuk menyimpan multi-meja, tambahkan di sini
                // 'meja_ids' => $allMejasJson,
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_menu'      => $item['id'],
                    'qty'          => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['harga'] * $item['qty'],
                ]);

                // Kurangi stok menu
                Menu::where('id', $item['id'])->decrement('stok', $item['qty']);
            }

            // Update status meja menjadi terisi (untuk dine-in multi-meja)
            if ($isDineIn && !empty($idMejas)) {
                Meja::whereIn('id', $idMejas)->update(['status' => 'terisi']);
            }

            DB::commit();

            $namaMejas = $isDineIn ? Meja::whereIn('id', $idMejas)->pluck('no_meja')->join(', ') : null;

            return response()->json([
                'success'      => true,
                'message'      => 'Order dikirim ke dapur.',
                'id_transaksi' => $transaksi->id,
                'no_transaksi' => $noTransaksi,
                'tanggal'      => now()->format('d-m-y'),
                'tipe'         => $request->tipe_order,
                'status'       => self::STATUS_TUNGGAK,
                'total'        => $total,
                'nama_mejas'   => $namaMejas,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
    // ==========================================
    // RIWAYAT TRANSAKSI
    // ==========================================
    public function riwayat(Request $request)
    {
        $transaksis = Transaksi::with(['detailTransaksi.menu', 'meja', 'kasir'])
            ->whereDate('tanggal', today())
            ->latest()
            ->get()
            ->map(function ($t) {
                // Nama kasir
                $t->nama_kasir = $t->kasir->nama ?? '-';

                // Nama pelanggan
                $t->nama_pelanggan = $t->nama_pelanggan ?? '-';

                // Meja
                $t->nama_meja = $t->meja->no_meja ?? null;

                // Tipe order
                $t->tipe_order = $t->jenis_pemesanan === 'dine_in'
                    ? 'dine_in'
                    : 'take_away';

                // Items
                $t->items = $t->detailTransaksi->map(function ($d) {
                    return (object)[
                        'nama'         => $d->menu->nama_makanan ?? '-',
                        'qty'          => $d->qty,
                        'harga_satuan' => $d->harga_satuan,
                    ];
                });

                return $t;
            });

        $totalHariIni = $transaksis
            ->where('status', self::STATUS_LUNAS)
            ->sum('total_harga');

        $jumlahTransaksi = $transaksis->count();

        return view('kasir.riwayat', compact(
            'transaksis',
            'totalHariIni',
            'jumlahTransaksi'
        ));
    }

    // ==========================================
    // TAGIH — bayar setelah makan / pesanan siap
    // ==========================================
    public function orderTagih(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|integer|min:0',
            'kembalian'    => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('detailTransaksi.menu')->findOrFail($id);

            if ($transaksi->status !== self::STATUS_TUNGGAK) {
                return response()->json(['success' => false, 'message' => 'Transaksi sudah lunas.'], 422);
            }

            if ($request->jumlah_bayar < $transaksi->total_harga) {
                return response()->json(['success' => false, 'message' => 'Jumlah bayar kurang.'], 422);
            }

            $transaksi->update([
                'status'       => self::STATUS_LUNAS,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian'    => $request->kembalian,
            ]);

            // Update status meja menjadi tersedia untuk dine-in
            if ($transaksi->jenis_pemesanan === 'dine_in') {
                $mejaIds = [];

                if (isset($transaksi->meja_ids) && !empty($transaksi->meja_ids)) {
                    $mejaIds = json_decode($transaksi->meja_ids, true);
                }

                if (empty($mejaIds) && $transaksi->id_meja) {
                    $mejaIds = [$transaksi->id_meja];
                }

                if (!empty($mejaIds)) {
                    Meja::whereIn('id', $mejaIds)->update(['status' => 'tersedia']);
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Pembayaran berhasil.',
                'no_transaksi' => $transaksi->no_transaksi ?? ('TRX-' . $transaksi->id),
                'tanggal'      => Carbon::parse($transaksi->tanggal)->format('d-m-Y H:i'),
                'total'        => $transaksi->total_harga,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian'    => $request->kembalian,
                'items'        => $transaksi->detailTransaksi->map(fn($d) => [
                    'nama'  => $d->menu->nama_makanan ?? '-',
                    'qty'   => $d->qty,
                    'harga' => $d->harga_satuan,
                ]),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
