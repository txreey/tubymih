<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Menu;
use App\Models\User;
use App\Models\Meja;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    // ==========================================
    // DASHBOARD OWNER
    // ==========================================
    public function dashboard()
    {
        $data = [
            'total_menu'       => 48,
            'total_user_aktif' => 12,
            'total_meja'       => 20,
            'total_kasir'      => 5,

            'pendapatan_hari'  => 2450000,
            'pendapatan_bulan' => 48750000,

            'transaksi_7hari'  => collect([
                ['tanggal' => Carbon::today()->subDays(6), 'total' => 1800000],
                ['tanggal' => Carbon::today()->subDays(5), 'total' => 2200000],
                ['tanggal' => Carbon::today()->subDays(4), 'total' => 1950000],
                ['tanggal' => Carbon::today()->subDays(3), 'total' => 2800000],
                ['tanggal' => Carbon::today()->subDays(2), 'total' => 3200000],
                ['tanggal' => Carbon::today()->subDays(1), 'total' => 4500000],
                ['tanggal' => Carbon::today(),             'total' => 3800000],
            ]),
        ];

        return view('owner.dashboard', compact('data'));
    }

    // ==========================================
    // LIHAT MENU (read-only)
    // ==========================================
    public function indexMenu(Request $request)
    {
        $query = Menu::with('kategori')->orderBy('nama_makanan');

        if ($request->filled('search')) {
            $query->where('nama_makanan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        $menus = $query->get();

        // Ringkasan tetap dari semua data (bukan hasil filter)
        $totalMenu     = Menu::count();
        $totalMakanan  = Menu::whereHas('kategori', fn($q) => $q->where('nama_kategori', 'Makanan'))->count();
        $totalMinuman  = Menu::whereHas('kategori', fn($q) => $q->where('nama_kategori', 'Minuman'))->count();

        return view('owner.menu', compact('menus', 'totalMenu', 'totalMakanan', 'totalMinuman'));
    }

    // ==========================================
    // USERS CRUD
    // ==========================================
    public function indexUser(Request $request)
    {
        $query = User::wherein('role', ['admin', 'kasir'])->orderBy('nama');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('username', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('nama')->get();

        $totalUser     = User::count();
        $totalAktif    = User::where('status', 'aktif')->count();
        $totalNonaktif = User::where('status', 'nonaktif')->count();

        return view('owner.users', compact('users', 'totalUser', 'totalAktif', 'totalNonaktif'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:kasir,admin,owner',
            'status'   => 'required|in:aktif,nonaktif',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        User::create([
            'nama'     => $request->nama,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
        ]);

        return redirect()->route('owner.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $id . '|max:255',
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:kasir,admin,owner',
            'status'   => 'required|in:aktif,nonaktif',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
        ]);

        $data = [
            'nama'     => $request->nama,
            'username' => $request->username,
            'role'     => $request->role,
            'status'   => $request->status,
            'no_hp'    => $request->no_hp,
            'alamat'   => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('owner.users.index')->with('success', 'User berhasil diupdate.');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('owner.users.index')->with('success', 'User berhasil dihapus.');
    }

    public function toggleStatusUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return redirect()->route('owner.users.index')->with('success', 'Status user berhasil diubah.');
    }

    // ==========================================
    // LIHAT MEJA (read-only)
    // ==========================================
    public function indexMeja(Request $request)
    {
        $query = Meja::orderBy('no_meja');

        if ($request->filled('search')) {
            $query->where('no_meja', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe_meja')) {
            $query->where('tipe_meja', $request->tipe_meja);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $mejas = $query->get();

        // Ringkasan tetap dari semua data (bukan hasil filter)
        $totalMeja    = Meja::count();
        $mejaTersedia = Meja::where('status', 'tersedia')->count();
        $mejaTerisi   = Meja::whereIn('status', ['terisi', 'reserved'])->count();

        return view('owner.meja', compact('mejas', 'totalMeja', 'mejaTersedia', 'mejaTerisi'));
    }

    // ==========================================
    // RIWAYAT TRANSAKSI (sama seperti admin, tapi owner bisa lihat semua)
    // ==========================================
    public function riwayatTransaksi(Request $request)
    {
        $query = Transaksi::with(['kasir', 'meja'])->latest();

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

        $transaksis = $query->get();

        // Ringkasan tetap dari semua data (bukan hasil filter)
        $totalTransaksi = Transaksi::count();
        $totalSelesai   = Transaksi::where('status', 'selesai')->count();
        $totalPending   = Transaksi::whereIn('status', ['pending', 'proses'])->count();

        return view('owner.riwayat', compact('transaksis', 'totalTransaksi', 'totalSelesai', 'totalPending'));
    }

    // ==========================================
    // LAPORAN (pendapatan + statistik)
    // ==========================================
    public function laporan(Request $request)
    {
        $kasirs = User::where('role', 'kasir')->orderBy('nama')->get();

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'selesai');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        if ($request->filled('id_kasir')) {
            $query->where('id_kasir', $request->id_kasir);
        }

        $transaksis = $query->latest()->get();

        // Group per kasir per tanggal
        $laporanData = $transaksis->groupBy(function ($trx) {
            return $trx->tanggal->format('Y-m-d') . '|' . ($trx->id_kasir ?? 'unknown');
        })->map(function ($group) {
            $first = $group->first();
            return [
                'tanggal'      => $first->tanggal->format('d-m-Y'),
                'kasir'        => $first->kasir->nama ?? '-',
                'transaksi'    => $group->count(),
                'penjualan'    => $group->sum(fn($trx) => $trx->detailTransaksi->sum('qty')),
                'pendapatan'   => $group->sum('total_harga'),
            ];
        })->values();

        $totalPendapatan = $transaksis->sum('total_harga');

        return view('owner.laporan', compact('kasirs', 'laporanData', 'totalPendapatan'));
    }

    // ==========================================
    // LOG AKTIVITAS (dummy dulu, nanti bisa pakai spatie/laravel-activitylog)
    // ==========================================
    public function logAktivitas(Request $request)
    {
        // Dummy log aktivitas
        $allLogs = [
            ['waktu' => now()->subHours(2),  'user' => 'Admin',  'role' => 'admin',  'aksi' => 'Login berhasil',               'detail' => 'IP: 127.0.0.1'],
            ['waktu' => now()->subHours(5),  'user' => 'Kasir1', 'role' => 'kasir',  'aksi' => 'Tambah transaksi #TRX-001',    'detail' => 'Total Rp 150.000'],
            ['waktu' => now()->subDay(),     'user' => 'Owner',  'role' => 'owner',  'aksi' => 'Update menu Nasi Goreng',      'detail' => 'Harga dari 25.000 → 28.000'],
            ['waktu' => now()->subDays(2),   'user' => 'Kasir1', 'role' => 'kasir',  'aksi' => 'Tambah transaksi #TRX-002',    'detail' => 'Total Rp 75.000'],
            ['waktu' => now()->subDays(2),   'user' => 'Admin',  'role' => 'admin',  'aksi' => 'Hapus user Kasir2',            'detail' => 'User ID: 5'],
        ];

        // Filter search
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allLogs = array_filter(
                $allLogs,
                fn($log) =>
                str_contains(strtolower($log['user']), $search) ||
                    str_contains(strtolower($log['aksi']), $search)
            );
        }

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $allLogs = array_filter(
                $allLogs,
                fn($log) =>
                $log['waktu']->format('Y-m-d') === $request->tanggal
            );
        }

        // Filter role
        if ($request->filled('role')) {
            $allLogs = array_filter(
                $allLogs,
                fn($log) =>
                $log['role'] === $request->role
            );
        }

        $logs = array_values($allLogs);

        // Handle export CSV
        if ($request->get('export') === 'csv') {
            $filename = 'log-aktivitas-' . now()->format('Ymd-His') . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Waktu', 'User', 'Role', 'Aksi', 'Detail']);
                foreach ($logs as $i => $log) {
                    fputcsv($file, [
                        $i + 1,
                        $log['waktu']->format('d-m-Y H:i'),
                        $log['user'],
                        $log['role'] ?? '-',
                        $log['aksi'],
                        $log['detail'],
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('owner.log', compact('logs'));
    }
}
