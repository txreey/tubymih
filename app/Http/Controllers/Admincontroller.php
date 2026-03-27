<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\Meja;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // DASHBOARD (tetep sama)
    public function dashboard()
    {
        $data = [
            'total_menu'            => 24,
            'total_kategori'        => 8,
            'total_transaksi'       => 15,
            'transaksi_belum_lunas' => 3,
            'total_kasir'           => 4,
            'total_meja'            => 15,
            'meja_tersedia'         => 8,
            'pendapatan_hari'       => 2450000,
            'persentase_pendapatan' => '+12% dari kemarin',

            'menu_terlaris' => [
                ['nama_makanan' => 'Nasi Goreng Spesial', 'total' => 18],
                ['nama_makanan' => 'Ayam Bakar',          'total' => 14],
                ['nama_makanan' => 'Es Teh Manis',        'total' => 12],
                ['nama_makanan' => 'Mie Goreng',          'total' => 8],
                ['nama_makanan' => 'Soto Ayam',           'total' => 6],
            ],

            'semua_meja' => collect([
                (object)['no_meja' => 'M01', 'status' => 'terisi'],
                (object)['no_meja' => 'M02', 'status' => 'tersedia'],
                (object)['no_meja' => 'M03', 'status' => 'terisi'],
                (object)['no_meja' => 'M04', 'status' => 'tersedia'],
                (object)['no_meja' => 'M05', 'status' => 'tersedia'],
                (object)['no_meja' => 'M06', 'status' => 'tersedia'],
                (object)['no_meja' => 'M07', 'status' => 'terisi'],
                (object)['no_meja' => 'M08', 'status' => 'tersedia'],
                (object)['no_meja' => 'M09', 'status' => 'terisi'],
                (object)['no_meja' => 'M10', 'status' => 'tersedia'],
                (object)['no_meja' => 'L01', 'status' => 'tersedia'],
                (object)['no_meja' => 'L02', 'status' => 'terisi'],
                (object)['no_meja' => 'L03', 'status' => 'tersedia'],
                (object)['no_meja' => 'K01', 'status' => 'tersedia'],
                (object)['no_meja' => 'K02', 'status' => 'terisi'],
            ]),
        ];

        return view('admin.dashboard', compact('data'));
    }

    // =============================================
    // USER MANAGEMENT – HANYA UNTUK KASIR
    // =============================================
    public function indexUser(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($q, $s) =>
            $q->where('nama', 'like', "%$s%")->orWhere('username', 'like', "%$s%"))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->role,   fn($q, $r) => $q->where('role', $r))
            ->orderByRaw("FIELD(role, 'owner', 'admin', 'kasir')")
            ->orderBy('nama')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users|regex:/^[a-zA-Z0-9._-]+$/',
            'password' => 'required|string|min:6',
            'no_hp'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:aktif,nonaktif',
        ], $this->userMessages());

        $user = User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'role'     => 'kasir',
        ]);

        return response()->json(['success' => true, 'message' => 'Kasir berhasil ditambahkan!', 'user' => $user], 201);
    }

    public function updateUser(Request $request, User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa edit kasir.');

        $data = $request->validate([
            'nama'     => 'required|string|max:100',
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($user->id), 'regex:/^[a-zA-Z0-9._-]+$/'],
            'password' => 'nullable|string|min:6',
            'no_hp'    => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s]{7,20}$/'],
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:aktif,nonaktif',
        ], $this->userMessages());

        $user->update(array_filter([
            'nama'     => $data['nama'],
            'username' => $data['username'],
            'no_hp'    => $data['no_hp'] ?? null,
            'alamat'   => $data['alamat'] ?? null,
            'status'   => $data['status'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : null,
        ], fn($v) => $v !== null));

        return response()->json(['success' => true, 'message' => 'Kasir berhasil diperbarui!', 'user' => $user->fresh()]);
    }

    public function destroyUser(User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa hapus kasir.');
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa hapus akun sendiri.');

        $user->delete();

        return response()->json(['success' => true, 'message' => 'Kasir berhasil dihapus!']);
    }

    public function toggleStatus(User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa toggle kasir.');

        $user->update(['status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif']);

        return response()->json(['success' => true, 'message' => 'Status: ' . ucfirst($user->status), 'status' => $user->status]);
    }

    private function userMessages(): array
    {
        return [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'nama.max'          => 'Nama maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah dipakai.',
            'username.regex'    => 'Username hanya boleh huruf, angka, titik, underscore, strip.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'no_hp.regex'       => 'Format nomor HP tidak valid.',
            'status.in'         => 'Status tidak valid.',
        ];
    }


    // ==========================================
    // KELOLA MENU
    // ==========================================
    public function indexMenu(Request $request)
    {
        $menus = Menu::with('kategori')
            ->when($request->search, fn($q, $s) => $q->where('nama_makanan', 'like', "%$s%"))
            ->when($request->kategori, fn($q, $k) => $q->where('id_kategori', $k))
            ->orderBy('nama_makanan')
            ->get();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.menu', compact('menus', 'kategoris'));
    }

    // STORE MENU - AJAX JSON (tetap sama, gak perlu ubah)
    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id',
            'nama_makanan'  => 'required|string|max:100',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'id_kategori'   => $validated['id_kategori'],
            'nama_makanan'  => $validated['nama_makanan'],
            'harga'         => $validated['harga'],
            'stok'          => $validated['stok'],
        ];

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
            $data['gambar'] = $path;
        }

        $menu = Menu::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'data'    => $menu->load('kategori')
        ]);
    }

    // UPDATE MENU - AJAX JSON (tetap sama)
    public function updateMenu(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'id_kategori'  => 'required|exists:kategori,id',
            'nama_makanan' => 'required|string|max:100',
            'harga'        => 'required|numeric|min:0',
            'stok'         => 'required|integer|min:0',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = collect($validated)->except('gambar')->toArray();

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) Storage::disk('public')->delete($menu->gambar);
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        $menu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!',
            'data'    => $menu->fresh()->load('kategori'),
        ]);
    }

    // DESTROY MENU - AJAX JSON (tetap sama)
    public function destroyMenu(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus!'
        ]);
    }

    // ==========================================
    // KELOLA MEJA
    // ==========================================

    public function indexMeja(Request $request)
    {
        $query = Meja::query();

        // FILTER KAPASITAS - OK, logika benar
        if ($request->filled('kapasitas')) {
            $kapasitas = trim($request->kapasitas);
            if (str_contains($kapasitas, '-')) {
                [$min, $max] = explode('-', $kapasitas);
                $query->whereBetween('kapasitas', [(int) trim($min), (int) trim($max)]);
            } else {
                $query->where('kapasitas', (int) $kapasitas);
            }
        }

        // FILTER TIPE MEJA - OK
        if ($request->filled('tipe_meja')) {
            $query->where('tipe_meja', $request->tipe_meja);
        }

        $mejas = $query->orderBy('no_meja', 'asc')->get();

        return view('admin.meja', compact('mejas')); // ← nama view sudah benar sesuai file lo
    }

    // STORE - OK, return JSON untuk AJAX modal
    public function storeMeja(Request $request)
    {
        $validated = $request->validate([
            'no_meja'   => 'required|string|max:20|unique:meja,no_meja',
            'tipe_meja' => 'nullable|string|max:50',
            'kapasitas' => 'required|string|max:20',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kapasitasInput = trim($validated['kapasitas']);
        if (str_contains($kapasitasInput, '-')) {
            [$min, $max] = explode('-', $kapasitasInput);
            $kapasitas = (int) trim($min);
        } else {
            $kapasitas = (int) $kapasitasInput;
        }

        $meja = Meja::create([
            'no_meja'   => $validated['no_meja'],
            'tipe_meja' => $validated['tipe_meja'],
            'kapasitas' => $kapasitas,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => 'tersedia',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan',
            'data' => $meja
        ]);
    }

    // UPDATE - OK, return JSON
    public function updateMeja(Request $request, Meja $meja)
    {
        $validated = $request->validate([
            'no_meja' => [
                'required',
                'string',
                'max:20',
                \Illuminate\Validation\Rule::unique('meja')->ignore($meja->id)
            ],
            'tipe_meja' => 'nullable|string|max:50',
            'kapasitas' => 'required|string|max:20',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|in:tersedia,terisi,reserved',
        ]);

        $kapasitasInput = trim($validated['kapasitas']);
        if (str_contains($kapasitasInput, '-')) {
            [$min, $max] = explode('-', $kapasitasInput);
            $kapasitas = (int) trim($min);
        } else {
            $kapasitas = (int) $kapasitasInput;
        }

        $meja->update([
            'no_meja'   => $validated['no_meja'],
            'tipe_meja' => $validated['tipe_meja'],
            'kapasitas' => $kapasitas,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diperbarui',
            'data' => $meja
        ]);
    }

    // DESTROY - OK, return JSON
    public function destroyMeja(Meja $meja)
    {
        $meja->delete();

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus'
        ]);
    }

    // INDEX KATEGORI
    // INDEX KATEGORI
    public function indexKategori(Request $request)
    {
        $kategoris = Kategori::withCount('menus')
            ->with('menus:id,id_kategori,nama_makanan')
            ->when($request->search, fn($q, $s) =>
            $q->where('nama_kategori', 'like', "%$s%")->orWhere('jenis', 'like', "%$s%"))
            ->when($request->jenis, fn($q, $j) => $q->where('jenis', $j))
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.kategori', compact('kategoris'));
    }

    // STORE KATEGORI - AJAX JSON
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis'         => 'required|string|max:100',
        ], [
            'nama_kategori.required' => 'Kategori wajib dipilih.',
            'jenis.required'         => 'Jenis wajib dipilih.',
        ]);

        // Cek kombinasi nama_kategori + jenis harus unik
        $exists = Kategori::where('nama_kategori', $request->nama_kategori)
            ->where('jenis', $request->jenis)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi kategori dan jenis ini sudah ada.',
                'errors'  => ['jenis' => ['Jenis "' . $request->jenis . '" di kategori "' . $request->nama_kategori . '" sudah terdaftar.']],
            ], 422);
        }

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'jenis'         => $request->jenis,
            'jumlah'        => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data'    => $kategori,
        ], 201);
    }

    public function updateKategori(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis'         => 'required|string|max:100',
        ], [
            'nama_kategori.required' => 'Kategori wajib dipilih.',
            'jenis.required'         => 'Jenis wajib dipilih.',
        ]);

        // Cek kombinasi unik, kecuali record ini sendiri
        $exists = Kategori::where('nama_kategori', $request->nama_kategori)
            ->where('jenis', $request->jenis)
            ->where('id', '!=', $kategori->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi kategori dan jenis ini sudah ada.',
                'errors'  => ['jenis' => ['Jenis "' . $request->jenis . '" di kategori "' . $request->nama_kategori . '" sudah terdaftar.']],
            ], 422);
        }

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'jenis'         => $request->jenis,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!',
            'data'    => $kategori->fresh(),
        ]);
    }

    // DESTROY KATEGORI - AJAX JSON
    public function destroyKategori(Kategori $kategori)
    {
        if ($kategori->menus()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa hapus kategori yang masih dipakai oleh menu!'
            ], 422);
        }

        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!'
        ]);
    }

    // RIWAYAT TRANSAKSI - UNTUK SEMENTARA PAKE DUMMY DATA AJA, NANTI BISA DIUBAH KE QUERY NYA
    public function indexRiwayat()
    {
        $dummyTransaksi = [
            [
                'id' => 'TRX-20260909001',
                'tanggal' => '09-09-2026',
                'staff' => 'Kasir A (Rina)',
                'items' => [
                    ['nama' => 'Nasi Goreng Spesial', 'qty' => 2, 'harga' => 25000],
                    ['nama' => 'Es Teh Manis', 'qty' => 2, 'harga' => 8000],
                    ['nama' => 'Cappuccino', 'qty' => 1, 'harga' => 28000],
                ],
                'total' => 94000,
                'metode' => 'Cash',
                'bayar' => 100000,
                'kembalian' => 6000,
            ],
            [
                'id' => 'TRX-20260909002',
                'tanggal' => '09-09-2026',
                'staff' => 'Kasir B (Budi)',
                'items' => [
                    ['nama' => 'Mie Ayam Bakso', 'qty' => 1, 'harga' => 22000],
                    ['nama' => 'Jus Mangga', 'qty' => 1, 'harga' => 15000],
                ],
                'total' => 37000,
                'metode' => 'QRIS',
                'bayar' => 37000,
                'kembalian' => 0,
            ],
            [
                'id' => 'TRX-20260909003',
                'tanggal' => '09-09-2026',
                'staff' => 'Kasir A (Rina)',
                'items' => [
                    ['nama' => 'Ayam Bakar Kecap', 'qty' => 1, 'harga' => 32000],
                ],
                'total' => 32000,
                'metode' => 'Cash',
                'bayar' => 50000,
                'kembalian' => 18000,
            ],
            [
                'id' => 'TRX-20260910001',
                'tanggal' => '10-09-2026',
                'staff' => 'Kasir B (Budi)',
                'items' => [
                    ['nama' => 'Nasi Goreng Spesial', 'qty' => 3, 'harga' => 25000],
                    ['nama' => 'Es Teh Manis', 'qty' => 3, 'harga' => 8000],
                ],
                'total' => 99000,
                'metode' => 'QRIS',
                'bayar' => 99000,
                'kembalian' => 0,
            ],
        ];

        $totalTransaksi   = count($dummyTransaksi);
        $totalPendapatan  = array_sum(array_column($dummyTransaksi, 'total'));
        $totalPengeluaran = 125000; // dummy aja

        return view('admin.riwayat', compact(
            'dummyTransaksi',
            'totalTransaksi',
            'totalPendapatan',
            'totalPengeluaran'
        ));
    }

    // LAPORAN PENDAPATAN - dengan filter tanggal dan kasir, serta export CSV
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

        $laporanData = $transaksis->groupBy(function ($trx) {
            return $trx->tanggal->format('Y-m-d') . '|' . ($trx->id_kasir ?? 'unknown');
        })->map(function ($group) {
            $first = $group->first();
            return [
                'tanggal'    => $first->tanggal->format('d-m-Y'),
                'kasir'      => $first->kasir->nama ?? '-',
                'transaksi'  => $group->count(),
                'penjualan'  => $group->sum(fn($trx) => $trx->detailTransaksi->sum('qty')),
                'pendapatan' => $group->sum('total_harga'),
            ];
        })->values();

        $totalPendapatan = $transaksis->sum('total_harga');

        // Handle export CSV
        if ($request->get('export') === 'csv') {
            $filename = 'laporan-pendapatan-' . now()->format('Ymd-His') . '.csv';
            $headers = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($laporanData, $totalPendapatan) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Tanggal', 'Kasir', 'Transaksi', 'Penjualan (item)', 'Pendapatan']);
                foreach ($laporanData as $i => $row) {
                    fputcsv($file, [
                        $i + 1,
                        $row['tanggal'],
                        $row['kasir'],
                        $row['transaksi'],
                        $row['penjualan'],
                        $row['pendapatan'],
                    ]);
                }
                fputcsv($file, ['', '', '', '', 'TOTAL', $totalPendapatan]);
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.laporan', compact('kasirs', 'laporanData', 'totalPendapatan'));
    }
}
