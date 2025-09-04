<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'first_name' => 'Admin',
    'last_name' => 'User', 
    'email' => 'admin@test.com',
    'password' => Hash::make('password123'),
    'is_active' => true
]);

$user->assignRole('admin');

echo "Admin user created with ID: " . $user->id . "\n";
echo "Email: " . $user->email . "\n";
