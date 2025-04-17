<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\StockBarang;
use App\Models\StockOpname;
use App\Models\StockOpnameD;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\StockOpnameCatatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class UtilityController extends Controller
{

    protected $cabang; // Properti untuk menyimpan nilai cabang

        public function __construct(Request $request)
        {
            // Ambil nilai cabang dari request dan set ke properti $cabang
            $this->cabang = $request->cabang;
        }
        
    public function stockOpname()
    {
        $gudang = DB::table('gudang as a')
                ->leftJoin('setting as b','a.cabang','=','b.stgcabang')
                ->where('stgcabang',$this->cabang)
                ->get();

        return view('utility.stockOpname.v_stockOpname',['gudang'=>$gudang]);
    }

    public function getStockOpname(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;

         $data = DB::table('stock_opname as a')
        ->select('a.sopid','a.sopnama','a.soptgl','a.user_created','a.sopgudang','g.gudangn','g.gudangutama')
        ->join('gudang as g','g.gudangid','=','a.sopgudang')
        ->where('a.soptgl', '>=', $tgldari . ' 00:00:00')        
        ->where('a.soptgl', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00') 
        ->where('a.cabang',$this->cabang) 
        // ->where('tnsnama', 'like', '%' . $nama . '%')
        ->orderBy('a.soptgl')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->sopid.'</span>';
                    return $id;
                })
                ->addColumn('ket', function ($data) {
                    $id = '<span class="text-black">'.$data->sopnama.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->addColumn('tgl', function ($data) {
                    $act = tanggal_indonesia($data->soptgl);
                    return $act;
                })
                ->addColumn('gudang', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->gudangn.'</span>';
                    return $id;
                })
                ->rawColumns(['id','ket','action','tgl','gudang'])
                ->make(true);       
        }

    }

    public function getStockOpnamedRiwayat(Request $request)
    {

        $data = DB::table('stock_opname as a')
            ->select('a.*','b.sopdbahann','b.sopdjumlah','c.gudangn')
            ->join('stock_opnamed as b','b.sopdparent','=','a.sopid')
            ->join('gudang as c','c.gudangid','=','a.sopgudang')
            ->where('b.sopdbahan',$request->riwayatBarang)
            // ->where('a.sopgudang',$request->gudang)
            ->where('a.cabang',$this->cabang)
            ->orderBy('a.soptgl','desc')
            ->limit(20)
            ->get();

         if ($request->ajax()) {
            return DataTables::of($data)

                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->sopid.'</span>';
                    return $id;
                })
                
                ->addColumn('tgl', function ($data) {
                    $act = tanggal_indonesia($data->soptgl);
                    return $act;
                })
                ->rawColumns(['id','tgl'])
                ->make(true);       
        }

    }

    public function addStockOpname(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;


        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
         $cekmax = DB::table('stock_opname')
            ->where('sopid', 'like', 'SOP-'.$prefixfix.'-'.$tgl.'-%')
            ->max('sopid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 18)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'SOP-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

        $validator = Validator::make($request->all(),[
            'sopnama'     => 'required',
            'soptgl'     => 'required',

        ],[
            'sopnama.required' => 'Keterangan barang wajib diisi',
            'soptgl.required' => 'Tanggal wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = StockOpname::create([
                'sopid' => $generateId,
                'sopnama' => $request->sopnama,
                'soptgl' => $request->soptgl,
                'user_created' => $login,
                'cabang'=>$this->cabang,
                'sopgudang'=>$request->sopgudang,

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
    }

    

     public function getStockOpnameD(Request $request)
    {
        $transaksi = $request->transaksi;

         $data = DB::table('stock_opnamed as a')
        ->select('a.*')
        ->leftJoin('stock_opname as b','a.sopdid','=','b.sopid')
        ->where('a.sopdparent', '=', $transaksi )        
        // ->where('a.cabang',$this->cabang)
        ->orderBy('a.created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->sopdid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Edit"><i class="bi-pencil-square mr-5"></i></a><a href="#" title="Hapus"><i class="bi bi-trash"></i></a>
                    ';
                    return $act;
                })
                ->addColumn('jenisz', function ($data) {
		    if (substr($data->sopdbahan, 0, 3) == 'BHN') {
		        $id = '<span class="badge bg-light-success text-black">Bahan Dasar</span>';
		    } else {
		        $id = '<span class="badge bg-light-warning text-black">Bahan Olah</span>';
		    }
		    return $id;
		})
                ->rawColumns(['id','action','jenisz'])
                ->make(true);       
        }
    }

    public function addStockOpnameD(Request $request)
{
    // Mendapatkan informasi pengguna yang sedang login
    $login = Auth::user()->username;
    $kode = Auth::user()->kode;
    $cabang_login = $this->cabang;

    // Mendapatkan prefix berdasarkan cabang pengguna
    $prefix = DB::table('setting')->where('stgcabang', $cabang_login)->first();
    $prefixfix = $prefix->stgprefix;
    $tgl = Carbon::today()->format('Ymd');

    // Mengenerate ID untuk Stock Opname Detail
    $cekmax = DB::table('stock_opnamed')
        ->where('sopdid', 'like', 'SOPD-' . $prefixfix . '-' . $tgl . '-%')
        ->max('sopdid');
    $maxNumber = $cekmax ? intval(substr($cekmax, 19)) + 1 : 1;
    $formattedNumber = sprintf('%03d', $maxNumber);
    $generateId = 'SOPD-' . $prefixfix . '-' . $tgl . '-' . $formattedNumber;
    // end generate id stock opname detail

    // Generate ID Stock Barang
        $cekmaxStock = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        
        $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
        $formattedNumberStock = sprintf('%03d', $maxNumberStock);
        $generateIdStock = "SB-$tgl-$formattedNumberStock";
        //END GENERATE ID STOCK BARANG
        $gudang = DB::table('gudang')
                    ->where('gudangutama', $request->tempGudangUtama)
                    ->where('cabang',$this->cabang)
                    ->value('gudangid');



    // Validasi input request
    $validator = Validator::make($request->all(), [
        'sopdbahan' => 'required',
    ], [
        'sopdbahan.required' => 'Bahan wajib diisi',
    ]);

    // Jika validasi gagal, kembalikan respon error
    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    }

    // Mulai transaksi database
    DB::beginTransaction();
    try {
        // Menambahkan data Stock Opname Detail
        $data = StockOpnameD::create([
            'sopdid' => $generateId,
            'sopdparent' => $request->sopdparent,
            'sopdnama' => $request->sopdnama,
            'sopdbahan' => $request->sopdbahan,
            'sopdbahann' => $request->sopdbahann,
            'sopdjumlah' => $request->sopdjumlah,
            'user_created' => $login,
            'sopdposisi' => $request->sopdposisi,
            'cabang'=>$this->cabang,
        ]);

        // Transaksi Stock Barang
        $datasToInsert = StockBarang::create([
                        'sbid'      => $generateIdStock,
                        'sbparent'  => $generateId,
                        'sbbahan'   => $request->sopdbahan,
                        'sbjenis'   => 'adjust',
                        'sbmasuk'   => null,
                        'sbkeluar'  => null,
                        'sbadjust'  => $request->sopdjumlah,
                        'sbgudang'  => $gudang, // Menggunakan ID Gudang
                        'sbuser'    => $login,
                        'created_at'=> now(),
                        'updated_at'=> null,
                        'sbcabang'  => $this->cabang,
                    ]);      


        // Mengenerate ID untuk Stock Opname Catatan
        $cekmaxcatatan = DB::table('stock_opname_catatan')
            ->where('sopcid', 'like', 'SOPC-' . $prefixfix . '-' . $tgl . '-%')
            ->max('sopcid');
        $maxNumber = $cekmaxcatatan ? intval(substr($cekmaxcatatan, 19)) + 1 : 1;
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateIdCat = 'SOPC-' . $prefixfix . '-' . $tgl . '-' . $formattedNumber;

        // Menambahkan catatan jika ada kelebihan atau kekurangan stok
        if ($request->sopdjumlah > $request->sopdposisi) {
            StockOpnameCatatan::create([
                'sopcid' => $generateIdCat,
                'sopcparent' => $generateId,
                'sopcket' => 'Jumlah kelebihan, barang: ' . $request->sopdbahann . ' jumlah sistem : ' . $request->sopdposisi . ', jumlah fisik : ' . $request->sopdjumlah,
                'sopctgl' => now(),
                'sopcbahan' => $request->sopdbahan,
                'sopcbahann' => $request->sopdbahann,
                'sopcfisik' => $request->sopdjumlah,
                'sopcuser' => $login,
                'sopcsaldo' => $request->sopdposisi,
                'cabang'=>$this->cabang,
            ]);
        } else if ($request->sopdjumlah < $request->sopdposisi) {
            StockOpnameCatatan::create([
                'sopcid' => $generateIdCat,
                'sopcparent' => $generateId,
                'sopcket' => 'Jumlah kurang, barang: ' . $request->sopdbahann . ' jumlah sistem : ' . $request->sopdposisi . ', jumlah fisik : ' . $request->sopdjumlah,
                'sopctgl' => now(),
                'sopcbahan' => $request->sopdbahan,
                'sopcbahann' => $request->sopdbahann,
                'sopcfisik' => $request->sopdjumlah,
                'sopcuser' => $login,
                'sopcsaldo' => $request->sopdposisi,
                'cabang'=>$this->cabang,
            ]);
        }

        // Update saldo bahan sesuai kondisi awalan kode bahan
        $awalan = substr($request->sopdbahan, 0, 3);
        if ($awalan == 'BHN') {
            DB::table('bahan_bar')
                ->where('bhnid', $request->sopdbahan)
                ->update(['bhnsaldo' => $request->sopdjumlah]);
        } else {
            DB::table('bahan_olah')
                ->where('bhoid', $request->sopdbahan)
                ->update(['bhosaldo' => $request->sopdjumlah]);
        }

        // Commit transaksi jika semua operasi berhasil
        DB::commit();

        return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
    } catch (\Exception $e) {
        // Rollback transaksi jika ada kesalahan
        DB::rollBack();
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}


public function refreshSaldo(Request $request){
    // ini adalah fungsi refresh saldo yang diambil dari stock barang , update baru sekarang pake ini


    $getGudang_besar = DB::table('gudang')->where('cabang',$this->cabang)->where('gudangutama',1)->value('gudangid');
    $getGudang = DB::table('gudang')->where('cabang',$this->cabang)->where('gudangutama',2)->value('gudangid');
    $getGudang_kitchen = DB::table('gudang')->where('cabang',$this->cabang)->where('gudangutama',3)->value('gudangid');


    $saldoSekarang_besar = DB::table('bahan AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$getGudang_besar'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$getGudang_besar'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_besar'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$getGudang_besar'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_besar'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) AS jumlahSekarang
            ")
        )
        ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhnsatuan')
        ->where('a.cabang', $this->cabang)
        ->orderBy('a.bhnnama')
        ->get();

        $saldoSekarang_bar = DB::table('bahan_bar AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) AS jumlahSekarang
            ")
        )
        ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhnsatuan')
        ->where('a.cabang', $this->cabang)
        ->orderBy('a.bhnnama')
        ->get();


        $saldoSekarang_kitchen = DB::table('bahan_kitchen AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_kitchen'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_kitchen'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) AS jumlahSekarang
            ")
        )
        ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhnsatuan')
        ->where('a.cabang', $this->cabang)
        ->orderBy('a.bhnnama')
        ->get();

    $saldoSekarang_olah_kitchen = DB::table('bahan_olah AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhoid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_kitchen'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$getGudang_kitchen'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhoid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang_kitchen'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) AS jumlahSekarang
            ")
        )
        ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhosatuan')
        ->where('a.cabang', $this->cabang)
        ->orderBy('a.bhonama')
        ->get();

        $saldoSekarang_olah_bar = DB::table('bahan_olah AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhoid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhoid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$getGudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhoid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$getGudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) AS jumlahSekarang
            ")
        )
        ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhosatuan')
        ->where('a.cabang', $this->cabang)
        ->orderBy('a.bhonama')
        ->get();
        
        
        

    if (!empty($this->cabang)) {

        foreach ($saldoSekarang_bar as $data) {
        DB::table('bahan_bar')
            ->where('bhnid', $data->bhnid)  // Mencocokkan berdasarkan bhnid
            ->update(['bhnsaldo' => $data->jumlahSekarang]);  // Mengupdate kolom bhnsaldo
        }
        
        foreach ($saldoSekarang_kitchen as $data) {
        DB::table('bahan_kitchen')
            ->where('bhnid', $data->bhnid)  // Mencocokkan berdasarkan bhnid
            ->update(['bhnsaldo' => $data->jumlahSekarang]);  // Mengupdate kolom bhnsaldo
        }

        foreach ($saldoSekarang_besar as $data) {
        DB::table('bahan')
            ->where('bhnid', $data->bhnid)  // Mencocokkan berdasarkan bhnid
            ->update(['bhnsaldo' => $data->jumlahSekarang]);  // Mengupdate kolom bhnsaldo
        }

        foreach ($saldoSekarang_olah_kitchen as $data) {
        DB::table('bahan_olah')
            ->where('bhoid', $data->bhoid)  // Mencocokkan berdasarkan bhnid
            ->update(['bhosaldo' => $data->jumlahSekarang]);  // Mengupdate kolom bhnsaldo
        }

        foreach ($saldoSekarang_olah_bar as $data) {
        DB::table('bahan_olah')
            ->where('bhoid', $data->bhoid)  // Mencocokkan berdasarkan bhnid
            ->update(['bhosaldo' => $data->jumlahSekarang]);  // Mengupdate kolom bhnsaldo
        }

        return response()->json(['status' => 200, 'pesan' => 'Berhasil refresh saldo']);


    } else {
        return response()->json(['status' => 400, 'pesan' => 'gudang tidak ditemukan, batal refresh']);
    }

}

public function refreshSaldoStock() {

    // buat refresh saldo di gudang bar

    $gudang = DB::table('gudang')
        ->where('gudangutama', 2)
        ->where('cabang', $this->cabang)
        ->value('gudangid');

    // First, create a temporary table for the transactions
    DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_transaksi_kumulatif');
    
    // Create and populate temporary table
    DB::statement("
        CREATE TEMPORARY TABLE temp_transaksi_kumulatif AS (
            -- Pembelian transactions
            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembelian-', pmb.pmbket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                pmb.pmbtgl AS tgl,
                'PMB' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN pembeliand pmbd ON pmbd.pmbdid = a.sbparent
            JOIN pembelian pmb ON pmb.pmbid = pmbd.pmbdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'PMB'
              AND pmb.created_at BETWEEN '2024-01-01' AND '2030-12-30'
              
             
            UNION ALL

            -- Pembuatan Barang transactions
            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembuatan barang-', tns.tnsnama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(tns.created_at) AS tgl,
                'TNS' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN transaksid tnsd ON tnsd.tnsdid = a.sbparent
            JOIN transaksi tns ON tns.tnsid = tnsd.tnsdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'TNS'
              AND tns.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            -- Mengolah Bahan transactions
            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Mengolah Bahan-', bho.bhonama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(a.created_at) AS tgl,
                'BHO' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan  
            JOIN bahan_olah bho ON bho.bhoid = a.sbparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'BHO'
              AND a.created_at BETWEEN '2024-01-01' AND '2030-12-30'
              
               UNION ALL

            -- Membuat Bahan olah
            SELECT 
                a.sbid,
                bho.bhonama,
                CONCAT('Membuat Bahan Olah -', bho.bhonama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(a.created_at) AS tgl,
                'BHO' AS sumber
            FROM stock_barang a 
            JOIN bahan_olah bho ON bho.bhoid = a.sbbahan
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'PMO'
              AND a.created_at BETWEEN '2024-01-01' AND '2030-12-30'
              
              
               UNION ALL
              
            -- Bahan Olah yang dibuat untuk campur bahan olah lagi
            SELECT 
                a.sbid,
                bho.bhonama,
                CONCAT('Bahan Olah yang dicampur untuk - ', buat.bhonama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(a.created_at) AS tgl,
                'BHO2' AS sumber
            FROM stock_barang a
            JOIN bahan_olah buat ON buat.bhoid = a.sbparent
            JOIN bahan_olah bho ON bho.bhoid = a.sbbahan
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'BHO'
              AND a.created_at BETWEEN '2024-01-01' AND '2030-12-30'
             
            UNION ALL
        
            -- Stock Opname transactions
            SELECT
                a.sbid,
                b.bhnnama,
                CONCAT('Stock Opname-', sop.sopnama) AS keterangan,
                a.sbjenis, 
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(sop.created_at) AS tgl,
                'SOP' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN stock_opnamed sopd ON sopd.sopdid = a.sbparent 
            JOIN stock_opname sop ON sop.sopid = sopd.sopdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'SOP'  
              AND sop.soptgl BETWEEN '2024-01-01' AND '2030-12-30'

              UNION ALL

              -- Stock Opname transactions OLAH
            SELECT
                a.sbid,
                b.bhonama,
                CONCAT('Stock Opname Bahan Olah-', sop.sopnama) AS keterangan,
                a.sbjenis, 
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(sop.created_at) AS tgl,
                'SOP' AS sumber
            FROM stock_barang a
            JOIN bahan_olah b ON b.bhoid = a.sbbahan
            JOIN stock_opnamed sopd ON sopd.sopdid = a.sbparent 
            JOIN stock_opname sop ON sop.sopid = sopd.sopdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'SOP'  
              AND sop.soptgl BETWEEN '2024-01-01' AND '2030-12-30'
              
              UNION ALL

              -- Mutasi transactions
            SELECT
                a.sbid,
                b.bhnnama,  
                CONCAT(jmu.jmunama, '-', mut.mutaket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                DATE(mut.mutatgl) AS tgl,
                'MUT' AS sumber  
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN mutasid mutd ON mutd.mutdid = a.sbparent
            JOIN mutasi mut ON mut.mutaid = mutd.mutdparent 
            JOIN jenis_mutasi jmu ON jmu.jmuid = mut.mutajenis
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'MUT'
              AND mut.mutatgl BETWEEN '2024-01-01' AND '2030-12-30'  
              

        )", [$gudang, $gudang, $gudang, $gudang, $gudang, $gudang, $gudang, $gudang]);

    
    
    DB::statement("
            CREATE TEMPORARY TABLE temp_saldo_calculated
            SELECT 
                sbid,
                bhnnama,
                @saldo := IF(@current_bhnnama = bhnnama,
                            CASE 
                                WHEN sbjenis = 'adjust' THEN COALESCE(sbadjust, 0)
                                ELSE @saldo + COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0)
                            END,
                            COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0) + COALESCE(sbadjust, 0)) AS sisa_saldo,
                @current_bhnnama := bhnnama AS dummy
            FROM temp_transaksi_kumulatif
            ORDER BY bhnnama, tgl
        ");

        // Update stock_barang
        DB::statement("
            UPDATE stock_barang sb
            JOIN temp_saldo_calculated calc ON sb.sbid = calc.sbid
            SET sb.sbsaldo = calc.sisa_saldo
        ");

    // Clean up
    DB::statement('DROP TEMPORARY TABLE IF EXISTS temp_transaksi_kumulatif');

    return response()->json(['message' => 'Saldo berhasil diupdate', 'status' => 200], 200);
}
public function refreshSaldoStock_besar()
{
    try {
        // Get gudang
        $gudang = DB::table('gudang')
            ->where('gudangutama', 1)
            ->where('cabang', $this->cabang)
            ->value('gudangid');

        if (!$gudang) {
            return response()->json(['message' => 'Gudang tidak ditemukan', 'status' => 404], 404);
        }

        DB::beginTransaction();

        // Initialize variables
        DB::statement('SET @saldo := 0');
        DB::statement('SET @current_bhnnama := "" COLLATE utf8mb4_unicode_ci');

        // Create temporary table for transactions
        DB::statement("
            CREATE TEMPORARY TABLE temp_transaksi_kumulatif
            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembelian-', pmb.pmbket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                pmb.created_at AS tgl,
                'PMB' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN pembeliand pmbd ON pmbd.pmbdid = a.sbparent
            JOIN pembelian pmb ON pmb.pmbid = pmbd.pmbdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'PMB'
              AND pmb.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembuatan barang-', tns.tnsnama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                tns.created_at AS tgl,
                'TNS' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN transaksid tnsd ON tnsd.tnsdid = a.sbparent
            JOIN transaksi tns ON tns.tnsid = tnsd.tnsdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'TNS'
              AND tns.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Mengolah Bahan-', bho.bhonama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                a.created_at AS tgl,
                'BHO' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN bahan_olah bho ON bho.bhoid = a.sbparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'BHO'
              AND a.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Stock Opname-', sop.sopnama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                sop.created_at AS tgl,
                'SOP' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN stock_opnamed sopd ON sopd.sopdid = a.sbparent
            JOIN stock_opname sop ON sop.sopid = sopd.sopdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'SOP'
              AND sop.soptgl BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT(jmu.jmunama, '-', mut.mutaket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                mut.created_at AS tgl,
                'MUT' AS sumber
            FROM stock_barang a
            JOIN bahan b ON b.bhnid = a.sbbahan
            JOIN mutasid mutd ON mutd.mutdid = a.sbparent
            JOIN mutasi mut ON mut.mutaid = mutd.mutdparent
            JOIN jenis_mutasi jmu ON jmu.jmuid = mut.mutajenis
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'MUT'
              AND mut.mutatgl BETWEEN '2024-01-01' AND '2030-12-30'
        ", array_fill(0, 5, $gudang));

        // Create temporary table for calculated balance
        DB::statement("
            CREATE TEMPORARY TABLE temp_saldo_calculated
            SELECT 
                sbid,
                bhnnama,
                @saldo := IF(@current_bhnnama = bhnnama,
                            CASE 
                                WHEN sbjenis = 'adjust' THEN COALESCE(sbadjust, 0)
                                ELSE @saldo + COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0)
                            END,
                            COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0) + COALESCE(sbadjust, 0)) AS sisa_saldo,
                @current_bhnnama := bhnnama AS dummy
            FROM temp_transaksi_kumulatif
            ORDER BY bhnnama, tgl
        ");

        // Update stock_barang
        DB::statement("
            UPDATE stock_barang sb
            JOIN temp_saldo_calculated calc ON sb.sbid = calc.sbid
            SET sb.sbsaldo = calc.sisa_saldo
        ");

        // Cleanup temporary tables
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_transaksi_kumulatif");
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_saldo_calculated");

        DB::commit();

        return response()->json(['message' => 'Saldo berhasil diupdate', 'status' => 200], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal update saldo: ' . $e->getMessage(),
            'status' => 500
        ], 500);
    }
}


public function refreshSaldoStock_kitchen()
{
    try {
        // Get gudang
        $gudang = DB::table('gudang')
            ->where('gudangutama', 3)
            ->where('cabang', $this->cabang)
            ->value('gudangid');

        if (!$gudang) {
            return response()->json(['message' => 'Gudang tidak ditemukan', 'status' => 404], 404);
        }

        DB::beginTransaction();

        // Initialize variables
        DB::statement('SET @saldo := 0');
        DB::statement('SET @current_bhnnama := "" COLLATE utf8mb4_unicode_ci');

        // Create temporary table for transactions
        DB::statement("
            CREATE TEMPORARY TABLE temp_transaksi_kumulatif
            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembelian-', pmb.pmbket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                pmb.created_at AS tgl,
                'PMB' AS sumber
            FROM stock_barang a
            JOIN bahan_kitchen b ON b.bhnid = a.sbbahan
            JOIN pembeliand pmbd ON pmbd.pmbdid = a.sbparent
            JOIN pembelian pmb ON pmb.pmbid = pmbd.pmbdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'PMB'
              AND pmb.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Pembuatan barang-', tns.tnsnama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                tns.created_at AS tgl,
                'TNS' AS sumber
            FROM stock_barang a
            JOIN bahan_kitchen b ON b.bhnid = a.sbbahan
            JOIN transaksid tnsd ON tnsd.tnsdid = a.sbparent
            JOIN transaksi tns ON tns.tnsid = tnsd.tnsdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'TNS'
              AND tns.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Mengolah Bahan-', bho.bhonama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                a.created_at AS tgl,
                'BHO' AS sumber
            FROM stock_barang a
            JOIN bahan_kitchen b ON b.bhnid = a.sbbahan
            JOIN bahan_olah bho ON bho.bhoid = a.sbparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'BHO'
              AND a.created_at BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT('Stock Opname-', sop.sopnama) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                sop.created_at AS tgl,
                'SOP' AS sumber
            FROM stock_barang a
            JOIN bahan_kitchen b ON b.bhnid = a.sbbahan
            JOIN stock_opnamed sopd ON sopd.sopdid = a.sbparent
            JOIN stock_opname sop ON sop.sopid = sopd.sopdparent
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'SOP'
              AND sop.soptgl BETWEEN '2024-01-01' AND '2030-12-30'

            UNION ALL

            SELECT 
                a.sbid,
                b.bhnnama,
                CONCAT(jmu.jmunama, '-', mut.mutaket) AS keterangan,
                a.sbjenis,
                a.sbmasuk,
                a.sbkeluar,
                a.sbadjust,
                a.sbsaldo,
                mut.created_at AS tgl,
                'MUT' AS sumber
            FROM stock_barang a
            JOIN bahan_kitchen b ON b.bhnid = a.sbbahan
            JOIN mutasid mutd ON mutd.mutdid = a.sbparent
            JOIN mutasi mut ON mut.mutaid = mutd.mutdparent
            JOIN jenis_mutasi jmu ON jmu.jmuid = mut.mutajenis
            WHERE a.sbgudang = ?
              AND LEFT(a.sbparent, 3) = 'MUT'
              AND mut.mutatgl BETWEEN '2024-01-01' AND '2030-12-30'
        ", array_fill(0, 5, $gudang));

        // Create temporary table for calculated balance
        DB::statement("
            CREATE TEMPORARY TABLE temp_saldo_calculated
            SELECT 
                sbid,
                bhnnama,
                @saldo := IF(@current_bhnnama = bhnnama,
                            CASE 
                                WHEN sbjenis = 'adjust' THEN COALESCE(sbadjust, 0)
                                ELSE @saldo + COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0)
                            END,
                            COALESCE(sbmasuk, 0) - COALESCE(sbkeluar, 0) + COALESCE(sbadjust, 0)) AS sisa_saldo,
                @current_bhnnama := bhnnama AS dummy
            FROM temp_transaksi_kumulatif
            ORDER BY bhnnama, tgl
        ");

        // Update stock_barang
        DB::statement("
            UPDATE stock_barang sb
            JOIN temp_saldo_calculated calc ON sb.sbid = calc.sbid
            SET sb.sbsaldo = calc.sisa_saldo
        ");

        // Cleanup temporary tables
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_transaksi_kumulatif");
        DB::statement("DROP TEMPORARY TABLE IF EXISTS temp_saldo_calculated");

        DB::commit();

        return response()->json(['message' => 'Saldo berhasil diupdate', 'status' => 200], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal update saldo: ' . $e->getMessage(),
            'status' => 500
        ], 500);
    }
}
}

