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
    public function register(Request $request) {}
    /**
     * Login a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) {}

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
