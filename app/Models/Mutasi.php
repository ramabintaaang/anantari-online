<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;
    protected $table = 'mutasi';
    protected $primaryKey = 'mutaid';
    protected $fillable = [
        'mutaid',
        'mutaket',
        'mutatgl',
        'mutajenis',
        'user_created',
        'mutastore',
        'mutagudang',
        'mutastatus',
        'cabang',
    ]; 
}
