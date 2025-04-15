<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;


class BahanExportGudangBesar implements FromCollection, WithHeadings,WithStyles,ShouldAutoSize,WithCustomStartCell,WithEvents
{
    protected $bahan;
    protected $startDate;
    protected $endDate;
    protected $gudang;


    public function __construct($bahan, $startDate, $endDate,$gudang)
    {
        $this->bahan = $bahan;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->gudang = $gudang;
    }

    public function styles(Worksheet $sheet)
    {
        return [
        // Style the first row as bold text.
        2    => ['font' => ['bold' => true]],
        ];
    }


    public function startCell(): string
    {
        return 'A2'; // Start headings from cell A2
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $gudangs = DB::table('gudang')
                    ->where('gudangid', $this->gudang)
                    ->first();  // Ambil satu data gudang

                // Jika data gudang ditemukan, gunakan nilai dari database, jika tidak gunakan nilai default
                $gudangMessage = $gudangs ? $gudangs->gudangn : 'Tidak ditemukan gudang';

                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:K1');  // Merge cells for the message
                $sheet->setCellValue('A1', 'File ini di export pada tanggal ' . date('Y-m-d h:m:s') . ', Pencarian untuk gudang : ' . $gudangMessage);  // Set message
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);  // Center the text
                $sheet->getStyle('A1')->getFont()->setBold(true);  // Make the text bold
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Barang',
            'Keterangan',
            'jenis',
            'masuk',
            'keluar',
            'adjust',
            'saldo',
            'tgl',
        ];
    }

    public function collection()
    {
        $bahan = $this->bahan;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $gudangz = $this->gudang;

        // Set saldo awal
    DB::statement('SET @saldo := 0');

    // Query saldo awal untuk setiap bahan
    $saldoAwal = DB::table('stock_barang as a')
        ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
        ->select(
            DB::raw('MAX(a.sbid) as sbid'),  // Menggunakan MAX atau FIRST untuk memilih satu sbid
            DB::raw('MAX(b.bhnnama) as bhnnama'),
            DB::raw("'Saldo Awal' AS keterangan"),
            DB::raw('NULL AS sbjenis'),
            DB::raw('NULL AS sbmasuk'),
            DB::raw('NULL AS sbkeluar'),
            DB::raw('NULL AS sbadjust'),
            DB::raw('MAX(a.sbsaldo) as sbsaldo'), // Menggunakan MAX atau FIRST untuk saldo
            DB::raw('MAX(a.created_at) AS tgl'),
            DB::raw("'' AS sumber")
        )
        ->whereIn('a.sbbahan', $bahan)
        ->where('a.sbgudang', $gudangz)
        ->where('a.created_at', '<=', $startDate)
        ->groupBy('b.bhnnama')  // Tidak perlu mengelompokkan berdasarkan sbid, cukup bhnnama saja
        ->orderBy('b.bhnnama')
        ->orderBy('a.created_at');  // Pastikan saldo awal diambil pertama kali untuk setiap bahan


    $transaksiPembelian = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('pembeliand as pmbd', 'pmbd.pmbdid', '=', 'a.sbparent')
    ->join('pembelian as pmb', 'pmb.pmbid', '=', 'pmbd.pmbdparent')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT('Pembelian-', pmb.pmbket) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'pmb.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereNotNull('pmb.pmbjenis')
    ->whereRaw("LEFT(a.sbparent, 3) = 'PMB'")
    ->whereBetween(DB::raw('DATE(pmb.created_at)'), [$startDate, $endDate]);
    
    
    $transaksiPengambilan = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('pembeliand as pmbd', 'pmbd.pmbdid', '=', 'a.sbparent')
    ->join('pembelian as pmb', 'pmb.pmbid', '=', 'pmbd.pmbdparent')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT('Pengambilan dari ',pmb.pmbdivisi,' - ',pmb.pmbket) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'pmb.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereNull('pmb.pmbjenis')
    ->whereRaw("LEFT(a.sbparent, 3) = 'PMB'")
    ->whereBetween(DB::raw('DATE(pmb.created_at)'), [$startDate, $endDate]);
    
    $transaksiPembuatanBarang = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('transaksid as tnsd', 'tnsd.tnsdid', '=', 'a.sbparent')
    ->join('transaksi as tns', 'tns.tnsid', '=', 'tnsd.tnsdparent')
    ->join('barang as brg','brg.brgid','=','tnsd.tnsdbarang')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT('Pembuatan barang-',tns.tnsnama,'-',brg.brgnama) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'tns.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereRaw("LEFT(a.sbparent, 3) = 'TNS'")
    ->whereBetween(DB::raw('DATE(tns.created_at)'), [$startDate, $endDate]);



    // Query untuk transaksi Mengolah Bahan
$transaksiMengolahBahan = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('bahan_olah as bho', 'bho.bhoid', '=', 'a.sbparent')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT('Mengolah Bahan-', bho.bhonama) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'a.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereRaw("LEFT(a.sbparent, 3) = 'BHO'")
    ->whereBetween(DB::raw('DATE(a.created_at)'), [$startDate, $endDate]);


    // Query untuk transaksi Stock Opname
$transaksiStockOpname = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('stock_opnamed as sopd', 'sopd.sopdid', '=', 'a.sbparent')
    ->join('stock_opname as sop', 'sop.sopid', '=', 'sopd.sopdparent')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT('Stock Opname-', sop.sopnama) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'sop.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereRaw("LEFT(a.sbparent, 3) = 'SOP'")
    ->whereBetween(DB::raw('DATE(sop.soptgl)'), [$startDate, $endDate]);


    // Query untuk transaksi Mutasi
$transaksiMutasi = DB::table('stock_barang as a')
    ->join('bahan as b', 'b.bhnid', '=', 'a.sbbahan')
    ->join('mutasid as mutd', 'mutd.mutdid', '=', 'a.sbparent')
    ->join('mutasi as mut', 'mut.mutaid', '=', 'mutd.mutdparent')
    ->join('jenis_mutasi as jmu', 'jmu.jmuid', '=', 'mut.mutajenis')
    ->select(
        'a.sbid',
        'b.bhnnama',
        DB::raw("CONCAT(jmu.jmunama, '-', mut.mutaket) AS keterangan"),
        'a.sbjenis',
        'a.sbmasuk',
        'a.sbkeluar',
        'a.sbadjust',
        'a.sbsaldo',
        'mut.created_at AS tgl',
        DB::raw("'' AS sumber")
    )
    ->whereIn('a.sbbahan', $bahan)
    ->where('a.sbgudang', $gudangz)
    ->whereRaw("LEFT(a.sbparent, 3) = 'MUT'")
    ->whereBetween(DB::raw('DATE(mut.mutatgl)'), [$startDate, $endDate]);
    // Gabungkan semua query menggunakan UNION ALL
    $query = $saldoAwal->unionAll($transaksiPembelian)
        ->unionAll($transaksiPengambilan)
        ->unionAll($transaksiPembuatanBarang)
        ->unionAll($transaksiMengolahBahan)
        ->unionAll($transaksiStockOpname)
        ->unionAll($transaksiMutasi)
        ->orderBy('bhnnama')
        ->orderBy('tgl');

    // Ambil hasilnya
    $results = $query->get();

    // Menampilkan hasil
    return $results;
        
    }
}
