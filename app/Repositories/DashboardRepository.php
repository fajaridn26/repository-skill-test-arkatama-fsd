<?php

namespace App\Repositories;

use App\Models\Booking;

class DashboardRepository
{
    protected $dashboardModel;

    // public function __construct(Booking $dashboardModel)
    // {
    //     $this->dashboardModel = $dashboardModel;
    // }

    // public function filterLapangan(string $tanggal)
    // {
    //     return Booking::whereDate('tanggal_sewa', $tanggal)->orderBy('nomor_lapangan')->orderBy('jam_awal_sewa')->get();
    // }

    // public function filterHari(string $tanggal)
    // {
    //     return Booking::whereDate('tanggal_sewa', $tanggal)
    //         ->sum('total_harga_sewa');
    // }

    // public function filterBulan(string $bulan, $tahun)
    // {
    //     return Booking::whereMonth('tanggal_sewa', $bulan)->whereYear('tanggal_sewa', $tahun)
    //         ->sum('total_harga_sewa');
    // }

    // public function filterTahun(string $tahun)
    // {
    //     return Booking::whereYear('tanggal_sewa', $tahun)
    //         ->sum('total_harga_sewa');
    // }

    // public function grafikPendapatan(string $tahun)
    // {
    //     return Booking::selectRaw('EXTRACT(MONTH FROM tanggal_sewa) as bulan, SUM(total_harga_sewa) as total')
    //     ->whereYear('tanggal_sewa', $tahun)
    //     ->groupBy('bulan')
    //     ->orderBy('bulan')
    //     ->get();
    // }
}
