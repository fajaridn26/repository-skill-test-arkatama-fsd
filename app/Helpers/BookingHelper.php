<?php

use App\Models\Booking;

if (!function_exists('isBooked')) {
    function isBooked($tanggal, $nomorLapangan, $jam)
    {
        return Booking::where('tanggal_sewa', $tanggal)
            ->where('nomor_lapangan', $nomorLapangan)
            ->where('jam_awal_sewa', '<=', $jam)
            ->where('jam_akhir_sewa', '>', $jam)
            ->exists();
    }
}
