<?php

namespace App\Repositories;

use App\Models\Booking;

class LapanganBadmintonRepository
{
    protected $lapanganBadmintonModel;

    public function __construct(Booking $lapanganBadmintonModel)
    {
        $this->lapanganBadmintonModel = $lapanganBadmintonModel;
    }

    public function store(array $data)
    {
        return $this->lapanganBadmintonModel->create($data);
    }

    public function filterLapangan(string $tanggal)
    {
        return Booking::whereDate('tanggal_sewa', $tanggal)->orderBy('nomor_lapangan')->orderBy('jam_awal_sewa')->get();
    }

    public function searchByName(string $query)
    {
        return Booking::whereRaw('LOWER(nama_penyewa) LIKE ?', ['%' . strtolower($query) . '%'])->paginate(10);
    }
}
