<?php

namespace App\Http\Controllers;

use App\Models\Menu;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil 6 menu dari database untuk ditampilkan di landing page
        // Hanya yang stok > 0
        $menus = Menu::with('kategori')
                     ->where('stok', '>', 0)
                     ->latest()
                     ->take(6)
                     ->get();

        return view('landing', compact('menus'));
    }
}