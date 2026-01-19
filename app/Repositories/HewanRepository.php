<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Pet;

class HewanRepository
{
    protected $hewanModel;

    public function __construct(Pet $hewanModel)
    {
        $this->hewanModel = $hewanModel;
    }

    public function store(array $data)
    {
        return $this->hewanModel->create($data);
    }

    public function searchByName(string $query)
    {
        return Pet::whereRaw('LOWER(nama_penyewa) LIKE ?', ['%' . strtolower($query) . '%'])->paginate(10);
    }
}
