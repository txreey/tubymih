<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectByRole(Auth::user()->role));
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('username', $request->username)
                    ->where('status', 'aktif')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['login' => 'Username atau password salah.'])
                ->withInput(['username' => $request->username]);
        }

        Auth::login($user, $request->boolean('remember'));

        Log::create([
            'id_user'   => $user->id,
            'aktivitas' => 'Login ke sistem',
            'waktu'     => now(),
        ]);

        // Redirect ke dashboard sesuai role
        return redirect($this->redirectByRole($user->role));
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::create([
                'id_user'   => Auth::id(),
                'aktivitas' => 'Logout dari sistem',
                'waktu'     => now(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda berhasil logout.');
    }

    /**
     * Tentukan URL dashboard berdasarkan role
     */
    private function redirectByRole(string $role): string
    {
        return match ($role) {
            'admin' => route('admin.dashboard'),
            'kasir' => route('kasir.dashboard'),
            'owner' => route('owner.dashboard'),
            default => route('landing'),
        };
    }
}