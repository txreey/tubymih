<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Log;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\Meja;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // DASHBOARD (tetep sama)
    public function dashboard()
    {
        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat dashboard',
            'detail'    => 'Admin melihat dashboard',
            'waktu'     => now(),
        ]);

        $semuaMeja    = Meja::orderBy('no_meja')->get();
        $mejaTersedia = $semuaMeja->where('status', 'tersedia')->count();

        $menuTerlaris = DetailTransaksi::with('menu')
            ->whereHas('transaksi', fn($q) => $q->whereDate('created_at', today())->where('status', 'lunas'))
            ->selectRaw('id_menu, SUM(qty) as total')
            ->groupBy('id_menu')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(fn($d) => [
                'nama_makanan' => $d->menu->nama_makanan ?? '-',
                'total'        => (int) $d->total,
            ]);

        $data = [
            'total_menu'      => Menu::count(),
            'total_kategori'  => Kategori::count(),
            'total_transaksi' => Transaksi::count(),
            'total_kasir'     => User::whereIn('role', ['kasir', 'admin'])->count(),
            'total_meja'      => $semuaMeja->count(),
            'meja_tersedia'   => $mejaTersedia,
            'menu_terlaris'   => $menuTerlaris,
            'semua_meja'      => $semuaMeja,
        ];

        return view('admin.dashboard', compact('data'));
    }

    // =============================================
    // USER MANAGEMENT – HANYA UNTUK KASIR
    // =============================================
    public function indexUser(Request $request)
    {
        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Melihat daftar user',
            'detail'    => 'Admin melihat daftar kasir',
            'waktu'     => now(),
        ]);

        $users = User::whereIn('role', ['kasir'])
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('nama', 'like', "%{$s}%")
                        ->orWhere('username', 'like', "%{$s}%");
                });
            })
            ->when($request->status, function ($q, $s) {
                $q->where('status', $s);
            })
            ->orderByRaw("FIELD(role, 'owner', 'admin', 'kasir')")
            ->orderBy('nama')
            ->get();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'username'  => 'required|string|max:100|unique:users|regex:/^[a-zA-Z0-9._-]+$/',
            'password'  => 'required|string|min:6',
            'no_hp'     => ['required', 'regex:/^08[0-9]{10}$/'],
            'alamat'    => 'required|string',
            'status'    => 'required|in:aktif,nonaktif',
        ], $this->userMessages());

        $user = User::create([
            'nama'      => $data['nama'],
            'username'  => $data['username'],
            'password'  => Hash::make($data['password']),
            'no_hp'     => $data['no_hp'],
            'alamat'    => $data['alamat'],
            'status'    => $data['status'],
            'role'      => 'kasir',
        ]);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Menambah kasir baru',
            'detail'    => 'Menambah kasir: ' . $user->nama . ' (Username: ' . $user->username . ')',
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kasir berhasil ditambahkan!',
            'user'    => $user
        ], 201);
    }

    public function updateUser(Request $request, User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa edit kasir.');

        $data = $request->validate([
            'nama'      => 'required|string|max:100',
            'username'  => ['required', 'string', 'max:100', Rule::unique('users')->ignore($user->id), 'regex:/^[a-zA-Z0-9._-]+$/'],
            'password'  => 'nullable|string|min:6',
            'no_hp'     => ['required', 'regex:/^08[0-9]{10}$/'],
            'alamat'    => 'required|string',
            'status'    => 'required|in:aktif,nonaktif',
        ], $this->userMessages());

        $user->update(array_filter([
            'nama'     => $data['nama'],
            'username' => $data['username'],
            'no_hp'    => $data['no_hp'],
            'alamat'   => $data['alamat'],
            'status'   => $data['status'],
            'password' => !empty($data['password']) ? Hash::make($data['password']) : null,
        ], fn($v) => $v !== null));

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Mengupdate data kasir',
            'detail'    => 'Mengupdate kasir: ' . $user->nama . ' (Username: ' . $user->username . ')',
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kasir berhasil diperbarui!',
            'user'    => $user->fresh()
        ]);
    }

    public function destroyUser(User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa hapus kasir.');
        abort_if($user->id === auth()->id(), 403, 'Tidak bisa hapus akun sendiri.');

        $namaUser = $user->nama;
        $user->delete();

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Menghapus kasir',
            'detail'    => 'Menghapus kasir: ' . $namaUser,
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kasir berhasil dihapus!'
        ]);
    }

    public function toggleStatus(User $user)
    {
        abort_if($user->role !== 'kasir', 403, 'Admin hanya bisa toggle kasir.');

        $statusLama = $user->status;
        $statusBaru = $user->status === 'aktif' ? 'nonaktif' : 'aktif';

        $user->update(['status' => $statusBaru]);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Mengubah status kasir',
            'detail'    => 'Kasir: ' . $user->nama . ' - Status: ' . $statusLama . ' → ' . $statusBaru,
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status: ' . ucfirst($user->status),
            'status'  => $user->status
        ]);
    }

    private function userMessages(): array
    {
        return [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'nama.max'           => 'Nama maksimal 100 karakter.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah dipakai.',
            'username.regex'     => 'Username hanya boleh huruf, angka, titik, underscore, strip.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'no_hp.required'     => 'Nomor HP wajib diisi.',
            'no_hp.regex'        => 'Nomor HP harus diawali 08 dan terdiri dari tepat 12 digit angka (contoh: 081234567890).',
            'status.in'          => 'Status tidak valid.',
            'alamat.required'    => 'Alamat wajib diisi.',
        ];
    }

    // ==========================================
    // KELOLA MEJA
    // ==========================================
    public function indexMeja(Request $request)
    {
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar meja',
            'detail' => 'Admin melihat daftar meja',
            'waktu' => now(),
        ]);

        $query = Meja::query();

        if ($request->filled('kapasitas')) {
            $kapasitas = trim($request->kapasitas);
            if (str_contains($kapasitas, '-')) {
                [$min, $max] = explode('-', $kapasitas);
                $query->whereBetween('kapasitas', [(int) trim($min), (int) trim($max)]);
            } else {
                $query->where('kapasitas', (int) $kapasitas);
            }
        }

        if ($request->filled('tipe_meja')) {
            $query->where('tipe_meja', $request->tipe_meja);
        }

        $mejas = $query->orderBy('no_meja', 'asc')->get();

        return view('admin.meja', compact('mejas'));
    }

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

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menambah meja baru',
            'detail' => 'Menambah meja: ' . $meja->no_meja . ' (Kapasitas: ' . $meja->kapasitas . ')',
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil ditambahkan',
            'data' => $meja
        ]);
    }

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

        $noMejaLama = $meja->no_meja;
        $meja->update([
            'no_meja'   => $validated['no_meja'],
            'tipe_meja' => $validated['tipe_meja'],
            'kapasitas' => $kapasitas,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status'    => $validated['status'],
        ]);

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Mengupdate meja',
            'detail' => 'Meja: ' . $noMejaLama . ' → ' . $meja->no_meja,
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil diperbarui',
            'data' => $meja
        ]);
    }

    public function destroyMeja(Meja $meja)
    {
        $noMeja = $meja->no_meja;
        $meja->delete();

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menghapus meja',
            'detail' => 'Menghapus meja: ' . $noMeja,
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meja berhasil dihapus'
        ]);
    }


    // ==========================================
    // KELOLA MENU (SUDAH DIBENARKAN FULL)
    // ==========================================

    public function indexMenu(Request $request)
    {
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar menu',
            'detail' => 'Admin melihat daftar menu',
            'waktu' => now(),
        ]);

        $menus = Menu::with('kategori')
            ->orderBy('nama_makanan')
            ->get();

        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('admin.menu', compact('menus', 'kategoris'));
    }

    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id',
            'nama_makanan'  => 'required|string|max:100',
            'harga'         => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu = Menu::create([
            'id_kategori'   => $validated['id_kategori'],
            'nama_makanan'  => $validated['nama_makanan'],
            'harga'         => $validated['harga'],
            'stok'          => $validated['stok'],
            'status'        => 'aktif',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
            $menu->update(['gambar' => $path]);
        }

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menambah menu baru',
            'detail' => 'Menu: ' . $menu->nama_makanan . ' - Harga: Rp ' . number_format($menu->harga, 0, ',', '.') . ' - Stok: ' . $menu->stok,
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'data'    => $menu->load('kategori')
        ]);
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        $hargaLama = $menu->harga;
        $stokLama  = $menu->stok;
        $namaLama  = $menu->nama_makanan;

        $validated = $request->validate([
            'id_kategori'   => 'sometimes|exists:kategori,id',
            'nama_makanan'  => 'sometimes|string|max:100',
            'harga'         => 'sometimes|numeric|min:0',
            'stok'          => 'sometimes|integer|min:0',
            'status'        => 'sometimes|in:aktif,nonaktif,kosong',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = collect($validated)->except('gambar')->toArray();

        if (isset($validated['stok'])) {
            if ((int)$validated['stok'] === 0) {
                $data['status'] = 'kosong';
            } elseif ((int)$validated['stok'] > 0 && $menu->status === 'kosong') {
                $data['status'] = isset($validated['status']) ? $validated['status'] : 'aktif';
            }
        }

        if (isset($validated['harga']) && (float) $validated['harga'] !== (float) $hargaLama) {
            $data['harga_sebelumnya'] = $hargaLama;
        }
        if (isset($validated['stok']) && (int) $validated['stok'] !== (int) $stokLama) {
            $data['stok_sebelumnya'] = $stokLama;
        }

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) Storage::disk('public')->delete($menu->gambar);
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        $menu->update($data);

        $perubahan = [];
        if (isset($namaLama) && $namaLama != $menu->nama_makanan) $perubahan[] = 'Nama: ' . $namaLama . ' → ' . $menu->nama_makanan;
        if ($hargaLama != $menu->harga) $perubahan[] = 'Harga: Rp ' . number_format($hargaLama, 0, ',', '.') . ' → Rp ' . number_format($menu->harga, 0, ',', '.');
        if ($stokLama != $menu->stok) $perubahan[] = 'Stok: ' . $stokLama . ' → ' . $menu->stok;

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Update menu',
            'detail'    => 'Menu: ' . $menu->nama_makanan . ($perubahan ? ' - ' . implode(', ', $perubahan) : ''),
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!',
            'data'    => $menu->fresh()->load('kategori'),
        ]);
    }

    /**
     * Toggle hanya aktif <-> nonaktif
     * Tidak bisa toggle jika status = kosong
     */
    public function toggleStatusMenu(Menu $menu)
    {
        if ($menu->status === 'kosong') {
            return response()->json([
                'success' => false,
                'message' => 'Menu habis stok. Tidak bisa mengubah status.',
            ], 422);
        }

        $sedangDipesan = $menu->detailTransaksi()
            ->whereHas('transaksi', function ($q) {
                $q->where('status', 'tunggak');
            })
            ->exists();

        if ($sedangDipesan) {
            return response()->json([
                'success' => false,
                'message' => 'Menu sedang dipesan. Tidak bisa mengubah status saat ini.',
            ], 422);
        }

        $statusBaru = ($menu->status === 'aktif') ? 'nonaktif' : 'aktif';

        $menu->update(['status' => $statusBaru]);

        Log::create([
            'id_user'   => Auth::id(),
            'aktivitas' => 'Toggle status menu',
            'detail'    => 'Menu: ' . $menu->nama_makanan . ' → ' . $statusBaru,
            'waktu'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status menu diubah menjadi ' . ucfirst($statusBaru) . '.',
            'data'    => $menu->fresh()->load('kategori'),
        ]);
    }

    public function checkOrders($id)
    {
        $menu = Menu::findOrFail($id);

        $hasOrders = $menu->detailTransaksi()
            ->whereHas('transaksi', function ($q) {
                $q->where('status', 'tunggak');
            })
            ->exists();

        return response()->json([
            'success'    => true,
            'has_orders' => $hasOrders,
        ]);
    }

    // =======================
    // KELOLA KATEGORI
    // ======================
    public function indexKategori(Request $request)
    {
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat daftar kategori',
            'detail' => 'Admin melihat daftar kategori',
            'waktu' => now(),
        ]);

        $kategoris = Kategori::withCount('menus')
            ->with('menus:id,id_kategori,nama_makanan')
            ->when($request->search, fn($q, $s) =>
            $q->where('nama_kategori', 'like', "%$s%")->orWhere('jenis', 'like', "%$s%"))
            ->when($request->jenis, fn($q, $j) => $q->where('jenis', $j))
            ->orderBy('nama_kategori')
            ->get();

        return view('admin.kategori', compact('kategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis'         => 'required|string|max:100',
        ], [
            'nama_kategori.required' => 'Kategori wajib dipilih.',
            'jenis.required'         => 'Jenis wajib dipilih.',
        ]);

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

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menambah kategori baru',
            'detail' => 'Kategori: ' . $kategori->nama_kategori . ' (Jenis: ' . $kategori->jenis . ')',
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan!',
            'data'    => $kategori,
        ], 201);
    }

    public function updateKategori(Request $request, Kategori $kategori)
    {
        $namaLama  = $kategori->nama_kategori;
        $jenisLama = $kategori->jenis;

        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis'         => 'required|string|max:100',
        ], [
            'nama_kategori.required' => 'Kategori wajib dipilih.',
            'jenis.required'         => 'Jenis wajib dipilih.',
        ]);

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

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Mengupdate kategori',
            'detail' => 'Kategori: ' . $namaLama . ' (' . $jenisLama . ') → ' . $kategori->nama_kategori . ' (' . $kategori->jenis . ')',
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui!',
            'data'    => $kategori->fresh(),
        ]);
    }

    public function destroyKategori(Kategori $kategori)
    {
        if ($kategori->menus()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa hapus kategori yang masih dipakai oleh menu!'
            ], 422);
        }

        $namaKategori = $kategori->nama_kategori;
        $kategori->delete();

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Menghapus kategori',
            'detail' => 'Menghapus kategori: ' . $namaKategori,
            'waktu' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus!'
        ]);
    }

    // RIWAYAT TRANSAKSI
    public function indexRiwayat()
    {
        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat riwayat transaksi',
            'detail' => 'Admin melihat riwayat transaksi',
            'waktu' => now(),
        ]);

        $transaksis = Transaksi::with([
            'kasir',
            'meja',
            'detailTransaksi.menu'
        ])->latest()->get();

        $totalTransaksi  = $transaksis->count();
        $totalPendapatan = $transaksis->where('status', 'lunas')->sum('total_harga');

        $transaksis = $transaksis->map(function ($trx) {
            return [
                'id'             => $trx->id,
                'no_transaksi'   => $trx->no_transaksi,
                'created_at'     => $trx->tanggal,
                'nama_kasir'     => $trx->kasir->nama ?? '-',
                'tipe_order'     => $trx->jenis_pemesanan,
                'nama_meja'      => $trx->meja->no_meja ?? '-',
                'status'         => $trx->status,
                'total_harga'    => $trx->total_harga,
                'jumlah_bayar'   => $trx->jumlah_bayar,
                'kembalian'      => $trx->kembalian,
                'nama_pelanggan' => $trx->nama_pelanggan,
                'detail_transaksi' => $trx->detailTransaksi->map(function ($item) {
                    return [
                        'nama_menu'    => $item->menu->nama_makanan ?? '-',
                        'qty'          => $item->qty,
                        'harga_satuan' => $item->harga_satuan,
                    ];
                }),
            ];
        });

        return view('admin.riwayat', compact('transaksis', 'totalTransaksi', 'totalPendapatan'));
    }

    // LAPORAN PENDAPATAN
    public function laporan(Request $request)
    {
        $kasirs = User::where('role', 'kasir')->orderBy('nama')->get();

        $query = Transaksi::with(['kasir', 'detailTransaksi'])->where('status', 'selesai');

        if ($request->filled('dari'))     $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai'))   $query->whereDate('tanggal', '<=', $request->sampai);
        if ($request->filled('id_kasir')) $query->where('id_kasir', $request->id_kasir);

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

        Log::create([
            'id_user' => Auth::id(),
            'aktivitas' => 'Melihat laporan',
            'detail' => 'Admin melihat laporan pendapatan',
            'waktu' => now(),
        ]);

        if ($request->get('export') === 'csv') {
            $filename = 'laporan-pendapatan-' . now()->format('Ymd-His') . '.csv';
            $headers  = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];
            $callback = function () use ($laporanData, $totalPendapatan) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['No', 'Tanggal', 'Kasir', 'Transaksi', 'Penjualan (item)', 'Pendapatan']);
                foreach ($laporanData as $i => $row) {
                    fputcsv($file, [$i + 1, $row['tanggal'], $row['kasir'], $row['transaksi'], $row['penjualan'], $row['pendapatan']]);
                }
                fputcsv($file, ['', '', '', '', 'TOTAL', $totalPendapatan]);
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        return view('admin.laporan', compact('kasirs', 'laporanData', 'totalPendapatan'));
    }
}
