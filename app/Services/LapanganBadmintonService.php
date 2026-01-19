<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Repositories\LapanganBadmintonRepository;
use Carbon\Carbon;

class LapanganBadmintonService
{
    protected $lapanganBadmintonRepository;

    public function __construct(LapanganBadmintonRepository $lapanganBadmintonRepository)
    {
        $this->lapanganBadmintonRepository = $lapanganBadmintonRepository;
    }

    public function index(array $data = [])
    {
        $perPage = $data['perPage'] ?? 10;
        $bookings = Booking::select(['id', 'nomor_lapangan', 'nama_penyewa', 'tanggal_sewa', 'jam_awal_sewa', 'jam_akhir_sewa', 'total_harga_sewa', 'status'])->orderBy('created_at', 'desc')->paginate($perPage);

        if (!empty($data['ajax']) && $data['ajax'] === true) {
            return response()->json($bookings);
        }
        return $bookings;
    }

    public function store(array $data)
    {
        $jamAwal = (int) $data['jam_awal_sewa'];
        $jamAkhir = (int) $data['jam_akhir_sewa'];
        $durasi = $jamAkhir - $jamAwal;

        $total = $durasi * $data['harga_sewa'];

        $durasi = $jamAkhir - $jamAwal;

        $bookingData = [
            'nama_penyewa' => $data['nama_penyewa'],
            'nomor_lapangan' => $data['nomor_lapangan'],
            'tanggal_sewa' =>  $data['tanggal_sewa'],
            'jam_awal_sewa' => $jamAwal,
            'jam_akhir_sewa' => $jamAkhir,
            'harga_sewa' => $data['harga_sewa'],
            'total_harga_sewa' => $total,
            'status' => 2, //Dipesan
        ];

        return $this->lapanganBadmintonRepository->store($bookingData);
    }

    public function filterJadwalLapangan(string $tanggal)
    {
        $tanggal = date('Y-m-d', strtotime($tanggal));

        return $this->lapanganBadmintonRepository->filterLapangan($tanggal);
    }

    public function search(string $query)
    {
        if (!$query) {
            return collect();
        }

        return $this->lapanganBadmintonRepository->searchByName($query);
    }
}
