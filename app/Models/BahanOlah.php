<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanOlah extends Model
{
     use HasFactory;
    protected $table = 'bahan_olah';
    protected $primaryKey = 'bhoid';
    protected $fillable = [
        'bhoid',
        'bhonama',
        'bhosatuan',
        'bhouser',
        'bhosaldo',
        'created_at',
        'updated_at',
        'bhomin',
        'bhomax',
        'user_update',
        'cabang',
        'bhogudang',
        'bhohasil',
    ]; 
}
