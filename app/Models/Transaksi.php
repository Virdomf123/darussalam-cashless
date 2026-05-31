<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * Daftar ini harus sesuai dengan kolom yang ada di migration transaksis.
     */
    protected $fillable = [
        'nim',
        'produk',
        'harga',
        'kategori',
    ];
}