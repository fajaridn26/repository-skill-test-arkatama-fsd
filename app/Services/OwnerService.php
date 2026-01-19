<?php

namespace App\Services;

use App\Models\Owner;
use App\Repositories\OwnerRepository;

class OwnerService
{
    protected $ownerRepository;
    public function __construct(OwnerRepository $ownerRepository)
    {
        $this->ownerRepository = $ownerRepository;
    }

    public function index(array $data = [])
    {
        $perPage = $data['perPage'] ?? 10;
        $owners = Owner::select(['id', 'name', 'phone', 'email', 'address'])->orderBy('created_at', 'desc')->paginate($perPage);

        if (!empty($data['ajax']) && $data['ajax'] === true) {
            return response()->json($owners);
        }
        return $owners;
    }

    public function store(array $data)
    {
        $owner = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
        ];

        return $this->ownerRepository->createOwner($owner);
    }

    public function getValidOwners()
    {
        $owners = $this->ownerRepository->getValidOwners();

        return $owners;
    }
}
