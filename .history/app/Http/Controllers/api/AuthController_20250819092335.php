<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $token = $user->createToken(config('app.name') ?: 'auth-token')->plainTextToken;
        $response['token'] = $token;
        $response['message'] = 'User created successfully';
        
        return response()->json($response, 201);
    }
    /**
     * Login a user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $response = [];
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken(config('app.name') ?: 'auth-token')->plainTextToken;

        $response['user'] = $user;
        $response['token'] = $token;
        $response['message'] = 'Logged in successfully';
        
        // Option 1: Using ToJsonResource (wraps in 'data' key)
        return new ToJsonResource($response);
        
        // Option 2: Direct JSON response (uncomment to use)
        // return response()->json($response, 200);
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return JsonResource
     */
    public function logout(Request $request)
    {
        $response = [];

        // Check if user is authenticated via Sanctum
        if ($request->bearerToken()) {
            $user = $request->user('sanctum');
            if ($user) {
                $user->tokens()->delete();
                $response['message'] = 'Logged out successfully';
                return new ToJsonResource($response);
            }
        }

        // User is not authenticated
        $response['message'] = 'You are not logged in';
        return response()->json($response, 401);
    }
}
