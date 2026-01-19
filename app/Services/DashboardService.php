<?php

namespace App\Services;

use App\Models\Booking;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function filterJadwalLapangan(string $tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));

        return $this->dashboardRepository->filterLapangan($tanggal);
    }

    public function filterHari(string $tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));

        return $this->dashboardRepository->filterHari($tanggal);
    }

    public function filterBulan(string $bulan, string $tahun)
    {
        return $this->dashboardRepository->filterBulan($bulan, $tahun);
    }

    public function filterTahun(string $tahun)
    {
        return $this->dashboardRepository->filterTahun($tahun);
    }

    public function grafikPendapatan(string $tahun)
    {
        $data = $this->dashboardRepository->grafikPendapatan($tahun);

        $result = array_fill(1, 12, 0);

        foreach ($data as $row) {
            $result[(int) $row->bulan] = (int) $row->total;
        }

        return array_values($result);
    }

}
