<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangBahan extends Model
{
    use HasFactory;
    protected $table = 'barang_bahan';
    protected $primaryKey = 'bhanid';
    protected $fillable = [
        'bhanid',
        'bhankuantiti',
        'bhanbarang',
        'bhnid',
        'bhannama',
        'bhansatuan',
        'user_created',
        'cabang',
    ]; 
}
