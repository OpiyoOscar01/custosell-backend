<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Interfaces\Auth\AuthInterface;

class AuthRepository implements AuthInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function exists(string $field, string $value): User
    {
        return  User::where($field, $value)->first();
    }
}
