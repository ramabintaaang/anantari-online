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


class BahanExportGudangBesarCopy implements FromCollection, WithHeadings,WithStyles,ShouldAutoSize,WithCustomStartCell,WithEvents
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
                $gudangMessage = '';
                if ($this->gudang === 'GDG002') {
                    $gudangMessage = 'GUDANG BAR';
                } elseif ($this->gudang === 'GDG003') {
                    $gudangMessage = 'GUDANG KITCHEN';
                } else {
                    $gudangMessage = 'GUDANG BESAR';  // Default message for other values
                }

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
            'Transaksi',
            'Parent',
            'Keterangan',
            'bahan',
            'Tanggal_buat',
            'user',
            'pemasukan',
            'pengeluaran',
            'Saldo_sebelumnya',
            'Stock_Opname',
            'Total',
        ];
    }

    public function collection()
    {
        $bahan = $this->bahan;
        $startDate = $this->startDate;
        $endDate = $this->endDate;


// Build the query step by step
$query1 = DB::table('pembeliand as a')
    ->select([
        'a.pmbdid as transaksi',
        'a.pmbdparent as parent',
        DB::raw("CONCAT('Order gudang besar - ', c.pmbket) AS keterangan"),
        'a.pmbdbrgn as bahan',
        'a.created_at as tanggal_buat',
        'a.user_created as user',
        'a.pmbdjumlah as pemasukan',
        DB::raw('0 as pengeluaran'),
        'a.pmbdposisi as saldo_sebelumnya',
        DB::raw('0 as stock_opname'),
        DB::raw('(COALESCE(a.pmbdposisi, 0) + a.pmbdjumlah) as total')
    ])
    ->leftJoin('bahan as b', 'a.pmbdbrg', '=', 'b.bhnid')
    ->leftJoin('pembelian as c', 'c.pmbid', '=', 'a.pmbdparent')
    ->whereIn('a.pmbdbrg', $bahan) // replace with actual $bahan values
    ->whereBetween(DB::raw('DATE(a.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
    // ->whereNotNull('a.pmbddivisi')
    ->where(DB::raw("LEFT(a.pmbdbrg, 3)"), '=', 'BHN');

// Additional unions are appended to the main query using the unionAll method
$query1->unionAll(
    DB::table('pembeliand as a')
        ->select([
            'a.pmbdid as transaksi',
            'a.pmbdparent as parent',
            DB::raw("'olah bahan' as keterangan"),
            'a.pmbdbrgn as bahan',
            'a.created_at as tanggal_buat',
            'a.user_created as user',
            'a.pmbdjumlah as pemasukan',
            DB::raw('0 as pengeluaran'),
            'a.pmbdposisi as saldo_sebelumnya',
            DB::raw('0 as stock_opname'),
            DB::raw('(COALESCE(a.pmbdposisi, 0) + a.pmbdjumlah) as total')
        ])
        ->leftJoin('bahan as b', 'a.pmbdbrg', '=', 'b.bhnid')
        ->leftJoin('pembelian as c', 'c.pmbid', '=', 'a.pmbdparent')
        ->whereIn('a.pmbdbrg', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(a.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
        ->where(DB::raw("LEFT(a.pmbdbrg, 3)"), '=', 'BHO')
);

$query1->unionAll(
    DB::table('transaksid as a')
        ->select([
            'a.tnsdid as transaksi',
            'a.tnsdparent as parent',
            DB::raw("CONCAT('Membuat Produk - ', e.brgnama) AS keterangan"),
            'c.bhnnama as bahan',
            'a.created_at as tanggal_buat',
            'a.user_created as user',
            DB::raw('0 as pemasukan'),
            'b.tnsbjumlah as pengeluaran',
            'b.tnsbposisi as saldo_sebelumnya',
            DB::raw('0 as stock_opname'),
            DB::raw('(COALESCE(b.tnsbposisi, 0) - b.tnsbjumlah) as total')
        ])
        ->leftJoin('transaksi_bahan as b', 'b.tnsbparent', '=', 'a.tnsdid')
        ->leftJoin('bahan as c', 'b.tnsbbahan', '=', 'c.bhnid')
        // ->leftJoin('transaksi as d','d.tnsid','=','a.tnsdparent')
        ->leftJoin('barang as e','e.brgid','=','a.tnsdbarang')
        ->whereIn('b.tnsbbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(b.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
        ->where(DB::raw("LEFT(b.tnsbbahan, 3)"), '=', 'BHN')
);

// buat bahan olah tapi dari bahan olah juga , lhaiske pye jal
$query1->unionAll(
    DB::table('transaksi_bahan as a')
        ->select([
            'a.tnsbid as transaksi',
            'a.tnsbparent as parent',
            DB::raw("CONCAT('Membuat bahan olah - ', c.bhonama) as keterangan"),
            'd.bhonama as bahan',
            'a.created_at as tanggal_buat',
            'a.user_created as user',
            DB::raw('0 as pemasukan'),
            'a.tnsbjumlah as pengeluaran',
            'a.tnsbposisi as saldo_sebelumnya',
            DB::raw('0 as stock_opname'),
            DB::raw('(COALESCE(a.tnsbposisi, 0) - a.tnsbjumlah) as total')
        ])
        ->leftJoin('bahan as b', 'a.tnsbbahan', '=', 'b.bhnid')
        ->leftJoin('bahan_olah as c', 'c.bhoid', '=', 'a.tnsbparent')
        ->leftJoin('bahan_olah as d', 'd.bhoid', '=', 'a.tnsbbahan')
        ->where(DB::raw("LEFT(a.tnsbid, 4)"), '=', 'TNBO')
        ->whereIn('a.tnsbbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(a.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
        ->whereNotNull('d.bhonama')
);

//buat bahan olah tapi dari bahan dasar
$query1->unionAll(
    DB::table('transaksi_bahan as a')
        ->select([
            'a.tnsbid as transaksi',
            'a.tnsbparent as parent',
            DB::raw("CONCAT('Membuat bahan olah - ', c.bhonama) as keterangan"),
            'b.bhnnama as bahan',
            'a.created_at as tanggal_buat',
            'a.user_created as user',
            DB::raw('0 as pemasukan'),
            'a.tnsbjumlah as pengeluaran',
            'a.tnsbposisi as saldo_sebelumnya',
            DB::raw('0 as stock_opname'),
            DB::raw('(COALESCE(a.tnsbposisi, 0) - a.tnsbjumlah) as total')
        ])
        ->leftJoin('bahan as b', 'a.tnsbbahan', '=', 'b.bhnid')
        ->leftJoin('bahan_olah as c', 'c.bhoid', '=', 'a.tnsbparent')
        ->where(DB::raw("LEFT(a.tnsbid, 4)"), '=', 'TNBO')
        ->whereIn('a.tnsbbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(a.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
        ->whereNotNull('b.bhnnama')

    );

$query1->unionAll(
    DB::table('transaksid as a')
        ->select([
            'a.tnsdid as transaksi',
            'a.tnsdparent as parent',
            DB::raw("CONCAT('Membuat produk - ', d.brgnama, ' -', a.tnsdjumlah) as keterangan"),
            'c.bhonama as bahan',
            'a.created_at as tanggal_buat',
            'a.user_created as user',
            DB::raw('0 as pemasukan'),
            'b.tnsbjumlah as pengeluaran',
            'b.tnsbposisi as saldo_sebelumnya',
            DB::raw('0 as stock_opname'),
            DB::raw('(COALESCE(b.tnsbposisi, 0) - b.tnsbjumlah) as total')
        ])
        ->leftJoin('transaksi_bahan as b', 'b.tnsbparent', '=', 'a.tnsdid')
        ->leftJoin('bahan_olah as c', 'b.tnsbbahan', '=', 'c.bhoid')
        ->leftJoin('barang as d', 'd.brgid', '=', 'a.tnsdbarang')
        ->where(DB::raw("LEFT(b.tnsbbahan, 3)"), '=', 'BHO')
        ->whereIn('b.tnsbbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(b.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
);

$query1->unionAll(
    DB::table('stock_opname as a')
        ->select([
            'b.sopdid as transaksi',
            'b.sopdparent as parent',
            DB::raw("'ini apa 5' as keterangan"),
            'c.bhonama as bahan',
            'b.created_at as tanggal_buat',
            'b.user_created as user',
            DB::raw('0 as pemasukan'),
            DB::raw('0 as pengeluaran'),
            'b.sopdposisi as saldo_sebelumnya',
            'b.sopdjumlah as stock_opname',
            DB::raw("(CASE 
                WHEN (COALESCE(b.sopdposisi, 0) - b.sopdjumlah) > 0 
                    THEN CONCAT('-', (COALESCE(b.sopdposisi, 0) - b.sopdjumlah))
                WHEN (COALESCE(b.sopdposisi, 0) - b.sopdjumlah) < 0 
                    THEN CONCAT('', (COALESCE(b.sopdposisi, 0) - b.sopdjumlah))
                ELSE 'Seimbang'
            END) AS total")
        ])
        ->leftJoin('stock_opnamed as b', 'b.sopdparent', '=', 'a.sopid')
        ->leftJoin('bahan_olah as c', 'b.sopdbahan', '=', 'c.bhoid')
        ->where(DB::raw("LEFT(b.sopdbahan, 3)"), '=', 'BHO')
        ->whereIn('b.sopdbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(b.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
);

$query1->unionAll(
    DB::table('stock_opname as a')
        ->select([
            'b.sopdid as transaksi',
            'b.sopdparent as parent',
            DB::raw("CONCAT('Stock Opname - ', a.sopnama) as keterangan"),
            'c.bhnnama as bahan',
            'b.created_at as tanggal_buat',
            'b.user_created as user',
            DB::raw('0 as pemasukan'),
            DB::raw('0 as pengeluaran'),
            'b.sopdposisi as saldo_sebelumnya',
            'b.sopdjumlah as stock_opname',
            DB::raw("(CASE 
                WHEN (COALESCE(b.sopdposisi, 0) - b.sopdjumlah) > 0 
                    THEN CONCAT('-', (COALESCE(b.sopdposisi, 0) - b.sopdjumlah))
                WHEN (COALESCE(b.sopdposisi, 0) - b.sopdjumlah) < 0 
                    THEN CONCAT('', (COALESCE(b.sopdposisi, 0) - b.sopdjumlah))
                ELSE 'Seimbang'
            END) AS total")
        ])
        ->leftJoin('stock_opnamed as b', 'b.sopdparent', '=', 'a.sopid')
        ->leftJoin('bahan as c', 'b.sopdbahan', '=', 'c.bhnid')
        ->where(DB::raw("LEFT(b.sopdbahan, 3)"), '=', 'BHN')
        ->whereIn('b.sopdbahan', $bahan) // replace with actual $bahan values
        ->whereBetween(DB::raw('DATE(b.created_at)'), [$startDate,$endDate]) // replace with actual $startDate and $endDate values
);

// Execute the query
$combinedQuery = $query1->orderBy('bahan')->orderBy('tanggal_buat')->get();



        return $combinedQuery;
        
    }
}
