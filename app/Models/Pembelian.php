<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    protected $table = 'pembelian';
    protected $primaryKey = 'pmbid';
    protected $fillable = [
        'pmbid',
        'pmbket',
        'pmbjenis',
        'pmbsupp',
        'user_created',
        'created_at',
        'updated_at',
        'pmbtgl',
        'pmbdivisi',
        'pmbgudang',
        'cabang',
        'pmbtotal',
    ]; 
}
