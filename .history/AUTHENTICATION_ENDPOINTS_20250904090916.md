# Authentication Endpoints Documentation

## Overview
The CustoSell backend now has a complete authentication system with comprehensive request validation, password reset functionality, email verification, and secure token management.

## Available Endpoints

### Public Authentication Endpoints (No Authentication Required)

#### 1. User Registration
- **Endpoint:** `POST /api/auth/register`
- **Request Class:** `RegisterUserRequest`
- **Description:** Register a new user with comprehensive profile information
- **Required Fields:** `first_name`, `last_name`, `email`, `password`, `password_confirmation`
- **Optional Fields:** `phone`, `employee_id`, `date_of_birth`, `gender`, `address`, `city`, `state`, `country`, `postal_code`, `timezone`, `locale`, `hourly_rate`, `is_active`
- **Response:** User object with roles/permissions and Bearer token

#### 2. User Login
- **Endpoint:** `POST /api/auth/login`
- **Request Class:** `LoginRequest`
- **Description:** Authenticate user and generate access token
- **Required Fields:** `email`, `password`
- **Optional Fields:** `remember_me` (boolean)
- **Response:** User object with roles/permissions and Bearer token

#### 3. Forgot Password
- **Endpoint:** `POST /api/auth/forgot-password`
- **Request Class:** `ForgotPasswordRequest`
- **Description:** Send password reset link to user's email
- **Required Fields:** `email`
- **Response:** Success message confirming email sent

#### 4. Reset Password
- **Endpoint:** `POST /api/auth/reset-password`
- **Request Class:** `ResetPasswordRequest`
- **Description:** Reset user password using token from email
- **Required Fields:** `token`, `email`, `password`, `password_confirmation`
- **Response:** Success message confirming password reset

#### 5. Verify Email
- **Endpoint:** `GET /api/auth/verify-email/{id}/{hash}`
- **Request Class:** None (URL parameters)
- **Description:** Verify user's email address using verification link
- **Parameters:** `id` (user ID), `hash` (verification hash)
- **Response:** Success message confirming email verification

#### 6. Resend Verification Email
- **Endpoint:** `POST /api/auth/resend-verification`
- **Request Class:** `ResendVerificationRequest`
- **Description:** Resend email verification notification
- **Required Fields:** `email`
- **Response:** Success message confirming verification email sent

### Protected Authentication Endpoints (Requires Bearer Token)

#### 7. Get Current User
- **Endpoint:** `GET /api/auth/me`
- **Request Class:** None
- **Description:** Get authenticated user's information
- **Authentication:** Bearer token required
- **Response:** User object with roles and permissions

#### 8. Update User Profile
- **Endpoint:** `PUT /api/auth/profile`
- **Request Class:** `UpdateProfileRequest`
- **Description:** Update authenticated user's profile information
- **Authentication:** Bearer token required
- **Optional Fields:** All profile fields + `current_password` and `password` for password change
- **Response:** Updated user object with roles/permissions

#### 9. Logout
- **Endpoint:** `POST /api/auth/logout`
- **Request Class:** None
- **Description:** Revoke current access token
- **Authentication:** Bearer token required
- **Response:** Success message confirming logout

#### 10. Logout from All Devices
- **Endpoint:** `POST /api/auth/logout-all`
- **Request Class:** None
- **Description:** Revoke all access tokens for the user
- **Authentication:** Bearer token required
- **Response:** Success message confirming logout from all devices

## Request Classes and Validation

### LoginRequest
```php
- email: required|email|max:255
- password: required|string|min:1
- remember_me: nullable|boolean
```

### RegisterUserRequest
```php
- first_name: required|string|max:255
- last_name: required|string|max:255
- email: required|string|email|max:255|unique:users
- password: required|string|min:8|confirmed
- phone: nullable|string|max:20
- employee_id: nullable|string|max:50|unique:users
- date_of_birth: nullable|date
- gender: nullable|in:male,female,other
- address: nullable|string|max:500
- city: nullable|string|max:100
- state: nullable|string|max:100
- country: nullable|string|max:100
- postal_code: nullable|string|max:20
- timezone: nullable|string|max:50
- locale: nullable|string|max:10
- hourly_rate: nullable|numeric|min:0
- is_active: nullable|boolean
```

### UpdateProfileRequest
```php
- All RegisterUserRequest fields (except password confirmation)
- current_password: required_with:password|string
- password: nullable|string|min:8|confirmed
```

### ForgotPasswordRequest
```php
- email: required|email|exists:users,email
```

### ResetPasswordRequest
```php
- token: required|string
- email: required|email|exists:users,email
- password: required|confirmed|Password::defaults()
```

### ResendVerificationRequest
```php
- email: required|email|exists:users,email
```

## Security Features

1. **Token-based Authentication:** Uses Laravel Sanctum for secure API token management
2. **Password Reset:** Secure token-based password reset with email verification
3. **Email Verification:** Users must verify their email addresses
4. **Rate Limiting:** Built-in protection against brute force attacks
5. **Input Validation:** Comprehensive validation for all requests
6. **Role-based Access:** Integration with Spatie Permission package
7. **Secure Logout:** Token revocation for single device or all devices
8. **Password Security:** Strong password requirements and hashing

## Authentication Flow

1. **Registration:** User registers → Email verification sent → User verifies email → Account active
2. **Login:** User provides credentials → Token generated → Access granted
3. **Password Reset:** User requests reset → Email sent → User clicks link → Password reset → All tokens revoked
4. **Profile Update:** Authenticated user updates profile → Password change requires current password verification

## Error Handling

All endpoints return consistent JSON responses with:
- `success`: Boolean indicating operation success
- `message`: Human-readable message
- `errors`: Validation errors (when applicable)
- `user`: User object (when applicable)
- `token`: Bearer token (for login/register)

## Middleware Protection

- Public routes: No authentication required
- Protected routes: `auth:sanctum` middleware ensures valid token
- Email verification: Can be enforced using `verified` middleware if needed

## Configuration

- Email verification is enabled (User model implements MustVerifyEmail)
- Password reset tokens expire after 60 minutes
- Password reset throttling: 60 seconds between requests
- All authentication settings configurable in `config/auth.php`
