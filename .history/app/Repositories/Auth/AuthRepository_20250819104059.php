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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
    }

    public function login(array $credentials): array
    {
        $response = [];
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken(config('app.name') ?: 'auth-token')->plainTextToken;

        $response['user'] = $user;
        $response['token'] = $token;
        $response['message'] = 'Logged in successfully';

        return $response;
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
