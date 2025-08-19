<?php

// Test authentication endpoints
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Testing Authentication Endpoints ===\n\n";

// Test 1: Login with non-existent user
echo "1. Testing login with non-existent user:\n";
$request = Illuminate\Http\Request::create('/api/auth/login', 'POST', [
    'email' => 'nonexistent@example.com',
    'password' => 'password123'
]);
$request->headers->set('Accept', 'application/json');

try {
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    echo "   Response: " . $response->getContent() . "\n\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Register a test user first
echo "2. Registering a test user:\n";
$request = Illuminate\Http\Request::create('/api/auth/register', 'POST', [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
]);
$request->headers->set('Accept', 'application/json');

try {
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    echo "   Response: " . $response->getContent() . "\n\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n\n";
}

// Test 3: Login with correct credentials
echo "3. Testing login with correct credentials:\n";
$request = Illuminate\Http\Request::create('/api/auth/login', 'POST', [
    'email' => 'test@example.com',
    'password' => 'password123'
]);
$request->headers->set('Accept', 'application/json');

try {
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    echo "   Response: " . $response->getContent() . "\n\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n\n";
}

// Test 4: Login with wrong password
echo "4. Testing login with wrong password:\n";
$request = Illuminate\Http\Request::create('/api/auth/login', 'POST', [
    'email' => 'test@example.com',
    'password' => 'wrongpassword'
]);
$request->headers->set('Accept', 'application/json');

try {
    $response = $kernel->handle($request);
    echo "   Status: " . $response->getStatusCode() . "\n";
    echo "   Response: " . $response->getContent() . "\n\n";
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n\n";
}

echo "=== Test Complete ===\n";
