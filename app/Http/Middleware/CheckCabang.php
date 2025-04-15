<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckCabang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil parameter cabang dari URL
        $cabang = $request->route('cabang');

        // Cek apakah cabang ada di database
        $cabangExists = DB::table('setting')->where('stgcabang', $cabang)->exists();

        // Jika tidak ada, tampilkan error 404
        if (!$cabangExists) {
            abort(404, 'Cabang tidak ditemukan.');
        }

        // Jika ada, lanjutkan request
        return $next($request);
    }
}
