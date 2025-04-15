<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksidBahan extends Model
{
    use HasFactory;
    protected $table = 'transaksi_barang';
    protected $primaryKey = 'tnsbid';
    protected $fillable = [
        'tnsbid',
        'tnsbparent',
        'tnsbsubparent',
        'tnsbbahan',
        'tnsbjumlah',
        'user_created',
        'tnsbposisi',
        'cabang',
    ]; 
}
