<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function signUp(array $userData)
    {
        $userData['password'] = Hash::make($userData['password']);

        return $this->userRepository->createUser($userData);
    }

    public function signIn(array $credentials)
    {
        return Auth::attempt($credentials);
    }

    public function signOut(): void
    {
        Auth::logout();
    }
}
