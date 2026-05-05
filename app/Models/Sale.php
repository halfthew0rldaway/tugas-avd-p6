<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['hari', 'tanggal', 'kegiatan', 'curah_hujan', 'penjualan'];
}
