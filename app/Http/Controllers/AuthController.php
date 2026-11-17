<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Cari user berdasarkan username
        $user = User::where('username', $username)->first();

        if (!$user) {
            return back()->with('error', 'Username tidak ditemukan!');
        }

        $passwordPassed = false;
        $dbPassword = $user->password;

        // 1. Cek jika password di DB adalah format MD5 (panjang 32 karakter)
        if (strlen($dbPassword) === 32) {
            if (md5($password) === $dbPassword) {
                $passwordPassed = true;
            }
        }
        // 2. Cek jika password di DB adalah plain text (misal: "12345")
        // Bcrypt hash biasanya diawali dengan '$2y$' dan panjangnya ~60 karakter
        elseif (!str_starts_with($dbPassword, '$2y$')) {
            if ($password === $dbPassword) {
                $passwordPassed = true;
            }
        }
        // 3. Cek menggunakan standard Bcrypt
        else {
            if (Hash::check($password, $dbPassword)) {
                $passwordPassed = true;
            }
        }

        if ($passwordPassed) {
            // Jika berhasil lewat MD5/Plain-text, update password ke Bcrypt terbaru
            if (strlen($dbPassword) === 32 || !str_starts_with($dbPassword, '$2y$')) {
                $user->password = Hash::make($password);
                $user->save();
            }

            // Login user
            Auth::login($user);

            // Simpan variabel session lama demi kompatibilitas
            session([
                'id' => $user->id,
                'user_id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama ?? 'Kader Posyandu',
                'role' => $user->role,
                'status' => 'login'
            ]);

            return $this->redirectUser($user);
        }

        return back()->with('error', 'Password salah!');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    /**
     * Helper to redirect user based on role.
     */
    private function redirectUser($user)
    {
        if ($user->role === 'kader') {
            return redirect()->route('kader.dashboard');
        } elseif ($user->role === 'orang_tua') {
            return redirect()->route('orang-tua.dashboard');
        }
        return redirect()->route('landing');
    }
}
