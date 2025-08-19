<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";

// Test login route
echo "Testing LOGIN route:\n";
$loginData = json_encode([
    "email" => "newtest@example.com",
    "password" => "password123"
]);

$loginRequest = Illuminate\Http\Request::create(
    "/api/auth/login", 
    "POST", 
    [], // Empty array for query parameters
    [], // Empty array for cookies
    [], // Empty array for files
    ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'], // Server variables
    $loginData // Request body
);

$loginResponse = $app->handle($loginRequest);
echo "Status Code: " . $loginResponse->getStatusCode() . "\n";
echo "Response: " . $loginResponse->getContent() . "\n\n";

// Test register route
echo "Testing REGISTER route:\n";
$registerData = json_encode([
    "name" => "Test User",
    "email" => "newtest@example.com",
    "password" => "password123",
    "password_confirmation" => "password123"
]);

$registerRequest = Illuminate\Http\Request::create(
    "/api/auth/register",
    "POST",
    [],
    [],
    [],
    ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
    $registerData
);

$registerResponse = $app->handle($registerRequest);
echo "Status Code: " . $registerResponse->getStatusCode() . "\n";
echo "Response: " . $registerResponse->getContent() . "\n";
