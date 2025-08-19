<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";

// Test login route
echo "Testing LOGIN route:\n";
$loginRequest = Illuminate\Http\Request::create("/api/auth/login", "POST", [
    "email" => "test@example.com",
    "password" => "password123"
]);
$loginRequest->headers->set("Content-Type", "application/json");
$loginRequest->headers->set("Accept", "application/json");
$loginResponse = $app->handle($loginRequest);
echo "Status Code: " . $loginResponse->getStatusCode() . "\n";
echo "Response: " . $loginResponse->getContent() . "\n\n";

// Test register route
echo "Testing REGISTER route:\n";
$registerRequest = Illuminate\Http\Request::create("/api/auth/register", "POST", [
    "name" => "Test User",
    "email" => "newtest@example.com",
    "password" => "password123",
    "password_confirmation" => "password123"
]);
$registerRequest->headers->set("Content-Type", "application/json");
$registerResponse = $app->handle($registerRequest);
echo "Status Code: " . $registerResponse->getStatusCode() . "\n";
echo "Response: " . $registerResponse->getContent() . "\n";
