<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Santri extends Model
{
    // Kolom yang diizinkan untuk pengisian massal (Mass Assignment)
    protected $fillable = ['nama', 'nim', 'saldo', 'tabungan'];
}