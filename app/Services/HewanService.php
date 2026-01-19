<?php

namespace App\Services;

use App\Models\Pet;
use App\Repositories\HewanRepository;
use Carbon\Carbon;

class HewanService
{
    protected $hewanRepository;

    public function __construct(HewanRepository $hewanRepository)
    {
        $this->hewanRepository = $hewanRepository;
    }

    public function index(array $data = [])
    {
        $perPage = $data['perPage'] ?? 10;
        $hewans = Pet::select(['id', 'owner_id', 'code', 'name', 'type', 'age', 'weight'])->orderBy('created_at', 'desc')->paginate($perPage);

        if (!empty($data['ajax']) && $data['ajax'] === true) {
            return response()->json($hewans);
        }
        return $hewans;
    }

    public function store(array $data)
    {
        $urut = Pet::count() + 1;
        $time = Carbon::now()->format('Hi');
        $ownerId = str_pad($data['owner_id'], 4, '0', STR_PAD_LEFT);
        $nomor = str_pad($urut, 4, '0', STR_PAD_LEFT);
        $code = $time . $ownerId . $nomor;

        $hewanData = [
            'owner_id' => $data['owner_id'],
            'code' => $code,
            'name' => strtoupper($data['name']),
            'type' => strtoupper($data['type']),
            'age' => $data['age'],
            'weight' => $data['weight'],
        ];

        return $this->hewanRepository->store($hewanData);
    }

    public function search(string $query)
    {
        if (!$query) {
            return collect();
        }

        return $this->hewanRepository->searchByName($query);
    }
}
