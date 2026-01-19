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

    public function findById($id)
    {
        return Pet::findOrFail($id);
    }

    public function store(array $data)
    {
        return $this->hewanModel->create($data);
    }

    public function searchByName(string $query)
    {
        return Pet::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($query) . '%'])->paginate(10);
    }

    public function delete($id)
    {
        $pet = Pet::findOrFail($id);
        return $pet->delete();
    }
}
