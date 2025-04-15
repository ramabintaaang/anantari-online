<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'tnsid';
    protected $fillable = [
        'tnsid',
        'tnsnama',
        'tnstotal',
        'tnsbayar',
        'tnstatus',
        'tnsjenis',
        'user_created',
        'tnsdivisi',
        'cabang',
    ]; 
}
