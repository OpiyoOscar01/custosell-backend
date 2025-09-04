<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Unit;
use App\Models\Brand;

// Create units only if they don't exist
$units = [
    ['company_id' => 1, 'name' => 'Piece', 'short_name' => 'pcs', 'type' => 'piece', 'is_active' => true],
    ['company_id' => 1, 'name' => 'Kilogram', 'short_name' => 'kg', 'type' => 'weight', 'is_active' => true],
    ['company_id' => 1, 'name' => 'Meter', 'short_name' => 'm', 'type' => 'length', 'is_active' => true],
    ['company_id' => 1, 'name' => 'Liter', 'short_name' => 'l', 'type' => 'volume', 'is_active' => true],
];

foreach ($units as $unitData) {
    $existing = Unit::where('company_id', $unitData['company_id'])
                   ->where('short_name', $unitData['short_name'])
                   ->first();
    if (!$existing) {
        Unit::create($unitData);
        echo "Unit created: " . $unitData['name'] . "\n";
    } else {
        echo "Unit already exists: " . $unitData['name'] . "\n";
    }
}

// Create brands only if they don't exist
$brands = [
    ['company_id' => 1, 'name' => 'Generic Brand', 'slug' => 'generic-brand', 'is_active' => true],
    ['company_id' => 1, 'name' => 'Premium Brand', 'slug' => 'premium-brand', 'is_active' => true],
];

foreach ($brands as $brandData) {
    $existing = Brand::where('company_id', $brandData['company_id'])
                    ->where('slug', $brandData['slug'])
                    ->first();
    if (!$existing) {
        Brand::create($brandData);
        echo "Brand created: " . $brandData['name'] . "\n";
    } else {
        echo "Brand already exists: " . $brandData['name'] . "\n";
    }
}

echo "Test data setup completed!\n";
