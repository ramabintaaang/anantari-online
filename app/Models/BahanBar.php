<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBar extends Model
{
     use HasFactory;
    protected $table = 'bahan_bar';
    protected $primaryKey = 'bhnid';
    protected $fillable = [
        'bhnid',
        'bhnnama',
        'bhnsatuan',
        'bhnuser',
        'bhnsaldo',
        'created_at',
        'updated_at',
        'bhnmin',
        'bhnmax',
        'bhnsupp',
        'cabang',
    ]; 
}
