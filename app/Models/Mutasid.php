<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasid extends Model
{
    use HasFactory;
    protected $table = 'mutasid';
    protected $primaryKey = 'mutdid';
    protected $fillable = [
        'mutdid',
        'mutdparent',
        'mutdbahan',
        'mutdket',
        'mutdjumlah',
        'user_created',
        'mutdposisi',
        'cabang',
        'mutdbahan_tujuan',
    ]; 
}
