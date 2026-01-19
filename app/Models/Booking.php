<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // protected $fillable = [
    //     'nama_penyewa',
    //     'nomor_lapangan',
    //     'tanggal_sewa',
    //     'jam_awal_sewa',
    //     'jam_akhir_sewa',
    //     'harga_sewa',
    //     'total_harga_sewa',
    //     'status',
    // ];

    protected $guarded = ['id'];
    
}
