<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Mutasi;
use App\Models\Mutasid;
use App\Models\BahanBar;
use App\Models\Pembelian;
use App\Models\Pembeliand;
use App\Models\StockBarang;
use App\Models\BahanKitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class PurchasingController extends Controller
{

        protected $cabang; // Properti untuk menyimpan nilai cabang

        public function __construct(Request $request)
        {
            // Ambil nilai cabang dari request dan set ke properti $cabang
            $this->cabang = $request->cabang;
        }



    ///TAMPILAN DAN CRUD PEMBELIAN
    public function pembelian(){
            $gudang = DB::select("SELECT * FROM gudang  where cabang = ? ORDER BY gudangid",[$this->cabang]);
            $supplier = DB::select("SELECT * FROM supplier ORDER BY suppnama");
            $login = Auth::user()->divisi;
            
            return view('purchasing.v_pembelian',['data'=>$gudang,'supp'=>$supplier,'divisi'=>$login]);
    }

    public function getPembelian(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;
        

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('pembelian as a')
        ->select('*')
        ->leftJoin('gudang as b','b.gudangid','=','a.pmbgudang')
        ->where('a.pmbtgl', '>=', $tgldari . ' 00:00:00')        
        ->where('a.pmbtgl', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00')  
        // ->where('tnsnama', 'like', '%' . $nama . '%')
        ->where('pmbdivisi', '=', 'umum')
        ->where('a.cabang',$this->cabang)
        ->orderBy('a.pmbtgl')
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
                ->addColumn('action', function ($data) {
                    $act = '<a id="btn-delete-pembelian" data-id='.$data->pmbid.' title="Hapus"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->addColumn('total', function ($data) {
    		    $act = "Rp " . number_format($data->pmbtotal, 0, ',', '.');
		    return $act;
		})
	        ->rawColumns(['id','action','tgl','total'])
                ->make(true);       
        }

    }

    public function getPembelianDetail(Request $request)
    {
        $transaksi = $request->transaksi;

         $data = DB::table('pembeliand as a')
            ->select('a.*')
            ->leftJoin('pembelian as b','a.pmbdparent','=','b.pmbid')
            ->where('a.pmbdparent', '=', $transaksi )      
            ->where('a.pmbd_delete', '=', 0 )
            ->where('a.cabang',$this->cabang)
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
                    data-bayar="'.$data->pmbdbayar.'"

                    
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
                ->addColumn('total', function ($data) {
    		    $act = "Rp " . number_format($data->pmbdbayar, 0, ',', '.');
		    return $act;
		})
		->addColumn('tgl', function ($data) {
		    $tanggal = tanggal_indonesia($data->created_at, false); // Format tanggal
		    $jam = \Carbon\Carbon::parse($data->created_at)->format('H:i'); // Format jam
    		    return '<span style="font-size: 12px;">' . $tanggal . ' ' . $jam . '</span>';
		})

                ->rawColumns(['id','action','total','tgl'])
                ->make(true);       
        }
    }
    public function addPembelian(Request $request){

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

        $validator = Validator::make($request->all(),[
            'pmbket'     => 'required',
            'pmbjenis'     => 'required',
            'pmbtgl'     => 'required',

        ],[
            'pmbket.required' => 'Keterangan barang wajib diisi',
            'pmbjenis.required' => 'Jenis wajib diisi',
            'pmbtgl.required' => 'Tanggal wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = Pembelian::create([
                'pmbid' => $generateId,
                'pmbket' => $request->pmbket,
                'pmbjenis' => $request->pmbjenis,
                'pmbsupp' => $request->pmbsupp,
                'pmbtgl' => $request->pmbtgl,
                'user_created' => $login,
                'pmbdivisi' => 'umum',
                'pmbgudang' => $request->pmbgudang,
                'cabang'=>$this->cabang,

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
    }

    public function addPembeliand(Request $request)
    {
        $login = Auth::user()->username;
        $cabang_login = $this->cabang;

        // Ambil prefix dan tanggal
        $prefix = DB::table('setting')->where('stgcabang', $cabang_login)->first()->stgprefix;
        $tgl = Carbon::today()->format('Ymd');

        // Generate ID Pembelian
        $cekmax = DB::table('pembeliand')
            ->where('pmbdid', 'like', "PMBD-$prefix-$tgl-%")
            ->max('pmbdid');
        
        $maxNumber = $cekmax ? intval(substr($cekmax, 19)) + 1 : 1;
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = "PMBD-$prefix-$tgl-$formattedNumber";

        // Generate ID Stock Barang
        $cekmaxStock = DB::table('stock_barang')
            ->where('sbid', 'like', "SB-$tgl-%")
            ->max('sbid');
        
        $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
        $formattedNumberStock = sprintf('%03d', $maxNumberStock);
        $generateIdStock = "SB-$tgl-$formattedNumberStock";

        // Validasi input
        $validator = Validator::make($request->all(), [
            'pmbdbrg' => 'required',
        ], [
            'pmbdbrg.required' => 'Barang wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
        }

        // Ambil data gudang dan bahan
        
        $gudang = DB::table('gudang')->where('gudangid', $request->pmbdgudang)->first();
        $posisiBahan = DB::table('bahan')->where('bhnid', $request->pmbdbrg)->value('bhnsaldo');

        

        // Logika untuk gudang
        if ($gudang->gudangutama == 2) {
            // Gudang utama level 2
            // $cekBahanGudangBar = DB::table('bahan_bar')->where('bhnid', $request->pmbdbrg)->first();

            // dd($cekBahanGudangBar);

            // if ($cekBahanGudangBar === null) {
            //     BahanBar::create([
            //         'bhnid'       => $request->pmbdbrg,
            //         'bhnnama'     => $request->pmbdbrgn,
            //         'bhnsatuan'   => $request->pmbdsatuan,
            //         'bhnuser'     => $login,
            //         'bhnsaldo'    => $request->pmbdjumlah,
            //         'created_at'  => now(),
            //         'updated_at'  => now(),
            //         'bhnmin'      => 10,
            //         'bhnmax'      => 300,
            //         'bhnsupp'     => $request->pmbdsupp,
            //         'cabang'      => $this->cabang,
            //     ]);
            // } 
            // else {
            //     // DB::table('bahan_bar')->where('bhnid', $request->pmbdbrg)->increment('bhnsaldo', $request->pmbdjumlah);
            // }

        $posisiBahanBar = DB::table('bahan_bar')
                    ->where('bhnid', $request->pmbdbrg)
                    ->value('bhnsaldo');


        // Transaksi Stock Barang
        StockBarang::create([
            'sbid'      => $generateIdStock,
            'sbparent'  => $generateId,
            'sbbahan'   => $request->pmbdbrg,
            'sbjenis'   => 'masuk',
            'sbmasuk'   => $request->pmbdjumlah,
            'sbkeluar'  => null,
            'sbgudang'  => $gudang->gudangid, // Menggunakan ID Gudang
            'sbuser'    => $login,
            'created_at'=> now(),
            'updated_at'=> null,
            'sbcabang'  => $this->cabang,
        ]);

        // Simpan data Pembeliand
        $data = Pembeliand::create([
            'pmbdid'      => $generateId,
            'pmbdparent'  => $request->pmbdparent,
            'pmbdket'     => $request->pmbdket,
            'pmbdbrg'     => $request->pmbdbrg,
            'pmbdbrgn'    => $request->pmbdbrgn,
            'pmbdsupp'    => $request->pmbdsupp,
            'pmbdjumlah'  => $request->pmbdjumlah,
            'pmbddivisi' => 'bar',
            'user_created'=> $login,
            'pmbdposisi'  => $posisiBahanBar,
            'pmbd_delete' => 0,
            'pmbdtotal'   =>$posisiBahanBar + $request->pmbdjumlah,
            'cabang'      => $this->cabang,
            'pmbdbayar'   => (int) str_replace('.', '', $request->pmbdbayar),

        ]);

        $updsaldo =  DB::table('bahan_bar')
                ->where('bhnid', $request->pmbdbrg)
                ->update(['bhnsaldo' => $posisiBahanBar + $request->pmbdjumlah]);
                
                   
        } else if ($gudang->gudangutama == 3) {
            // Gudang utama level 3
            $cekBahanGudangKitchen = DB::table('bahan_kitchen')->where('bhnid', $request->pmbdbrg)->first();

            // if ($cekBahanGudangKitchen === null) {
            //    BahanKitchen::create([
            //       'bhnid'       => $request->pmbdbrg,
            //        'bhnnama'     => $request->pmbdbrgn,
            //        'bhnsatuan'   => $request->pmbdsatuan,
            //        'bhnuser'     => $login,
            //        'bhnsaldo'    => $request->pmbdjumlah,
            //        'created_at'  => now(),
            //       'updated_at'  => now(),
            //        'bhnmin'      => 10,
            //        'bhnmax'      => 300,
            //        'bhnsupp'     => $request->pmbdsupp,
             //       'cabang'      => $this->cabang,
            //    ]);
            //} 
            // else {
            //     // DB::table('bahan_kitchen')->where('bhnid', $request->pmbdbrg)->increment('bhnsaldo', $request->pmbdjumlah);
            // }

        $posisiBahanKitchen = DB::table('bahan_kitchen')
                ->where('bhnid', $request->pmbdbrg)
                ->value('bhnsaldo');
            // Transaksi Stock Barang
        StockBarang::create([
            'sbid'      => $generateIdStock,
            'sbparent'  => $generateId,
            'sbbahan'   => $request->pmbdbrg,
            'sbjenis'   => 'masuk',
            'sbmasuk'   => $request->pmbdjumlah,
            'sbkeluar'  => null,
            'sbgudang'  => $gudang->gudangid, // Menggunakan ID Gudang
            'sbuser'    => $login,
            'created_at'=> now(),
            'updated_at'=> null,
            'sbcabang'  => $this->cabang,
        ]);

        // Simpan data Pembeliand
        $data = Pembeliand::create([
            'pmbdid'      => $generateId,
            'pmbdparent'  => $request->pmbdparent,
            'pmbdket'     => $request->pmbdket,
            'pmbdbrg'     => $request->pmbdbrg,
            'pmbdbrgn'    => $request->pmbdbrgn,
            'pmbdsupp'    => $request->pmbdsupp,
            'pmbdjumlah'  => $request->pmbdjumlah,
            'pmbddivisi' => 'kitchen',
            'user_created'=> $login,
            'pmbdposisi'  => $posisiBahanKitchen,
            'pmbd_delete' => 0,
            'pmbdtotal' =>$posisiBahanKitchen + $request->pmbdjumlah,
            'cabang'      => $this->cabang,
            'pmbdbayar'   => (int) str_replace('.', '', $request->pmbdbayar),
        ]);

        $updsaldo =  DB::table('bahan_kitchen')
                ->where('bhnid', $request->pmbdbrg)
                ->update(['bhnsaldo' => $posisiBahanKitchen + $request->pmbdjumlah]);

        } else {
            // Gudang besar

        $posisiBahan = DB::table('bahan')
            ->where('bhnid', $request->pmbdbrg)
            ->value('bhnsaldo');
        // Transaksi Stock Barang
        StockBarang::create([
            'sbid'      => $generateIdStock,
            'sbparent'  => $generateId,
            'sbbahan'   => $request->pmbdbrg,
            'sbjenis'   => 'masuk',
            'sbmasuk'   => $request->pmbdjumlah,
            'sbkeluar'  => null,
            'sbgudang'  => $gudang->gudangid, // Menggunakan ID Gudang
            'sbuser'    => $login,
            'created_at'=> now(),
            'updated_at'=> null,
            'sbcabang'  => $this->cabang,
        ]);

        // Simpan data Pembeliand
        $data = Pembeliand::create([
            'pmbdid'      => $generateId,
            'pmbdparent'  => $request->pmbdparent,
            'pmbdket'     => $request->pmbdket,
            'pmbdbrg'     => $request->pmbdbrg,
            'pmbdbrgn'    => $request->pmbdbrgn,
            'pmbdsupp'    => $request->pmbdsupp,
            'pmbdjumlah'  => $request->pmbdjumlah,
            'user_created'=> $login,
            'pmbdposisi'  => $posisiBahan,
            'pmbd_delete' => 0,
            'pmbdtotal' =>$posisiBahan + $request->pmbdjumlah,
            'cabang'      => $this->cabang,
    	    'pmbdbayar'   => (int) str_replace('.', '', $request->pmbdbayar),
        ]);
        }
        
        $totalPembeliand = DB::table('pembeliand')
		    ->where('pmbdparent', $request->pmbdparent)
		    ->sum('pmbdbayar');
        		
       $updTotal = DB::table('pembelian')
                ->where('pmbid', $request->pmbdparent)
                ->update(['pmbtotal' => $totalPembeliand]);

        if ($data) {
            return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
        }
    }
    
    public function updatePembelian(Request $request)
{
    $login = Auth::user()->username;
    $cabang_login = $this->cabang;

    // Mulai transaksi
    DB::beginTransaction();

    try {
        $getGudangNow = DB::table('pembelian')
            ->where('pmbid', $request->pmbid)
            ->value('pmbgudang');

        $sb = true;


        if ($getGudangNow != $request->pmbgudang) {
            $pmbdparent = DB::table('stock_barang as sb')
                ->join('pembeliand as pmbd', 'pmbd.pmbdid', '=', 'sb.sbparent')
                ->join('pembelian as pmb', 'pmb.pmbid', '=', 'pmbd.pmbdparent')
                ->where('pmb.pmbid', $request->pmbid)
                ->value('pmbd.pmbdparent');

            // dd($pmbdparent);

            $sb = DB::table('stock_barang as sb')
                ->join('pembeliand as pmbd', 'pmbd.pmbdid', '=', 'sb.sbparent')
                ->join('pembelian as pmb', 'pmb.pmbid', '=', 'pmbd.pmbdparent')
                ->where('pmb.pmbid', $request->pmbid)
                ->update(
                    ['sb.sbgudang' => $request->pmbgudang]
                );
        }

        $data = Pembelian::where('pmbid', $request->pmbid)
            ->update([
                'pmbket' => $request->pmbket,
                'pmbtgl' => $request->pmbtgl,
                'pmbjenis' => $request->pmbjenis,
                'pmbgudang' => $request->pmbgudang,
                'pmbsupp' => $request->pmbsupp,
                'updated_at' => now(),
            ]);

        if ($data && $sb) {
            // Commit transaksi jika semua berhasil
            DB::commit();
            return response()->json(['pesan' => 'Data berhasil diupdate'], 200);
        }

        // Rollback jika salah satu operasi gagal
        DB::rollBack();
        return response()->json(['pesan' => 'Gagal melakukan update'], 500);
    } catch (\Exception $e) {
        // Rollback jika terjadi error
        DB::rollBack();
        return response()->json(['pesan' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
}


    public function updatePembeliand(Request $request)
    {
        $login = Auth::user()->username;
        $cabang_login = $this->cabang;

         $data = Pembeliand::where('pmbdid', $request->pmbdid_edit)
                ->update([
                    'pmbdjumlah' => $request->pmbdjumlah_edit,
                    'pmbdbayar' => $request->pmbdbayar_edit,

                    'updated_at' => now(),
                ]);

        $sb = StockBarang::where('sbparent', $request->pmbdid_edit)
                ->update([
                    'sbmasuk' => $request->pmbdjumlah_edit,
                    'updated_at' => now(),
                ]);
                
          $totalPembeliand = DB::table('pembeliand')
		    ->where('pmbdparent', $request->pmbdparent)
		    ->sum('pmbdbayar');
        		
       $updTotal = DB::table('pembelian')
                ->where('pmbid', $request->pmbdparent)
                ->update(['pmbtotal' => $totalPembeliand]);


            if ($data && $sb) {
                return response()->json(['pesan'=>'jumlah berhasil update'],200);
            }
    }

    public function deletePembeliand(Request $request) 
    {

         

        DB::beginTransaction();
        try {
            $login = Auth::user()->username;

             // Hapus data dari tabel stock_barang terkait
            $stock = DB::table('stock_barang')
                ->where('sbparent', $request->pmbdid)
                ->delete();

            $pmbd = DB::table('pembeliand')
                ->where('pmbdid', $request->pmbdid)
                ->delete();


            // // Dapatkan jenis gudang
            // $jenisGudang = DB::table('gudang')
            //     ->where('gudangid', $request->pmbdgudang)
            //     ->value('gudangutama');

            // // Tentukan tabel yang digunakan berdasarkan jenis gudang
            // $table = null;
            // if ($jenisGudang == 2) {
            //     $table = 'bahan_bar';
            // } else if ($jenisGudang == 3) {
            //     $table = 'bahan_kitchen';
            // } else {
            //     $table = 'bahan';
            // }

            // // Jika tabel ditemukan, kurangi saldo
            // if ($table) {
            //     DB::table($table)
            //         ->where('bhnid', $request->pmbdbrg)
            //         ->decrement('bhnsaldo', $request->pmbdjumlah);
            // }

            // Tandai data pembeliand sebagai dihapus (soft delete)
            DB::table('pembeliand')
                ->where('pmbdid', $request->pmbdid)
                ->update([
                    'pmbd_delete' => 1,
                    'pmbd_delete_user' => $login,
                ]);

           

            // Commit transaksi jika tidak ada masalah
            DB::commit();
            return response()->json(['pesan' => 'Berhasil hapus data'], 200);

        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();
            return response()->json(['pesan' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }


    public function deletePembelian(Request $request) 
    {

        DB::beginTransaction();
        try {
            $login = Auth::user()->username;

             // Hapus data dari tabel stock_barang terkait

             $pmbdid = DB::table('pembeliand')
                ->where('pmbdparent', $request->pmbid)
                ->pluck('pmbdid');


            $stock = DB::table('stock_barang')
                ->whereIn('sbparent', $pmbdid)
                ->delete();

            $pembeliand = DB::table('pembelian')
                ->where('pmbid', $request->pmbid)
                ->delete();

            $pembeliand = DB::table('pembeliand')
                ->where('pmbdparent', $request->pmbid)
                ->delete();

           
            // Commit transaksi jika tidak ada masalah
            DB::commit();
            return response()->json(['status'=>200,'pesan' => 'Berhasil hapus data'], 200);

        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();
            return response()->json(['status'=>500,'pesan' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }




    public function pengeluaran(){
        $gudang = DB::table('gudang')
                ->where('cabang',$this->cabang)
                ->get();
        $cabang = DB::table('setting')
                ->whereNot('stgcabang',$this->cabang)
                ->get();
        // $gudang = DB::select("SELECT * FROM GUDANG ORDER BY GUDANGID");
        $supplier = DB::select("SELECT * FROM supplier ORDER BY suppnama");
        $jenisMutasi = DB::select("SELECT *  FROM jenis_mutasi  ORDER BY jmuid");

        $login = Auth::user()->divisi;
        return view('purchasing.v_pengeluaran',['data'=>$gudang,'supp'=>$supplier,'divisi'=>$login,'mutasi'=>$jenisMutasi,'cabang'=>$cabang]);
    }


    public function getMutasi(Request $request)
    {
        $tgldari = $request->ipt_tgldari;
        $tglsampai = $request->ipt_tglsampai;
        $nama = $request->ipt_nama;
        

        // $data = DB::select("SELECT * FROM TRANSAKSI WHERE CREATED_AT BETWEEN '$tgldari' AND '$tglsampai' AND TNSNAMA LIKE %'$nama'%");
         $data = DB::table('mutasi as a')
        ->select('a.*','b.jmunama','c.gudangn as asalGudang')
        ->leftJoin('jenis_mutasi as b','b.jmuid','=','a.mutajenis')
        ->join('gudang as c','gudangid','=','a.mutagudang')
        ->where('a.mutatgl', '>=', $tgldari . ' 00:00:00')        
        ->where('a.mutatgl', '<', date('Y-m-d', strtotime($tglsampai . ' +1 day')) . ' 00:00:00')  
        ->where('a.cabang',$this->cabang)
        ->orderBy('a.mutatgl')
        ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->mutaid.'</span>';
                    return $id;
                })
                
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus" data-id='.$data->mutaid.'><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->addColumn('tgl', function ($data) {
                    $act = tanggal_indonesia($data->mutatgl);
                    return $act;
                })
                ->addColumn('jenisz', function ($data) {
                    $act = '<span class="badge rounded-pill bg-warning text-dark">'.$data->jmunama.'</span>';
                    return $act;
                })
                ->rawColumns(['id','action','tgl','jenisz'])
                ->make(true);       
        }

    }


    public function getMutasiRiwayat(Request $request)
    {
        $bahan = $request->bahan;

        $data = DB::table('mutasi as a')
            ->select('d.bhnnama','c.jmuid','b.mutdjumlah','a.mutatgl','e.gudangn','c.jmunama')
            ->join('mutasid as b','b.mutdparent','=','a.mutaid')
            ->join('jenis_mutasi as c','c.jmuid','=','a.mutajenis')
            ->join('bahan as d','d.bhnid','=','b.mutdbahan')
            ->join('gudang as e','e.gudangid','=','a.mutagudang')
            ->where('b.mutdbahan',$bahan)
            ->where('a.cabang',$this->cabang)
            ->orderBy('a.mutatgl','desc')
            ->get();

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($data) {
                    $act = tanggal_indonesia($data->mutatgl);
                    return $act;
                })
                ->addColumn('jenisz', function ($data) {
                    $act = '<span class="badge rounded-pill bg-warning text-dark">'.$data->jmunama.'</span>';
                    return $act;
                })
                ->rawColumns(['tgl','jenisz'])
                ->make(true);       
        }

    }

    public function getMutasiDetail(Request $request)
    {
        $transaksi = $request->transaksi;

         $data = DB::table('mutasid as a')
            ->select('a.*','c.bhnnama')
            ->leftJoin('mutasi as b','a.mutdparent','=','b.mutaid')
            ->leftJoin('bahan as c','c.bhnid','=','a.mutdbahan')
            ->where('a.mutdparent', '=', $transaksi )    
            ->where('a.cabang',$this->cabang)  
            ->orderBy('a.created_at')
            ->get();

        $cabangTujuan = DB::table('bahan')
            ->where('cabang',$request->cabangTujuan)
            ->get();
        

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->mutdid.'</span>';
                    return $id;
                })
                ->addColumn('jumlah', function ($data) {
                    $id = '<span class="badge bg-danger">'.$data->mutdjumlah.'</span>';
                    return $id;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Edit" id="edit-btn-pmbd"><i class="bi-pencil-square mr-5"></i></a>
                    <a href="#" title="Hapus"  id="delete-btn-pmbd"
                    data-id="'.$data->mutdid.'"
                    data-bahan="'.$data->mutdbahan.'"
                    data-jumlah="'.$data->mutdjumlah.'"
                    ><i class="bi bi-trash"></i></a>
                    ';
                    return $act;
                })
                ->rawColumns(['id','action','jumlah'])
                ->make(true);       
        }
    }


    public function addMutasi(Request $request){

        $login = Auth::user()->username;
        $cabang_login = $this->cabang;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');
        $cekmax = DB::table('mutasi')
            ->where('mutaid', 'like', 'MUT-'.$prefixfix.'-'.$tgl.'-%')
            ->max('mutaid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 18)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'MUT-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

        $validator = Validator::make($request->all(),[
            'mutaket'     => 'required',
            'mutajenis'     => 'required',
            'mutatgl'     => 'required',

        ],[
            'mutaket.required' => 'Keterangan barang wajib diisi',
            'mutajenis.required' => 'Jenis wajib diisi',
            'mutatgl.required' => 'Tanggal wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            
            if($request->mutajenis == 'JMU001'){
                $data = Mutasi::create([
                    'mutaid' => $generateId,
                    'mutaket' => $request->mutaket,
                    'mutajenis' => $request->mutajenis,
                    'mutatgl' => $request->mutatgl,
                    'user_created' => $login,
                    'mutastatus' => 0,
                    'mutastore'=>$request->mutastore,
                    'mutagudang'=>$request->mutagudang,
                    'cabang'=>$this->cabang

                ]);
            } else {
                $data = Mutasi::create([
                    'mutaid' => $generateId,
                    'mutaket' => $request->mutaket,
                    'mutajenis' => $request->mutajenis,
                    'mutatgl' => $request->mutatgl,
                    'user_created' => $login,
                    'mutstatus' => 0,
                    'mutagudang'=>$request->mutagudang,
                    'cabang'=>$this->cabang
                ]);
            }
            
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
            }
        }
    }


    public function getBahanFromGudang(Request $request)
    {
        $jenisGudang = DB::table('gudang')
                ->where('gudangid',$request->gudang)
                ->value('gudangutama');
        
        if($jenisGudang == 2){

             $data = DB::table('bahan_bar as a')
                ->select('*')
                ->leftJoin('satuan as b','b.satid','=','a.bhnsatuan')
                ->where('cabang',$this->cabang)
                ->orderBy('a.bhnnama')
                ->get();
        } else if ($jenisGudang == 3){

            $data = DB::table('bahan_kitchen AS a')
        ->select(
        '*',
        DB::raw("
        CAST(
            COALESCE(
                (SELECT sbadjust
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'adjust' 
                   AND a2.sbcabang = $this->cabang
                 ORDER BY a2.created_at DESC
                 LIMIT 1), 0
            ) +
            COALESCE(
                (SELECT SUM(a2.sbmasuk)
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'masuk'  
                   AND a2.sbcabang = '2'
                   AND a2.created_at > (
                       SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                       FROM stock_barang a3
                       WHERE a3.sbbahan = a.bhnid 
                         AND a3.sbjenis = 'adjust' 
                         AND a3.sbgudang = '$request->gudang' 
                         AND a3.sbcabang = $this->cabang
                   )), 0
            ) -
            COALESCE(
                (SELECT SUM(a2.sbkeluar)
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'keluar' 
                   AND a2.sbcabang = '2'
                   AND a2.created_at > (
                       SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                       FROM stock_barang a3
                       WHERE a3.sbbahan = a.bhnid 
                         AND a3.sbjenis = 'adjust' 
                         AND a3.sbgudang = '$request->gudang' 
                         AND a3.sbcabang = $this->cabang
                   )), 0
                   ) AS UNSIGNED
            ) AS jumlahSekarang
        ")
    )
    ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhnsatuan')
    ->where('a.cabang',$this->cabang)
    ->orderBy('a.bhnnama')
    ->get();
            
            //  $data_old = DB::table('bahan_kitchen as a')
            // ->select('*')
            // ->leftJoin('satuan as b','b.satid','=','a.bhnsatuan')
            // ->where('cabang',$this->cabang)
            // ->orderBy('a.bhnnama')
            // ->get();
        } else {
        $data = DB::table('bahan AS a')
        ->select(
        '*',
        DB::raw("
        CAST(
            COALESCE(
                (SELECT sbadjust
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'adjust' 
                   AND a2.sbcabang = $this->cabang
                 ORDER BY a2.created_at DESC
                 LIMIT 1), 0
            ) +
            COALESCE(
                (SELECT SUM(a2.sbmasuk)
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'masuk'  
                   AND a2.sbcabang = '2'
                   AND a2.created_at > (
                       SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                       FROM stock_barang a3
                       WHERE a3.sbbahan = a.bhnid 
                         AND a3.sbjenis = 'adjust' 
                         AND a3.sbgudang = '$request->gudang' 
                         AND a3.sbcabang = $this->cabang
                   )), 0
            ) -
            COALESCE(
                (SELECT SUM(a2.sbkeluar)
                 FROM stock_barang a2
                 WHERE a2.sbbahan = a.bhnid 
                   AND a2.sbjenis = 'keluar' 
                   AND a2.sbcabang = '2'
                   AND a2.created_at > (
                       SELECT COALESCE(MAX(a3.created_at), '1900-01-01')
                       FROM stock_barang a3
                       WHERE a3.sbbahan = a.bhnid 
                         AND a3.sbjenis = 'adjust' 
                         AND a3.sbgudang = '$request->gudang' 
                         AND a3.sbcabang = $this->cabang
                   )), 0
                ) AS UNSIGNED
            ) AS jumlahSekarang
        ")
    )
    ->leftJoin('satuan AS b', 'b.satid', '=', 'a.bhnsatuan')
    ->where('a.cabang',$this->cabang)
    ->orderBy('a.bhnnama')
    ->get();


            // $data_old = DB::table('bahan as a')
            //     ->select('*')
            //     ->leftJoin('satuan as b','b.satid','=','a.bhnsatuan')
            //     ->where('cabang',$this->cabang)
            //     ->orderBy('a.bhnnama')
            //     ->get();
        }

         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus" data-id="'.$data->bhnid.'"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function getBahanFromGudangTujuan(Request $request)
    {
        $data = DB::table('bahan as a')
            ->select('a.*','b.satnama')
            ->join('satuan as b','a.bhnsatuan','=','b.satid')
            ->where('a.cabang',$request->gudang)
            ->get();
         if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('id', function ($data) {
                    $id = '<span class="badge bg-light-secondary text-black">'.$data->bhnid.'</span>';
                    return $id;
                })
                
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus" data-id="'.$data->bhnid.'"><i class="bi bi-trash"></i></a>';
                    return $act;
                })
                ->rawColumns(['id','action'])
                ->make(true);       
        }

    }

    public function addMutasiDetail(Request $request) {
        $login = Auth::user()->username;
        $gudang = $request->mutdgudang;
        $asalgudang = $request->mutdgudang;
        $jenismutasi = $request->mutajenis_temp;

        // Mulai transaksi
        DB::beginTransaction();

        try {
            $jenisGudang = DB::table('gudang')
                ->where('gudangid', $asalgudang)
                ->value('gudangutama');

            
            $cabang_login = $this->cabang;
            $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = '$cabang_login'");
            $prefixfix = $prefix[0]->stgprefix;
            $tgl = Carbon::today()->format('Ymd');

            $cekmax = DB::table('mutasid')
                ->where('mutdid', 'like', 'MUTD-'.$prefixfix.'-'.$tgl.'-%')
                ->max('mutdid');
            
            if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 19)) + 1;
            } else {
                $maxNumber = 1;
            }
            
            $formattedNumber = sprintf('%03d', $maxNumber);
            $generateId = 'MUTD-'.$prefixfix.'-'.$tgl.'-'.$formattedNumber;

            // Generate ID Stock Barang
            $cekmaxStock = DB::table('stock_barang')
                ->where('sbid', 'like', "SB-$tgl-%")
                ->max('sbid');
            
            $maxNumberStock = $cekmaxStock ? intval(substr($cekmaxStock, 12)) + 1 : 1;
            $formattedNumberStock = sprintf('%03d', $maxNumberStock);
            $generateIdStock = "SB-$tgl-$formattedNumberStock";
            // End Generate ID Stock Barang


            // Validasi input
            $validator = Validator::make($request->all(), [
                'mutdbahan' => 'required',
            ], [
                'mutdbahan.required' => 'Bahan wajib diisi',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
            }

            // Ambil posisi bahan berdasarkan jenis gudang
            $posisiBahan = null;
            if ($jenisGudang == 2) {
                $posisiBahan = DB::table('bahan_bar')
                    ->where('bhnid', $request->mutdbahan)
                    ->value('bhnsaldo');
            } else if ($jenisGudang == 3) {
                $posisiBahan = DB::table('bahan_kitchen')
                    ->where('bhnid', $request->mutdbahan)
                    ->value('bhnsaldo');
            } else {
                $posisiBahan = DB::table('bahan')
                    ->where('bhnid', $request->mutdbahan)
                    ->value('bhnsaldo');
            }

            

            // Buat entri mutasi
            $data = Mutasid::create([
                'mutdid' => $generateId,
                'mutdparent' => $request->mutdparent,
                'mutdket' => $request->pmbdket,
                'mutdbahan' => $request->mutdbahan,
                'mutdjumlah' => $request->mutdjumlah,
                'user_created' => $login,
                'mutdposisi' => $posisiBahan,
                'cabang' => $this->cabang,
                'mutdbahan_tujuan' => $request->mutdbahan_tujuan,
            ]);

            // Transaksi Stock Barang
                StockBarang::create([
                'sbid' => $generateIdStock,
                'sbparent' => $generateId,
                'sbbahan' => $request->mutdbahan,
                'sbjenis' => 'keluar',
                'sbmasuk' => null,
                'sbkeluar' => $request->mutdjumlah,
                'sbadjust' => null,
                'sbgudang' => $request->mutdgudang,
                'sbuser' => $login,
                'created_at' => now(),
                'updated_at' => null,
                'sbcabang' => $this->cabang,
                ]);
            // END CREATE STOCK BARANG



            // Jika jenis mutasi dioper ke store lain
            if ($jenismutasi == 'JMU001') {

                // Generate ID Stock Barang Keluar
            $cekmaxStockMasuk = DB::table('stock_barang')
                ->where('sbid', 'like', "SB-$tgl-%")
                ->max('sbid');
            $maxNumberMasuk = $cekmaxStockMasuk ? intval(substr($cekmaxStockMasuk, 12)) + 1 : 1;
            $generateIdStockMasuk = "SB-$tgl-" . sprintf('%03d', $maxNumberMasuk + 1); // Pastikan ID unik

            $gudangTujuan = DB::table('gudang')
                ->where('cabang',$request->mutdstore)
                ->where('gudangutama',1)
                ->value('gudangid');
                // Transaksi Stock Barang
                StockBarang::create([
                'sbid'      => $generateIdStockMasuk,
                'sbparent'  => $generateId,
                'sbbahan'   => $request->mutdbahan_tujuan,
                'sbjenis'   => 'masuk',
                'sbmasuk'   => $request->mutdjumlah,
                'sbkeluar'  => null,
                'sbadjust' => null,
                'sbgudang'  => $gudangTujuan, // Menggunakan ID Gudang
                'sbuser'    => $login,
                'created_at'=> now(),
                'updated_at'=> null,
                'sbcabang'  => $request->mutdstore,
            ]);
            } 

            // Commit transaksi
            DB::commit();
            return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();
            return response()->json(['status' => 500, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }   
}
