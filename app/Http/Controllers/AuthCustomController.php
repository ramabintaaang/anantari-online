<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthCustomController extends Controller
{
     public function index()
    {
        return view('Auth.login'); // Ganti dengan path ke view login Anda
    }
    // Menangani proses login
   public function login(Request $request)
{
    // Validasi input username dan password
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    // Cek apakah username ada di database
    $user = User::where('username', $request->username)->first();

    // Jika username tidak ditemukan
    if (!$user) {
        return back()->withErrors([
            'username' => 'Username tidak ditemukan.',
        ])->onlyInput('username');
    }

    // Jika username ditemukan tapi password salah
    if (Auth::attempt($credentials)) {
        return redirect()->intended('/lobby'); // Redirect setelah login berhasil
    }

    // Jika login gagal (username atau password salah)
    return back()->withErrors([
        'password' => 'password salah.',
    ])->onlyInput('username');
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function resetPassword(Request $request)
{
    // Validasi input username
    $validated = $request->validate([
        'username_reset' => 'required|string',
    ]);

    // Cari pengguna berdasarkan username
    $user = User::where('username', $request->username_reset)->first();

    // Cek apakah pengguna ditemukan
    if ($user) {
        // Reset password ke '123'
        $user->password = Hash::make('123');  // Password yang direset
        $user->save();

        // Mengirim pesan sukses
        return back()->with('success', 'Password telah direset menjadi 123.');
    }

    // Jika tidak ditemukan user dengan username tersebut
    return back()->withErrors(['error' => 'Username tidak ditemukan, tidak dapat direset.']);
}


}
