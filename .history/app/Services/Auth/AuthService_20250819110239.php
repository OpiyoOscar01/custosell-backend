<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Interfaces\Auth\AuthInterface;

class AuthService
{
    /**
     * Create a new class instance.
     */
    protected $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }
    public function create(array $data)
    {
        return $this->authInterface->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    public function checkUserPresence(string $email): User
    {
        return $this->authInterface->exists('email', $email);
    }
}
