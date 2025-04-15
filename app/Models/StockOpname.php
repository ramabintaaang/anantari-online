<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;
    protected $table = 'stock_opname';
    protected $primaryKey = 'sopid';
    protected $fillable = [
        'sopid',
        'sopnama',
        'created_at',
        'updated_at',
        'user_created',
        'soptgl',
        'cabang',
        'sopgudang',

    ]; 
}
