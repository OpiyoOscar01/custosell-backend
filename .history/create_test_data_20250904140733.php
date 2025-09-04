<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Unit;
use App\Models\Brand;

// Create units
$units = [
    ['name' => 'Piece', 'abbreviation' => 'pcs', 'is_active' => true],
    ['name' => 'Kilogram', 'abbreviation' => 'kg', 'is_active' => true],
    ['name' => 'Meter', 'abbreviation' => 'm', 'is_active' => true],
    ['name' => 'Liter', 'abbreviation' => 'l', 'is_active' => true],
];

foreach ($units as $unitData) {
    Unit::create($unitData);
    echo "Unit created: " . $unitData['name'] . "\n";
}

// Create brands
$brands = [
    ['name' => 'Generic Brand', 'is_active' => true],
    ['name' => 'Premium Brand', 'is_active' => true],
];

foreach ($brands as $brandData) {
    Brand::create($brandData);
    echo "Brand created: " . $brandData['name'] . "\n";
}

echo "Test data created successfully!\n";
