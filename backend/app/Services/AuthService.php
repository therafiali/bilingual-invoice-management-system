<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
public function createUser(array $data):User
{
    $user = User::create([

        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'] ?? 'user', 
        'password' => Hash::make($data['password'])

    ]);

    return $user;
}
}