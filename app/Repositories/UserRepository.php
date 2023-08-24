<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function create(
        string $name,
        string $email,
        string $password
    ): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }
}
