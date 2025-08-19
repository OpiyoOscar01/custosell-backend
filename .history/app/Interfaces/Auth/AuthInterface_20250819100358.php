<?php

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function register(array $data): array;
    public function login(array $credentials): array;
    public function logout(Request $request): array;
}
