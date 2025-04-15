<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\BahanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\BahanExportKitchen;
use App\Exports\BahanExportGudangBesar;

class LaporanController extends Controller
{
    protected $cabang; // Properti untuk menyimpan nilai cabang

    public function __construct(Request $request)
    {
        // Ambil nilai cabang dari request dan set ke properti $cabang
        $this->cabang = $request->cabang;
    }

    public function laporanBarang()
    {
        return view('laporan.laporan_barang');
    }
    public function laporanBahan()
    {
        $data = DB::table('bahan as a')
                ->where('cabang',$this->cabang)
                ->orderBy('bhnnama')
                ->get();

        $gudang = DB::table('gudang as a')
                ->where('cabang',$this->cabang)
                ->orderBy('gudangn')
                 ->get();

        $bahan_olah = DB::table('bahan_olah as a')
                    ->where('cabang',$this->cabang)
                    ->orderBy('bhonama')
                    ->get();
        return view('laporan.laporan_bahan.v_laporan_bahan',['bahan'=>$data,'gudang'=>$gudang,'bahanOlah'=>$bahan_olah]);
    }

    public function exportBahan(Request $request) 
    {
        $startDate = $request->input('tgldari');
        $endDate = $request->input('tglsampai');
        $gudang = $request->input('gudang');
        $bahan = $request->input('listFormBahan');

        $jenisGudang = DB::table('gudang')
                ->where('gudangid',$gudang)
                ->value('gudangutama');

        if($jenisGudang == 2){
            return Excel::download(new BahanExport($bahan,$startDate,$endDate,$gudang),'laporan-bahan-gudangbar.xlsx');
        } else if($jenisGudang == 3){
            return Excel::download(new BahanExportKitchen($bahan,$startDate,$endDate,$gudang),'laporan-bahan-gudangkitchen.xlsx');
        } else {
            return Excel::download(new BahanExportGudangBesar($bahan,$startDate,$endDate,$gudang),'laporan-bahan-gudangbesar.xlsx');

        }
    } 
}
