<?php

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;

interface AuthInterface
{
    public function create(array $data);
}
