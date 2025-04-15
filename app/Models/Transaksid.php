<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksid extends Model
{
    use HasFactory;
    protected $table = 'transaksid';
    protected $primaryKey = 'tnsdid';
    protected $fillable = [
        'tnsdid',
        'tnsdparent',
        'tnsdbarang',
        'tnsdjumlah',
        'tnsdtotal',
        'tnsdketerangan',
        'tnsdstatus',
        'user_created',
        'tnsddivisi',
        'cabang',
    ]; 
}
