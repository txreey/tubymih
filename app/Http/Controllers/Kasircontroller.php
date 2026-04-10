<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Log;
use App\Models\Meja;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    const STATUS_TUNGGAK   = 'tunggak';
    const STATUS_LUNAS     = 'lunas';
    const STATUS_RESERVASI = 'reservasi';

    // ==========================================
    // DASHBOARD KASIR
    // ==========================================
    public function dashboard()
    {
        $idKasir = Auth::id();
        $today   = today();

        $transaksiHariIni = Transaksi::where('id_kasir', $idKasir)
            ->whereDate('tanggal', $today)
            ->count();

        $pendapatanHari = Transaksi::where('id_kasir', $idKasir)
            ->where('status', self::STATUS_LUNAS)
            ->whereDate('tanggal', $today)
            ->sum('total_harga');

        $mejaTersedia = Meja::where('status', 'tersedia')->count();
        $totalMeja    = Meja::count();

        $pendingTagihan = Transaksi::where('status', self::STATUS_TUNGGAK)
            ->whereDate('tanggal', $today)
            ->count();

        $totalReservasi = Transaksi::where('status', self::STATUS_RESERVASI)
            ->whereDate('tanggal', $today)
            ->count();

        $transaksiTerakhir = Transaksi::with(['meja', 'kasir', 'detailTransaksi'])
            ->where('id_kasir', $idKasir)
            ->whereDate('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($trx) {
                $totalItem = $trx->detailTransaksi->sum('qty');
                $totalMenu = $trx->detailTransaksi->count();

                $statusColor = match ($trx->status) {
                    'lunas'     => 'green',
                    'tunggak'   => 'red',
                    'reservasi' => 'blue',
                    default     => 'gray',
                };

                $statusLabel = match ($trx->status) {
                    'lunas'     => 'Lunas',
                    'tunggak'   => 'Belum Bayar',
                    'reservasi' => 'Reservasi',
                    default     => ucfirst($trx->status),
                };

                return (object)[
                    'id'            => $trx->id,
                    'no_transaksi'  => $trx->no_transaksi,
                    'kasir_nama'    => $trx->kasir->nama ?? '-',
                    'tipe_order'    => $trx->jenis_pemesanan === 'dine_in' ? 'Dine in' : ($trx->jenis_pemesanan === 'reservasi' ? 'Reservasi' : 'Take away'),
                    'meja'          => $trx->meja->no_meja ?? '-',
                    'item_text'     => $totalMenu . '/' . $totalItem,
                    'total_harga'   => $trx->total_harga,
                    'status'        => $trx->status,
                    'status_color'  => $statusColor,
                    'status_label'  => $statusLabel,
                ];
            });

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat dashboard',
            'detail'    => 'Kasir melihat dashboard',
            'waktu'     => now(),
        ]);

        $data = [
            'transaksi_hari_ini' => $transaksiHariIni,
            'pendapatan_hari'    => $pendapatanHari,
            'meja_tersedia'      => $mejaTersedia,
            'total_meja'         => $totalMeja,
            'pending_tagihan'    => $pendingTagihan,
            'total_reservasi'    => $totalReservasi,
            'transaksi_terakhir' => $transaksiTerakhir,
        ];

        return view('kasir.dashboard', compact('data'));
    }

    // ==========================================
    // HALAMAN ORDER UTAMA
    // ==========================================
    public function orderIndex()
    {
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
            ]);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Membuka halaman order',
            'detail'    => 'Kasir membuka halaman pemesanan',
            'waktu'     => now(),
        ]);

        return view('kasir.order', compact('mejas', 'menus'));
    }

    // ==========================================
    // SIMPAN TRANSAKSI BARU (Kirim Dapur / Reservasi)
    // ==========================================
    public function orderStore(Request $request)
    {
        $isReservasi = $request->tipe_order === 'reservasi';

        $request->validate([
            'tipe_order'        => 'required|in:dine_in,take_away,reservasi',
            'id_mejas'          => 'required_if:tipe_order,dine_in|required_if:tipe_order,reservasi|nullable|array',
            'id_mejas.*'        => 'integer|exists:meja,id',
            'nama_pelanggan'    => 'required|string|max:100',
            'bayar_nanti'       => 'boolean',
            'items'             => 'required_unless:tipe_order,reservasi|nullable|array',
            'items.*.id'        => 'required_with:items|integer',
            'items.*.qty'       => 'required_with:items|integer|min:1',
            'items.*.harga'     => 'required_with:items|integer|min:0',
            'tanggal_reservasi' => 'required_if:tipe_order,reservasi|nullable|date|after_or_equal:today',
            'jam_reservasi' => 'required_if:tipe_order,reservasi|nullable|string',
            'jumlah_orang' => 'nullable|integer|min:1',
            'catatan' => 'nullable|string|max:255',
        ]);

        $idMejas = $request->input('id_mejas', []);

        DB::beginTransaction();

        try {
            $total       = collect($request->items ?? [])->sum(fn($i) => $i['harga'] * $i['qty']);
            $noTransaksi = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
            $idMejaUtama = !empty($idMejas) ? $idMejas[0] : null;

            if ($isReservasi) {
                $jenisPemesanan = 'reservasi';
                $statusAwal     = self::STATUS_RESERVASI;
            } else {
                $jenisPemesanan = $request->tipe_order === 'dine_in' ? 'dine_in' : 'takeaway';
                $statusAwal     = self::STATUS_TUNGGAK;
            }

            $transaksi = Transaksi::create([
                'id_kasir'          => Auth::id(),
                'id_meja'           => $idMejaUtama,
                'no_transaksi'      => $noTransaksi,
                'jenis_pemesanan'   => $jenisPemesanan,
                'nama_pelanggan'    => $request->nama_pelanggan,
                'tanggal'           => now(),
                'total_harga'       => $total,
                'jumlah_bayar'      => 0,
                'kembalian'         => 0,
                'status'            => $statusAwal,
                'waktu_pemesanan'   => $isReservasi ? $request->tanggal_reservasi . ' ' . $request->jam_reservasi . ':00' : null,
                'jam_reservasi'     => $isReservasi ? $request->jam_reservasi : null,
                'jumlah_orang'      => $isReservasi ? $request->jumlah_orang : null,
                'catatan'           => $request->catatan ?? null,
            ]);

            foreach ($request->items ?? [] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_menu'      => $item['id'],
                    'qty'          => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['harga'] * $item['qty'],
                ]);
                Menu::where('id', $item['id'])->decrement('stok', $item['qty']);
            }

            if (!$isReservasi && $request->tipe_order === 'dine_in' && !empty($idMejas)) {
                Meja::whereIn('id', $idMejas)->update(['status' => 'terisi']);
            }

            DB::commit();

            $namaMejas     = !empty($idMejas) ? Meja::whereIn('id', $idMejas)->pluck('no_meja')->join(', ') : null;
            $tipeOrderText = $isReservasi ? 'Reservasi' : ($request->tipe_order === 'dine_in' ? 'Dine In' : 'Take Away');
            $mejaText      = $namaMejas ? ' - Meja: ' . $namaMejas : '';

            Log::create([
                'id_user'   => Auth::id(),
                'aktivitas' => $isReservasi ? 'Membuat reservasi' : 'Membuat transaksi baru',
                'detail'    => 'Transaksi #' . $noTransaksi . ' - ' . $tipeOrderText . $mejaText .
                    ($isReservasi ? ' - Tgl: ' . $request->tanggal_reservasi . ' ' . $request->jam_reservasi . ' - ' . $request->jumlah_orang . ' orang' : '') .
                    ' - Total: Rp ' . number_format($total, 0, ',', '.'),
                'waktu'     => now(),
            ]);

            return response()->json([
                'success'      => true,
                'message'      => $isReservasi ? 'Reservasi berhasil disimpan.' : 'Order dikirim ke dapur.',
                'id_transaksi' => $transaksi->id,
                'no_transaksi' => $noTransaksi,
                'tipe'         => $request->tipe_order,
                'status'       => $statusAwal,
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

    private function normalizeTipeMeja($tipe)
    {
        $tipe = trim($tipe ?? '');
        if (stripos($tipe, 'lesehan') !== false) return 'lesehan';
        if (stripos($tipe, 'kursi') !== false || stripos($tipe, 'meja kursi') !== false) return 'kursi';
        return 'kursi';
    }

    // ==========================================
    // HALAMAN RESERVASI
    // ==========================================
    public function reservasiIndex()
    {
        $reservasis = Transaksi::where('jenis_pemesanan', 'reservasi')
            ->with(['detailTransaksi.menu', 'meja', 'kasir'])
            ->latest()                    // atau orderBy('created_at', 'desc')
            ->get()
            ->map(function ($r) {
                $r->nama_kasir = $r->kasir->nama ?? '-';
                $r->nama_meja  = $r->meja->no_meja ?? '-';

                $r->tanggal_reservasi = $r->waktu_pemesanan
                    ? Carbon::parse($r->waktu_pemesanan)->toDateString()
                    : null;

                $r->jam_reservasi = $r->waktu_pemesanan
                    ? Carbon::parse($r->waktu_pemesanan)->format('H:i')
                    : null;

                $r->items = $r->detailTransaksi->map(fn($d) => [
                    'id_menu'      => $d->id_menu,
                    'nama'         => $d->menu->nama_makanan ?? '-',
                    'qty'          => $d->qty,
                    'harga_satuan' => $d->harga_satuan,
                    'emoji'        => $d->menu->emoji ?? '🍽️',
                    'kategori'     => $d->menu->kategori->nama_kategori ?? '-'
                ]);

                return $r;
            });

        $menus = Menu::where('stok', '>', 0)
            ->orderBy('nama_makanan')
            ->get()
            ->map(fn($m) => (object)[
                'id'       => $m->id,
                'nama'     => $m->nama_makanan,
                'harga'    => (int)$m->harga,
                'kategori' => $m->kategori->nama_kategori ?? 'Lainnya',
                'emoji'    => $m->emoji ?? '🍽️',
                'stok'     => (int)$m->stok,
            ]);

        return view('kasir.reservasi', compact('reservasis', 'menus'));
    }

    // ==========================================
    // AKTIFKAN RESERVASI (dari modal)
    // ==========================================
    public function aktifkanReservasi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);

            if ($transaksi->status !== self::STATUS_RESERVASI) {
                return response()->json(['success' => false, 'message' => 'Bukan reservasi yang valid.'], 422);
            }

            // Hapus semua detail lama, ganti dengan item terbaru dari modal
            // (modal cart sudah berisi pre-order + tambahan sekaligus)
            $existingIds = $transaksi->detailTransaksi->pluck('id_menu')->toArray();
            $existingQty = $transaksi->detailTransaksi->pluck('qty', 'id_menu')->toArray();

            // Kembalikan stok item lama dulu sebelum di-replace
            foreach ($transaksi->detailTransaksi as $old) {
                Menu::where('id', $old->id_menu)->increment('stok', $old->qty);
            }
            $transaksi->detailTransaksi()->delete();

            $total = 0;
            foreach ($request->items ?? [] as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_menu'      => $item['id'],
                    'qty'          => $item['qty'],
                    'harga_satuan' => $item['harga'],
                    'subtotal'     => $item['harga'] * $item['qty'],
                ]);
                Menu::where('id', $item['id'])->decrement('stok', $item['qty']);
                $total += $item['harga'] * $item['qty'];
            }

            $transaksi->update([
                'status'          => self::STATUS_TUNGGAK,
                'jenis_pemesanan' => 'dine_in',
                'tanggal'         => now(),
                'total_harga'     => $total,
            ]);

            if ($transaksi->id_meja) {
                Meja::where('id', $transaksi->id_meja)->update(['status' => 'terisi']);
            }

            DB::commit();

            Log::create([
                'id_user'   => Auth::id(),
                'aktivitas' => 'Mengaktifkan reservasi',
                'detail'    => 'Reservasi #' . $transaksi->no_transaksi . ' diaktifkan - Total: Rp ' . number_format($total, 0, ',', '.'),
                'waktu'     => now(),
            ]);

            return response()->json([
                'success'      => true,
                'message'      => 'Reservasi berhasil diaktifkan.',
                'no_transaksi' => $transaksi->no_transaksi,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // BATALKAN TRANSAKSI
    // ==========================================
    public function batalTransaksi($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);

            // Cek kalau sudah lunas, tidak boleh dibatalkan
            if ($transaksi->status === self::STATUS_LUNAS) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi sudah lunas, tidak bisa dibatalkan.'
                ], 400);
            }

            // Kembalikan stok menu yang sudah dipesan
            foreach ($transaksi->detailTransaksi as $detail) {
                Menu::where('id', $detail->id_menu)->increment('stok', $detail->qty);
            }

            // Kembalikan status meja jadi tersedia (khusus dine_in)
            if (in_array($transaksi->jenis_pemesanan, ['dine_in']) && $transaksi->id_meja) {
                Meja::where('id', $transaksi->id_meja)->update(['status' => 'tersedia']);
            }

            // === PERUBAHAN UTAMA ===
            $transaksi->update([
                'status' => 'batal'        // hanya ubah status, TIDAK DIHAPUS
            ]);

            Log::create([
                'id_user'   => Auth::id(),
                'aktivitas' => 'Membatalkan reservasi',
                'detail'    => 'Membatalkan reservasi #' . ($transaksi->no_transaksi ?? 'TRX-' . $transaksi->id),
                'waktu'     => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan reservasi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==========================================
    // GET RESERVASI AKTIF (untuk polling di halaman reservasi)
    // ==========================================
    public function getReservasiAktif()
    {
        $reservasis = Transaksi::where('jenis_pemesanan', 'reservasi')
            ->where('status', self::STATUS_RESERVASI)
            ->whereDate('tanggal_reservasi', '>=', now()->subDay()) // H+1 masih tampil
            ->with(['detailTransaksi', 'meja'])
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'id_meja' => $r->id_meja,
                    'tanggal_reservasi' => $r->tanggal_reservasi,
                    'jam_reservasi' => $r->jam_reservasi,
                    'status' => $r->status,
                    'items' => $r->detailTransaksi->map(function ($d) {
                        return [
                            'id_menu' => $d->id_menu,
                            'qty' => $d->qty,
                            'nama' => $d->menu->nama_makanan ?? '-'
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $reservasis
        ]);
    }

    // ==========================================
    // RIWAYAT TRANSAKSI (Hanya transaksi yang sudah aktif)
    // ==========================================
    public function riwayat(Request $request)
    {
        $transaksis = Transaksi::with(['detailTransaksi.menu', 'meja', 'kasir'])
            ->whereDate('created_at', Carbon::today())
            ->where('status', '!=', 'batal')                    // ← Tambahkan ini
            ->where(function ($query) {
                $query->where('jenis_pemesanan', '!=', 'reservasi')
                    ->orWhere(function ($q) {
                        $q->where('jenis_pemesanan', 'reservasi')
                            ->where('status', '!=', self::STATUS_RESERVASI); // ini boleh tetap
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                $t->nama_kasir     = $t->kasir->nama ?? '-';
                $t->nama_pelanggan = $t->nama_pelanggan ?? '-';
                $t->nama_meja      = $t->meja->no_meja ?? null;
                $t->tipe_order     = $t->jenis_pemesanan;
                $t->tanggal_reservasi = $t->tanggal_reservasi;
                $t->jam_reservasi     = $t->jam_reservasi;
                $t->items = $t->detailTransaksi->map(function ($d) {
                    return (object)[
                        'nama'         => $d->menu->nama_makanan ?? '-',
                        'qty'          => $d->qty,
                        'harga_satuan' => $d->harga_satuan,
                    ];
                });
                return $t;
            });

        $totalHariIni    = $transaksis->where('status', self::STATUS_LUNAS)->sum('total_harga');
        $jumlahTransaksi = $transaksis->count();

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat riwayat transaksi',
            'detail'    => 'Menampilkan ' . $jumlahTransaksi . ' transaksi hari ini',
            'waktu'     => now(),
        ]);

        return view('kasir.riwayat', compact('transaksis', 'totalHariIni', 'jumlahTransaksi'));
    }

    // ==========================================
    // TAGIH PEMBAYARAN
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
                return response()->json(['success' => false, 'message' => 'Transaksi tidak bisa ditagih.'], 422);
            }

            if ($request->jumlah_bayar < $transaksi->total_harga) {
                return response()->json(['success' => false, 'message' => 'Jumlah bayar kurang.'], 422);
            }

            $transaksi->update([
                'status'       => self::STATUS_LUNAS,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian'    => $request->kembalian,
            ]);

            if ($transaksi->jenis_pemesanan === 'dine_in' && $transaksi->id_meja) {
                Meja::where('id', $transaksi->id_meja)->update(['status' => 'tersedia']);
            }

            DB::commit();

            Log::create([
                'id_user'   => Auth::id(),
                'aktivitas' => 'Melakukan pembayaran',
                'detail'    => 'Transaksi #' . ($transaksi->no_transaksi ?? 'TRX-' . $transaksi->id),
                'waktu'     => now(),
            ]);

            return response()->json([
                'success'      => true,
                'message'      => 'Pembayaran berhasil.',
                'no_transaksi' => $transaksi->no_transaksi ?? ('TRX-' . $transaksi->id),
                'total'        => $transaksi->total_harga,
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian'    => $request->kembalian,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // CETAK STRUK
    // ==========================================
    public function cetakStruk($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.menu', 'meja', 'kasir'])->findOrFail($id);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Mencetak struk',
            'detail'    => 'Mencetak struk untuk transaksi #' . ($transaksi->no_transaksi ?? 'TRX-' . $transaksi->id),
            'waktu'     => now(),
        ]);

        return view('kasir.struk', compact('transaksi'));
    }

    // ==========================================
    // INDEX MENU
    // ==========================================
    public function indexMenu()
    {
        $menus = Menu::with('kategori')->orderBy('nama_makanan')->get();

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat daftar menu',
            'detail'    => 'Kasir melihat ' . $menus->count() . ' menu',
            'waktu'     => now(),
        ]);

        return view('kasir.menu', compact('menus'));
    }

    // ==========================================
    // INDEX MEJA
    // ==========================================
    public function indexMeja()
    {
        $mejas   = Meja::orderBy('no_meja')->get();
        $tersedia = $mejas->where('status', 'tersedia')->count();
        $terisi   = $mejas->where('status', 'terisi')->count();

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat daftar meja',
            'detail'    => 'Kasir melihat ' . $mejas->count() . ' meja (Tersedia: ' . $tersedia . ', Terisi: ' . $terisi . ')',
            'waktu'     => now(),
        ]);

        return view('kasir.meja', compact('mejas', 'tersedia', 'terisi'));
    }
}
