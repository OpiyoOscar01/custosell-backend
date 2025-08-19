<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $response = $this->authService->register($request->all());
            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Login a user.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function login(Request $request)
    {
        $this->authService->login($request->all());
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);
    }
}
