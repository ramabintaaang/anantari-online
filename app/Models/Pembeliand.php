<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeliand extends Model
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
        'pmbdsupp',
        'pmbddivisi',
        'pmbd_delete',
        'pmbd_delete_user',
        'pmbdtotal',
        'cabang',
        'pmbdtgl',
        'pmbdbayar',

    ]; 
}
