<?php

use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('lobby');
});

Route::get('/run-route-cache', function () {
    // Jalankan perintah route:cache
    Artisan::call('route:cache');

    return response()->json([
        'status' => 'success',
        'message' => 'Routes cached successfully!',
    ]);
}); // Tambahkan middleware jika perlu.
// Route::get('/', [App\Http\Controllers\HomeController::class, 'awal'])->name('awal');


// Auth::routes(); ganti manual saja
Route::get('/login', [App\Http\Controllers\AuthCustomController::class, 'index'])->name('login_view');
Route::post('/login', [App\Http\Controllers\AuthCustomController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\AuthCustomController::class, 'logout'])->name('logout');
Route::post('/reset-password', [App\Http\Controllers\AuthCustomController::class, 'resetPassword'])
    ->name('reset.password');





Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/getCabang', [App\Http\Controllers\MasterController::class, 'getCabang'])->name('getCabang');
Route::get('/getKodeCabang', [App\Http\Controllers\MasterController::class, 'getKodeCabang'])->name('getKodeCabang');
Route::post('/addCabang', [App\Http\Controllers\MasterController::class, 'addCabang'])->name('addCabang');

Route::get('/getUser', [App\Http\Controllers\MasterController::class, 'getUser'])->name('getUser');
Route::get('/getKodeUser', [App\Http\Controllers\MasterController::class, 'getKodeUser'])->name('getKodeUser');
Route::post('/addUser', [App\Http\Controllers\MasterController::class, 'addUser'])->name('addUser');
Route::get('/getUserDetail', [App\Http\Controllers\MasterController::class, 'getUserDetail'])->name('getUserDetail');
Route::post('/updateUser', [App\Http\Controllers\MasterController::class, 'updateUser'])->name('updateUser');






// global tanpa auth,tanpa cabang
Route::get('/getAllSupplier', [App\Http\Controllers\MasterController::class, 'getAllSupplier'])->name('getAllSupplier');
Route::post('/addSupplier', [App\Http\Controllers\MasterController::class, 'addSupplier'])->name('addSupplier');
Route::get('/getAllSaldoGudang', [App\Http\Controllers\MasterController::class, 'getAllSaldoGudang'])->name('getAllSaldoGudang');

Route::get('/chartTrenPenjualan', [App\Http\Controllers\HomeController::class, 'chartTrenPenjualan'])->name('chartTrenPenjualan');






Route::get('/lobby', [App\Http\Controllers\HomeController::class, 'lobby'])->name('lobby')->withoutMiddleware('checkCabang');

Route::middleware(['auth','checkCabang','checkUserCabang'])->group(function () {
    
    Route::get('{cabang}/dashboard', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard2', [App\Http\Controllers\HomeController::class, 'dashboard2'])->name('dashboard2');


    ///GLOBAL
    Route::get('{cabang}/getAjaxBarang', [App\Http\Controllers\MasterController::class, 'getAjaxBarang'])->name('getAjaxBarang');
    



    
    // START CONTROLLER PREFIX MASTER
    
    Route::prefix('{cabang}/master')->group(function () {
        Route::get('/barang', [App\Http\Controllers\MasterController::class, 'barang'])->name('barang');
        Route::get('/getAllBarang', [App\Http\Controllers\MasterController::class, 'getAllBarang'])->name('getAllBarang');
        Route::get('/getAllBahan', [App\Http\Controllers\MasterController::class, 'getAllBahan'])->name('getAllBahan');
        Route::get('/getAllBahan_bar_olah', [App\Http\Controllers\MasterController::class, 'getAllBahan_bar_olah'])->name('getAllBahan_bar_olah');
        Route::get('/getAllBahanOnly', [App\Http\Controllers\MasterController::class, 'getAllBahanOnly'])->name('getAllBahanOnly');
        Route::get('/getAllBahanOlah', [App\Http\Controllers\MasterController::class, 'getAllBahanOlah'])->name('getAllBahanOlah');
        Route::get('/getAllBahanBar', [App\Http\Controllers\MasterController::class, 'getAllBahanBar'])->name('getAllBahanBar');
        Route::get('/getAllBahanKitchen', [App\Http\Controllers\MasterController::class, 'getAllBahanKitchen'])->name('getAllBahanKitchen');
        Route::get('/getBarangBahan', [App\Http\Controllers\MasterController::class, 'getBarangBahan'])->name('getBarangBahan');
        Route::get('/getAllVarian', [App\Http\Controllers\MasterController::class, 'getAllVarian'])->name('getAllVarian');
        Route::get('/getBahanOlahUsed', [App\Http\Controllers\MasterController::class, 'getBahanOlahUsed'])->name('getBahanOlahUsed'); 
        Route::get('/bahan', [App\Http\Controllers\MasterController::class, 'bahan'])->name('bahan');
        Route::get('/dtAllBahan', [App\Http\Controllers\MasterController::class, 'dtAllBahan'])->name('dtAllBahan');
        Route::get('/bahanOlah', [App\Http\Controllers\MasterController::class, 'bahanOlah'])->name('bahanOlah');
        Route::get('/dtAllBahanOlah', [App\Http\Controllers\MasterController::class, 'dtAllBahanOlah'])->name('dtAllBahanOlah');
        Route::get('/supplier', [App\Http\Controllers\MasterController::class, 'supplier'])->name('supplier');
        ///METHOD CREATE
            Route::post('/addBarang', [App\Http\Controllers\MasterController::class, 'addBarang'])->name('addBarang');
            Route::post('/addBarangBahan', [App\Http\Controllers\MasterController::class, 'addBarangBahan'])->name('addBarangBahan');
            Route::post('/addBahan', [App\Http\Controllers\MasterController::class, 'addBahan'])->name('addBahan');
            Route::post('/addBahanOlah', [App\Http\Controllers\MasterController::class, 'addBahanOlah'])->name('addBahanOlah');
            Route::post('/addBahanOlahUsed', [App\Http\Controllers\MasterController::class, 'addBahanOlahUsed'])->name('addBahanOlahUsed');
        ///END METHOD CREATE PREFIX MASTER


        ///METHOD UPDATE
            Route::post('/updateBarang', [App\Http\Controllers\MasterController::class, 'updateBarang'])->name('updateBarang');
            Route::post('/updateBahan', [App\Http\Controllers\MasterController::class, 'updateBahan'])->name('updateBahan');
            Route::post('/updateBarangBahan', [App\Http\Controllers\MasterController::class, 'updateBarangBahan'])->name('updateBarangBahan');
            Route::post('/updateBahanOlah', [App\Http\Controllers\MasterController::class, 'updateBahanOlah'])->name('updateBahanOlah');
            Route::post('/updateSupplier', [App\Http\Controllers\MasterController::class, 'updateSupplier'])->name('updateSupplier');    
            Route::get('/cekBarangBahanDouble', [App\Http\Controllers\MasterController::class, 'cekBarangBahanDouble'])->name('cekBarangBahanDouble');

        //METHOD DELETE
        Route::post('/deleteBarang', [App\Http\Controllers\MasterController::class, 'deleteBarang'])->name('deleteBarang');
        Route::post('/deleteBahan', [App\Http\Controllers\MasterController::class, 'deleteBahan'])->name('deleteBahan');
        Route::post('/deleteBarangBahan', [App\Http\Controllers\MasterController::class, 'deleteBarangBahan'])->name('deleteBarangBahan');
        Route::post('/deleteBahanOlah', [App\Http\Controllers\MasterController::class, 'deleteBahanOlah'])->name('deleteBahanOlah');



    });


    // START CONTROLLER PREFIX TRANSAKSI
    Route::prefix('{cabang}/transaksi')->group(function () {
        Route::get('/kasir', [App\Http\Controllers\TransaksiController::class, 'kasir'])->name('kasir');
        Route::get('/getTransaksi', [App\Http\Controllers\TransaksiController::class, 'getTransaksi'])->name('getTransaksi');
        Route::get('/getTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'getTransaksiDetail'])->name('getTransaksiDetail');
        Route::get('/getTransaksiDetailBahan', [App\Http\Controllers\TransaksiController::class, 'getTransaksiDetailBahan'])->name('getTransaksiDetailBahan');
        Route::get('/getBahanFromTransaksid', [App\Http\Controllers\TransaksiController::class, 'getBahanFromTransaksid'])->name('getBahanFromTransaksid');
        Route::get('/getBahanFromVarian', [App\Http\Controllers\TransaksiController::class, 'getBahanFromVarian'])->name('getBahanFromVarian');
        Route::get('/getVarianFromBarang', [App\Http\Controllers\TransaksiController::class, 'getVarianFromBarang'])->name('getVarianFromBarang');
        Route::get('/generateIdTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'generateIdTransaksiDetail'])->name('generateIdTransaksiDetail');  

        // METHOD ADD
            Route::post('/addTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'addTransaksiDetail'])->name('addTransaksiDetail');
        // END METHOD ADD

        // METHOD DELETE
            Route::post('/deleteTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'deleteTransaksiDetail'])->name('deleteTransaksiDetail');  
        // END METHOD DELETE prefix transaksi

    });
    // END PREFIX TRANSAKSI

    // START PREFIX BAR------------------
    
        Route::prefix('{cabang}/bar')->group(function () {
            Route::get('/pembelian_bar', [App\Http\Controllers\TransaksiController::class, 'pembelian_bar'])->name('pembelian_bar');
            Route::get('/getPembelian_bar', [App\Http\Controllers\TransaksiController::class, 'getPembelian_bar'])->name('getPembelian_bar');
            Route::get('/getPembeliand_bar', [App\Http\Controllers\TransaksiController::class, 'getPembeliand_bar'])->name('getPembeliand_bar');
            Route::get('/getRiwayatBahan_bar_persediaan', [App\Http\Controllers\TransaksiController::class, 'getRiwayatBahan_bar_persediaan'])->name('getRiwayatBahan_bar_persediaan'); 
            Route::get('/getRiwayatBahanOlah_bar_persediaan', [App\Http\Controllers\TransaksiController::class, 'getRiwayatBahanOlah_bar_persediaan'])->name('getRiwayatBahanOlah_bar_persediaan');  
 


            Route::get('/kasir_bar', [App\Http\Controllers\TransaksiController::class, 'kasir_bar'])->name('kasir_bar');
            // Route::get('/getTransaksi', [App\Http\Controllers\TransaksiController::class, 'getTransaksi'])->name('getTransaksi');
            // Route::get('/getTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'getTransaksiDetail'])->name('getTransaksiDetail');


            Route::get('/persediaan_bar', [App\Http\Controllers\TransaksiController::class, 'persediaan_bar'])->name('persediaan_bar');
            Route::get('/getTabelBahanBar', [App\Http\Controllers\TransaksiController::class, 'getTabelBahanBar'])->name('getTabelBahanBar');
            Route::get('/getTabelBahanOlah', [App\Http\Controllers\TransaksiController::class, 'getTabelBahanOlah'])->name('getTabelBahanOlah');
            Route::get('/getBahanFromBahanOlah', [App\Http\Controllers\TransaksiController::class, 'getBahanFromBahanOlah'])->name('getBahanFromBahanOlah');





            ///METHOD CREATE
            Route::post('/addPembelian_bar', [App\Http\Controllers\TransaksiController::class, 'addPembelian_bar'])->name('addPembelian_bar');
            Route::post('/addPembeliand_bar', [App\Http\Controllers\TransaksiController::class, 'addPembeliand_bar'])->name('addPembeliand_bar');

            Route::post('/addTransaksiDetailBar', [App\Http\Controllers\TransaksiController::class, 'addTransaksiDetailBar'])->name('addTransaksiDetailBar');

            Route::post('/addBahanOlahUsed', [App\Http\Controllers\TransaksiController::class, 'addBahanOlahUsedTrans'])->name('addBahanOlahUsedTrans');



        });

    // END PREFIX BAR---------------------

    // START PREFIX kitchen------------------
    
        Route::prefix('{cabang}/kitchen')->group(function () {
            Route::get('/pembelian_kitchen', [App\Http\Controllers\TransaksiController::class, 'pembelian_kitchen'])->name('pembelian_kitchen');
            Route::get('/getPembelian_kitchen', [App\Http\Controllers\TransaksiController::class, 'getPembelian_kitchen'])->name('getPembelian_kitchen');
            Route::get('/getPembeliand_kitchen', [App\Http\Controllers\TransaksiController::class, 'getPembeliand_kitchen'])->name('getPembeliand_kitchen');
            Route::get('/getRiwayatBahan_kitchen_persediaan', [App\Http\Controllers\TransaksiController::class, 'getRiwayatBahan_kitchen_persediaan'])->name('getRiwayatBahan_kitchen_persediaan');  


            // Route::get('/kasir_bar', [App\Http\Controllers\TransaksiController::class, 'kasir_bar'])->name('kasir_bar');
            

            Route::get('/persediaan_kitchen', [App\Http\Controllers\TransaksiController::class, 'persediaan_kitchen'])->name('persediaan_kitchen');
            Route::get('/getTabelBahanKitchen', [App\Http\Controllers\TransaksiController::class, 'getTabelBahanKitchen'])->name('getTabelBahanKitchen');
            // Route::get('/getTabelBahanOlah', [App\Http\Controllers\TransaksiController::class, 'getTabelBahanOlah'])->name('getTabelBahanOlah');
            // Route::get('/getBahanFromBahanOlah', [App\Http\Controllers\TransaksiController::class, 'getBahanFromBahanOlah'])->name('getBahanFromBahanOlah');





            // ///METHOD CREATE
            Route::post('/addPembelian_kitchen', [App\Http\Controllers\TransaksiController::class, 'addPembelian_kitchen'])->name('addPembelian_kitchen');
            Route::post('/addPembeliand_kitchen', [App\Http\Controllers\TransaksiController::class, 'addPembeliand_kitchen'])->name('addPembeliand_kitchen');

            // Route::post('/addTransaksiDetailBar', [App\Http\Controllers\TransaksiController::class, 'addTransaksiDetailBar'])->name('addTransaksiDetailBar');

            // Route::post('/addBahanOlahUsed', [App\Http\Controllers\TransaksiController::class, 'addBahanOlahUsedTrans'])->name('addBahanOlahUsedTrans');



        });

    // END PREFIX BAR---------------------
    // START CONTROLLER PREFIX laporan
    Route::prefix('{cabang}/laporan')->group(function () {
        Route::get('/laporanBarang', [App\Http\Controllers\LaporanController::class, 'laporanBarang'])->name('laporanBarang');
        Route::get('/laporanBahan', [App\Http\Controllers\LaporanController::class, 'laporanBahan'])->name('laporanBahan');
        Route::get('exportBahan', [LaporanController::class, 'exportBahan'])->name('exportBahan');

    });

    // START CONTROLLER PREFIX UTILITY
    Route::prefix('{cabang}/utility')->group(function () {
        Route::get('/stockOpname', [App\Http\Controllers\UtilityController::class, 'stockOpname'])->name('stockOpname');
        Route::get('/getStockOpname', [App\Http\Controllers\UtilityController::class, 'getStockOpname'])->name('getStockOpname');
        Route::get('/getStockOpnameD', [App\Http\Controllers\UtilityController::class, 'getStockOpnameD'])->name('getStockOpnameD');
        Route::get('/getStockOpnamedRiwayat', [App\Http\Controllers\UtilityController::class, 'getStockOpnamedRiwayat'])->name('getStockOpnamedRiwayat');


        Route::get('/refreshSaldo', [App\Http\Controllers\UtilityController::class, 'refreshSaldo'])->name('refreshSaldo');
        Route::get('/refreshSaldoStock', [App\Http\Controllers\UtilityController::class, 'refreshSaldoStock'])->name('refreshSaldoStock');
        Route::get('/refreshSaldoStock_besar', [App\Http\Controllers\UtilityController::class, 'refreshSaldoStock_besar'])->name('refreshSaldoStock_besar');
        Route::get('/refreshSaldoStock_kitchen', [App\Http\Controllers\UtilityController::class, 'refreshSaldoStock_kitchen'])->name('refreshSaldoStock_kitchen');


        
        // START METHOD ADD PREFIX UTILITY
        Route::post('/addStockOpname', [App\Http\Controllers\UtilityController::class, 'addStockOpname'])->name('addStockOpname');
        Route::post('/addStockOpnameD', [App\Http\Controllers\UtilityController::class, 'addStockOpnameD'])->name('addStockOpnameD');

    });




// START  prefix persediaan
     Route::prefix('{cabang}/persediaan')->group(function () {
            Route::get('/gudang', [App\Http\Controllers\TransaksiController::class, 'gudang'])->name('persediaanGudang');
            Route::get('/getGudangBesar', [App\Http\Controllers\TransaksiController::class, 'getGudangBesar'])->name('getGudangBesar');
            Route::get('/getRiwayatBahanGudangBesar', [App\Http\Controllers\TransaksiController::class, 'getRiwayatBahanGudangBesar'])->name('getRiwayatBahanGudangBesar');
            Route::get('/getRiwayatBahan', [App\Http\Controllers\TransaksiController::class, 'getRiwayatBahan'])->name('getRiwayatBahan');
            Route::get('/getGudangBar', [App\Http\Controllers\TransaksiController::class, 'getGudangBar'])->name('getGudangBar');
            Route::get('/getGudangKitchen', [App\Http\Controllers\TransaksiController::class, 'getGudangKitchen'])->name('getGudangKitchen');
            Route::get('/getMacamGudang', [App\Http\Controllers\TransaksiController::class, 'getMacamGudang'])->name('getMacamGudang');

            ///METHOD CREATE

        });

    // START  PREFIX PURCHASING
     Route::prefix('{cabang}/purchasing')->group(function () {

        // SUB - PEMBELIAN
            Route::prefix('pembelian')->group(function () {
            Route::get('/', [App\Http\Controllers\PurchasingController::class, 'pembelian'])->name('pembelian');
            Route::get('/getPembelian', [App\Http\Controllers\PurchasingController::class, 'getPembelian'])->name('getPembelian');
            Route::get('/getPembelianDetail', [App\Http\Controllers\PurchasingController::class, 'getPembelianDetail'])->name('getPembelianDetail');
                ///METHOD CREATE
                Route::post('/addTransaksi', [App\Http\Controllers\TransaksiController::class, 'addTransaksi'])->name('addTransaksi');
                Route::post('/addTransaksiDetailBahan', [App\Http\Controllers\TransaksiController::class, 'addTransaksiDetailBahan'])->name('addTransaksiDetailBahan');
                Route::post('/addPembelian', [App\Http\Controllers\PurchasingController::class, 'addPembelian'])->name('addPembelian');
                Route::post('/addPembeliand', [App\Http\Controllers\PurchasingController::class, 'addPembeliand'])->name('addPembeliand');
                //METHOD UPDATE
                Route::post('/updateTransaksiDetail', [App\Http\Controllers\TransaksiController::class, 'updateTransaksiDetail'])->name('updateTransaksiDetail');
                Route::post('/updateTransaksiDetail_new', [App\Http\Controllers\TransaksiController::class, 'updateTransaksiDetail_new'])->name('updateTransaksiDetail_new');
                Route::post('/updatePembelian', [App\Http\Controllers\PurchasingController::class, 'updatePembelian'])->name('updatePembelian');
                Route::post('/updatePembeliand', [App\Http\Controllers\PurchasingController::class, 'updatePembeliand'])->name('updatePembeliand');

                //METHOD DELETE
                Route::post('/deletePembelian', [App\Http\Controllers\PurchasingController::class, 'deletePembelian'])->name('deletePembelian');
                Route::post('/deletePembeliand', [App\Http\Controllers\PurchasingController::class, 'deletePembeliand'])->name('deletePembeliand');
        // END SUB - PEMBELIAN        
        });


        // START SUB - PENGELUARAN
                Route::prefix('pengeluaran')->group(function () {
                    Route::get('/', [App\Http\Controllers\PurchasingController::class, 'pengeluaran'])->name('pengeluaran');
                    Route::get('/getMutasi', [App\Http\Controllers\PurchasingController::class, 'getMutasi'])->name('getMutasi');
                    Route::get('/getMutasiRiwayat', [App\Http\Controllers\PurchasingController::class, 'getMutasiRiwayat'])->name('getMutasiRiwayat');
                    Route::get('/getMutasiDetail', [App\Http\Controllers\PurchasingController::class, 'getMutasiDetail'])->name('getMutasiDetail');
                    Route::get('/getBahanFromGudang', [App\Http\Controllers\PurchasingController::class, 'getBahanFromGudang'])->name('getBahanFromGudang');
                    Route::get('/getBahanFromGudangTujuan', [App\Http\Controllers\PurchasingController::class, 'getBahanFromGudangTujuan'])->name('getBahanFromGudangTujuan');

                        ///METHOD CREATE
                        Route::post('/addMutasi', [App\Http\Controllers\PurchasingController::class, 'addMutasi'])->name('addMutasi');
                        Route::post('/addMutasiDetail', [App\Http\Controllers\PurchasingController::class, 'addMutasiDetail'])->name('addMutasiDetail');


                    
                });

        //END SUB - PENGELUARAN


        });

// end prefix PURCHASING

// end prefix persediaan
    
});
