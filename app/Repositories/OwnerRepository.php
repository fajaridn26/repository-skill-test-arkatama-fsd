<?php

namespace App\Repositories;

use App\Models\Owner;

class OwnerRepository
{
    protected $ownerRepository;

    public function __construct(Owner $ownerRepository)
    {
        $this->ownerRepository = $ownerRepository;
    }

    public function createOwner(array $ownerData)
    {
        return $this->ownerRepository->create($ownerData);
    }

    public function getValidOwners()
    {
        return Owner::select('id', 'name', 'phone')
            ->orderBy('name')
            ->get();
    }

    public function findByEmail(string $email)
    {
        return $this->ownerRepository->where('email', $email)->first();
    }
}
