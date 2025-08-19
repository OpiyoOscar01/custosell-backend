<?php

namespace App\Interfaces\Auth;

interface AuthInterface
{
    public function register(array $data): array;
    public function login(array $credentials): array;
    public function logout(): array;
}
