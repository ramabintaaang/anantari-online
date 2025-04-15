<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\BahanBar;
use App\Models\Pembelian;
use App\Models\Transaksi;
use App\Models\Pembeliand;
use App\Models\Transaksid;
use App\Models\StockBarang;
use App\Models\BahanKitchen;
use Illuminate\Http\Request;
use App\Models\PembeliandBar;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    protected $cabang; // Properti untuk menyimpan nilai cabang

        public function __construct(Request $request)
        {
            // Ambil nilai cabang dari request dan set ke properti $cabang
            $this->cabang = $request->cabang;
        }
   // TAMPILAN DAN CRUD KASIR
    public function kasir()
    {
        $satuan = DB::table('barang_satuan')
                ->where('cabang',$this->cabang)
                ->get();
        $jenis = DB::table('barang_jenis')
                ->where('cabang',$this->cabang)
                ->get();
        return view('transaksi.kasir',['satuan' => $satuan,'jenis'=>$jenis]);
    }
    public function getTransaksi(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $divisi = $request->tnsdivisi;

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('transaksi')
        ->select('*')
        ->where('created_at', '>=', $tgldari . ' 00:00:00')        
        ->where('created_at', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00')  
        ->where('tnsdivisi','=', $divisi)
        ->where('cabang',$this->cabang)
        ->orderBy('created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->tnsid.'</span>';
                    return $id;
                })
                ->addColumn('status', function ($data) {
                    if($data->tnstatus == '1' && $data->tnsbayar != null){
                        $txt = '<span class="badge bg-success">Selesai</span>
                        <span class="badge bg-success">Terbayar</span>';
                    } else if ($data->tnstatus == '0' && $data->tnsbayar != null){
                        $txt = '<span class="badge bg-danger">blm selesai</span>
                        <span class="badge bg-success">Terbayar</span>';
                    } else if ($data->tnstatus == '1' && $data->tnsbayar == null){
                        $txt = '<span class="badge bg-success">Selesai</span>
                        <span class="badge bg-danger">blm bayar</span>';
                    }
                    else {
                        $txt = '<span class="badge bg-danger">blm selesai</span>
                        <span class="badge bg-danger">blm bayar</span>';
                    }
                    return $txt;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','status','action'])
                ->make(true);       
        }

    }

    public function getTransaksiDetail(Request $request)
    {
        $transaksi = $request->transaksi;
        

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
        $data = DB::table('transaksid as a')
            ->select(
                'a.tnsdid',
                'a.tnsdbarang',
                'a.tnsdjumlah',
                'a.tnsdtotal',
                'a.tnsdparent',
                'a.tnsdketerangan',
                'a.tnsdstatus',
                'a.created_at as tglbuat',
                'b.brgid',
                'b.brgnama',
                'b.brgharga',
                'b.brggudang',
                'c.brjnama',
                'd.tnsbparent',
                DB::raw('GROUP_CONCAT(d.tnsbid SEPARATOR ",") as tnsbid'), // Menggabungkan semua tnsbid
                DB::raw('GROUP_CONCAT(d.tnsbbahan SEPARATOR ",") as tnsbbahan') // Menggabungkan semua tnsbbahan
            )
            ->leftJoin('barang as b', 'a.tnsdbarang', '=', 'b.brgid')
            ->leftJoin('barang_jenis as c', 'b.brgjenis', '=', 'c.brjid')
            ->leftJoin('transaksi_bahan as d', 'd.tnsbparent', '=', 'a.tnsdid')
            ->where('tnsdparent', '=', $transaksi)
            ->groupBy(
                'a.tnsdid',
                'a.tnsdbarang',
                'a.tnsdjumlah',
                'a.tnsdtotal',
                'a.tnsdparent',
                'a.tnsdketerangan',
                'a.tnsdstatus',
                'a.created_at',
                'b.brgid',
                'b.brgnama',
                'b.brgharga',
                'b.brggudang',
                'd.tnsbparent',
                'c.brjnama',
            )
            ->orderBy('a.created_at')
            ->get();




         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if($data->tnsdstatus == '1'){
                        $txt = '<span class="badge bg-success">Selesai</span>';
                    } 
                    else {
                        $txt = '<span class="badge bg-danger">blm selesai</span>';
                    }
                    return $txt;
                })
                ->addColumn('action', function ($data) {
                    
                    $act = '<a href="#" title="Edit" class="edit-btn" 
                            data-tnsbid="'.$data->tnsbid.'" 
                            data-id="'.$data->tnsdid.'" 
                            data-barang="'.$data->brgnama.'" 
                            data-barangid="'.$data->brgid.'" 
                            data-jumlah="'.$data->tnsdjumlah.'" 
                            data-total="'.$data->tnsdtotal.'" 
                            data-bahan="'.$data->tnsbbahan.'" 
                            data-ket="'.$data->tnsdketerangan.'" 
                            data-harga="'.$data->brgharga.'" 
                            data-tnsbparent="'.$data->tnsbparent.'" 
                            data-parent="'.$data->tnsdparent.'"
                            data-gudang="'.$data->brggudang.'">
                            <i class="bi-pencil-square mr-5"></i></a>
                            <a href="#" title="Hapus" class="delete-btn"
                            data-tnsbid="'.$data->tnsbid.'" 
                            data-id="'.$data->tnsdid.'" 
                            data-barang="'.$data->brgnama.'" 
                            data-total="'.$data->tnsdtotal.'" 
                            data-parent="'.$data->tnsdparent.'">
                            <i class="bi bi-trash">
                            </i>
                            </a>';
                    return $act;
                })
                ->rawColumns(['status','action'])
                ->make(true);       
        }
    }

   public function deleteTransaksiDetail(Request $request)
    {
        // Validasi input
        $request->validate([
            'tnsdid' => 'required|exists:transaksid,tnsdid', // Pastikan tnsdid ada di tabel transaksid
        ]);

        DB::beginTransaction(); // Mulai transaksi

        try {
            // Hapus data dari tabel 'stock_barang' terkait dengan tnsdid
            $updateSaldo = DB::table('transaksi')
                ->where('tnsid', $request->tnsdparent)
                ->decrement('tnstotal',$request->tnstotal);
                // dd($request->tnstotal);
            // Hapus data dari tabel 'transaksid'
            $transaksidDeleted = DB::table('transaksid')
                ->where('tnsdid', $request->tnsdid)
                ->delete();
            // Hapus data dari tabel 'stock_barang' terkait dengan tnsdid
            $stockBarangDeleted = DB::table('stock_barang')
                ->where('sbparent', $request->tnsdid)
                ->delete();
             // Hapus data dari tabel 'transaksi_bahan' terkait dengan tnsdid
            $stockBarangDeleted = DB::table('transaksi_bahan')
                ->where('tnsbparent', $request->tnsdid)
                ->delete();

            // Jika kedua operasi berhasil
            if ($transaksidDeleted && $stockBarangDeleted && $updateSaldo) {
                DB::commit(); // Commit transaksi
                return response()->json(['status' => 200, 'pesan' => 'Berhasil menghapus barang']);
            } else {
                DB::rollBack(); // Rollback transaksi jika gagal
                return response()->json(['status' => 500, 'pesan' => 'Gagal menghapus barang']);
            }
        } catch (\Exception $e) {
            // Jika terjadi error, rollback transaksi
            DB::rollBack();
            return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


    public function getTransaksiDetailBahan(Request $request)
{
    $transaksi = $request->transaksi;

    $jenisGudang = DB::table('gudang')
                ->where('gudangid',$request->gudang)
                ->value('gudangutama');

    $data = DB::table('transaksi_bahan as a')
            ->select('a.tnsbbahan')
            ->where('tnsbparent', '=', $transaksi)
            ->get();

    // Proses data untuk mengecek awalan
    foreach ($data as $item) {
        $awalan = substr($item->tnsbbahan, 0, 3); // Ambil 3 huruf depan dari tnsbbahan

        $query_bar = DB::table('transaksi_bahan as a')
                    ->select('a.*', 'b.bhnid','b.bhnnama','b.bhnsatuan','b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','sat.satnama')
                    ->leftJoin('bahan_bar as b', 'a.tnsbbahan', '=', 'b.bhnid')
                    ->join('satuan as sat','sat.satid','=','b.bhnsatuan')
                    ->where('tnsbparent', '=', $transaksi)
                    ->where(DB::raw('LEFT(b.bhnid, 3)'), '=', 'BHN');
        $query_kitchen = DB::table('transaksi_bahan as a')
                    ->select('a.*', 'b.bhnid','b.bhnnama','b.bhnsatuan','b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','sat.satnama')
                    ->leftJoin('bahan_kitchen as b', 'a.tnsbbahan', '=', 'b.bhnid')
                    ->join('satuan as sat','sat.satid','=','b.bhnsatuan')
                    ->where('tnsbparent', '=', $transaksi)
                    ->where(DB::raw('LEFT(b.bhnid, 3)'), '=', 'BHN');

        $query_biasa = DB::table('transaksi_bahan as a')
                    ->select('a.*', 'b.bhnid','b.bhnnama','b.bhnsatuan','b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','sat.satnama')
                    ->leftJoin('bahan as b', 'a.tnsbbahan', '=', 'b.bhnid')
                    ->join('satuan as sat','sat.satid','=','b.bhnsatuan')
                    ->where('tnsbparent', '=', $transaksi)
                    ->where(DB::raw('LEFT(b.bhnid, 3)'), '=', 'BHN');

        $query_olah = DB::table('transaksi_bahan as a')
                    ->select('a.*', 'b.bhoid', 'b.bhonama', 'b.bhosatuan', 'b.bhomax', 'b.bhomin', 'b.bhosaldo', 'b.bhoid')
                    ->leftJoin('bahan_olah as b', 'a.tnsbbahan', '=', 'b.bhoid')
                    ->where('tnsbparent', '=', $transaksi)
                    ->where(DB::raw('LEFT(b.bhoid, 3)'), '=', 'BHO');
        

        // Query berdasarkan gudang
        if ($jenisGudang == 3) {
            $data = $query_kitchen->union($query_olah)->get();
        } elseif ($jenisGudang == 2) {
            $data = $query_bar->union($query_olah)->get();
        } else {
            $data = $query_biasa->union($query_olah)->get();
        }

        // unionni
        
    }

    if ($request->ajax()) {
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                $act = '<a href="#" title="Edit">
                <i class="bi-pencil-square mr-5"></i></a>
                <a href="#" title="Hapus">
                <i class="bi bi-trash">
                </i>
                </a>';
                return $act;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return response()->json($data);
}


   
    public function generateIdTransaksiDetail(){
        $login = Auth::user()->username;
        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE STGCABANG = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');



         $cekmax = DB::table('transaksid')
            ->where('tnsdid', 'like', 'TNSD-'.$prefixfix.'-'.$tgl.'-%')
            ->max('tnsdid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 18)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'TNSD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

       

        return response()->json(['data'=>$generateId]);
    }
public function addTransaksi(Request $request){
    $login = Auth::user()->username;
    $cabang_login = $this->cabang;
    $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
    $prefixfix = $prefix[0]->stgprefix;
    $tgl = Carbon::today()->format('Ymd');

         // Generate ID transaksi 
       $cekmax = DB::table('transaksi')
            ->where('tnsid', 'like', 'TNS-'.$prefixfix.'-'.$tgl.'-%')
            ->max('tnsid');

            if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 17)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'TNS-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;




        $validator = Validator::make($request->all(),[
            'tnsnama'     => 'required',
        ],[
            'tnsnama.required' => 'Nama wajib diisi',

        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = Transaksi::create([
                'tnsid' => $generateId,
                'tnsnama' => $request->tnsnama,
                'tnstatus' => '0',
                'user_created' => $login,
                'tnsdivisi'=>$request->tnsdivisi,
                'cabang'=>$this->cabang,
            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
            }
        }
    }


public function addTransaksiDetail(Request $request) {
    $login = Auth::user()->username;
    $cabang_login = $this->cabang;
    $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
    $prefixfix = $prefix[0]->stgprefix;
    $tgl = Carbon::today()->format('Ymd');

    $jenisGudang = DB::table('gudang')
            ->where('gudangid', $request->tnsdgudang)
            ->value('gudangutama');

    // Validasi request TRANSAKSI DETAIL
    $validator = Validator::make($request->all(), [
        'tnsdjumlah' => 'required',
        'tnsdbarang' => 'required',
    ], [
        'tnsdjumlah.required' => 'wajib diisi',
        'tnsdbarang.required' => 'wajib diisi',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    }

    // Decode JSON-encoded bahan data
    $bahanData = json_decode($request->input('bahan'), true);
    if (empty($bahanData)) {
        return response()->json(['status' => 400, 'pesan' => 'Data bahan tidak ditemukan']);
    } 

    $BahanVarian = json_decode($request->input('bahanVarian'), true);
    // if (empty($BahanVarian)) {
    //     return response()->json(['status' => 400, 'pesan' => 'Varian tidak ditemukan NIH ah']);
    // } 


    // Pengecekan saldo bahan
    
    foreach ($bahanData as $datas) {
        $awalan = substr($datas['bhnid'],0,3);

            
        if($jenisGudang == 2){
            if($awalan == 'BHN'){
                $bahan = DB::table('bahan_bar')
                    ->where('bhnid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan || $bahan->bhnsaldo < $datas['bhankuantiti']) {
                            return response()->json(['status' => 400, 'pesan' => 'Saldo bahan di gudang bar tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                    }
                    }
            } else {
                $bahan_olah = DB::table('bahan_olah')
                    ->where('bhoid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan_olah || $bahan_olah->bhosaldo < $datas['bhankuantiti']) {
                        return response()->json(['status' => 400, 'pesan' => 'Saldo bahan OLAH di gudang bar tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                    }
                }
            }
            
        } else if($jenisGudang == 3){
            if($awalan == 'BHN'){
                $bahan = DB::table('bahan_kitchen')
                    ->where('bhnid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan || $bahan->bhnsaldo < $datas['bhankuantiti']) {
                            return response()->json(['status' => 400, 'pesan' => 'Saldo bahan di gudang kitchen tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                    }
                    }
            } else {
                $bahan_olah = DB::table('bahan_olah')
                    ->where('bhoid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan_olah || $bahan_olah->bhosaldo < $datas['bhankuantiti']) {
                        return response()->json(['status' => 400, 'pesan' => 'Saldo bahan OLAH di gudang kitchen tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                    }
                }
            }
        } else {
            if($awalan == 'BHN'){
                $bahan = DB::table('bahan')
                    ->where('bhnid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan || $bahan->bhnsaldo < $datas['bhankuantiti']) {
                            return response()->json(['status' => 400, 'pesan' => 'Saldo bahan di gudang besar tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                        }
                    }
            } else {
                $bahan_olah = DB::table('bahan_olah')
                    ->where('bhoid', $datas['bhnid'])
                    ->first();
                    if($request->bypassBahan == 0){
                        if (!$bahan_olah || $bahan_olah->bhosaldo < $datas['bhankuantiti']) {
                        return response()->json(['status' => 400, 'pesan' => 'Saldo bahan OLAH di gudang besar tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
                        }
                    }
            }
        }   
    }
    

    foreach ($BahanVarian as $datasv) {
        $varians = DB::table('bahan')->where('bhnid', $datasv['bvarbahan'])->first();
        if (!$varians || $varians->bhnsaldo < $datasv['bvarsaldo']) {
            return response()->json(['status' => 400, 'pesan' => 'Dengan Varian ini, bahan tidak mencukupi: ' . $datasv['bvarbahan']]);
        }
    }

    // Jika semua bahan cukup, lakukan transaksi
    DB::beginTransaction();
    


    try {
        $cekmax = DB::table('transaksid')
            ->where('tnsdid', 'like', 'TNSD-'.$prefixfix.'-'.$tgl.'-%')
            ->max('tnsdid');
        if ($cekmax) {
            $maxNumber = intval(substr($cekmax, 18)) + 1;
        } else {
            $maxNumber = 1;
        }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'TNSD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

        // Input transaksi detail
        $data = DB::table('transaksid')->insert([
            'tnsdid' => $generateId,
            'tnsdparent' => $request->tnsdparent,
            'tnsdbarang' => $request->tnsdbarang,
            'tnsdjumlah' => $request->tnsdjumlah,
            'tnsdtotal' => $request->tnsdtotal,
            'tnsdketerangan' => $request->tnsdketerangan,
            'tnsdstatus' => '0',
            'user_created' => $login,
            'created_at' => now(),
            'updated_at' => now(),
            'cabang'=>$this->cabang,
        ]);

         // Update harga transaksi
       $cekTotal = DB::select("SELECT SUM(tnsdtotal) AS total FROM transaksid WHERE tnsdparent = ?", [$request->tnsdparent]);

        // Pastikan untuk mengakses hasil query dengan benar
        $total = $cekTotal[0]->total ?? 0; // Jika tidak ada hasil, total menjadi 0

        // Update kolom tnstotal di tabel transaksi
        DB::table('transaksi')
            ->where('tnsid', $request->tnsdparent)
            ->update(['tnstotal' => $total]);

        // Lanjutkan dengan mengurangi saldo dan memasukkan data
        foreach ($bahanData as $datas) {
        // Generate ID transaksi bahan
        $cekmaxTransaksiBahan = DB::table('transaksi_bahan')
            ->where('tnsbid', 'like', 'TNSB-'.$prefixfix.'-'.$tgl.'-%')
            ->max('tnsbid');
        if ($cekmaxTransaksiBahan) {
            $maxNumberTransaksiBahan = intval(substr($cekmaxTransaksiBahan, 18)) + 1;
        } else {
            $maxNumberTransaksiBahan = 1;
        }
        $formattedNumber = sprintf('%03d', $maxNumberTransaksiBahan);
        $generateIdTransaksiBahan= 'TNSB-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

            // Cek apakah ada varian yang sesuai untuk bahan ini
            $varian = collect($BahanVarian)->firstWhere('bvarbahan', $datas['bhnid']);
            $kuantiti = $varian ? $varian['bvarsaldo'] : $datas['bhankuantiti'];

            // Insert each data to the database
            $datasToInsert = [
                'tnsbid' => $generateIdTransaksiBahan,
                'tnsbparent' => $generateId,
                'tnsbsubparent' => '',
                'tnsbbahan' => $datas['bhnid'],
                'tnsbjumlah' => $kuantiti,
                'user_created' => $login,
                'created_at' => now(),
                'updated_at' => now(),
                'tnsbposisi' => $datas['bhnsaldo'],
                'cabang'=>$this->cabang,
                'tnsbgudang' => $datas['brggudang'],
            ];

            DB::table('transaksi_bahan')->insert($datasToInsert);

            $awalan = substr($datas['bhnid'],0,3);


        // ----------
        // Generate ID Stock Barang
        $cekmaxStock = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        
        $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
        $formattedNumberStock = sprintf('%03d', $maxNumberStock);
        $generateIdStock = "SB-$tgl-$formattedNumberStock";
        //END GENERATE ID STOCK BARANG
        $gudangsz = DB::table('gudang')
                    ->where('gudangutama', $jenisGudang)
                    ->where('cabang',$this->cabang)
                    ->value('gudangid');


        // Transaksi Stock Barang
        
        $datasToInsert = StockBarang::create([
                        'sbid'      => $generateIdStock,
                        'sbparent'  => $generateId,
                        'sbbahan'   => $datas['bhnid'],
                        'sbjenis'   => 'keluar',
                        'sbmasuk'   => null,
                        'sbkeluar'  => $kuantiti,
                        'sbgudang'  => $gudangsz, // Menggunakan ID Gudang
                        'sbuser'    => $login,
                        'created_at'=> now(),
                        'updated_at'=> null,
                        'sbcabang'  => $this->cabang,
                    ]);            
        }
        DB::commit();
        return response()->json(['status' => 200, 'pesan' => 'Semua Data berhasil ditambah', 'bahan'=>$datasToInsert, 'bahanVarian'=>$BahanVarian]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}


public function updateTransaksiDetail_new(Request $request) {
    $login = Auth::user()->username;

    $jenisGudang = DB::table('gudang')
                ->where('gudangid',$request->tnsdgudang_edit)
                ->value('gudangutama');

    // Validasi request TRANSAKSI DETAIL
    $validator = Validator::make($request->all(), [
        'tnsdjumlah_edit' => 'required',
        'tnsdbarang_edit' => 'required',
    ], [
        'tnsdjumlah_edit.required' => 'wajib diisi',
        'tnsdbarang.required' => 'wajib diisi',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    }

    // Variabel bahan terpakai dan varian
    $bahan = json_decode($request->input('bahan'), true);
    $bahanTerpakai = json_decode($request->input('bahanTerpakai'), true);
    $BahanVarian = json_decode($request->input('bahanVarian'), true);
    $rawBahan = json_decode($request->input('rawBahan'), true);

    // Validasi bahanTerpakai dan BahanVarian
    if (is_null($bahan) || !is_array($bahan)) {
        return response()->json(['status' => 500, 'pesan' => 'Data bahan tidak valid atau kosong.']);
    }

    if (is_null($bahanTerpakai) || !is_array($bahanTerpakai)) {
        return response()->json(['status' => 500, 'pesan' => 'Data bahan terpakai tidak valid atau kosong.']);
    }

    if (is_null($BahanVarian) || !is_array($BahanVarian)) {
        return response()->json(['status' => 500, 'pesan' => 'Data varian bahan tidak valid atau kosong.']);
    }

    if (is_null($rawBahan) || !is_array($rawBahan)) {
        return response()->json(['status' => 500, 'pesan' => 'RAW bahan tidak valid atau kosong.']);
    }

    // Mulai transaksi database
    DB::beginTransaction();

    // dd($request->tnsdgudang_edit);

    try {
        // Update transaksi detail
        DB::table('transaksid')
            ->where('tnsdid', $request->tnsdid_edit)
            ->update([
                'tnsdjumlah' => intval($request->tnsdjumlah_edit),
                'tnsdtotal' => floatval($request->tnsdtotal_edit),
                'tnsdketerangan' => $request->tnsdketerangan_edit,
                'user_updated' => $login,
                'updated_at' => now(),
            ]);


        // Update harga transaksi
        $cekTotal = DB::select("SELECT SUM(tnsdtotal) AS total FROM transaksid WHERE tnsdparent = ?", [$request->tnsdparent_edit]);
        $total = $cekTotal[0]->total ?? 0;
        DB::table('transaksi')
            ->where('tnsid', $request->tnsdparent_edit)
            ->update(['tnstotal' => $total]);

        // Update bahan dan varian bahan
        $processedBhnid = [];

        foreach ($bahanTerpakai as $bahanTerpakais) {
            foreach ($bahan as $bahans) {
                $bhnid = $bahanTerpakais['bhnid'];

                // dd($awalan);

                // Check if this bhnid is already processed
                if (in_array($bhnid, $processedBhnid)) {
                    continue;
                }

                $kuantiti = intval($bahans['bhankuantiti']);
                $selisih = $request->tnsdjumlah_edit - $request->tnsdjumlah_edit_old;

                $currentJumlah = DB::table('transaksi_bahan')
                    ->where('tnsbid', $bahanTerpakais['tnsbid'])
                    ->value('tnsbjumlah'); 

                $currentJumlahStockBarang = DB::table('stock_barang')
                                            ->where('sbparent', $bahanTerpakais['tnsbparent'])
                                            ->where('sbbahan', $bahanTerpakais['tnsbbahan'])
                                            ->value('sbkeluar'); 

                
                $currentJumlahOld = $currentJumlah / $request->tnsdjumlah_edit_old; // Get old jumlah
                $currentJumlahOldStockBarang = $currentJumlahStockBarang / $request->tnsdjumlah_edit_old; // Get old jumlah

                

                $awalan = substr($bahanTerpakais['tnsbbahan'],0,3);


                if ($selisih > 0) {
                    foreach ($rawBahan as $rawBahans) {
                        if ($rawBahans['bhnid'] == $bhnid) {
                            $kuantiti = $rawBahans['bhankuantiti'];
                        }
                    }
                        if($jenisGudang == 2){
                            if($awalan == 'BHN'){
                                $currentSaldo = DB::table('bahan_bar')
                                    ->where('bhnid', $bhnid)
                                    ->value('bhnsaldo');
                            } elseif ($awalan == 'BHO'){
                                $currentSaldo = DB::table('bahan_olah')
                                    ->where('bhoid', $bhnid)
                                    ->value('bhosaldo');

                                    // dd($currentSaldo);
                            }
                        } else if($jenisGudang == 3){
                            if($awalan == 'BHN'){
                                $currentSaldo = DB::table('bahan_kitchen')
                                    ->where('bhnid', $bhnid)
                                    ->value('bhnsaldo');
                            } elseif ($awalan == 'BHO'){
                                $currentSaldo = DB::table('bahan_olah')
                                    ->where('bhoid', $bhnid)
                                    ->value('bhosaldo');
                            }
                        } else {
                            if($awalan == 'BHN'){
                                $currentSaldo = DB::table('bahan')
                                    ->where('bhnid', $bhnid)
                                    ->value('bhnsaldo');
                            } elseif ($awalan == 'BHO'){
                                $currentSaldo = DB::table('bahan_olah')
                                    ->where('bhoid', $bhnid)
                                    ->value('bhosaldo');
                            }
                        }
                    

                    $newSaldo = $currentSaldo - ($kuantiti * $selisih);

                    // Update transaksi bahan dan saldo bahan
                    DB::table('transaksi_bahan')
                        ->where('tnsbid', $bahanTerpakais['tnsbid'])
                        ->update(['tnsbjumlah' => $currentJumlah + ($kuantiti * $selisih)]);


                        // sekarang pakainya di stock barang, tapi karna udh kompleks di transaksi bahan
                        // yauda lanjut aja wkw
                    DB::table('stock_barang')
                        ->where('sbparent', $bahanTerpakais['tnsbparent'])
                        ->where('sbbahan', $bahanTerpakais['tnsbbahan'])
                        ->update(['sbkeluar' => $currentJumlahStockBarang + ($kuantiti * $selisih)]);


                    $updateBahanOlah = DB::table('bahan_olah')
                                ->where('bhoid', $bhnid)
                                ->update(['bhosaldo' => $newSaldo]);



                    if($jenisGudang == 2){
                        if($awalan == 'BHN'){
                            DB::table('bahan_bar')
                                ->where('bhnid', $bhnid)
                                ->update(['bhnsaldo' => $newSaldo]);
                        } else if($awalan == 'BHO'){
                            $updateBahanOlah;
                        }
                    } else if($jenisGudang == 3){
                        if($awalan == 'BHN'){
                            DB::table('bahan_kitchen')
                                ->where('bhnid', $bhnid)
                                ->update(['bhnsaldo' => $newSaldo]);
                        } else if($awalan == 'BHO'){
                            $updateBahanOlah;
                        }
                    } else {
                        if($awalan == 'BHN'){
                            DB::table('bahan')
                                ->where('bhnid', $bhnid)
                                ->update(['bhnsaldo' => $newSaldo]);
                        } else if($awalan == 'BHO'){
                            $updateBahanOlah;
                        }
                    }
                } else if ($selisih < 0) {
                    foreach ($rawBahan as $rawBahans) {
                        if ($rawBahans['bhnid'] == $bhnid) {
                            if($jenisGudang == 2){
                                if($awalan == 'BHN'){
                                    DB::table('bahan_bar')
                                        ->where('bhnid', $bhnid)
                                        ->increment('bhnsaldo', $rawBahans['bhankuantiti']);
                                } else{
                                    DB::table('bahan_olah')
                                        ->where('bhoid', $bhnid)
                                        ->increment('bhosaldo', $rawBahans['bhankuantiti']);
                                }
                                
                            } else if($jenisGudang == 3){
                                if($awalan == 'BHN'){
                                    DB::table('bahan_kitchen')
                                        ->where('bhnid', $bhnid)
                                        ->increment('bhnsaldo', $rawBahans['bhankuantiti']);
                                } else{
                                    DB::table('bahan_olah')
                                        ->where('bhoid', $bhnid)
                                        ->increment('bhosaldo', $rawBahans['bhankuantiti']);
                                }
                            } else {
                                if($awalan == 'BHN'){
                                    DB::table('bahan')
                                        ->where('bhnid', $bhnid)
                                        ->increment('bhnsaldo', $rawBahans['bhankuantiti']);
                                } else{
                                    DB::table('bahan_olah')
                                        ->where('bhoid', $bhnid)
                                        ->increment('bhosaldo', $rawBahans['bhankuantiti']);
                                }
                            }
                        }
                    }

                    DB::table('transaksi_bahan')
                        ->where('tnsbid', $bahanTerpakais['tnsbid'])
                        ->update(['tnsbjumlah' => $currentJumlahOld * $request->tnsdjumlah_edit]);


                    DB::table('stock_barang')
                        ->where('sbparent', $bahanTerpakais['tnsbparent'])
                        ->where('sbbahan', $bahanTerpakais['tnsbbahan'])
                        ->update(['sbkeluar' => $currentJumlahOld * $request->tnsdjumlah_edit]);




                }

                $processedBhnid[] = $bhnid; 
            }
        }

        // Commit transaksi jika semua proses berhasil
        DB::commit();

        return response()->json(['status' => 200, 'pesan' => 'Data berhasil diupdate']);

    } catch (\Throwable $th) {
        // Rollback transaksi jika terjadi kesalahan
        DB::rollBack();
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $th->getMessage()]);
    }
}



public function updateTransaksiDetail(Request $request) {
    $login = Auth::user()->username;

    // Validasi request TRANSAKSI DETAIL
    $validator = Validator::make($request->all(), [
        'tnsdjumlah_edit' => 'required',
        'tnsdbarang_edit' => 'required',
    ], [
        'tnsdjumlah.required' => 'wajib diisi',
        'tnsdbarang.required' => 'wajib diisi',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    }

    $tnsbid = json_decode($request->input('tnsbid'), true);
    $bahanData = json_decode($request->input('bahan'), true);
    $BahanVarian = json_decode($request->input('bahanVarian'), true);

    if (empty($bahanData)) {
        return response()->json(['status' => 400, 'pesan' => 'Data bahan tidak ditemukan']);
    }


    try {
        // Mengembalikan stok bahan ke kondisi sebelum transaksi dan hapus data lama
        foreach ($tnsbid as $existingTnsbId) {
            // Mengambil data bahan terkait dengan tnsbid yang lama
            $transaksiBahan = DB::table('transaksi_bahan')
                ->where('tnsbid', $existingTnsbId)
                ->get();

            foreach ($transaksiBahan as $tb) {
                DB::table('bahan')
                    ->where('bhnid', $tb->tnsbbahan)
                    ->increment('bhnsaldo', $tb->tnsbjumlah);
            }

            // Hapus data transaksi bahan yang lama
            DB::table('transaksi_bahan')
                ->where('tnsbid', $existingTnsbId)
                ->delete();
        }
        // dd($tnsbid);


        // Insert data bahan baru
        foreach ($bahanData as $datas) {
            $varian = collect($BahanVarian)->firstWhere('bvarbahan', $datas['bhnid']);
            $kuantiti = $varian ? $varian['bvarsaldo'] : $datas['bhankuantiti'];

            // foreach ($tnsbid as $newTnsbId) {
            //     DB::table('transaksi_bahan')->insert([
            //         'tnsbid' => $newTnsbId, // Gunakan tnsbid yang baru
            //         'tnsbparent' => $request->tnsdid_edit, // Perbaiki jika perlu
            //         'tnsbsubparent' => '',
            //         'tnsbbahan' => $datas['bhnid'],
            //         'tnsbjumlah' => $kuantiti,
            //         'user_created' => $login,
            //         'created_at' => now(),
            //         'updated_at' => now(),
            //         'tnsbposisi' => $datas['bhnsaldo'],
            //     ]);
            // }

            // dd($datas);

            // Kurangi stok bahan sesuai kuantiti
            DB::table('bahan')
                ->where('bhnid', $datas['bhnid'])
                ->decrement('bhnsaldo', $kuantiti);
        }

        // Update transaksi detail
        DB::table('transaksid')
            ->where('tnsdid', $request->tnsdid)
            ->update([
                'tnsdbarang' => $request->tnsdbarang_edit,
                'tnsdjumlah' => $request->tnsdjumlah_edit,
                'tnsdtotal' => $request->tnsdtotal_edit,
                'tnsdketerangan' => $request->tnsdketerangan,
                'user_updated' => $login,
                'updated_at' => now(),
            ]);

        // Update harga transaksi
        $cekTotal = DB::select("SELECT SUM(tnsdtotal) AS total FROM transaksid WHERE tnsdparent = ?", [$request->tnsdparent]);
        $total = $cekTotal[0]->total ?? 0;
        DB::table('transaksi')
            ->where('tnsid', $request->tnsdparent)
            ->update(['tnstotal' => $total]);

        return response()->json(['status' => 200, 'pesan' => 'Data berhasil diupdate']);
    } catch (\Exception $e) {
        
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);

    }


}



public function getBahanFromTransaksid(Request $request){
        $barang = $request->tnsdbarang;
        $transaksi = $request->transaksi;
        $transaksiParentDetail = $request->transaksiParentDetail;
        $bvarid = $request->bvarid;
        $gudang = $request->gudang;

        $jenisGudang = DB::table('gudang')
                    ->where('gudangid',$request->gudang)
                    ->value('gudangutama');

        $query_bar = DB::table('barang_bahan as a')
                    ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','c.brggudang')
                    ->leftJoin('bahan_bar as b', 'a.bhnid', '=', 'b.bhnid')
                    ->leftJoin('barang as c','c.brgid','=','a.bhanbarang')
                    ->where('a.bhanbarang', '=', $barang)
                    ->where(DB::raw('LEFT(a.bhnid, 3)'), '=', 'BHN');
        $query_kitchen = DB::table('barang_bahan as a')
                    ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','c.brggudang')
                    ->leftJoin('bahan_kitchen as b', 'a.bhnid', '=', 'b.bhnid')
                    ->leftJoin('barang as c','c.brgid','=','a.bhanbarang')
                    ->where('a.bhanbarang', '=', $barang)
                    ->where(DB::raw('LEFT(a.bhnid, 3)'), '=', 'BHN');

        $query_biasa = DB::table('barang_bahan as a')
                    ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo','c.brggudang')
                    ->leftJoin('bahan_kitchen as b', 'a.bhnid', '=', 'b.bhnid')
                    ->leftJoin('barang as c','c.brgid','=','a.bhanbarang')
                    ->where('a.bhanbarang', '=', $barang)
                    ->where(DB::raw('LEFT(a.bhnid, 3)'), '=', 'BHN');

        $query_olah = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhomax', 'b.bhomin', 'b.bhosaldo','b.bhoid as brggudang')
                ->leftJoin('bahan_olah as b', 'a.bhnid', '=', 'b.bhoid')
                ->where('a.bhanbarang', '=', $barang)
                ->where(DB::raw('LEFT(a.bhnid, 3)'), '=', 'BHO');

        if($jenisGudang == 2){
                // Gabungkan kedua query dengan union
                $data = $query_bar->union($query_olah)->get();

        } else if($jenisGudang == 3){
                $data = $query_kitchen->union($query_olah)->get();

        }else {
                $data = $query_biasa->union($query_olah)->get();

        }

        

        $datas = DB::table('barang_varian as a')
        ->select('a.*')
        ->where('a.bvarid', '=', $bvarid )        
        ->get();


        $queryBahanBiasa_terpakai = DB::table('transaksi_bahan as a')
            ->select('a.*', 'b.bhnid', 'b.bhnnama')
            ->leftJoin('bahan as b', 'a.tnsbbahan', '=', 'b.bhnid')
            ->where('a.tnsbparent', '=', $transaksiParentDetail)
            ->whereRaw('LEFT(b.bhnid, 3) = ?', ['BHN']);
        
            // Query untuk bahan_olah
        $queryBahanOlah_terpakai = DB::table('transaksi_bahan as a')
            ->select('a.*', 'b.bhoid', 'b.bhonama')
            ->leftJoin('bahan_olah as b', 'a.tnsbbahan', '=', 'b.bhoid')
            ->where('a.tnsbparent', '=', $transaksiParentDetail)
            ->whereRaw('LEFT(b.bhoid, 3) = ?', ['BHO']);

        $bahanTerpakai =  $queryBahanBiasa_terpakai->union($queryBahanOlah_terpakai)->get();

        // Ambil varian barang
        $barangData = DB::table('barang')
        ->select('brgvarian')
        ->where('brgid', '=', $barang)
        ->first();

        // Jika varian barang ditemukan
        $varian = [];
        if ($barangData && !empty($barangData->brgvarian)) {
            // Urai brgvarian menjadi array
            $varianIds = explode(',', $barangData->brgvarian);

            // Gabungkan dengan tabel varian
            $varian = DB::table('barang_varian')
                ->whereIn('bvarid', $varianIds)
                ->get();
                
        } 
        return response()->json(['bahan'=>$data,'bahanVarian'=>$datas,'bahanTerpakai'=>$bahanTerpakai,'transaksi'=>$transaksi]);
    }

    public function getBahanFromVarian(Request $request){
        $bvarid = $request->bvarid;
       
       

        // Ambil varian barang
        $varianData = DB::table('barang_varian')
        ->select('*')
        ->where('bvarid', '=', $bvarid)
        ->first();

        // Jika bahan varian ditemukan
        $varian = [];
        if ($varianData && !empty($varianData->brgvarian)) {
            // Urai brgvarian menjadi array
            $varianIds = explode(',', $varianData->brgvarian);

            // Gabungkan dengan tabel varian
            $varian = DB::table('barang_varian')
                ->whereIn('bvarid', $varianIds)
                ->get();
                
        } 

        return response()->json(['bahanVarian'=>$varianData]);
    }
public function getVarianFromBarang(Request $request){

        $barang = $request->tnsdbarang;

        // Ambil varian barang
        $barangData = DB::table('barang')
        ->select('brgvarian')
        ->where('brgid', '=', $barang)
        ->first();


        // Jika varian barang ditemukan
        $varian = [];
        if ($barangData && !empty($barangData->brgvarian)) {
            // Urai brgvarian menjadi array
            $varianIds = explode(',', $barangData->brgvarian);

            // Gabungkan dengan tabel varian
            $varian = DB::table('barang_varian')
                ->whereIn('bvarid', $varianIds)
                ->get();
                
        } 

        return response()->json(['varian'=>$varian]);
    }


    public function getBahanDetail(Request $request)
    {
        $transaksi = $request->transaksi;

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('bahan as a')
        ->select('*','a.created_at as tglbuat')
        ->leftJoin('barang as b','a.tnsdbarang','=','b.brgid')
        ->where('tnsdparent', '=', $transaksi )        
        ->orderBy('a.created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if($data->tnsdstatus == '1'){
                        $txt = '<span class="badge bg-success">Selesai</span>';
                    } 
                    else {
                        $txt = '<span class="badge bg-danger">blm selesai</span>';
                    }
                    return $txt;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Edit"><i class="bi-pencil-square mr-5"></i></a><a href="#" title="Hapus"><i class="bi bi-trash"></i></a>
                    ';
                    return $act;
                })
                ->rawColumns(['status','action'])
                ->make(true);       
        }

    }

// START KITCHEN
public function pembelian_kitchen(){
    
        $login = Auth::user()->divisi;
        return view('transaksi.kitchen.v_pembelian_kitchen',['divisi'=>$login]);
    
}


public function persediaan_kitchen(){
        return view('kitchen.v_persediaan_kitchen');
}
public function getPembelian_kitchen(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;
        

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('pembelian')
        ->select('*')
        ->where('created_at', '>=', $tgldari . ' 00:00:00')        
        ->where('created_at', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00')  
        ->where('pmbdivisi', '=', 'kitchen')
        ->orderBy('created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->pmbid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function getPembeliand_kitchen(Request $request)
    {
        $transaksi = $request->transaksi;

         $data = DB::table('pembeliand as a')
        ->select('a.*')
        ->leftJoin('pembelian as b','a.pmbdparent','=','b.pmbid')
        ->where('a.pmbdparent', '=', $transaksi )        
        ->orderBy('a.created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->pmbdid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Edit"><i class="bi-pencil-square mr-5"></i></a><a href="#" title="Hapus"><i class="bi bi-trash"></i></a>
                    ';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }
    }

    


    public function addPembelian_kitchen(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;


        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
         $cekmax = DB::table('pembelian')
            ->where('pmbid', 'like', 'PMB-'.$prefixfix.'-'.$tgl.'-%')
            ->max('pmbid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 18)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'PMB-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

        $gudang = DB::table('gudang')
            ->where('gudangutama',3)
            ->where('cabang',$this->cabang)
            ->value('gudangid');

        $validator = Validator::make($request->all(),[
            'pmbket'     => 'required',
            // 'pmbjenis'     => 'required',
            'pmbtgl'     => 'required',

        ],[
            'pmbket.required' => 'Keterangan barang wajib diisi',
            // 'pmbjenis.required' => 'Jenis wajib diisi',
            'pmbtgl.required' => 'Tanggal wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = Pembelian::create([
                'pmbid' => $generateId,
                'pmbket' => $request->pmbket,
                'pmbjenis' => $request->pmbjenis,
                'pmbtgl' => $request->pmbtgl,
                'user_created' => $login,
                'pmbdivisi' => 'kitchen',
                'pmbgudang'=>$gudang,
                'cabang'=>$this->cabang

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
    }

    public function addPembeliand_kitchen(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;

        // dd($this->cabang);

        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
        
        $tglpick = str_replace('-', '', $request->pmbtgl);



   
         $cekmax = DB::table('pembeliand')
            ->where('pmbdid', 'like', 'PMBD-'.$prefixfix.'-'.$tgl.'-%')
            ->max('pmbdid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 19)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'PMBD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;


        // Generate ID Stock Barang
        $cekmaxStock = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        
        $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
        $formattedNumberStock = sprintf('%03d', $maxNumberStock);
        $generateIdStock = "SB-$tgl-$formattedNumberStock";
        //END GENERATE ID STOCK BARANG

        // Generate ID Stock Barang Keluar
        $cekmaxStockKeluar = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        $maxNumberKeluar = $cekmaxStockKeluar ? intval(substr($cekmaxStockKeluar, 12)) + 1 : 1;
        $generateIdStockKeluar = "SB-$tgl-" . sprintf('%03d', $maxNumberKeluar + 1); // Pastikan ID unik

        // Transaksi Stock Barang Keluar


        $gudang_kitchen = DB::table('gudang')
                    ->where('gudangutama', 3)
                    ->where('cabang',$this->cabang)
                    ->value('gudangid');

        $gudang_besar = DB::table('gudang')
            ->where('gudangutama', 1)
            ->where('cabang',$this->cabang)
            ->value('gudangid');


        $validator = Validator::make($request->all(),[
            'pmbdbrg'     => 'required',

        ],[
            'pmbdbrg.required' => 'Barang wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

            $posisiBahanBar = DB::table('bahan_bar')
                        ->where('bhnid', $request->pmbdbrg)
                        ->value('bhnsaldo');

            
            $data = Pembeliand::create([
                'pmbdid' => $generateId,
                'pmbdparent' => $request->pmbdparent,
                'pmbdket' => $request->pmbdket,
                'pmbdbrg' => $request->pmbdbrg,
                'pmbdbrgn' => $request->pmbdbrgn,
                'pmbdjumlah' => $request->pmbdjumlah,
                'user_created' => $login,
                'pmbdposisi' => $posisiBahanBar,
                'pmbddivisi' => 'kitchen',
                'pmbdtotal' =>$posisiBahanBar + $request->pmbdjumlah,
                'cabang' => $this->cabang,
            ]);

            // Transaksi Stock Barang
            StockBarang::create([
                'sbid'      => $generateIdStock,
                'sbparent'  => $generateId,
                'sbbahan'   => $request->pmbdbrg,
                'sbjenis'   => 'masuk',
                'sbmasuk'   => $request->pmbdjumlah,
                'sbkeluar'  => null,
                'sbgudang'  => $gudang_kitchen, // Menggunakan ID Gudang
                'sbuser'    => $login,
                'created_at'=> now(),
                'updated_at'=> null,
                'sbcabang'  => $this->cabang,
            ]);

             // Transaksi Stock Barang keluar
            StockBarang::create([
                'sbid'      => $generateIdStockKeluar,
                'sbparent'  => $generateId,
                'sbbahan'   => $request->pmbdbrg,
                'sbjenis'   => 'keluar',
                'sbkeluar'   => $request->pmbdjumlah,
                'sbmasuk'  => null,
                'sbgudang'  => $gudang_besar, // Menggunakan ID Gudang
                'sbuser'    => $login,
                'created_at'=> now(),
                'updated_at'=> null,
                'sbcabang'  => $this->cabang,
            ]);

            $cekBahanGudangKitchen = DB::table('bahan_kitchen')
            ->where('bhnid', $request->pmbdbrg)
            ->first();

            if($cekBahanGudangKitchen === null){
                BahanBar::create([
                'bhnid' => $request->pmbdbrg,
                'bhnnama' => $request->pmbdbrgn,
                'bhnsatuan' => $request->pmbdsatuan,
                'bhnuser' => $login,
                'bhnsaldo' => $request->pmbdjumlah,
                'created_at' => now(),
                'updated_at' => now(),
                'bhnmin' => '0',
                'bhnmax' => '0',
                'bhnsupp' => $request->bhnsupp,
                'cabang'=> $this->cabang,
            ]);

            
            } else {

                //nothing
            }
            

            DB::table('bahan')
                    ->where('bhnid', $request->pmbdbrg)
                    ->decrement('bhnsaldo', $request->pmbdjumlah);

            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
            }
        }
    }

    public function getTabelBahanKitchen(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;

        $gudang = DB::table("gudang")
            ->where("gudangutama", 3)
            ->where("cabang", $this->cabang)
            ->value("gudangid");

    if ($gudang !== null) {
    $data = DB::table('bahan_kitchen AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$gudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$gudang'
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
    
            // dd($data);
        } else {
            return response()->json(['error' => 'Gudang tidak ditemukan'], 404);
        }




         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a id="btn-riwayat" href="#" title="Riwayat order" 
                    data-id="'.$data->bhnid.'"
                    data-bhnnama="'.$data->bhnnama.'">
                    <i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

// END KITCHEN



    


    ///TAMPILAN DAN CRUD PEMBELIAN------------------------------------------------------------------
public function pembelian_bar(){
        $login = Auth::user()->divisi;
        return view('transaksi.bar.v_pembelian_bar',['divisi'=>$login]);

}

public function persediaan_bar(){
        return view('bar.v_persediaan_bar');
}

    public function getTabelBahanBar(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;

        $gudang = DB::table("gudang")
            ->where("gudangutama", 2)
            ->where("cabang", $this->cabang)
            ->value("gudangid");

    if ($gudang !== null) {
    $data = DB::table('bahan_bar AS a')
        ->select(
            'a.*',
            DB::raw("
                COALESCE(
                    (SELECT sbadjust
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'adjust' 
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                     ORDER BY a2.created_at DESC
                     LIMIT 1), 0
                ) +
                COALESCE(
                    (SELECT SUM(a2.sbmasuk)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'masuk'  
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$gudang'
                             AND a3.sbcabang = $this->cabang
                       )
                    ), 0
                ) -
                COALESCE(
                    (SELECT SUM(a2.sbkeluar)
                     FROM stock_barang a2
                     WHERE a2.sbbahan = a.bhnid 
                       AND a2.sbjenis = 'keluar' 
                       AND a2.sbgudang = '$gudang'
                       AND a2.sbcabang = $this->cabang
                       AND a2.created_at > (
                           SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                           FROM stock_barang a3
                           WHERE a3.sbbahan = a.bhnid 
                             AND a3.sbjenis = 'adjust' 
                             AND a3.sbgudang = '$gudang'
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
    
            // dd($data);
        } else {
            return response()->json(['error' => 'Gudang tidak ditemukan'], 404);
        }




         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a id="btn-riwayat" href="#" title="Riwayat order" 
                    data-id="'.$data->bhnid.'"
                    data-bhnnama="'.$data->bhnnama.'">
                    <i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function getTabelBahanOlah(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;
        
        $data = DB::table('bahan_olah as a')
        ->select('a.*','b.gudangn')
        ->join('gudang as b','a.bhogudang','=','b.gudangid')
        ->where('a.cabang', $this->cabang)
        ->get();

        
         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhoid.'</span>';
                    return $id;
                })
                ->addColumn('gudang', function ($data) {
                    $id = '<span class="badge bg-light-warning text-black">'.$data->gudangn.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Riwayat order"><i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action','gudang'])
                ->make(true);       
        }

    }

    public function getPembelian_bar(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;
        

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('pembelian')
        ->select('*')
        ->where('pmbtgl', '>=', $tgldari . ' 00:00:00')        
        ->where('pmbtgl', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00')  
        ->where('pmbdivisi', '=', 'bar')
        ->orderBy('created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->pmbid.'</span>';
                    return $id;
                })
                 ->addColumn('tgl', function ($data) {
                    $act = tanggal_indonesia($data->pmbtgl);
                    return $act;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a id="btn-delete-pembelian" data-id='.$data->pmbid.' title="Hapus"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action','tgl'])
                ->make(true);       
        }


    }

    public function getPembeliand_bar(Request $request)
    {
        $transaksi = $request->transaksi;

         $data = DB::table('pembeliand as a')
        ->select('a.*')
        ->leftJoin('pembelian as b','a.pmbdparent','=','b.pmbid')
        ->where('a.pmbdparent', '=', $transaksi )        
        ->orderBy('a.created_at')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->pmbdid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Edit" id="edit-btn-pmbd"
                    data-id="'.$data->pmbdid.'"
                    data-barang="'.$data->pmbdbrg.'"
                    data-barangn="'.$data->pmbdbrgn.'"
                    data-jumlah="'.$data->pmbdjumlah.'"

                    
                    ><i class="bi-pencil-square mr-5"></i></a>


                    <a href="#" title="Hapus"  id="delete-btn-pmbd"
                    data-id="'.$data->pmbdid.'"
                    data-barang="'.$data->pmbdbrg.'"
                    data-barangn="'.$data->pmbdbrgn.'"
                    data-jumlah="'.$data->pmbdjumlah.'"
                    ><i class="bi bi-trash"></i></a>
                    ';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }
    }

    


    public function addPembelian_bar(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;


        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
        
        $tglpick = str_replace('-', '', $request->pmbtgl);


         $cekmax = DB::table('pembelian')
            ->where('pmbid', 'like', 'PMB-'.$prefixfix.'-'.$tglpick.'-%')
            ->max('pmbid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 18)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'PMB-'.$prefixfix.'-'.$tglpick.'-'.$formattedNumber;

        $gudang = DB::table('gudang')
            ->where('gudangutama',2)
            ->where('cabang',$this->cabang)
            ->value('gudangid');

        $validator = Validator::make($request->all(),[
            'pmbket'     => 'required',
            // 'pmbjenis'     => 'required',
            'pmbtgl'     => 'required',

        ],[
            'pmbket.required' => 'Keterangan barang wajib diisi',
            // 'pmbjenis.required' => 'Jenis wajib diisi',
            'pmbtgl.required' => 'Tanggal wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = Pembelian::create([
                'pmbid' => $generateId,
                'pmbket' => $request->pmbket,
                'pmbjenis' => $request->pmbjenis,
                'pmbtgl' => $request->pmbtgl,
                'user_created' => $login,
                'pmbdivisi' => 'bar',
                'pmbgudang'=>$gudang,
                'cabang'=>$this->cabang

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
    }

    public function addPembeliand_bar(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;

        // dd($this->cabang);

        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
        
        $tglpick = str_replace('-', '', $request->pmbtgl);



   
         $cekmax = DB::table('pembeliand')
            ->where('pmbdid', 'like', 'PMBD-'.$prefixfix.'-'.$tgl.'-%')
            ->max('pmbdid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 19)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'PMBD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;


        // Generate ID Stock Barang
        $cekmaxStock = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        
        $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
        $formattedNumberStock = sprintf('%03d', $maxNumberStock);
        $generateIdStock = "SB-$tgl-$formattedNumberStock";
        //END GENERATE ID STOCK BARANG

        // Generate ID Stock Barang Keluar
        $cekmaxStockKeluar = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        $maxNumberKeluar = $cekmaxStockKeluar ? intval(substr($cekmaxStockKeluar, 12)) + 1 : 1;
        $generateIdStockKeluar = "SB-$tgl-" . sprintf('%03d', $maxNumberKeluar + 1); // Pastikan ID unik

        // Transaksi Stock Barang Keluar


        $gudang_bar = DB::table('gudang')
                    ->where('gudangutama', 2)
                    ->where('cabang',$this->cabang)
                    ->value('gudangid');

        $gudang_besar = DB::table('gudang')
            ->where('gudangutama', 1)
            ->where('cabang',$this->cabang)
            ->value('gudangid');


        $validator = Validator::make($request->all(),[
            'pmbdbrg'     => 'required',

        ],[
            'pmbdbrg.required' => 'Barang wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

            $posisiBahanBar = DB::table('bahan_bar')
                        ->where('bhnid', $request->pmbdbrg)
                        ->value('bhnsaldo');

            
            $data = Pembeliand::create([
                'pmbdid' => $generateId,
                'pmbdparent' => $request->pmbdparent,
                'pmbdket' => $request->pmbdket,
                'pmbdbrg' => $request->pmbdbrg,
                'pmbdbrgn' => $request->pmbdbrgn,
                'pmbdjumlah' => $request->pmbdjumlah,
                'user_created' => $login,
                'pmbdposisi' => $posisiBahanBar,
                'pmbddivisi' => 'bar',
                'pmbdtotal' =>$posisiBahanBar + $request->pmbdjumlah,
                'cabang' => $this->cabang,
            ]);

            // Transaksi Stock Barang
            StockBarang::create([
                'sbid'      => $generateIdStock,
                'sbparent'  => $generateId,
                'sbbahan'   => $request->pmbdbrg,
                'sbjenis'   => 'masuk',
                'sbmasuk'   => $request->pmbdjumlah,
                'sbkeluar'  => null,
                'sbgudang'  => $gudang_bar, // Menggunakan ID Gudang
                'sbuser'    => $login,
                'created_at'=> now(),
                'updated_at'=> null,
                'sbcabang'  => $this->cabang,
            ]);

             // Transaksi Stock Barang keluar
            StockBarang::create([
                'sbid'      => $generateIdStockKeluar,
                'sbparent'  => $generateId,
                'sbbahan'   => $request->pmbdbrg,
                'sbjenis'   => 'keluar',
                'sbkeluar'   => $request->pmbdjumlah,
                'sbmasuk'  => null,
                'sbgudang'  => $gudang_besar, // Menggunakan ID Gudang
                'sbuser'    => $login,
                'created_at'=> now(),
                'updated_at'=> null,
                'sbcabang'  => $this->cabang,
            ]);

            $cekBahanGudangBar = DB::table('bahan_bar')
            ->where('bhnid', $request->pmbdbrg)
            ->first();

            if($cekBahanGudangBar === null){
                BahanBar::create([
                'bhnid' => $request->pmbdbrg,
                'bhnnama' => $request->pmbdbrgn,
                'bhnsatuan' => $request->pmbdsatuan,
                'bhnuser' => $login,
                'bhnsaldo' => $request->pmbdjumlah,
                'created_at' => now(),
                'updated_at' => now(),
                'bhnmin' => '0',
                'bhnmax' => '0',
                'bhnsupp' => $request->bhnsupp,
                'cabang'=> $this->cabang,
            ]);

            
            } else {
                // // Ambil nilai bhnsaldo untuk bahan yang dimaksud
                // $bhnsaldo = DB::table('bahan_bar')
                //     ->where('bhnid', $request->pmbdbrg)
                //     ->value('bhnsaldo');

                // // Jika bhnsaldo NULL, update menggunakan DB::raw dengan COALESCE
                // if (is_null($bhnsaldo)) {
                //     DB::table('bahan_bar')
                //         ->where('bhnid', $request->pmbdbrg)
                //         ->update(['bhnsaldo' => DB::raw('COALESCE(bhnsaldo, 0) + ' . (int)$request->pmbdjumlah)]);
                // } else {
                //     // Jika bhnsaldo tidak NULL, lakukan increment
                //     DB::table('bahan_bar')
                //         ->where('bhnid', $request->pmbdbrg)
                //         ->increment('bhnsaldo', (int)$request->pmbdjumlah);
                // }

                //nothing
            }
            

            DB::table('bahan')
                    ->where('bhnid', $request->pmbdbrg)
                    ->decrement('bhnsaldo', $request->pmbdjumlah);

            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
            }
        }
    }

// END PEMBELIAN BAR

// START KASIR BAR
public function kasir_bar()
    {
        $jenis = DB::select('SELECT * FROM BARANG_JENIS ORDER BY BRJNAMA');
        return view('transaksi.bar.v_kasir_bar',['jenis'=>$jenis]);

    }
public function addTransaksiDetailBar(Request $request) {
    $login = Auth::user()->username;
    $cabang_login = Auth::user()->cabang;
    $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
    $prefixfix = $prefix[0]->stgprefix;
    $tgl = Carbon::today()->format('Ymd');

    // Validasi request TRANSAKSI DETAIL
    $validator = Validator::make($request->all(), [
        'tnsdjumlah' => 'required',
        'tnsdbarang' => 'required',
    ], [
        'tnsdjumlah.required' => 'wajib diisi',
        'tnsdbarang.required' => 'wajib diisi',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    }

    // Decode JSON-encoded bahan data
    $bahanData = json_decode($request->input('bahan'), true);
    if (empty($bahanData)) {
        return response()->json(['status' => 400, 'pesan' => 'Data bahan tidak ditemukan']);
    } 

    $BahanVarian = json_decode($request->input('bahanVarian'), true);
    // if (empty($BahanVarian)) {
    //     return response()->json(['status' => 400, 'pesan' => 'Varian tidak ditemukan NIH ah']);
    // } 


    // Pengecekan saldo bahan
    foreach ($bahanData as $datas) {
        $bahan = DB::table('bahan_bar')->where('bhnid', $datas['bhnid'])->first();
        if (!$bahan || $bahan->bhnsaldo < $datas['bhankuantiti']) {
            return response()->json(['status' => 400, 'pesan' => 'Saldo bahan tidak mencukupi untuk bahan ID: ' . $datas['bhannama']]);
        }
    }

    foreach ($BahanVarian as $datasv) {
        $varians = DB::table('bahan_bar')->where('bhnid', $datasv['bvarbahan'])->first();
        if (!$varians || $varians->bhnsaldo < $datasv['bvarsaldo']) {
            return response()->json(['status' => 400, 'pesan' => 'Dengan Varian ini, bahan tidak mencukupi: ' . $datasv['bvarbahan']]);
        }
    }

    // Jika semua bahan cukup, lakukan transaksi
    DB::beginTransaction();
    try {
        $cekmax = DB::table('transaksid')
            ->where('tnsdid', 'like', 'TNSD-'.$prefixfix.'-'.$tgl.'-%')
            ->where('tnsddivisi','=','bar')
            ->max('tnsdid');
        if ($cekmax) {
            $maxNumber = intval(substr($cekmax, 18)) + 1;
        } else {
            $maxNumber = 1;
        }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'TNSD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

        // Input transaksi detail
        $data = DB::table('transaksid')->insert([
            'tnsdid' => $generateId,
            'tnsdparent' => $request->tnsdparent,
            'tnsdbarang' => $request->tnsdbarang,
            'tnsdjumlah' => $request->tnsdjumlah,
            'tnsdtotal' => $request->tnsdtotal,
            'tnsdketerangan' => $request->tnsdketerangan,
            'tnsdstatus' => '0',
            'user_created' => $login,
            'created_at' => now(),
            'updated_at' => now(),
            'tnsddivisi' => 'bar',
        ]);

        // Update harga transaksi
       $cekTotal = DB::select("SELECT SUM(tnsdtotal) AS total FROM transaksid WHERE tnsdparent = ? and tnsddivisi = 'bar'", [$request->tnsdparent]);

        // Pastikan untuk mengakses hasil query dengan benar
        $total = $cekTotal[0]->total ?? 0; // Jika tidak ada hasil, total menjadi 0

        // Update kolom tnstotal di tabel transaksi
        DB::table('transaksi')
            ->where('tnsid', $request->tnsdparent)
            ->update(['tnstotal' => $total]);


        // Lanjutkan dengan mengurangi saldo dan memasukkan data
        foreach ($bahanData as $datas) {
            // Generate ID transaksi bahan
        $cekmaxTransaksiBahan = DB::table('transaksi_bahan')
            ->where('tnsbid', 'like', 'TNSB-'.$prefixfix.'-'.$tgl.'-%')
            ->max('tnsbid');
        if ($cekmaxTransaksiBahan) {
            $maxNumberTransaksiBahan = intval(substr($cekmaxTransaksiBahan, 18)) + 1;
        } else {
            $maxNumberTransaksiBahan = 1;
        }
        $formattedNumber = sprintf('%03d', $maxNumberTransaksiBahan);
        $generateIdTransaksiBahan= 'TNSB-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

            // Cek apakah ada varian yang sesuai untuk bahan ini
            $varian = collect($BahanVarian)->firstWhere('bvarbahan', $datas['bhnid']);
            $kuantiti = $varian ? $varian['bvarsaldo'] : $datas['bhankuantiti'];

            // Insert each data to the database
            $datasToInsert = [
                'tnsbid' => $generateIdTransaksiBahan,
                'tnsbparent' => $generateId,
                'tnsbsubparent' => '',
                'tnsbbahan' => $datas['bhnid'],
                'tnsbjumlah' => $kuantiti,
                'user_created' => $login,
                'created_at' => now(),
                'updated_at' => now(),
                'tnsbposisi' => $datas['bhnsaldo']
            ];

            DB::table('transaksi_bahan')->insert($datasToInsert);

            // Update the bahan table to reduce the bhnsaldo
            DB::table('bahan_bar')
                ->where('bhnid', $datas['bhnid'])
                ->decrement('bhnsaldo', $kuantiti);

            // DB::table('transaksi')
            //     ->where('tnsid', $request->tnsdparent)
            //     ->increment('tnstotal', $request->tnsdtotal);
        }



        DB::commit();
        return response()->json(['status' => 200, 'pesan' => 'Semua Data berhasil ditambah', 'bahan'=>$datasToInsert, 'bahanVarian'=>$BahanVarian]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}

     public function getBahanFromBahanOlah(Request $request){
        $bahanOlah = $request->bhoid;

        $jenisGudang = DB::table('gudang')
            ->where('gudangid', $request->gudang)
            ->value('gudangutama');


        if($jenisGudang == 2){

            $query_gudang2 = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo', 'b.bhnsaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan_bar as b', 'a.bhnid', '=', 'b.bhnid')
                ->where('a.bhanbarang', $bahanOlah);

            $query_gudang2_olah = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhomax', 'b.bhomin', 'b.bhosaldo', 'b.bhosaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan_olah as b', 'a.bhnid', '=', 'b.bhoid')
                ->where('a.bhanbarang', $bahanOlah);

            // Menyatukan hasil kedua query dengan UNION
            $data = $query_gudang2->union($query_gudang2_olah)->get();
            return response()->json(['bahan'=>$data]);


        } else if($jenisGudang == 3){
            $query_gudang3 = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo', 'b.bhnsaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan_kitchen as b', 'a.bhnid', '=', 'b.bhnid')
                ->where('a.bhanbarang', $bahanOlah);

            $query_gudang3_olah = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhomax', 'b.bhomin', 'b.bhosaldo', 'b.bhosaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan_olah as b', 'a.bhnid', '=', 'b.bhoid')
                ->where('a.bhanbarang', $bahanOlah);

            // Menyatukan hasil kedua query dengan UNION
            $data = $query_gudang3->union($query_gudang3_olah)->get();

        return response()->json(['bahan'=>$data]);
        } else {
            $query_gudang1 = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhnmax', 'b.bhnmin', 'b.bhnsaldo', 'b.bhnsaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan as b', 'a.bhnid', '=', 'b.bhnid')
                ->where('a.bhanbarang', $bahanOlah);

            $query_gudang1_olah = DB::table('barang_bahan as a')
                ->select('a.*', 'b.bhomax', 'b.bhomin', 'b.bhosaldo', 'b.bhosaldo as jumlahSekarang') // Perbaikan di sini
                ->join('bahan_olah as b', 'a.bhnid', '=', 'b.bhoid')
                ->where('a.bhanbarang', $bahanOlah);

            // Menyatukan hasil kedua query dengan UNION
            $data = $query_gudang1->union($query_gudang1_olah)->get();
        }
    }

    public function addBahanOlahUsedTrans(Request $request) {
        $login = Auth::user()->username;
        $cabang_login = $this->cabang;
        $prefix = DB::table('setting')->where('stgcabang', $cabang_login)->first();
        $prefixfix = $prefix->stgprefix;
        $tgl = Carbon::today()->format('Ymd');

        $jenisGudang = DB::table('gudang')
            ->where('gudangid', $request->bhogudang)
            ->value('gudangutama');

        // Validasi request TRANSAKSI DETAIL
        $validator = Validator::make($request->all(), [
            'bhoid' => 'required',
            'bhokuantiti' => 'required',
        ], [
            'bhoid.required' => 'wajib diisi',
            'bhokuantiti.required' => 'wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
        }

        // Decode JSON-encoded bahan data
        $bahanData = json_decode($request->input('bahan'), true);
        if (empty($bahanData)) {
            return response()->json(['status' => 400, 'pesan' => 'Data bahan tidak ditemukan']);
        }


        

    // Pengecekan saldo bahan
    foreach ($bahanData as $datas) {
        $awalan = substr($datas['bhnid'],0,3);
        $bahano = null;
        if($jenisGudang == 2){
            if($awalan == 'BHN'){
                $bahanz = DB::table('bahan_bar')->where('bhnid', $datas['bhnid'])->first();
            } else if($awalan == 'BHO'){
                $bahano = DB::table('bahan_olah')->where('bhoid', $datas['bhnid'])->first();
            }
        } else if ($jenisGudang == 3){
            if($awalan == 'BHN'){
                $bahanz = DB::table('bahan_kitchen')->where('bhnid', $datas['bhnid'])->first();
            } else if($awalan == 'BHO'){
                $bahano = DB::table('bahan_olah')->where('bhoid', $datas['bhnid'])->first();
            }
        } else{
            if($awalan == 'BHN'){
                $bahanz = DB::table('bahan')->where('bhnid', $datas['bhnid'])->first();
            } else if($awalan == 'BHO'){
                $bahano = DB::table('bahan_olah')->where('bhoid', $datas['bhnid'])->first();
            }
        }

        if ($bahano !== null && (!is_numeric($bahano->bhosaldo) || $bahano->bhosaldo < $datas['bhankuantiti'] || $bahano->bhosaldo === null)) {
        return response()->json(['status' => 400, 'pesan' => 'Saldo bahan OLAH tidak cukup: ' . $datas['bhannama']]);
        }

        if (!is_numeric($bahanz->bhnsaldo) || $bahanz->bhnsaldo < $datas['bhankuantiti'] || $bahanz->bhnsaldo == null) {
            return response()->json(['status' => 400, 'pesan' => 'Saldo bahan tidak valid atau tidak cukup untuk bahan ID: ' . $datas['bhannama']]);
        }
    }

    // Jika semua bahan cukup, lakukan transaksi
    DB::beginTransaction();
    try {
        foreach ($bahanData as $datas) {

            $awalan = substr($datas['bhnid'],0,3);
            

            // Generate ID Stock Barang untuk keluar
            $cekmaxStockKeluar = DB::table('stock_barang')
                ->where('sbid', 'like', "SB-$tgl-%")
                ->max('sbid');

            $maxNumberStockKeluar = $cekmaxStockKeluar ? intval(substr($cekmaxStockKeluar, 12)) + 1 : 1;
            $formattedNumberStockKeluar = sprintf('%03d', $maxNumberStockKeluar);
            $generateIdStockKeluar = "SB-$tgl-$formattedNumberStockKeluar";

            $kuantiti = $datas['bhankuantiti'];



            // Input ke stock barang dengan jenis keluar
            $dataInsertSB = [
                'sbid' => $generateIdStockKeluar,
                'sbparent' => $request->bhoid,
                'sbbahan' => $datas['bhnid'],
                'sbjenis' => 'keluar',
                'sbmasuk' => null,
                'sbkeluar' => $kuantiti,
                'sbadjust' => null,
                'sbgudang' => $request->bhogudang,
                'sbuser' => $login,
                'created_at' => now(),
                'updated_at' => null,
                'sbcabang' => $this->cabang,
            ];

            $SBJeniskeluar = DB::table('stock_barang')->insert($dataInsertSB);
            

        }

            
        $cekmax_pmo = DB::table('pembeliand')
            ->where('pmbdid', 'like', 'PMO-'.$prefixfix.'-'.$tgl.'-%')
            ->max('pmbdid');
        if ($cekmax_pmo) {
                $maxNumberPMO = intval(substr($cekmax_pmo, 18)) + 1;
            } else {
                $maxNumberPMO = 1;
            }
        $formattedNumberPMO = sprintf('%03d', $maxNumberPMO);
        $generateIdPMO = 'PMO-'.$prefixfix.'-'.$tgl.'-'.$formattedNumberPMO;


        // Generate ID Stock Barang untuk masuk
        $cekmaxStockMasuk = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        $maxNumberStockMasuk = $cekmaxStockMasuk ? intval(substr($cekmaxStockMasuk, 12)) + 1 : 1;
        $formattedNumberStockMasuk = sprintf('%03d', $maxNumberStockMasuk);
        $generateIdStockMasuk = "SB-$tgl-$formattedNumberStockMasuk";

         $row = DB::table('bahan_olah')
            ->where('bhoid', $request->bhoid)
            ->get();

            if ($row) {

            $insertPembeliand = [
                'pmbdid' => $generateIdPMO,
                'pmbdparent' => $generateIdPMO,
                'pmbdbrg' => $request->bhoid,
                'pmbdbrgn' => $request->bhonama,
                'pmbdjumlah' => $request->bhototal,
                'user_created' => $login,
                'created_at' => now(),
                'updated_at' => now(),
                'pmbdposisi' => $request->bhosaldo,
                'pmbddivisi' => 'bar',
                'cabang'=>$this->cabang,
            ];

            DB::table('pembeliand')->insert($insertPembeliand);


            ///setelah input ke stock barang dengan jenis keluar, lalu input
            // stok_barang dengan jenis masuk (barang olah yang jadi)
            if($SBJeniskeluar){
                $dataInsertSBin = [
                    'sbid' => $generateIdStockMasuk,
                    'sbparent' => $generateIdPMO,
                    'sbbahan' => $request->bhoid,
                    'sbjenis' => 'masuk',
                    'sbmasuk' => $request->bhototal,
                    'sbkeluar' => null,
                    'sbadjust' => null,
                    'sbgudang' => $request->bhogudang,
                    'sbuser' => $login,
                    'created_at' => now(),
                    'updated_at' => null,
                    'sbcabang' => $this->cabang,
                ];
                DB::table('stock_barang')->insert($dataInsertSBin);

            }
            } else {
                dd('Barang olah tidak ditemukan: ' . $request->bhoid);
            }
       
        DB::commit();
        return response()->json(['status' => 200, 'pesan' => 'Semua Data berhasil ditambah', 'bahan' => $dataInsertSB]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}


// START PERSEDIAAN - GUDANG
    public function gudang()
    {
        $satuan = DB::select('SELECT * FROM barang_satuan ORDER BY bsatnama');
        $jenis = DB::select('SELECT * FROM barang_jenis ORDER BY brjnama');
        return view('persediaan.v_gudang',['satuan' => $satuan,'jenis'=>$jenis]);
    }

    public function getGudangBesar(Request $request)
    {
        
         $data = DB::table('bahan')
        ->select('*')
        ->where('cabang',$this->cabang)
        ->orderBy('bhnnama')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Riwayat order" class="btn-riwayat" 
                    data-id='.$data->bhnid.'
                    data-bhnnama='.$data->bhnnama.'>
                    <i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function getRiwayatBahan(Request $request)
    {
        $bahan = $request->bhnid;
        $gudang = DB::table("gudang")
                ->where("gudangutama",2)
                ->where("cabang",$this->cabang)
                ->value("gudangid");

        $data = DB::table("stock_barang as a")
                ->select("a.*","b.bhnnama")
                ->join("bahan_bar as b","b.bhnid","=","a.sbbahan")
                ->where("sbcabang",$this->cabang)
                ->where("sbgudang",$gudang)
                ->where("sbbahan",$bahan)
                ->orderBy("a.created_at","desc")
                ->get();
    

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->sbjenis == 'masuk'){
                        $txt = '<span class="badge bg-success">Masuk</span>';
                    } else if($data->sbjenis == 'keluar'){
                        $txt = '<span class="badge bg-danger">Keluar</span>';
                    } else {
                        $txt = '<span class="badge bg-secondary">Adjust</span>';
                    }
                    return $txt;
                })
                ->rawColumns(['jenis'])
                ->make(true);       
        }
    }


    public function getRiwayatBahanGudangBesar(Request $request)
    {
        $bahan = $request->bhnid;
        $gudang = DB::table("gudang")
                ->where("gudangutama",1)
                ->where("cabang",$this->cabang)
                ->value("gudangid");

        $data = DB::table("stock_barang as a")
                ->select("a.*","b.bhnnama")
                ->join("bahan_bar as b","b.bhnid","=","a.sbbahan")
                ->where("sbcabang",$this->cabang)
                ->where("sbgudang",$gudang)
                ->where("sbbahan",$bahan)
                ->orderBy("a.created_at","DESC")
                ->get();
    

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->sbjenis == 'masuk'){
                        $txt = '<span class="badge bg-success">Masuk</span>';
                    } else if($data->sbjenis == 'keluar'){
                        $txt = '<span class="badge bg-danger">Keluar</span>';
                    } else {
                        $txt = '<span class="badge bg-secondary">Adjust</span>';
                    }
                    return $txt;
                })
                ->rawColumns(['jenis'])
                ->make(true);       
        }


    }

    public function getGudangBar(Request $request)
    {
        
         $data = DB::table('bahan_bar')
        ->select('*')
        ->where('cabang',$this->cabang)
        ->orderBy('bhnnama')
        ->whereNotNull('bhnsaldo')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Riwayat order" class="btn-riwayat" 
                    data-id='.$data->bhnid.'
                    data-bhnnama='.$data->bhnnama.'>
                    <i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }
    
     public function getGudangKitchen(Request $request)
    {
        
         $data = DB::table('bahan_kitchen')
        ->select('*')
        ->where('cabang',$this->cabang)
        ->orderBy('bhnnama')
        ->whereNotNull('bhnsaldo')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Riwayat order" class="btn-riwayat" 
                    data-id='.$data->bhnid.'
                    data-bhnnama='.$data->bhnnama.'>
                    <i class="bi bi-clock-history"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function getMacamGudang(Request $request)
    {
        
        
         $data = DB::table('gudang')
                ->select('*')
                ->where('cabang',$this->cabang)
                ->where('gudangutama',$request->gudangutama)
                ->get();

        return response()->json(['data'=>$data],200);

    }


    public function getRiwayatBahan_bar_persediaan(Request $request)
    {
        $bahan = $request->bhnid;
        $gudang = DB::table("gudang")
                ->where("gudangutama",2)
                ->where("cabang",$this->cabang)
                ->value("gudangid");

        $data = DB::table("stock_barang as a")
                ->select("a.*","b.bhnnama")
                ->join("bahan_bar as b","b.bhnid","=","a.sbbahan")
                ->where("sbcabang",$this->cabang)
                ->where("sbgudang",$gudang)
                ->where("sbbahan",$bahan)
                ->orderBy("a.created_at","DESC")
                ->get();
    

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->sbjenis == 'masuk'){
                        $txt = '<span class="badge bg-success">Masuk</span>';
                    } else if($data->sbjenis == 'keluar'){
                        $txt = '<span class="badge bg-danger">Keluar</span>';
                    } else {
                        $txt = '<span class="badge bg-secondary">Adjust</span>';
                    }
                    return $txt;
                })
                ->rawColumns(['jenis'])
                ->make(true);       
        }
    }

    public function getRiwayatBahan_kitchen_persediaan(Request $request)
    {
        $bahan = $request->bhnid;
        $gudang = DB::table("gudang")
                ->where("gudangutama",3)
                ->where("cabang",$this->cabang)
                ->value("gudangid");

        $data = DB::table("stock_barang as a")
                ->select("a.*","b.bhnnama")
                ->join("bahan_bar as b","b.bhnid","=","a.sbbahan")
                ->where("sbcabang",$this->cabang)
                ->where("sbgudang",$gudang)
                ->where("sbbahan",$bahan)
                ->orderBy("a.created_at","DESC")
                ->get();
    

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->sbjenis == 'masuk'){
                        $txt = '<span class="badge bg-success">Masuk</span>';
                    } else if($data->sbjenis == 'keluar'){
                        $txt = '<span class="badge bg-danger">Keluar</span>';
                    } else {
                        $txt = '<span class="badge bg-secondary">Adjust</span>';
                    }
                    return $txt;
                })
                ->rawColumns(['jenis'])
                ->make(true);       
        }
    }


    


}


