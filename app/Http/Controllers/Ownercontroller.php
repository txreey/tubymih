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
            'detail' => 'Owner melihat dashboard',
            'waktu' => now(),
        ]);

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
        // LOG: Owner melihat riwayat transaksi
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat riwayat transaksi',
            'detail' => 'Owner melihat riwayat transaksi',
            'waktu' => now(),
        ]);

        $query = Transaksi::with(['kasir', 'meja', 'detailTransaksi'])->latest();

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

        // Data real untuk card (menggunakan status yang sama dengan Admin)
        $totalTransaksi = Transaksi::count();
        $totalLunas     = Transaksi::where('status', 'lunas')->count();
        $totalNunggak   = Transaksi::where('status', 'tunggak')->count();

        return view('owner.riwayat', compact('transaksis', 'totalTransaksi', 'totalLunas', 'totalNunggak'));
    }

    // ==========================================
    // LAPORAN
    // ==========================================
    public function laporan(Request $request)
    {
        // LOG: Owner melihat laporan
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat laporan',
            'detail' => 'Owner melihat laporan pendapatan',
            'waktu' => now(),
        ]);

        $kasirs = User::where('role', 'kasir')->orderBy('nama')->get();

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'lunas');

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

        // Ringkasan Card
        $totalTransaksi = $transaksis->count();
        $totalPenjualan = $transaksis->sum(fn($trx) => $trx->detailTransaksi->sum('qty') ?? 0);
        $totalPendapatan = $transaksis->sum('total_harga');

        // Data Tabel
        $laporanData = $transaksis->groupBy(function ($trx) {
            $tanggalStr = $trx->tanggal
                ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d')
                : '0000-00-00';
            return $tanggalStr . '|' . ($trx->id_kasir ?? 'unknown');
        })->map(function ($group) {
            $first = $group->first();
            return [
                'tanggal'    => $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y') : '-',
                'kasir'      => $first->kasir->nama ?? '-',
                'transaksi'  => $group->count(),
                'penjualan'  => $group->sum(fn($trx) => $trx->detailTransaksi->sum('qty') ?? 0),
                'pendapatan' => $group->sum('total_harga'),
            ];
        })->values();

        // === DATA GRAFIK REAL (Per Tanggal) ===
        $chartData = $transaksis->groupBy(function ($trx) {
            return $trx->tanggal
                ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d')
                : '0000-00-00';
        })->map(function ($group) {
            $first = $group->first();
            return [
                'tanggal'    => $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d M') : 'No Date',
                'pendapatan' => $group->sum('total_harga')
            ];
        })->sortBy(function ($item) {
            return \Carbon\Carbon::parse($item['tanggal']);
        })->values();

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
    // LOG AKTIVITAS
    // ==========================================
    public function logAktivitas(Request $request)
    {
        $query = Log::with('user')->orderBy('waktu', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                    ->orWhere('detail', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQ) use ($search) {
                        $userQ->where('name', 'like', "%{$search}%");
                    });
            });
        }

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

        // Export CSV (pakai get() khusus untuk export)
        if ($request->get('export') === 'csv') {
            $logs = $query->get();
            $filename = 'log-aktivitas-' . now()->format('Ymd-His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Waktu', 'User', 'Role', 'Aksi', 'Detail']);
                foreach ($logs as $i => $log) {
                    fputcsv($file, [
                        $i + 1,
                        $log->waktu->format('d-m-Y H:i'),
                        $log->user->name ?? 'Unknown',
                        $log->user->role ?? '-',
                        $log->aktivitas,
                        $log->detail ?? '-',
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $total = $query->count(); // hitung total sebelum paginate
        $logs = $query->paginate(15); // pakai paginate bukan get()

        return view('owner.log', compact('logs', 'total'));
    }
}
