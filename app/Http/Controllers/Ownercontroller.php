<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\Log;
use App\Models\User;
use App\Models\Meja;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;


class OwnerController extends Controller
{
    // ==========================================
    // DASHBOARD OWNER
    // ==========================================
    public function dashboard()
    {
        // LOG: Owner melihat dashboard
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat dashboard',
            'detail'   => 'Owner melihat dashboard',
            'waktu'    => now(),
        ]);

        // 1. Total Menu 
        $total_menu = Menu::count();

        // 2. Total Kasir Aktif (tetap dihitung untuk cadangan)
        $total_kasir = User::where('role', 'kasir')
            ->where('status', 'aktif')
            ->count();

        // 3. Total Meja
        $total_meja = Meja::count();

        // 4. Total User (Hanya Admin + Kasir, Owner TIDAK DIHITUNG)
        $total_user = User::whereIn('role', ['admin', 'kasir'])
            ->count();

        // 5. Pendapatan Hari Ini (hanya lunas)
        $pendapatan_hari = Transaksi::where('status', 'lunas')
            ->whereDate('tanggal', Carbon::today())
            ->sum('total_harga');

        // 6. Pendapatan Bulan Ini
        $pendapatan_bulan = Transaksi::where('status', 'lunas')
            ->whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('total_harga');

        // 7. Pendapatan Tahun Ini
        $pendapatan_tahun = Transaksi::where('status', 'lunas')
            ->whereYear('tanggal', Carbon::now()->year)
            ->sum('total_harga');

        // 8. Jumlah Transaksi Hari Ini (semua status)
        $transaksi_hari_ini = Transaksi::whereDate('tanggal', Carbon::today())
            ->count();

        // 9. Pendapatan 7 Hari Terakhir
        $transaksi_7hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);

            $total = Transaksi::where('status', 'lunas')
                ->whereDate('tanggal', $tanggal)
                ->sum('total_harga');

            $transaksi_7hari->push([
                'tanggal' => $tanggal,
                'total'   => $total ?? 0,
            ]);
        }

        // 10. MENU TERLARIS 7 HARI TERAKHIR (REAL) - Maksimal 5
        $menu_terlaris = \App\Models\DetailTransaksi::select('id_menu')
            ->with('menu')
            ->whereHas('transaksi', function ($query) {
                $query->where('status', 'lunas')
                    ->where('tanggal', '>=', Carbon::today()->subDays(7));
            })
            ->groupBy('id_menu')
            ->selectRaw('id_menu, SUM(qty) as total_terjual')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->menu->nama_makanan ?? 'Menu Tidak Diketahui',
                    'qty'  => $item->total_terjual ?? 0,
                ];
            });

        // Jika tidak ada data transaksi
        if ($menu_terlaris->isEmpty()) {
            $menu_terlaris = collect([
                ['name' => 'Belum ada transaksi', 'qty' => 0],
            ]);
        }

        // ==================== KIRIM KE VIEW ====================
        $data = [
            'total_menu'         => $total_menu,
            'total_user'         => $total_user,
            'total_meja'         => $total_meja,
            'total_kasir'        => $total_kasir,

            'pendapatan_hari'    => $pendapatan_hari,
            'pendapatan_bulan'   => $pendapatan_bulan,
            'pendapatan_tahun'   => $pendapatan_tahun,

            'transaksi_hari_ini' => $transaksi_hari_ini,
            'transaksi_7hari'    => $transaksi_7hari,
            'menu_terlaris'      => $menu_terlaris,
        ];

        return view('owner.dashboard', compact('data'));
    }

    // ==========================================
    // LIHAT MENU (read-only)
    // ==========================================
    public function indexMenu(Request $request)
    {
        // LOG: Owner melihat daftar menu
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar menu',
            'detail' => 'Owner melihat daftar menu',
            'waktu' => now(),
        ]);

        // Kirim SEMUA data ke view — filter & pagination ditangani JS
        $menus = Menu::with('kategori')->orderBy('nama_makanan')->get();

        return view('owner.menu', compact('menus'));
    }

    // ==========================================
    // USERS CRUD
    // ==========================================
    public function indexUser(Request $request)
    {
        // LOG: Owner melihat daftar user
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar user',
            'detail' => 'Owner melihat daftar user',
            'waktu' => now(),
        ]);

        // Kirim semua user non-owner — filter & pagination ditangani JS
        $users = User::whereIn('role', ['admin', 'kasir'])
            ->orderBy('nama')
            ->get();

        return view('owner.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:kasir,admin',
            'status'   => 'required|in:aktif,nonaktif',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        $user = User::create([
            'nama'     => $validated['nama'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role'     => $validated['role'],
            'status'   => $validated['status'],
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
        ]);

        // LOG: Owner menambah user baru
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menambah user baru',
            'detail' => 'Menambah user: ' . $user->nama . ' (Role: ' . $user->role . ')',
            'waktu' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return redirect()->route('owner.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $namaLama = $user->nama;
        $roleLama = $user->role;

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id . '|max:255',
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:kasir,admin',
            'status'   => 'required|in:aktif,nonaktif',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        $data = [
            'nama'     => $validated['nama'],
            'username' => $validated['username'],
            'role'     => $validated['role'],
            'status'   => $validated['status'],
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        $user->refresh();

        // LOG: Owner mengupdate user
        $perubahan = [];
        if ($namaLama != $user->nama) $perubahan[] = 'Nama: ' . $namaLama . ' → ' . $user->nama;
        if ($roleLama != $user->role) $perubahan[] = 'Role: ' . $roleLama . ' → ' . $user->role;

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Mengupdate user',
            'detail' => 'User: ' . $user->nama . ' - ' . implode(', ', $perubahan),
            'waktu' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'user' => $user]);
        }

        return redirect()->route('owner.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroyUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $namaUser = $user->nama;
        $roleUser = $user->role;

        $user->delete();

        // LOG: Owner menghapus user
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menghapus user',
            'detail' => 'Menghapus user: ' . $namaUser . ' (Role: ' . $roleUser . ')',
            'waktu' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus.']);
        }

        return redirect()->route('owner.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatusUser(Request $request, $id)
    {
        $user         = User::findOrFail($id);
        $statusLama = $user->status;
        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        // LOG: Owner mengubah status user
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Mengubah status user',
            'detail' => 'User: ' . $user->nama . ' - Status: ' . $statusLama . ' → ' . $user->status,
            'waktu' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah menjadi ' . $user->status . '.',
                'status'  => $user->status,
            ]);
        }

        return redirect()->route('owner.users.index')->with('success', 'Status user berhasil diubah.');
    }

    // ==========================================
    // LIHAT MEJA (read-only)
    // ==========================================
    public function indexMeja(Request $request)
    {
        // LOG: Owner melihat daftar meja
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar meja',
            'detail' => 'Owner melihat daftar meja',
            'waktu' => now(),
        ]);

        // Kirim SEMUA data ke view — filter & pagination ditangani JS
        $mejas = Meja::orderBy('no_meja')->get();

        return view('owner.meja', compact('mejas'));
    }

    // ==========================================
    // RIWAYAT TRANSAKSI
    // ==========================================
    public function riwayatTransaksi(Request $request)
    {
        // LOG
        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat riwayat transaksi',
            'detail'    => 'Owner melihat riwayat transaksi',
            'waktu'     => now(),
        ]);

        $query = Transaksi::with(['kasir', 'meja', 'detailTransaksi.menu'])
            ->latest();

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search_kasir')) {
            $query->whereHas('kasir', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search_kasir . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transaksis = $query->get()->map(function ($t) {
            $t->nama_kasir      = $t->kasir->nama ?? '-';
            $t->nama_pelanggan  = $t->nama_pelanggan ?? '-';
            $t->tipe_order      = $t->jenis_pemesanan ?? $t->tipe_order ?? '-';
            $t->nama_meja       = $t->nama_meja ?? '-';

            // Mapping items supaya sama dengan halaman Kasir
            $t->items = $t->detailTransaksi->map(function ($d) {
                return (object)[
                    'nama'         => $d->menu->nama_makanan ?? $d->nama_menu ?? '-',
                    'qty'          => $d->qty,
                    'harga_satuan' => $d->harga_satuan,
                ];
            });

            return $t;
        });

        // Data untuk card statistik
        $totalTransaksi = Transaksi::count();
        $totalLunas     = Transaksi::where('status', 'lunas')->count();
        $totalNunggak   = Transaksi::where('status', 'tunggak')->count();

        return view('owner.riwayat', compact('transaksis', 'totalTransaksi', 'totalLunas', 'totalNunggak'));
    }

    // ==========================================
    // LAPORAN - TAMPILAN WEB
    // ==========================================

    public function laporan(Request $request)
    {
        $validated = $request->validate([
            'dari'     => 'nullable|date|date_format:Y-m-d',
            'sampai'   => 'nullable|date|date_format:Y-m-d|after_or_equal:dari',
            'id_kasir' => 'nullable|exists:users,id',
        ]);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat laporan',
            'detail'    => 'Owner melihat laporan pendapatan',
            'waktu'     => now(),
        ]);

        $kasirs = User::where('role', 'kasir')->orderBy('nama')->get();

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'lunas')
            ->latest();

        if ($request->filled('dari')) $query->whereDate('tanggal', '>=', $validated['dari']);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $validated['sampai']);
        if ($request->filled('id_kasir')) $query->where('id_kasir', $validated['id_kasir']);

        $transaksis = $query->get();

        $totalTransaksi  = $transaksis->count();
        $totalPenjualan  = $transaksis->sum(fn($trx) => $trx->detailTransaksi->sum('qty'));
        $totalPendapatan = $transaksis->sum('total_harga');

        $laporanRaw = $transaksis->groupBy(function ($trx) {
            $tanggal = $trx->tanggal ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d') : '0000-00-00';
            return $tanggal . '|' . ($trx->id_kasir ?? 'unknown');
        })->map(function ($group) {
            $first = $group->first();
            return [
                'tanggal'    => $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y') : '-',
                'kasir'      => $first->kasir->nama ?? '-',
                'transaksi'  => $group->count(),
                'penjualan'  => $group->sum(fn($trx) => $trx->detailTransaksi->sum('qty')),
                'pendapatan' => $group->sum('total_harga'),
            ];
        })->values();

        // Paginate manual
        $perPage     = 5;
        $currentPage = $request->input('page', 1);
        $items       = $laporanRaw->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $laporanData = new LengthAwarePaginator($items, $laporanRaw->count(), $perPage, $currentPage, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        $chartData = $transaksis->groupBy(function ($trx) {
            return $trx->tanggal ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d') : '0000-00-00';
        })->map(fn($group) => [
            'tanggal'    => \Carbon\Carbon::parse($group->first()->tanggal)->format('d M'),
            'pendapatan' => $group->sum('total_harga'),
        ])->values();

        return view('owner.laporan', compact(
            'kasirs',
            'laporanData',
            'totalTransaksi',
            'totalPenjualan',
            'totalPendapatan',
            'chartData'
        ));
    }

    // ==========================================
    // EXPORT EXCEL
    // ==========================================
    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'dari'     => 'nullable|date|date_format:Y-m-d',
            'sampai'   => 'nullable|date|date_format:Y-m-d|after_or_equal:dari',
            'id_kasir' => 'nullable|exists:users,id',
        ]);

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'lunas')
            ->latest();

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $validated['dari']);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $validated['sampai']);
        }
        if ($request->filled('id_kasir')) {
            $query->where('id_kasir', $validated['id_kasir']);
        }

        $transaksis = $query->get();

        $export = new \App\Exports\LaporanPendapatanExport($transaksis, $validated);
        $filename = 'laporan_pendapatan_' . now()->format('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
    }

    // ==========================================
    // EXPORT PDF
    // ==========================================
    public function exportPdf(Request $request)
    {
        $validated = $request->validate([
            'dari'     => 'nullable|date|date_format:Y-m-d',
            'sampai'   => 'nullable|date|date_format:Y-m-d|after_or_equal:dari',
            'id_kasir' => 'nullable|exists:users,id',
        ]);

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'lunas')
            ->latest();

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $validated['dari']);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $validated['sampai']);
        }
        if ($request->filled('id_kasir')) {
            $query->where('id_kasir', $validated['id_kasir']);
        }

        $transaksis = $query->get();

        $export = new \App\Exports\LaporanPendapatanExport($transaksis, $validated);
        $filename = 'laporan_pendapatan_' . now()->format('Ymd_His') . '.pdf';

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            $filename,
            \Maatwebsite\Excel\Excel::DOMPDF
        );
    }

    // ==========================================
    // LOG AKTIVITAS
    // ==========================================
    public function logAktivitas(Request $request)
    {
        $query = Log::with('user')->orderBy('waktu', 'desc');

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('waktu', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('waktu', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('role')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->role);
            });
        }

        $total = $query->count();
        $logs  = $query->paginate(5);

        return view('owner.log', compact('logs', 'total'));
    }
}
