<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => $request->get('is_active', true), // Default to active
        ];

        // Add optional fields if provided
        $optionalFields = [
            'phone', 'employee_id', 'date_of_birth', 'gender', 
            'address', 'city', 'state', 'country', 'postal_code', 
            'timezone', 'locale', 'hourly_rate'
        ];

        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $userData[$field] = $request->get($field);
            }
        }

        // Set default timezone and locale if not provided
        if (!$request->filled('timezone')) {
            $userData['timezone'] = config('app.timezone', 'UTC');
        }
        if (!$request->filled('locale')) {
            $userData['locale'] = config('app.locale', 'en');
        }

        $user = User::create($userData);

        // Assign default role
        $user->assignRole('employee');

        // Update last login timestamp
        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user->load('roles', 'permissions'),
            'token' => $token,
            'token_type' => 'Bearer',
            'message' => 'User registered successfully'
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        
        // Update last login timestamp
        $user->update(['last_login_at' => now()]);
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user->load('roles', 'permissions'),
            'token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Login successful'
        ]);
    }

    /**
     * Get the authenticated user
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()->load('roles', 'permissions'),
            'message' => 'User retrieved successfully'
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $updateData = [];

        // Basic profile fields
        $basicFields = [
            'first_name', 'last_name', 'email', 'phone', 'employee_id',
            'date_of_birth', 'gender', 'address', 'city', 'state', 
            'country', 'postal_code', 'timezone', 'locale', 'hourly_rate',
            'preferences'
        ];

        foreach ($basicFields as $field) {
            if ($request->filled($field)) {
                $updateData[$field] = $request->get($field);
            }
        }

        // Handle password change
        if ($request->filled('password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ], 422);
            }
            
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($updateData);

        return response()->json([
            'success' => true,
            'user' => $user->fresh()->load('roles', 'permissions'),
            'message' => 'Profile updated successfully'
        ]);
    }

    /**
     * Logout user (Revoke the token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all devices successfully'
        ]);
    }
}
