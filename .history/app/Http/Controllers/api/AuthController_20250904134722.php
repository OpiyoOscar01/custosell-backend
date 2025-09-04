<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ResendVerificationRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        // Core required fields from request
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        // Optional profile fields from request
        $optionalFields = [
            'phone',
            'employee_id',
            'date_of_birth',
            'gender',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'timezone',
            'locale',
            'hourly_rate',
            'is_active'
        ];

        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $userData[$field] = $request->get($field);
            }
        }

        // Set defaults for fields not provided in request (manually obtained)
        $userData['timezone'] = $userData['timezone'] ?? config('app.timezone', 'UTC');
        $userData['locale'] = $userData['locale'] ?? config('app.locale', 'en');
        $userData['is_active'] = $userData['is_active'] ?? true;
        $userData['last_login_at'] = now(); // Manually set current timestamp

        // Create user with all the processed data
        $user = User::create($userData);

        // Assign default role (manually obtained business logic)
        $user->assignRole('employee');

        // Create Bearer token for authentication
        $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user->load('roles', 'permissions', 'workspaces'),
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => 30 * 24 * 60 * 60, // 30 days in seconds
            'message' => 'User registered successfully'
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Authenticate using the request class method
        $request->authenticate();

        // Get authenticated user using the email from request
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication failed'
            ], 401);
        }

        // Check if user is active
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is disabled. Please contact administrator.'
            ], 403);
        }

        // Update last login timestamp (manually obtained)
        $user->update([
            'last_login_at' => now()
        ]);

        // Handle remember_me from request
        $expirationDays = $request->boolean('remember_me') ? 365 : 30;

        // Create Bearer token with proper expiration
        $token = $user->createToken(
            'auth_token',
            ['*'],
            now()->addDays($expirationDays)
        )->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user->load('roles', 'permissions', 'workspaces'),
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $expirationDays * 24 * 60 * 60, // Convert to seconds
            'remember_me' => $request->boolean('remember_me'),
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

        // Basic profile fields from request
        $fieldsFromRequest = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'employee_id',
            'date_of_birth',
            'gender',
            'address',
            'city',
            'state',
            'country',
            'postal_code',
            'timezone',
            'locale',
            'hourly_rate',
            'preferences'
        ];

        // Process fields received from request
        foreach ($fieldsFromRequest as $field) {
            if ($request->filled($field)) {
                $updateData[$field] = $request->get($field);
            }
        }

        // Handle password change (manually validated)
        if ($request->filled('password')) {
            // Verify current password first
            if (!$request->filled('current_password')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is required to change password',
                    'errors' => ['current_password' => ['Current password is required']]
                ], 422);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ], 422);
            }

            // Hash new password (manually obtained)
            $updateData['password'] = Hash::make($request->password);
        }

        // Add manually obtained fields
        if (!empty($updateData)) {
            $updateData['updated_at'] = now();
        }

        // Update user with processed data
        $user->update($updateData);

        // Load fresh user data with relationships
        $freshUser = $user->fresh()->load('roles', 'permissions', 'workspaces');

        return response()->json([
            'success' => true,
            'user' => $freshUser,
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

    /**
     * Send password reset link to email
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Get email from request
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email',
                'email' => $request->email
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to send password reset link',
            'errors' => ['email' => [trans($status)]]
        ], 400);
    }

    /**
     * Reset password with token
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        // Get all required fields from request
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');

        $status = Password::reset(
            $credentials,
            function (User $user, string $password) use ($request) {
                // Update user with new password (manually obtained)
                $user->forceFill([
                    'password' => Hash::make($password),
                    'last_login_at' => now(), // Set login time since they'll be logged in
                    'updated_at' => now()
                ]);

                $user->save();

                // Revoke all existing tokens for security (manually obtained)
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Get the user and create a new token
            $user = User::where('email', $request->email)->first();

            if ($user) {
                // Create new Bearer token
                $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Password has been reset successfully',
                    'user' => $user->load('roles', 'permissions', 'workspaces'),
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 30 * 24 * 60 * 60
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to reset password',
            'errors' => ['email' => [trans($status)]]
        ], 400);
    }

    /**
     * Verify email address
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        // Get user ID from route parameter (manually obtained)
        $userId = $request->route('id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Get hash from route parameter and verify (manually obtained)
        $hash = $request->route('hash');
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification link'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email already verified',
                'user' => $user->load('roles', 'permissions')
            ]);
        }

        // Mark email as verified and trigger event (manually obtained)
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'user' => $user->fresh()->load('roles', 'permissions')
        ]);
    }

    /**
     * Resend email verification notification
     */
    public function resendVerification(ResendVerificationRequest $request): JsonResponse
    {
        // Get email from request
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found with this email address'
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified'
            ], 400);
        }

        // Send verification notification (manually obtained)
        $user->sendEmailVerificationNotification();

        return response()->json([
            'success' => true,
            'message' => 'Verification email sent successfully',
            'email' => $request->email
        ]);
    }
}
