<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembeliandBar extends Model
{
    use HasFactory;
    protected $table = 'pembeliand';
    protected $primaryKey = 'pmbdid';
    protected $fillable = [
        'pmbdid',
        'pmbdparent',
        'pmbdbrg',
        'pmbdbrgn',
        'pmbdjumlah',
        'pmbdposisi',
        'user_created',
        'pmbdket',
        'pmbddivisi',
        'cabang',

    ]; 
}
