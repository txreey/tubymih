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
        // ✅ VALIDASI INPUT
        $validated = $request->validate([
            'dari' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('sampai') && $value > $request->sampai) {
                        $fail('Tanggal "Dari" tidak boleh melebihi tanggal "Sampai".');
                    }
                    // Opsional: batasi maksimal 1 tahun ke belakang
                    if ($value && \Carbon\Carbon::parse($value)->lt(\Carbon\Carbon::now()->subYears(2))) {
                        $fail('Tanggal "Dari" tidak boleh lebih dari 2 tahun ke belakang.');
                    }
                },
            ],
            'sampai' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('dari') && $value < $request->dari) {
                        $fail('Tanggal "Sampai" tidak boleh sebelum tanggal "Dari".');
                    }
                    // Opsional: tidak boleh tanggal masa depan
                    if ($value && \Carbon\Carbon::parse($value)->gt(\Carbon\Carbon::now())) {
                        $fail('Tanggal "Sampai" tidak boleh melebihi hari ini.');
                    }
                },
            ],
            'id_kasir' => 'nullable|exists:users,id',
            'export' => 'nullable|in:csv',
        ], [
            'dari.date' => 'Format tanggal "Dari" tidak valid.',
            'sampai.date' => 'Format tanggal "Sampai" tidak valid.',
        ]);

        // LOG: Owner melihat laporan
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat laporan',
            'detail' => 'Owner melihat laporan pendapatan' .
                ($request->filled('dari') ? " ({$validated['dari']} s/d {$validated['sampai']})" : ''),
            'waktu' => now(),
        ]);

        $kasirs = User::where('role', 'kasir')->orderBy('nama')->get();

        $query = Transaksi::with(['kasir', 'detailTransaksi'])
            ->where('status', 'lunas');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $validated['dari']);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $validated['sampai']);
        }
        if ($request->filled('id_kasir')) {
            $query->where('id_kasir', $validated['id_kasir']);
        }

        // ✅ HANDLE EXPORT CSV
        if ($request->filled('export') && $request->export === 'csv') {
            return $this->exportLaporanCsv($query, $request->all());
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

        // Data Grafik
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

    /**
     * Export laporan ke format CSV
     */
    private function exportLaporanCsv($query, array $filters)
    {
        try {
            // Ambil data dengan filter yang sama
            $transaksis = $query->latest()->get();

            // Grouping sama seperti view
            $laporanData = $transaksis->groupBy(function ($trx) {
                $tanggalStr = $trx->tanggal
                    ? \Carbon\Carbon::parse($trx->tanggal)->format('Y-m-d')
                    : '0000-00-00';
                return $tanggalStr . '|' . ($trx->id_kasir ?? 'unknown');
            })->map(function ($group) {
                $first = $group->first();
                return [
                    'tanggal'    => $first->tanggal ? \Carbon\Carbon::parse($first->tanggal)->format('d-m-Y') : '-',
                    'kasir'      => $first->kasir?->nama ?? '-',
                    'transaksi'  => $group->count(),
                    'penjualan'  => $group->sum(fn($trx) => $trx->detailTransaksi->sum('qty') ?? 0),
                    'pendapatan' => $group->sum('total_harga'),
                ];
            })->values();

            // Siapkan nama file
            $filename = 'laporan_pendapatan_' . date('Y-m-d_His') . '.csv';

            // Headers yang benar untuk CSV download
            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ];

            // Buffer output untuk mencegah whitespace/error keluar duluan
            if (ob_get_level()) {
                ob_end_clean();
            }

            // Generate CSV content di memory (lebih aman daripada stream langsung)
            $output = fopen('php://temp', 'r+');

            // BOM untuk Excel support UTF-8
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($output, ['LAPORAN PENDAPATAN']);
            fputcsv($output, ['Periode: ' . ($filters['dari'] ?? '-') . ' s/d ' . ($filters['sampai'] ?? '-')]);
            fputcsv($output, [
                'Kasir: ' .
                    (!empty($filters['id_kasir'])
                        ? (\App\Models\User::find($filters['id_kasir'])?->nama ?? '-')
                        : 'Semua Kasir')
            ]);
            fputcsv($output, ['']); // Empty row

            // Column headers
            fputcsv($output, ['No', 'Tanggal', 'Kasir', 'Jumlah Transaksi', 'Total Item Terjual', 'Pendapatan (Rp)']);

            // Data rows
            foreach ($laporanData as $index => $row) {
                fputcsv($output, [
                    $index + 1,
                    $row['tanggal'],
                    $row['kasir'],
                    $row['transaksi'],
                    $row['penjualan'],
                    number_format($row['pendapatan'], 0, ',', '.'),
                ]);
            }

            // Total row
            fputcsv($output, ['']);
            fputcsv($output, [
                'TOTAL',
                '',
                '',
                $laporanData->sum('transaksi') . ' transaksi',
                $laporanData->sum('penjualan') . ' item',
                'Rp ' . number_format($laporanData->sum('pendapatan'), 0, ',', '.'),
            ]);

            // Rewind dan ambil content
            rewind($output);
            $csvContent = stream_get_contents($output);
            fclose($output);

            // Return response dengan content yang sudah jadi
            return response($csvContent, 200, $headers);
        } catch (\Exception $e) {
            // Kalau error, redirect back dengan pesan error
            \Log::error('Export CSV Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
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
