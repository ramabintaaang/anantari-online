<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameD extends Model
{
    use HasFactory;
    protected $table = 'stock_opnamed';
    protected $primaryKey = 'sopid';
    protected $fillable = [
        'sopdid',
        'sopdparent',
        'sopdbahan',
        'sopdbahann',
        'sopdjumlah',
        'sopdposisi',
        'sopdket',
        'created_at',
        'updated_at',
        'user_created',
        'cabang',

    ]; 
}
