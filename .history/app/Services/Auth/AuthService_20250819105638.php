<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\AuthInterface;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public $authInterface;

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
    public function checkUserPresence(string $email): bool
    {
        return $this->authInterface->exists('email', $email);
    }
}
