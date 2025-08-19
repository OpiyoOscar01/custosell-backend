<?php

namespace App\Interfaces\Auth;

use App\Models\User;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function create(array $data);
    public function exists(string $field, string $value): User;
}
