<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::firstWhere('email', $email);

    public function deleteUserTokens(User $user)
    {
        return $user->tokens()->delete();
    }
}
