<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckUserCabangAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $idcabang
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, $idcabang = null)
    {
        // Ambil iduser dari session atau Auth
        $iduser = Auth::id();
        $cabangx = $request->route('cabang');


        // Debugging: Tampilkan ID User dan ID Cabang
        // dd("ID User: $iduser, ID Cabang: $cabangx");

        // Cek apakah user memiliki divisi admin
        $user = User::find($iduser);

        if ($user && $user->divisi === 'admin') {
            // Jika user adalah admin, lanjutkan tanpa memeriksa akses cabang
            return $next($request);
        }

        // Ambil data cabang berdasarkan ID cabang
        $cabang = DB::table('setting') // Tabel yang menyimpan data cabang
                    ->where('stgcabang', $cabangx) // Sesuaikan dengan nama kolom yang ada
                    ->first(); // Ambil satu baris data cabang

        // Pastikan cabang ditemukan
        if (!$cabang) {
            abort(404, 'Cabang tidak ditemukan.');
        }

        // Jika bukan admin, lanjutkan pengecekan akses user ke cabang
        $access = UserAccess::where('iduser', $iduser)
                            ->where('idcabang', $cabangx) // Gunakan ID cabang yang ditemukan
                            ->exists();

        // Jika tidak ada akses, tampilkan pesan error
        if (!$access) {
            abort(403, 'Akses ditolak ke cabang ini.');
        }

        // Lanjutkan request jika akses diizinkan
        return $next($request);
    }
}
