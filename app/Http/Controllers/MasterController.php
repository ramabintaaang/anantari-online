<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Cabang;
use App\Models\Mutasid;
use App\Models\BahanBar;
use App\Models\Supplier;
use App\Models\BahanOlah;
use App\Models\Pembeliand;
use App\Models\UserAccess;
use App\Models\BarangBahan;
use App\Models\StockBarang;
use App\Models\BahanKitchen;
use App\Models\TransaksidBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MasterController extends Controller
    {

    protected $cabang; // Properti untuk menyimpan nilai cabang

    public function __construct(Request $request)
    {
        // Ambil nilai cabang dari request dan set ke properti $cabang
        $this->cabang = $request->cabang;
    }

    public function getCabang(Request $request){
        $data = DB::table('setting')
                ->where('stgcabang', $request->id)
                ->get();

        if ($data->isNotEmpty()) {
        return response()->json([
            'message' => 'Berhasil get cabang',
            'cabang' => $data
            ]);
        } else {
            return response()->json([
                'message' => 'Data cabang tidak ditemukan'
            ], 404);
        }
    }

    public function getKodeCabang(Request $request){
        $maxStgcabang = DB::table('setting')->max('stgcabang');
        $nextCabang = $maxStgcabang + 1;

        return response()->json(['status'=>2,'stgcabang'=> $nextCabang]);
    }

    public function addCabang(Request $request) {

    // Validasi input
    $validator = Validator::make($request->all(), [
        'stgcabang'     => 'required',
        'stgnama'    => 'required',
        'stgprefix'   => 'required|max:3|',  // Validasi regex untuk huruf kapital
    ], [
        'stgcabang.required' => 'kode wajib diisi',
        'stgnama.required' => 'nama cabang wajib diisi',
        'stgprefix.required' => 'prefix wajib diisi',
        'stgprefix.regex' => 'Prefix harus 3 huruf',  // Pesan error regex

    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    } else {
        try {
            // Memulai transaksi
            DB::beginTransaction();
            $stgprefix = strtoupper($request->stgprefix);


            // Membuat data barang baru
            $data = Cabang::create([
                'stgcabang' => $request->stgcabang,
                'stgnama' => $request->stgnama,
                'stgprefix' => $stgprefix,
            ]);

            if ($data) {
                // Menambahkan 3 gudang untuk cabang yang baru dibuat
                $gudangNames = ['Gudang Besar', 'Gudang Bar', 'Gudang Kitchen'];
                $gudangUtm = [1, 2, 3]; // Nilai untuk 'gudangutama': Gudang Besar = 1, Gudang Bar = 2, Gudang Kitchen = 3

                // Mendapatkan ID gudang terakhir dari tabel 'gudang'
                $lastGudangId = DB::table('gudang')->max('gudangid'); 

                // Jika ada ID terakhir, ekstrak angka terakhir dan tambahkan 1
                if ($lastGudangId) {
                    // Mengambil angka dari ID terakhir, misalnya dari 'GDG007' menjadi '007'
                    preg_match('/\d+/', $lastGudangId, $matches);
                    $lastNumber = (int)$matches[0];  // Mengubah hasil match ke integer

                    // Menambahkan 1 ke angka terakhir untuk ID berikutnya
                    $nextNumber = $lastNumber + 1;
                } else {
                    // Jika tidak ada ID sebelumnya, mulai dari GDG001
                    $nextNumber = 1;
                }
                foreach ($gudangNames as $index => $gudangName) {
                    // Membuat entri gudang
                    DB::table('gudang')->insert([
                        'gudangid' => 'GDG' . str_pad($nextNumber + $index, 3, '0', STR_PAD_LEFT), // Format ID gudang seperti GDG001, GDG002, ...
                        'gudangn' => $gudangName . ' - ' . $request->stgnama, // Menambahkan nama cabang ke nama gudang
                        'gudangutama' => $gudangUtm[$index],
                        'created_at' => now(),  // Menetapkan waktu dibuat
                        'cabang' => $request->stgcabang, // Menyimpan ID cabang yang terkait
                    ]);
                }
                // Commit transaksi jika sukses
                DB::commit();
                return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
            } else {
                // Rollback jika ada masalah saat insert
                DB::rollBack();
                return response()->json(['status' => 500, 'pesan' => 'Data gagal ditambah']);
            }

        } catch (\Exception $e) {
            // Rollback transaksi jika ada exception
            DB::rollBack();
            return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        }
    }

        // TAMPILAN DAN CRUD BARANG
            public function barang($cabang)
        {

            // dd($cabang);
            // Menggunakan Query Builder untuk tabel GUDANG
            $gudang = DB::table('gudang')
                ->where('cabang', $cabang)
                ->orderBy('gudangid')
                ->get();

            // Menggunakan Query Builder untuk tabel BARANG_SATUAN
            $satuan = DB::table('barang_satuan')
                ->where('cabang', $cabang)
                ->orderBy('bsatnama')
                ->get();

            // Menggunakan Query Builder untuk tabel BARANG_JENIS
            $jenis = DB::table('barang_jenis')
                ->where('cabang', $cabang)
                ->orderBy('brjnama')
                ->get();

            return view('master.barang', [
                'satuan' => $satuan,
                'jenis' => $jenis,
                'gudang' => $gudang
            ]);
        }

   public function getAjaxBarang(Request $request)
    {
        $barangJenis = $request->barangJenis;
        $namaBarang = $request->namaBarang;


        $query = DB::table('barang as a')
                    ->select('a.*', 'b.brjnama')
                    ->leftJoin('barang_jenis as b', 'a.brgjenis', '=', 'b.brjid')
                    ->where('a.cabang',$this->cabang)
                    ->where('a.brgnama', 'like', '%' . $namaBarang . '%')
                    ->orderBy('b.brjnama') // Urutkan berdasarkan jenis barang
                    ->orderBy('a.brgnama');

        if ($barangJenis != 'All') {
            
            $query
            ->where('b.brjid', $barangJenis);
        }

        $data = $query->get();
        
        return response()->json(['data' => $data], 200);
    }


    public function getAllBarang(Request $request, $cabang){
        $login = Auth::user()->username;
         $data = DB::table('barang as a')
        ->select('a.*','b.brjnama','c.*')
        ->leftJoin('barang_jenis as b','a.brgjenis','=','b.brjid')
        ->leftJoin('gudang as c','c.gudangid','=','a.brggudang')
        ->where('a.cabang',$cabang)
        ->orderBy('a.brgnama')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if($data->brgstatus == '1'){
                        $txt = '<span class="badge bg-success">Aktif</span>';
                    } 
                    else {
                        $txt = '<span class="badge bg-danger">Nonaktif</span>';
                    }
                    return $txt;
                })
                ->addColumn('harga', function ($data) {
                    $uang = format_uang($data->brgharga);
                    return $uang;
                })
                ->rawColumns(['harga','status'])
                ->make(true);       
        }
    }

    public function getAllBahanOnly(Request $request){
        $login = Auth::user()->username;

        // Query untuk tabel bahan dengan kolom bhohasil sebagai null
        $data = DB::table('bahan as a')
            ->select('a.*','sat.satnama')
            ->leftJoin('satuan as sat','sat.satid','=','a.bhnsatuan')
            ->orderBy('bhnnama')
            ->where('cabang',$this->cabang)
            ->get();

        

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('satuans', function ($data) {
                    $txt = "$data->satnama";
                    return $txt;
                })
                ->rawColumns(['satuans'])
                ->make(true);       
        }
    }

    public function getAllBahan(Request $request){
        $login = Auth::user()->username;

        // Query untuk tabel bahan dengan kolom bhohasil sebagai null
        $bahan = DB::table('bahan as a')
            ->select(DB::raw("a.*, 'dasar' as bhogudang, sat.satnama"))
            ->leftJoin('satuan as sat','sat.satid','=','a.bhnsatuan')
            ->where('cabang',$this->cabang);

        // Query untuk tabel bahan_olah dengan kolom bhohasil
        $bahanOlah = DB::table('bahan_olah as a')
            ->select('a.*','sat.satnama')
            ->leftJoin('satuan as sat','sat.satid','=','a.bhosatuan')
            ->where('cabang',$this->cabang);


        // Menggabungkan kedua query dengan UNION dan mengurutkan berdasarkan bhnnama
        $data = $bahan->union($bahanOlah)
            ->orderBy('bhnnama')
            ->get();

        

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->bhogudang == 'dasar'){
                        $txt = '<span class="badge bg-primary">bahan dasar</span>';
                    } 
                    else {
                        $txt = '<span class="badge bg-success">bahan olah</span>';
                    }
                    return $txt;
                })
                ->addColumn('satuans', function ($data) {
                        $sat = $data->satnama;
                        return $sat;
                    
                })
                ->rawColumns(['jenis','satuans'])
                ->make(true);       
        }
    }

    public function getAllBahan_bar_olah(Request $request){
        $login = Auth::user()->username;

        // Query untuk tabel bahan dengan kolom bhohasil sebagai null
        $bahan_bar = DB::table('bahan_bar as a')
            ->select(DB::raw("a.*, 'dasar' as bhogudang, sat.satnama"))
            ->leftJoin('satuan as sat','sat.satid','=','a.bhnsatuan')
            ->where('cabang',$this->cabang);

        $bahan_kitchen = DB::table('bahan_kitchen as a')
            ->select(DB::raw("a.*, 'dasar' as bhogudang, sat.satnama"))
            ->leftJoin('satuan as sat','sat.satid','=','a.bhnsatuan')
            ->where('cabang',$this->cabang);
        
        $bahan_besar = DB::table('bahan as a')
            ->select(DB::raw("a.*, 'dasar' as bhogudang, sat.satnama"))
            ->leftJoin('satuan as sat','sat.satid','=','a.bhnsatuan')
            ->where('cabang',$this->cabang);

        // Query untuk tabel bahan_olah dengan kolom bhohasil
        $bahanOlah = DB::table('bahan_olah as a')
            ->select('a.*','sat.satnama')
            ->leftJoin('satuan as sat','sat.satid','=','a.bhosatuan')
            ->where('cabang',$this->cabang);

        // Menggabungkan kedua query dengan UNION dan mengurutkan berdasarkan bhnnama
        $data = $bahan_bar->union($bahanOlah)->union($bahan_kitchen)->union($bahan_besar)
            ->orderBy('bhnnama')
            ->get();

        

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    if($data->bhogudang == 'dasar'){
                        $txt = '<span class="badge bg-primary">bahan dasar</span>';
                    } 
                    else {
                        $txt = '<span class="badge bg-success">bahan olah</span>';
                    }
                    return $txt;
                })
                ->addColumn('satuans', function ($data) {
                    $sat = $data->satnama;
                    return $sat;
                })
                ->rawColumns(['jenis','satuans'])
                ->make(true);       
        }
    }

    public function getAllBahanOlah(Request $request){
        $login = Auth::user()->username;

        $data = DB::table('bahan_olah as bo')
            ->where('cabang',$this->cabang)
            ->leftJoin('satuan as sat','sat.satid','=','bo.bhosatuan')
            ->orderBy('bhonama')
            ->get();
        

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);       
        }
    }

    public function getAllBahanBar(Request $request){
        $login = Auth::user()->username;
         $data = DB::table('bahan as a')
        ->select('a.*','b.bhnsaldo as saldoBar','sat.satnama')
        ->leftJoin('bahan_bar as b','a.bhnid','=','b.bhnid')
        ->leftJoin('satuan as sat','sat.satid','=','b.bhnsatuan')
        ->where('a.cabang',$this->cabang)
        ->orderBy('a.bhnnama')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('saldoBar', function ($data) {
                    if($data->saldoBar == null){
                        $txt = '<span class="badge bg-danger">Belum pernah order barang</span>';
                    } else {
                        $txt = $data->saldoBar;
                    }
                    return $txt;
                })
                 ->addColumn('satuans', function ($data) {
                    $txt = $data->satnama;
                    return $txt;
                })
                ->rawColumns(['saldoBar','satuans'])
                ->make(true);       
        }
    }

    public function getAllBahanKitchen(Request $request){
        $login = Auth::user()->username;
         $data = DB::table('bahan as a')
        ->select('a.*','b.bhnsaldo as saldoKitchen','sat.satnama')
        ->leftJoin('bahan_kitchen as b','a.bhnid','=','b.bhnid')
        ->leftJoin('satuan as sat','sat.satid','=','b.bhnsatuan')
        ->where('a.cabang',$this->cabang)
        ->orderBy('a.bhnnama')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('saldoKitchen', function ($data) {
                    if($data->saldoKitchen == null){
                        $txt = '<span class="badge bg-danger">Belum pernah order barang</span>';
                    } else {
                        $txt = $data->saldoKitchen;
                    }
                    return $txt;
                })
                 ->addColumn('satuans', function ($data) {
                    $txt = $data->satnama;
                    return $txt;
                })
                ->rawColumns(['saldoKitchen','satuans'])
                ->make(true);       
        }
    }
    
    public function getBarangBahan(Request $request){
        $login = Auth::user()->username;
        $barang = $request->brgid;


        // Bagian pertama dari UNION
    $query1 = DB::table('barang_bahan as a')
        ->select(
            
            'a.bhanid',
            'a.bhanbarang',
            'a.bhannama',
            'a.bhansatuan',
            'a.bhankuantiti',
            'a.bhanbarang',
            'a.bhnid',
            'a.user_created',
            'a.created_at',
            'a.updated_at',
            'b.bhnsupp as bhohasil',
            'sat.satnama',
        )
        ->leftJoin('bahan as b', 'a.bhnid', '=', 'b.bhnid')
        ->leftJoin('satuan as sat', 'a.bhansatuan', '=', 'sat.satid')
        ->where('a.bhanbarang', $barang)
        ->whereNotNull('b.bhnsupp');

    // Bagian kedua dari UNION
    $query2 = DB::table('barang_bahan as a')
        ->select(
            'a.bhanid',
            'a.bhanbarang',
            'a.bhannama',
            'a.bhansatuan',
            'a.bhankuantiti',
            'a.bhanbarang',
            'a.bhnid',
            'a.user_created',
            'a.created_at',
            'a.updated_at',
            'bo.bhohasil',
            'sat.satnama',
        )
        ->leftJoin('bahan_olah as bo', 'a.bhnid', '=', 'bo.bhoid')
        ->leftJoin('satuan as sat', 'a.bhansatuan', '=', 'sat.satid')
        ->where('a.bhanbarang', $barang);

        // UNION kedua query dan urutkan berdasarkan bhannama
        $data = $query1->union($query2)
            ->orderBy('bhannama')
            ->get();


        //  $data = DB::table('barang_bahan as a')
        //     ->select('*')
        //     ->leftJoin('bahan as b','a.bhnid','=','b.bhnid')
        //     ->where('bhanbarang','=',$barang)
        //     ->orderBy('b.bhnnama')
        //     ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kuantitix', function ($data) {
                    $k = '  <input type="text" id='.$data->bhanid.' class="form-control col-md-2 bhankuantiti_edit"
                            name="bhankuantiti_edit" value='.$data->bhankuantiti.'
                            >';
                    return $k;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash barangbahan_delete" id='.$data->bhanid.'></i></a>
                    ';
                    return $act;
                })
                ->addColumn('jenis', function ($data) {
                    if($data->bhohasil == null){
                        $jenis = '<span class="badge bg-primary">bahan dasar</span>';
                    } else {
                        $jenis = '<span class="badge bg-success">bahan olah</span>';
                    }
                    return $jenis;
                })
                ->addColumn('satuans', function ($data) {
                    $satuan = "$data->satnama";
                    return $satuan;
                })
                // ->addColumn('status', function ($data) {
                //     if($data->brgstatus == '1'){
                //         $txt = '<span class="badge bg-success">Aktif</span>';
                //     } 
                //     else {
                //         $txt = '<span class="badge bg-danger">Nonaktif</span>';
                //     }
                //     return $txt;
                // })
                ->rawColumns(['kuantitix','action','jenis','satuans'])
                ->make(true);       
        }
    }

    public function getAllVarian(Request $request){
        $barang = $request->brgid;

         $data = DB::table('barang_varian as a')
            ->select('*')
            ->get();

        

        return response()->json(['data'=>$data]);
    }
    
    public function addBarang(Request $request) {
    $login = Auth::user()->username;
    $kode = Auth::user()->kode;

    // Generate ID
    $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = $this->cabang");
    $prefixfix = $prefix[0]->stgprefix;

    $cekmax = DB::table('barang')
        ->where('brgid', 'like', 'BRG-' . $prefixfix . '-%')
        ->max('brgid');
        
    $maxNumber = intval(substr($cekmax, 8));
    $maxNumber++;
    $formattedNumber = sprintf('%03d', $maxNumber);

    $generateId = 'BRG-' . $prefixfix . '-' . $formattedNumber;

    // Validasi input
    $validator = Validator::make($request->all(), [
        'ipt_brgnama'     => 'required',
        'ipt_brgharga'    => 'required',
        'ipt_brgsatuan'   => 'required',
    ], [
        'ipt_brgnama.required' => 'Nama barang wajib diisi',
        'ipt_brgharga.required' => 'Harga wajib diisi',
        'ipt_brgsatuan.required' => 'wajib diisi',
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    } else {
        try {
            // Memulai transaksi
            DB::beginTransaction();

            // Membuat data barang baru
            $data = Barang::create([
                'brgid' => $generateId,
                'brgnama' => $request->ipt_brgnama,
                'brgharga' => $request->ipt_brgharga,
                'brgsatuan' => $request->ipt_brgsatuan,
                'brgsupp' => $request->ipt_brgsupp,
                'brgjenis' => $request->ipt_brgjenis,
                'brgstatus' => $request->ipt_brgstatus,
                'brggudang' => $request->brggudang,
                'user_created' => $login,
                'cabang' => $this->cabang,
            ]);

            if ($data) {
                // Commit transaksi jika sukses
                DB::commit();
                return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
            } else {
                // Rollback jika ada masalah saat insert
                DB::rollBack();
                return response()->json(['status' => 500, 'pesan' => 'Data gagal ditambah']);
            }

        } catch (\Exception $e) {
            // Rollback transaksi jika ada exception
            DB::rollBack();
            return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}

    

    public function updateBarang(Request $request){
        $login = Auth::user()->username;

        $validator = Validator::make($request->all(),[
            'ipt_brgnama'     => 'required',
            'ipt_brgharga'     => 'required',
            'ipt_brgsatuan'     => 'required',
        ],[
            'ipt_brgnama.required' => 'Nama barang wajib diisi',
            'ipt_brgharga.required' => 'Harga wajib diisi',
            'ipt_brgsatuan.required' => 'wajib diisi'
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = barang::where('brgid', $request->ipt_brgid)
                ->update([
                    'brgnama' => $request->ipt_brgnama,
                    'brgharga' => $request->ipt_brgharga,
                    'brgsatuan' => $request->ipt_brgsatuan,
                    'brgsupp' => $request->ipt_brgsupp,
                    'brgjenis' => $request->ipt_brgjenis,
                    'brgstatus' => $request->ipt_brgstatus,
                    'brggudang' => $request->brggudang,
                    'updated_at' => now(),
                    'user_update' => $login,
                ]);
            if ($data) {
                return response()->json(['pesan'=>'Data barang berhasil update'],200);
            }
        }
    }


   public function deleteBarang(Request $request)
    {
        try {
            // Memulai transaksi
            DB::beginTransaction();

            // Hapus barang
            $data = Barang::where('brgid', $request->ipt_brgid);
            $simpan = $data->delete();  // Tidak perlu parameter di delete

            if (!$simpan) {
                throw new \Exception('Gagal menghapus data barang.');
            }

            // Hapus barang terkait di BarangBahan
            $bahan = BarangBahan::where('bhanbarang', $request->ipt_brgid);

        // Jika data bahan terkait ditemukan, hapus
            if ($bahan->exists()) {
                $simpan2 = $bahan->delete();  // Hapus data bahan terkait

                if (!$simpan2) {
                    throw new \Exception('Gagal menghapus data bahan terkait.');
                }
            }

            // Commit jika semua berhasil
            DB::commit();

            return response()->json([
                'status' => 200,
                'pesan' => 'Berhasil hapus data'
            ]);

        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'pesan' => 'Gagal hapus data: ' . $e->getMessage()
            ]);
        }
    }

    

///crud barang bahan
   public function addBarangBahan(Request $request)
{
    // Ambil informasi login user
    $login = Auth::user()->username;
    $kode = Auth::user()->kode;

    // Mulai transaksi
    DB::beginTransaction();
    try {

            // Generate ID
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = $this->cabang");
        $prefixfix = $prefix[0]->stgprefix;

        $cekmax = DB::table('barang_bahan')
            ->where('bhanid', 'like', 'BHAN-' . $prefixfix . '-%')
            ->max('bhanid');
            
        $maxNumber = intval(substr($cekmax, 9));
        $maxNumber++;
        $formattedNumber = sprintf('%03d', $maxNumber);

        $generateId = 'BHAN-' . $prefixfix . '-' . $formattedNumber;


        // Validasi input
        $validator = Validator::make($request->all(), [
            'bhannama'     => 'required',
            'bhankuantiti' => 'required',
        ], [
            'bhannama.required' => 'Wajib diisi',
            'bhankuantiti.required' => 'Wajib diisi'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
        }

        // Cek apakah bahan sudah ada di database berdasarkan bhnid
        $cek = DB::table('barang_bahan')
        ->where('bhnid', $request->bhnid)
        ->where('bhanbarang', $request->bhanbarang)
        ->exists();

        if ($cek) {
            return response()->json(['status' => 500, 'pesan' => 'Bahan sudah ada di list']);
        }

        // Insert data ke tabel barang_bahan menggunakan transaksi
        $data = BarangBahan::create([
            'bhanid'      => $generateId,
            'bhannama'    => $request->bhannama,
            'bhankuantiti' => $request->bhankuantiti,
            'bhanbarang'  => $request->bhanbarang,
            'bhnid'       => $request->bhnid,
            'bhansatuan'  => $request->bhansatuan,
            'user_created'=> $login,
            'cabang'      => $this->cabang,
        ]);

        // Jika data berhasil disimpan, commit transaksi
        DB::commit();

        // Kembalikan response sukses
        return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
    } catch (\Exception $e) {
        // Jika terjadi error, rollback transaksi
        DB::rollBack();
        
        // Kembalikan response error
        return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}


    public function updateBarangBahan(Request $request){
        $login = Auth::user()->username;

        $validator = Validator::make($request->all(),[
            // 'bhannama'     => 'required',
            'bhankuantiti'     => 'required',
        ],[
            // 'bhannama.required' => 'wajib diisi',
            'bhankuantiti.required' => 'wajib diisi'
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = BarangBahan::where('bhanid', $request->bhanid)
                ->update([
                    'bhankuantiti' => $request->bhankuantiti,
                    'updated_at' => now(),
                    'user_updated' => $login,
                ]);
            if ($data) {
                return response()->json(['pesan'=>'Data berhasil update'],200);
            }
        }
    }

    public function deleteBarangBahan(Request $request)
    {
        $data = BarangBahan::where('bhanid', $request->bhanid);
        $simpan = $data->delete($request->all());
        if ($simpan) {
            return response()->json([
                'pesan' => 'Berhasil hapus data'
            ]);
        } else {
            return response()->json([
                'pesan' => 'Gagal hapus data'
            ]);
        }
    }

    public function cekBarangBahanDouble(Request $request){
        $data = DB::select("SELECT * FROM barang_bahan WHERE bhanbarang = '$request->bhanbarang' and bhnid = '$request->bhnid' ");
        return response()->json(['data'=>$data],200);
    }


    // TAMPILAN DAN CRUD BAHAN

    public function bahan($cabang)
    {

        // Cek apakah cabang yang diminta ada di database
        $cabangExists = DB::table('setting')->where('stgcabang', $this->cabang)->exists();

        // Jika cabang tidak ditemukan, tampilkan halaman 404
        if (!$cabangExists) {
            abort(404, 'Cabang tidak ditemukan.');
        }

        $jenis = DB::select('SELECT * FROM barang_jenis ORDER BY brjnama');
        $satuan = DB::select('SELECT * FROM satuan ORDER BY satid');

        return view('master.bahan',['satuan' => $satuan,'jenis'=>$jenis]);
    }

    public function addBahan(Request $request)
    {
        $login = Auth::user()->username;

        // Ambil prefix dan format tanggal
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = $this->cabang");
        $prefixfix = $prefix[0]->stgprefix;

        // Cek ID terakhir yang terdaftar
        $cekmax = DB::table('bahan')
            ->where('bhnid', 'like', 'BHN-' . $prefixfix . '-%')
            ->max('bhnid');
            
        if ($cekmax) {
            $maxNumber = intval(substr($cekmax, 8)) + 1;
        } else {
            $maxNumber = 1;
        }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'BHN-' . $prefixfix . '-' . $formattedNumber;

        // Validasi input
        $validator = Validator::make($request->all(), [
            'bhnnama'  => 'required',
            'bhnmax'   => 'required',
            'bhnmin'   => 'required',
        ], [
            'bhnnama.required'  => 'Nama barang wajib diisi',
            'bhnmax.required'   => 'Kuantiti max wajib diisi',
            'bhnmin.required'   => 'Kuantiti min wajib diisi',
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
        }

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Buat data pada tabel bahan
            Bahan::create([
                'bhnid'      => $generateId,
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'bhnsaldo'   => '0',
                'created_at' => now(),
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                'cabang'     => $this->cabang,
            ]);

            // Buat data pada tabel BahanBar
            BahanBar::create([
                'bhnid'      => $generateId,
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'bhnsaldo'   => null,
                'created_at' => now(),
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                'cabang'     => $this->cabang,
            ]);

            // Buat data pada tabel BahanBar
            BahanKitchen::create([
                'bhnid'      => $generateId,
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'bhnsaldo'   => null,
                'created_at' => now(),
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                'cabang'     => $this->cabang,
            ]);


           

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
        } catch (\Exception $e) {
            // Jika terjadi error, rollback transaksi
            DB::rollBack();

            return response()->json(['status' => 500, 'pesan' => 'Gagal menambah data: ' . $e->getMessage()]);
        }
    }

    public function updateBahan(Request $request){
        $login = Auth::user()->username;

        // Validasi input
        $validator = Validator::make($request->all(), [
            'bhnnama'  => 'required',
            'bhnmax'   => 'required',
            'bhnmin'   => 'required',
        ], [
            'bhnnama.required'  => 'Nama barang wajib diisi',
            'bhnmax.required'   => 'Kuantiti max wajib diisi',
            'bhnmin.required'   => 'Kuantiti min wajib diisi',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = Bahan::where('bhnid', $request->bhnid)
                ->update([
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                ]);

                BahanKitchen::where('bhnid', $request->bhnid)
                ->update([
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                ]);

                BahanBar::where('bhnid', $request->bhnid)
                ->update([
                'bhnnama'    => $request->bhnnama,
                'bhnsatuan'  => $request->bhnsatuan,
                'bhnuser'    => $login,
                'updated_at' => now(),
                'bhnmin'     => $request->bhnmin,
                'bhnmax'     => $request->bhnmax,
                'bhnsupp'    => $request->bhnsupp,
                ]);

            if ($data) {
                return response()->json(['pesan'=>'Data BAHAN berhasil update'],200);
            }
        }
    }


    public function deleteBahan(Request $request)
    {
        try {
            // Memulai transaksi
            DB::beginTransaction();

            $data = Bahan::where('bhnid', $request->bhnid)->first();
            $data2 = BahanBar::where('bhnid', $request->bhnid)->first();
            $data3 = BahanKitchen::where('bhnid', $request->bhnid)->first();



            if (!$data) {
                return response()->json([
                    'status' => 404,
                    'pesan' => 'Data bahan tidak ditemukan.'
                ]);
            }

            $simpan = $data->delete();
            $data2->delete();
            $data3->delete();

            if (!$simpan) {
                throw new \Exception('Gagal menghapus data bahan asli.');
            }

            // Hapus bahan terkait di menu/barang
            $bahanBarang = BarangBahan::where('bhnid', $request->bhnid);

            // Hapus bahan di pembeliand
            $bahanPembeliand = Pembeliand::where('pmbdbrg', $request->bhnid);
            // Hapus bahan di mutasi
            $bahanMutasid = Mutasid::where('mutdbahan', $request->bhnid);
            // Hapus bahan di transaksi bahan
            $bahanTransakiBahan = TransaksidBahan::where('tnsbbahan', $request->bhnid);

        // Jika data bahan terkait ditemukan, hapus
            if ($bahanBarang->exists()) {
                $simpan2 = $bahanBarang->delete();  // Hapus data bahan terkait
                $bahanPembeliand->delete();
                $bahanMutasid->delete();
                $bahanTransakiBahan->delete();

                if (!$simpan2) {
                    throw new \Exception('Gagal menghapus data bahan terkait.');
                }
            }

            // Commit jika semua berhasil
            DB::commit();

            return response()->json([
                'status' => 200,
                'pesan' => 'Berhasil hapus data'
            ]);

        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'pesan' => 'Gagal hapus data: ' . $e->getMessage()
            ]);
        }
    }

    
    public function dtAllBahan(Request $request){
        $login = Auth::user()->username;
        
         $data = DB::table('bahan as a')
                ->select('*')
                ->leftJoin('satuan as b','a.bhnsatuan','=','b.satid')
                ->where('a.cabang',$this->cabang)
                ->orderBy('bhnid')
                ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);       
        }
    }



    ///supplier
    public function supplier()
    {
        $satuan = DB::select('SELECT * FROM barang_satuan ORDER BY bsatnama');
        $jenis = DB::select('SELECT * FROM barang_jenis ORDER BY brjnama');
        return view('master.supplier',['satuan' => $satuan,'jenis'=>$jenis]);
    }

    public function getAllSupplier(Request $request){
        $login = Auth::user()->username;
         $data = DB::table('supplier')
        ->select('*')
        ->orderBy('suppid')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash supp_delete" id='.$data->suppid.'></i></a>
                    ';
                    return $act;
                })
                ->rawColumns(['action'])
                ->make(true);       
        }
    }

    public function addSupplier(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;

        /// generate iD
        $cekmax = DB::table('supplier')
        ->max('suppid');

        $maxNumber = intval(substr($cekmax, 4));
        $maxNumber++;
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'SUP'.$formattedNumber;




        $validator = Validator::make($request->all(),[
            'ipt_suppnama'     => 'required',
        ],[
            'ipt_suppnama.required' => 'Nama Supplier wajib diisi',

        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = Supplier::create([
                'suppid' => $generateId,
                'suppnama' => $request->ipt_suppnama,
                'suppalamat' => $request->ipt_suppalamat,
                'supptelp' => $request->ipt_supptelp,
                'user_created' => $login,

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
    }

    public function updateSupplier(Request $request){
        $login = Auth::user()->username;

        $validator = Validator::make($request->all(),[
            'ipt_suppnama'     => 'required',
        ],[
            'ipt_suppnama.required' => 'Nama Supplier wajib diisi',

        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = Supplier::where('suppid', $request->ipt_suppid)
                ->update([
                    'suppnama' => $request->ipt_suppnama,
                    'suppalamat' => $request->ipt_suppalamat,
                    'supptelp' => $request->ipt_supptelp,
                    'updated_at' => now(),
                    'user_update' => $login,
                ]);
            if ($data) {
                return response()->json(['pesan'=>'Data barang berhasil update'],200);
            }
        }
    }

    // END SUPPLIER
    
    // TAMPILAN DAN CRUD BAHAN

    public function bahanOlah()
    {
        $satuan = DB::select('SELECT * FROM barang_satuan ORDER BY bsatnama');
        $jenis = DB::select('SELECT * FROM barang_jenis ORDER BY brjnama');
        $gudang = DB::table('gudang')
                ->where('cabang',$this->cabang)
                ->get();
        $satuans = DB::select('SELECT * FROM satuan ORDER BY SATID');
        return view('master.v_bahanOlah',['satuan' => $satuan,'jenis'=>$jenis,'gudang'=>$gudang,'satuans'=>$satuans]);
    }

    public function deleteBahanOlah(Request $request)
    {
        try {
            // Memulai transaksi
            DB::beginTransaction();

            // Hapus barang
            $data = BahanOlah::where('bhoid', $request->bhoid);
            $simpan = $data->delete();  // Tidak perlu parameter di delete

            if (!$simpan) {
                throw new \Exception('Gagal menghapus data barang.');
            }

            // Hapus barang terkait di BarangBahan
            $bahan = BarangBahan::where('bhnid', $request->bhoid);

        // Jika data bahan terkait ditemukan, hapus
            if ($bahan->exists()) {
                $simpan2 = $bahan->delete();  // Hapus data bahan terkait

                if (!$simpan2) {
                    throw new \Exception('Gagal menghapus data bahan terkait.');
                }
            }

            // Commit jika semua berhasil
            DB::commit();

            return response()->json([
                'status' => 200,
                'pesan' => 'Berhasil hapus data'
            ]);

        } catch (\Exception $e) {
            // Rollback jika ada kesalahan
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'pesan' => 'Gagal hapus data: ' . $e->getMessage()
            ]);
        }
    }

    public function dtAllBahanOlah(Request $request){
        $login = Auth::user()->username;
         $data = DB::table('bahan_olah as a')
        ->select('a.*','b.satnama')
        ->leftJoin('satuan as b','a.bhosatuan','=','b.satid')
        ->where('cabang',$this->cabang)
        ->orderBy('bhoid')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('satuans', function ($data) {
                    $txt = "$data->satnama";
                    return $txt;
                })
                ->rawColumns(['satuans'])
                ->make(true);       
        }
    }
    public function addBahanOlah(Request $request){

        $login = Auth::user()->username;
        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = $this->cabang");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');

         $cekmax = DB::table('bahan_olah')
            ->where('bhoid', 'like', 'BHO-'.$prefixfix.'-%')
            ->max('bhoid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 8)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'BHO-'.$prefixfix.'-'.$formattedNumber;

    


        $validator = Validator::make($request->all(),[
            'bhonama'     => 'required',
            'bhohasil'     => 'required|integer',

        ],[
            'bhonama.required' => 'Nama barang wajib diisi',
            'bhohasil.required' => 'hasil wajib diisi',
            'bhohasil.integer' => 'hasil wajib angka',

        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            $data = BahanOlah::create([
                'bhoid' => $generateId,
                'bhonama' => $request->bhonama,
                'bhosatuan' => $request->bhosatuan,
                'bhouser' => $login,
                'bhosaldo' => '0',
                'created_at' => now(),
                'updated_at' => now(),
                'bhomin' => $request->bhomin,
                'bhomax' => $request->bhomax,
                'bhohasil' => $request->bhohasil,
                'bhogudang' => $request->bhogudang,
                'cabang'=>$this->cabang

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data bahan olah berhasil ditambah']);
                // dd($data);
            }
        }
    }

    public function updateBahanOlah(Request $request){
        $login = Auth::user()->username;

       $validator = Validator::make($request->all(),[
            'bhonama'     => 'required',
        ],[
            'bhonama.required' => 'Nama barang wajib diisi',
        ]);


        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = BahanOlah::where('bhoid', $request->bhoid)
                ->update([
                    'bhonama' => $request->bhonama,
                    'bhosatuan' => $request->bhosatuan,
                    'bhomin' => $request->bhomin,
                    'bhomax' => $request->bhomax,
                    'bhohasil' => $request->bhohasil,
                    'bhogudang' => $request->bhogudang,
                    'updated_at' => now(),
                    'user_update' => $login,
                    'cabang'=>$this->cabang,
                ]);
            if ($data) {
                return response()->json(['pesan'=>'Data barang berhasil update'],200);
            }
        }
    }
    public function getBahanOlahUsed(Request $request){
        $login = Auth::user()->username;
        $bahanOlah = $request->bhoid;

        // Bagian pertama dari UNION
    $query1 = DB::table('barang_bahan as a')
        ->select(
            'a.bhanid',
            'a.bhanbarang',
            'a.bhannama',
            'a.bhansatuan',
            'a.bhankuantiti',
            'a.bhanbarang',
            'a.bhnid',
            'a.user_created',
            'a.created_at',
            'a.updated_at',
            'b.bhnsupp as bhohasil',
            'sat.satnama',
        )
        ->leftJoin('bahan as b', 'a.bhnid', '=', 'b.bhnid')
        ->leftJoin('satuan as sat','sat.satid','=','a.bhansatuan')
        ->where('a.bhanbarang', $bahanOlah)
        ->where('a.cabang',$this->cabang)
        ->whereNotNull('b.bhnsupp');

    // Bagian kedua dari UNION
    $query2 = DB::table('barang_bahan as a')
        ->select(
            'a.bhanid',
            'a.bhanbarang',
            'a.bhannama',
            'a.bhansatuan',
            'a.bhankuantiti',
            'a.bhanbarang',
            'a.bhnid',
            'a.user_created',
            'a.created_at',
            'a.updated_at',
            'bo.bhohasil',
            'sat.satnama',
        )
        ->leftJoin('bahan_olah as bo', 'a.bhnid', '=', 'bo.bhoid')
        ->leftJoin('satuan as sat','sat.satid','=','a.bhansatuan')
        ->where('a.bhanbarang', $bahanOlah)
        ->where('a.cabang',$this->cabang);

        // UNION kedua query dan urutkan berdasarkan bhannama
        $data = $query1->union($query2)
            ->orderBy('bhannama')
            ->get();

        //  $data = DB::table('barang_bahan as a')
        //     ->select('*')
        //     ->leftJoin('bahan as b','a.bhnid','=','b.bhnid')
        //     ->where('bhanbarang','=',$bahanOlah)
        //     ->orderBy('b.bhnnama')
        //     ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('kuantitix', function ($data) {
                    $k = '  <input type="text" id='.$data->bhanid.' class="form-control col-md-2 bhankuantiti_edit"
                            name="bhankuantiti_edit" value='.$data->bhankuantiti.'
                            >';
                    return $k;
                })
                ->addColumn('action', function ($data) {
                    $act = '<a href="#" title="Hapus"><i class="bi bi-trash barangbahan_delete" id='.$data->bhanid.'></i></a>
                    ';
                    return $act;
                })
                ->addColumn('jenis', function ($data) {
                    if($data->bhohasil == null){
                        $jenis = '<span class="badge bg-primary">bahan dasar</span>';
                    } else {
                        $jenis = '<span class="badge bg-success">bahan olah</span>';
                    }
                    return $jenis;
                })
                ->addColumn('satuans', function ($data) {
                    $satuan = "$data->satnama";
                    return $satuan;
                })
                // ->addColumn('status', function ($data) {
                //     if($data->brgstatus == '1'){
                //         $txt = '<span class="badge bg-success">Aktif</span>';
                //     } 
                //     else {
                //         $txt = '<span class="badge bg-danger">Nonaktif</span>';
                //     }
                //     return $txt;
                // })
                ->rawColumns(['kuantitix','action','jenis','satuans'])
                ->make(true);       
        }
    }
    public function addBahanOlahUsed(Request $request){

        $login = Auth::user()->username;

        $kode = Auth::user()->kode;


        $prefix = DB::select("SELECT * FROM setting WHERE stgcabang = $this->cabang");
        $prefixfix = $prefix[0]->stgprefix;
        $tgl = Carbon::today()->format('Ymd');

         $cekmax = DB::table('barang_bahan')
            ->where('bhanid', 'like', 'BHAN-'.$prefixfix.'-%')
            ->max('bhanid');
        if ($cekmax) {
                $maxNumber = intval(substr($cekmax, 9)) + 1;
            } else {
                $maxNumber = 1;
            }
        $formattedNumber = sprintf('%03d', $maxNumber);
        $generateId = 'BHAN-'.$prefixfix.'-'.$formattedNumber;



        // Cek apakah bahan sudah ada di database berdasarkan bhnid
        $cek = DB::table('barang_bahan')
        ->where('bhnid', $request->bhnid)
        ->where('bhanbarang', $request->bhanbahan)
        ->exists();

        if ($cek) {
            return response()->json(['status' => 500, 'pesan' => 'Bahan sudah ada di list']);
        }


        $validator = Validator::make($request->all(),[
            'bhannama'     => 'required',
            'bhankuantiti'     => 'required',
        ],[
            'bhannama.required' => 'wajib diisi',
            'bhankuantiti.required' => 'wajib diisi'

        ]);

        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {
            
            $data = BarangBahan::create([
                'bhanid' => $generateId,
                'bhannama' => $request->bhannama,
                'bhankuantiti' => $request->bhankuantiti,
                'bhanbarang' => $request->bhanbahan,
                'bhnid' => $request->bhnid,
                'bhansatuan' => $request->bhansatuan,
                'user_created' => $login,
                'cabang'=>$this->cabang

            ]);
            if ($data) {
                return response()->json(['status'=>200,'pesan'=>'Data berhasil ditambah']);
                // dd($data);
            }
        }
        
    }

    public function getAllSaldoGudang(Request $request){
    // Query untuk tabel bahan (bahan_bar, bahan_kitchen, dan bahan)
    $bahan = DB::table('bahan as a')
    ->select(
        'a.bhnid',
        'a.bhnnama',
        'sat.satnama',
        'a.bhnsaldo as saldoBesar',
        'b.bhnsaldo as saldoBar',  
        'c.bhnsaldo as saldoKitchen',
        DB::raw('"dasar" as jenis'),
        DB::raw('null as saldoOlah')
    )
    ->join('bahan_bar as b', 'a.bhnid', '=', 'b.bhnid')
    ->join('bahan_kitchen as c', 'a.bhnid', '=', 'c.bhnid')
    ->join('satuan as sat', 'sat.satid', '=', 'a.bhnsatuan')
    ->where('a.cabang', $this->cabang);

    // Query untuk tabel bahan_olah
    $bahanOlah = DB::table('bahan_olah as a')
        ->select(
            'a.bhoid as bhoid',
            'a.bhonama as bhnnama',
            'sat.satnama',
            DB::raw('null as saldoBesar'),
            'a.bhosaldo as saldoBar',
            // DB::raw('null as saldoBar'),
            DB::raw('null as saldoKitchen'),
            DB::raw('"olah" as jenis'),
            'a.bhosaldo as saldoOlah'
        )
        ->join('satuan as sat', 'sat.satid', '=', 'a.bhosatuan')
        ->where('a.cabang', $this->cabang);

    // Menggabungkan kedua query dengan UNION dan mengurutkan berdasarkan bhnnama
    $data = $bahan->union($bahanOlah)
        ->orderBy('bhnnama')
        ->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('satuans', function ($data) {
                        $txt = '<span class="badge bg-success">'.$data->satnama.'</span>';
                        return $txt;
                })
                ->addColumn('saldoBesar_custom', function ($data) {
                        if($data->saldoBesar == null){
                            $txt = '<span class="badge bg-danger">-<span>';
                        } else {
                            $txt = '<span class="badge bg-primary">'.$data->saldoBesar.'</span>';
                        }
                        return $txt;
                })
                ->addColumn('saldoBar_custom', function ($data) {
                        if($data->saldoBar == null){
                            $txt = '<span class="badge bg-danger">-<span>';
                        } else {
                            $txt = '<span class="badge bg-primary">'.$data->saldoBar.'</span>';
                        }
                        return $txt;
                })
                ->addColumn('saldoKitchen_custom', function ($data) {
                        if($data->saldoKitchen == null){
                            $txt = '<span class="badge bg-danger">-<span>';
                        } else {
                            $txt = '<span class="badge bg-primary">'.$data->saldoKitchen.'</span>';
                        }
                        return $txt;
                })
                ->addColumn('saldoOlah_custom', function ($data) {
                        if($data->saldoOlah == null){
                            $txt = '<span class="badge bg-danger">-<span>';
                        } else {
                            $txt = '<span class="badge bg-primary">'.$data->saldoOlah.'</span>';
                        }
                        return $txt;
                })
                ->addColumn('jenisz', function ($data) {
                        if($data->jenis == 'dasar'){
                            $txt = '<span class="badge bg-success">bahan dasar<span>';
                        } else {
                            $txt = '<span class="badge bg-warning">bahan olah</span>';
                        }
                        return $txt;
                })
                ->rawColumns(['satuans','saldoBesar_custom','saldoBar_custom','saldoKitchen_custom','saldoOlah_custom','jenisz'])
                ->make(true);       
        }

        }
    public function getKodeUser(Request $request){
        $maxUser = DB::table('users')->max('id');
        $nextUser = $maxUser + 1;

        return response()->json(['status'=>200,'iduser'=> $nextUser]);
    }

    public function addUser(Request $request) {

    // Validasi input
    $validator = Validator::make($request->all(), [
        'iduser'     => 'required',
        'name'    => 'required',
        'username'   => 'required|unique:users,username', 
        'password'   => 'required', 
    ], [
        'iduser.required' => 'kode wajib diisi',
        'username.required' => 'username wajib diisi',
        'password.required' => 'prefix wajib diisi',
        'username.unique' => 'Username sudah terdaftar, pilih username lain',


    ]);

    if ($validator->fails()) {
        return response()->json(['status' => 500, 'error' => $validator->errors()->toArray()]);
    } else {
        try {
            // Memulai transaksi
            DB::beginTransaction();


            // Membuat data barang baru
            $data = User::create([
                'id' => $request->iduser,
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'divisi' => $request->divisi,
                'email' =>''
            ]);

            // add hak akses cabang
            foreach ($request->cabang as $cabangId) {
                UserAccess::create([
                    'iduser' => $request->iduser,
                    'idcabang' => $cabangId,
                ]);
            }

            if ($data) {
                // Commit transaksi jika sukses
                DB::commit();
                return response()->json(['status' => 200, 'pesan' => 'Data berhasil ditambah']);
            } else {
                // Rollback jika ada masalah saat insert
                DB::rollBack();
                return response()->json(['status' => 500, 'pesan' => 'Data gagal ditambah']);
            }

        } catch (\Exception $e) {
            // Rollback transaksi jika ada exception
            DB::rollBack();
            return response()->json(['status' => 500, 'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
        }
    }


        public function getUser(Request $request){
        $login = Auth::user()->username;

        // Query untuk tabel bahan dengan kolom bhohasil sebagai null
        $data = DB::table('users')
            ->select('*')
            ->orderBy('name')
            ->get();

        

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addColumn('divisi', function ($data) {
                    if($data->divisi == 'bar'){
                        $txt = '<span class="badge bg-primary">bar</span>';
                    } 
                    else if($data->divisi == 'purchasing') {
                        $txt = '<span class="badge bg-success">purchasing</span>';
                    } 
                    else if($data->divisi == 'kitchen')
                    {
                        $txt = '<span class="badge bg-warning">kitchen</span>';

                    }
                    else {
                        $txt = '<span class="badge bg-secondary">admin</span>';

                    }
                    return $txt;
                })
                // ->addColumn('satuans', function ($data) {
                //         $sat = $data->satnama;
                //         return $sat;
                    
                // })
                ->rawColumns(['divisi'])
                ->make(true);       
        }
    }


    public function getUserDetail(Request $request){
        $user = DB::table('users')
            ->where('username',$request->username)
            ->get();

        $akses = DB::table('users_access')
            ->where('iduser',$request->iduser)
            ->get();

        return response()->json(['status'=>200,'user'=>$user,'akses'=>$akses]);
    }


    public function updateUser(Request $request){
        $login = Auth::user()->username;

        $validator = Validator::make($request->all(), [
        'name'    => 'required',
    ], [
        

    ]);


        if($validator->fails()){
            return response()->json(['status'=>500,'error'=>$validator->errors()->toArray()]);
        } else {

             $data = User::where('id', $request->iduser)
                ->update([
                'name' => $request->name,
                'divisi' => $request->divisi,
                ]);

            UserAccess::where('iduser', $request->iduser)->delete();

            
               // add hak akses cabang
            foreach ($request->cabang as $cabangId) {
                UserAccess::create([
                    'iduser' => $request->iduser,
                    'idcabang' => $cabangId,
                ]);
            }


            if ($data) {
                return response()->json(['pesan'=>'Data berhasil update','status'=>200],200);
            }
        }
    }
        

    
}
