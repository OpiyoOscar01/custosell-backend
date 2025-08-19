<?php

namespace App\Repositories\Auth;

use App\Models\User;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements AuthInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function exists(string $field, string $value): bool
    {
        return User::where($field, $value)->exists();
    }
    public function logout(Request $request): array
    {
        $response = [];

        // Check if user is authenticated via Sanctum
        if ($request->bearerToken()) {
            $user = $request->user('sanctum');
            if ($user) {
                $user->tokens()->delete();
                $response['message'] = 'Logged out successfully';
                return $response;
            }
        }

        // User is not authenticated
        $response['message'] = 'You are not logged in';
        return $response;
    }
}
