<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function awal()
    {
        return view('login_custom');
    }
    public function index()
    {
        return view('home');
    }

    public function lobby()
    {
        $cabang = DB::table('setting')
                ->get();
        // dd($cabang);
        
        return view('lobby',['setting'=>$cabang]);
    }
    public function dashboard($cabang, Request $request)
    {

        $jmlBarang = DB::select("SELECT count(*) as jumlah FROM barang WHERE brgstatus = '1' AND cabang = ?", [$cabang]);
        $jmlBahan = DB::select("SELECT count(*) as jumlah FROM bahan WHERE cabang = ?", [$cabang]);
        $jmlBahanOlah = DB::select("SELECT count(*) as jumlah FROM bahan_olah WHERE cabang = ?", [$cabang]);
        $pembelianTerbaru = DB::select("SELECT * FROM pembeliand WHERE pmbddivisi IS NULL AND cabang = ? ORDER BY created_at DESC LIMIT 20", [$cabang]);

        
       $catatanSO = DB::select("SELECT DATE_FORMAT(created_at, '%d-%m-%Y') as tanggal,TIME(created_at) AS Jam,a.* FROM stock_opname_catatan AS a WHERE cabang = ?  ORDER BY created_at DESC LIMIT 20", [$cabang]);
       
       
       $listBarang = DB::select("SELECT brgid,brgnama FROM barang WHERE cabang = ? order by brgnama", [$cabang]);
               
        $topBarang = DB::table('transaksid as a')
            ->join('barang as b', 'b.brgid', '=', 'a.tnsdbarang')
            ->join('barang_jenis as c', 'c.brjid', '=', 'b.brgjenis')
            ->select('b.brgnama', DB::raw('COUNT(*) as jumlah'),'c.brjnama')
            ->groupBy('a.tnsdbarang','b.brgnama','c.brjnama')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();

            if ($request->ajax()) {
            return DataTables::of($topBarang)
                ->addIndexColumn()
                ->addColumn('jumlahs', function ($topBarang) {
                    $txt = '<span class="badge bg-success">'.$topBarang->jumlah.'</span>';
                    return $txt;
                })
                ->rawColumns(['jumlahs'])
                ->make(true);       
        }




        return view('dashboard', [
            'jmlBarang' => $jmlBarang,
            'pembelianTerbaru' => $pembelianTerbaru,
            'catSO' => $catatanSO,
            'jmlBahan' => $jmlBahan,
            'jmlBahanOlah' => $jmlBahanOlah,
            'listBarang' => $listBarang

        ]);
    }
    
    public function chartTrenPenjualan(Request $request)
    {
        $barang = $request->brgid;
        $data = DB::table('transaksid as a')
        ->join('barang as b', 'b.brgid', '=', 'a.tnsdbarang')
        ->select(
            DB::raw('DATE(a.created_at) AS tanggal'),
            'b.brgnama',
            DB::raw('SUM(a.tnsdjumlah) AS jumlah') // Perbaikan
        )
        ->whereDate('a.created_at', '>=', $request->tgldari)
        ->whereDate('a.created_at', '<=', $request->tglsampai)
        ->where('a.tnsdbarang', $barang)
        ->groupBy(DB::raw('DATE(a.created_at)'), 'b.brgnama') // Perbaikan
        ->orderBy('tanggal', 'ASC')
        ->get();



        // Format data untuk ApexCharts
        $chartData = [
            'labels' => [],
            'series' => []
        ];

        foreach ($data as $row) {
            $chartData['labels'][] = Carbon::parse($row->tanggal)->format('Y-m-d');
            $chartData['series'][] = $row->jumlah;
        }

        return response()->json($chartData);
    }

    public function dashboard2()
    {
        return view('dashboard2');
    }
}
