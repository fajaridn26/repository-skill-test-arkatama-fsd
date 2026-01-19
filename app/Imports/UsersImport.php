<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new User([
            'nama'     => $row['nama'],
            'email'    => $row['email'],
            'kelas'    => $row['kelas'],
            'angkatan' => $row['angkatan'],
            'role'    => 'Siswa',
            'password' => Hash::make('12345'),
        ]);
    }
}
