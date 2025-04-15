<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBarang extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'stock_barang';
    protected $primaryKey = 'sbid';
    protected $fillable = [
        'sbid',
        'sbparent',
        'sbbahan',
        'sbjenis',
        'sbmasuk',
        'sbkeluar',
        'sbadjust',
        'created_at',
        'updated_at',
        'sbgudang',
        'sbcabang',
        'sbuser',
    ]; 
}
