<?php

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function create(array $data);
    public function exists(string $field, string $value): bool;
}
