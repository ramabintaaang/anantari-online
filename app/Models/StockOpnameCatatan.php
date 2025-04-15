<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameCatatan extends Model
{
    use HasFactory;
    protected $table = 'stock_opname_catatan';
    protected $primaryKey = 'sopcid';
    protected $fillable = [
        'sopcid',
        'sopcket',
        'sopcuser',
        'sopcbahan',
        'sopcbahann',
        'sopctgl',
        'sopcsaldo',
        'sopcfisik',
        'created_at',
        'updated_at',
        'cabang',
    ]; 
}
