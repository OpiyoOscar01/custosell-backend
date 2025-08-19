<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ToJsonResource;
use Illuminate\Http\Request;
use app\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $response = [];
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $response['user'] = $user;

        $token = $user->createToken(config('app.name'))->plainTextToken;

        $response['token'] = $token;
        $response['message'] = 'User created successfully';
        return new ToJsonResource($response);
    }
    public function login(Request $request)
    {
        $response = [];
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        $$response['message'] = 'Logged in successfully';
        return new ToJsonResource($response);
    }
}
