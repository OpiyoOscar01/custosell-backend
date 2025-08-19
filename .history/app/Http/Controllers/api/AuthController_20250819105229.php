<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Auth\AuthService;
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
    public $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(Request $request)
    {
        $response = [];
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->authService->create($request->all());

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
     * @return JsonResponse
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

        return response()->json($response, 200);
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return JsonResponse
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
                return response()->json($response, 200);
            }
        }

        // User is not authenticated
        $response['message'] = 'You are not logged in';
        return response()->json($response, 401);
    }
}
