<?php
require_once __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";

$request = Illuminate\Http\Request::create("/api/auth/login", "POST", [
    "email" => "test@example.com",
    "password" => "password123"
]);

$request->headers->set("Content-Type", "application/json");
$response = $app->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
echo "Response: " . $response->getContent() . "\n";
