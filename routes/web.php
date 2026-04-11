<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerController;

// ================================================================
// PUBLIC
// ================================================================
// Route::get('/', [LandingController::class, 'index'])->name('landing');

// ================================================================
// AUTH
// ================================================================
Route::get('/',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================================================================
// ADMIN
// ================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',                      [AdminController::class, 'indexUser'])->name('index');
            Route::post('/',                     [AdminController::class, 'storeUser'])->name('store');
            Route::put('/{user}',                [AdminController::class, 'updateUser'])->name('update');
            Route::delete('/{user}',             [AdminController::class, 'destroyUser'])->name('destroy');
            Route::post('/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('toggleStatus');
        });

        // Meja
        Route::prefix('meja')->name('meja.')->group(function () {
            Route::get('/',          [AdminController::class, 'indexMeja'])->name('index');
            Route::post('/',         [AdminController::class, 'storeMeja'])->name('store');
            Route::put('/{meja}',    [AdminController::class, 'updateMeja'])->name('update');
            Route::delete('/{meja}', [AdminController::class, 'destroyMeja'])->name('destroy');
        });

        // Kategori
        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('/',              [AdminController::class, 'indexKategori'])->name('index');
            Route::post('/',             [AdminController::class, 'storeKategori'])->name('store');
            Route::put('/{kategori}',    [AdminController::class, 'updateKategori'])->name('update');
            Route::delete('/{kategori}', [AdminController::class, 'destroyKategori'])->name('destroy');
        });

        // Menu
        Route::prefix('menu')->name('menu.')->group(function () {
            Route::get('/',                          [AdminController::class, 'indexMenu'])->name('index');
            Route::post('/',                         [AdminController::class, 'storeMenu'])->name('store');
            Route::put('/{menu}',                    [AdminController::class, 'updateMenu'])->name('update');
            Route::delete('/{menu}',                 [AdminController::class, 'destroyMenu'])->name('destroy');
            Route::get('/{id}/check-orders',         [AdminController::class, 'checkOrders'])->name('checkOrders');
            Route::post('/{menu}/toggle-status',     [AdminController::class, 'toggleStatusMenu'])->name('toggleStatus');
        });

        // Riwayat Transaksi
        Route::get('/riwayat', [AdminController::class, 'indexRiwayat'])->name('riwayat');

        Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    });

// ================================================================
// KASIR ROUTES
// ================================================================
Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [KasirController::class, 'dashboard'])->name('dashboard');

        // Order Baru
        Route::get('/order',                 [KasirController::class, 'orderIndex'])->name('order.index');
        Route::post('/order',                [KasirController::class, 'orderStore'])->name('order.store');

        // Riwayat Transaksi
        Route::get('/riwayat',               [KasirController::class, 'riwayat'])->name('riwayat');

        // Reservasi
        Route::get('/reservasi',             [KasirController::class, 'reservasiIndex'])->name('reservasi');
        // Tambahkan route ini di dalam route group kasir
        Route::get('/reservasi/aktif', [KasirController::class, 'getReservasiAktif'])->name('kasir.reservasi.aktif');

        // Aktifkan Reservasi (dipanggil dari modal di reservasi.blade.php)
        Route::post(
            '/reservasi/{id}/aktifkan',
            [KasirController::class, 'aktifkanReservasi']
        )
            ->name('reservasi.aktifkan');

        // Tagih Pembayaran
        Route::patch(
            '/order/{id}/tagih',
            [KasirController::class, 'orderTagih']
        )
            ->name('order.tagih');

        // Cetak Struk (opsional, jika kamu pakai)
        Route::get(
            '/order/{id}/struk',
            [KasirController::class, 'cetakStruk']
        )
            ->name('order.struk');

        // Batalkan Transaksi (opsional)
        Route::delete(
            '/order/{id}/batal',
            [KasirController::class, 'batalTransaksi']
        )
            ->name('order.batal');
    });

// ================================================================
// OWNER
// ================================================================
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

        Route::get('/menu', [OwnerController::class, 'indexMenu'])->name('menu');
        Route::get('/meja', [OwnerController::class, 'indexMeja'])->name('meja');

        // Users CRUD
        Route::get('/users', [OwnerController::class, 'indexUser'])->name('users.index');
        Route::post('/users', [OwnerController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [OwnerController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{id}', [OwnerController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/users/{id}/toggle-status', [OwnerController::class, 'toggleStatusUser'])->name('users.toggleStatus');

        Route::get('/riwayat-transaksi', [OwnerController::class, 'riwayatTransaksi'])->name('riwayat');
        Route::get('/laporan', [OwnerController::class, 'laporan'])->name('laporan');

        Route::get('/laporan/export-excel', [OwnerController::class, 'exportExcel'])->name('laporan.export.excel');
        Route::get('/laporan/export-pdf',   [OwnerController::class, 'exportPdf'])->name('laporan.export.pdf');

        // LOG AKTIVITAS (yang diminta)
        Route::get('/log-aktivitas', [OwnerController::class, 'logAktivitas'])->name('log');
    });;
