<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custosell ERP/POS - Database Schema Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        .table-container {
            max-height: none;
            overflow: visible;
        }
        .code-block {
            background: #1f2937;
            color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            line-height: 1.4;
            margin: 1rem 0;
            overflow-x: auto;
        }
        .relationship-line {
            border-left: 2px solid #3b82f6;
            padding-left: 1rem;
            margin-left: 1rem;
        }
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
            .print-break { page-break-inside: avoid; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    <!-- Header -->
    <header class="bg-blue-600 text-white py-8 px-6">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold mb-2">
                <i class="fas fa-database mr-3"></i>
                Custosell ERP/POS
            </h1>
            <h2 class="text-2xl font-light">Database Schema Documentation</h2>
            <p class="text-blue-100 mt-4">Comprehensive guide for backend developers to implement Custosell ERP and POS system</p>
        </div>
    </header>

    <!-- Table of Contents -->
    <nav class="bg-white shadow-sm py-6 px-6">
        <div class="max-w-7xl mx-auto">
            <h3 class="text-xl font-bold mb-4 text-gray-800">
                <i class="fas fa-list mr-2"></i>
                Table of Contents
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <h4 class="font-semibold text-blue-600 mb-2">System Overview</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="#architecture" class="hover:text-blue-600">Architecture</a></li>
                        <li><a href="#key-features" class="hover:text-blue-600">Key Features</a></li>
                        <li><a href="#tech-stack" class="hover:text-blue-600">Tech Stack</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-600 mb-2">Database Modules</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="#core-system" class="hover:text-blue-600">Core System</a></li>
                        <li><a href="#product-management" class="hover:text-blue-600">Product Management</a></li>
                        <li><a href="#inventory-management" class="hover:text-blue-600">Inventory Management</a></li>
                        <li><a href="#sales-pos" class="hover:text-blue-600">Sales & POS</a></li>
                        <li><a href="#financial" class="hover:text-blue-600">Financial Management</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-600 mb-2">Implementation</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="#relationships" class="hover:text-blue-600">Table Relationships</a></li>
                        <li><a href="#business-logic" class="hover:text-blue-600">Business Logic</a></li>
                        <li><a href="#api-guidelines" class="hover:text-blue-600">API Guidelines</a></li>
                        <li><a href="#best-practices" class="hover:text-blue-600">Best Practices</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- System Architecture -->
        <section id="architecture" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-sitemap mr-3 text-blue-600"></i>
                System Architecture
            </h2>
            
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4 text-blue-600">Multi-Tenant ERP/POS System</h3>
                <p class="text-gray-700 mb-4">
                    Custosell is designed as a comprehensive ERP and POS solution with multi-company and multi-branch support, 
                    targeting African businesses from small retail shops to large enterprises.
                </p>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-3">
                            <i class="fas fa-building mr-2"></i>
                            Multi-Tenancy Structure
                        </h4>
                        <ul class="space-y-2 text-sm">
                            <li><strong>Company Level:</strong> Top-level tenant isolation</li>
                            <li><strong>Branch Level:</strong> Multi-location support within company</li>
                            <li><strong>User Level:</strong> Role-based access across branches</li>
                            <li><strong>Data Isolation:</strong> Complete separation between companies</li>
                        </ul>
                    </div>
                    
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-800 mb-3">
                            <i class="fas fa-cogs mr-2"></i>
                            Core Modules
                        </h4>
                        <ul class="space-y-2 text-sm">
                            <li><strong>Inventory:</strong> Stock management & tracking</li>
                            <li><strong>POS:</strong> Point of sale transactions</li>
                            <li><strong>Sales:</strong> Order management & CRM</li>
                            <li><strong>Procurement:</strong> Supplier & purchase management</li>
                            <li><strong>Finance:</strong> Accounting & payment processing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Features -->
        <section id="key-features" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-star mr-3 text-blue-600"></i>
                Key Features & African Context
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-blue-600 mb-3">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        Mobile-First Design
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Offline-capable POS</li>
                        <li>• Mobile money integration</li>
                        <li>• Low bandwidth optimization</li>
                        <li>• Progressive Web App ready</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-3">
                        <i class="fas fa-coins mr-2"></i>
                        Financial Features
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Multi-currency support (NGN default)</li>
                        <li>• VAT/Tax compliance</li>
                        <li>• Credit/Debit management</li>
                        <li>• Double-entry bookkeeping</li>
                    </ul>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-purple-600 mb-3">
                        <i class="fas fa-chart-line mr-2"></i>
                        Business Intelligence
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Real-time inventory tracking</li>
                        <li>• Sales analytics & reports</li>
                        <li>• Low stock alerts</li>
                        <li>• Performance dashboards</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Core System Tables -->
        <section id="core-system" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-users mr-3 text-blue-600"></i>
                Core System Tables
            </h2>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-user mr-2"></i>
                    users
                </h3>
                <p class="text-gray-700 mb-4">Central user management with African business context support</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Column</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Type</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>id</code></td>
                                <td class="border border-gray-300 px-4 py-2">bigint</td>
                                <td class="border border-gray-300 px-4 py-2">Primary key</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>first_name, last_name</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">User's full name</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>employee_id</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">Unique employee identifier</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>timezone</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">Default: Africa/Lagos</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>locale</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">Language preference (en, fr, sw, etc.)</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>country</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">Default: Nigeria</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 bg-blue-50 p-4 rounded">
                    <h4 class="font-semibold text-blue-800 mb-2">Implementation Notes:</h4>
                    <ul class="text-sm space-y-1">
                        <li>• Use Laravel's built-in authentication with Sanctum for API tokens</li>
                        <li>• Implement middleware for timezone and locale handling</li>
                        <li>• Store preferences as JSON for flexibility</li>
                        <li>• Index email and employee_id for fast lookups</li>
                    </ul>
                </div>
            </div>

            <!-- Companies Table -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-building mr-2"></i>
                    companies
                </h3>
                <p class="text-gray-700 mb-4">Multi-tenant company/business management</p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Column</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Type</th>
                                <th class="border border-gray-300 px-4 py-2 text-left font-semibold">Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>registration_number</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">CAC registration number (Nigeria)</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>tax_number</code></td>
                                <td class="border border-gray-300 px-4 py-2">string</td>
                                <td class="border border-gray-300 px-4 py-2">TIN or VAT number</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>currency</code></td>
                                <td class="border border-gray-300 px-4 py-2">string(3)</td>
                                <td class="border border-gray-300 px-4 py-2">Default: NGN (ISO 4217)</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><code>business_type</code></td>
                                <td class="border border-gray-300 px-4 py-2">enum</td>
                                <td class="border border-gray-300 px-4 py-2">retail, wholesale, manufacturing, service</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <h4 class="font-semibold text-green-800 mb-2">Business Logic:</h4>
                    <div class="code-block">
// Company settings JSON structure
{
  "tax_settings": {
    "vat_rate": 7.5,
    "vat_inclusive": false,
    "tax_id": "12345678-0001"
  },
  "business_hours": {
    "monday": {"open": "08:00", "close": "18:00"},
    "sunday": {"closed": true}
  },
  "pos_settings": {
    "receipt_footer": "Thank you for your business!",
    "auto_print": true
  }
}
                    </div>
                </div>
            </div>

            <!-- Branches Table -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-store mr-2"></i>
                    branches
                </h3>
                <p class="text-gray-700 mb-4">Multi-location support with geographic data</p>
                
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h5 class="font-semibold mb-2">Key Features:</h5>
                        <ul class="text-sm space-y-1">
                            <li>• GPS coordinates for delivery routing</li>
                            <li>• Separate POS and warehouse flags</li>
                            <li>• Operating hours in JSON format</li>
                            <li>• Manager assignment per branch</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-2">Use Cases:</h5>
                        <ul class="text-sm space-y-1">
                            <li>• Multi-store retail chains</li>
                            <li>• Warehouse distribution centers</li>
                            <li>• Franchise operations</li>
                            <li>• Mobile sales points</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded">
                    <h4 class="font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Implementation Tip:
                    </h4>
                    <p class="text-sm">Use middleware to set branch context based on subdomain or user selection. Example: <code>lagos.custosell.com</code> or user selection in multi-branch interface.</p>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>
                    roles & user_roles
                </h3>
                <p class="text-gray-700 mb-4">Flexible role-based access control system</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Predefined Roles:</h5>
                        <div class="space-y-2">
                            <div class="bg-red-50 p-3 rounded border-l-4 border-red-400">
                                <strong>Super Admin:</strong> System-wide access
                            </div>
                            <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                                <strong>Company Admin:</strong> Company-wide management
                            </div>
                            <div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
                                <strong>Branch Manager:</strong> Branch operations
                            </div>
                            <div class="bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">
                                <strong>Cashier:</strong> POS operations only
                            </div>
                            <div class="bg-purple-50 p-3 rounded border-l-4 border-purple-400">
                                <strong>Sales Rep:</strong> Customer & sales management
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Permissions Structure:</h5>
                        <div class="code-block">
{
  "products": ["view", "create", "edit", "delete"],
  "inventory": ["view", "adjust", "transfer"],
  "sales": ["view", "create", "process", "refund"],
  "reports": ["view", "export"],
  "pos": ["access", "void_transaction"],
  "customers": ["view", "create", "edit"],
  "suppliers": ["view", "create", "edit"],
  "settings": ["view", "edit"]
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Product Management -->
        <section id="product-management" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-box mr-3 text-blue-600"></i>
                Product Management Module
            </h2>

            <!-- Products Table -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-cube mr-2"></i>
                    products
                </h3>
                <p class="text-gray-700 mb-4">Comprehensive product catalog with variants and attributes</p>

                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded">
                        <h5 class="font-semibold text-blue-800 mb-2">Product Types:</h5>
                        <ul class="text-sm space-y-1">
                            <li><strong>Simple:</strong> Single SKU products</li>
                            <li><strong>Variable:</strong> Multiple variants (size, color)</li>
                            <li><strong>Service:</strong> Non-physical products</li>
                            <li><strong>Digital:</strong> Downloadable products</li>
                        </ul>
                    </div>
                    <div class="bg-green-50 p-4 rounded">
                        <h5 class="font-semibold text-green-800 mb-2">Pricing & Costing:</h5>
                        <ul class="text-sm space-y-1">
                            <li><code>cost_price</code>: Purchase/manufacturing cost</li>
                            <li><code>selling_price</code>: Base selling price</li>
                            <li><code>tax_rate</code>: Product-specific tax</li>
                            <li>Support for margin calculations</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 p-4 rounded">
                        <h5 class="font-semibold text-purple-800 mb-2">Inventory Control:</h5>
                        <ul class="text-sm space-y-1">
                            <li><code>track_quantity</code>: Enable/disable tracking</li>
                            <li><code>minimum_quantity</code>: Reorder level</li>
                            <li><code>maximum_quantity</code>: Stock limit</li>
                            <li>Automated alerts for low stock</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h4 class="font-semibold mb-3">Product Creation Workflow:</h4>
                    <div class="code-block">
// Example: Create a variable product (T-shirt with sizes and colors)
$product = Product::create([
    'name' => 'Cotton T-Shirt',
    'type' => 'variable',
    'sku' => 'TSHIRT-001',
    'category_id' => $categoryId,
    'brand_id' => $brandId,
    'track_quantity' => true,
    'minimum_quantity' => 10
]);

// Create variants
$sizes = ['S', 'M', 'L', 'XL'];
$colors = ['Red', 'Blue', 'Black'];

foreach($sizes as $size) {
    foreach($colors as $color) {
        ProductVariant::create([
            'product_id' => $product->id,
            'sku' => "TSHIRT-001-{$size}-{$color}",
            'attributes' => ['size' => $size, 'color' => $color],
            'cost_price' => 15.00,
            'selling_price' => 25.00
        ]);
    }
}
                    </div>
                </div>
            </div>

            <!-- Categories & Brands -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-tags mr-2"></i>
                    categories & brands
                </h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Category Hierarchy:</h5>
                        <div class="relationship-line">
                            <div class="mb-2">📁 Electronics</div>
                            <div class="relationship-line">
                                <div class="mb-1">📱 Mobile Phones</div>
                                <div class="relationship-line ml-4">
                                    <div>🍎 iPhone</div>
                                    <div>🤖 Android</div>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">
                            Use <code>parent_id</code> for nested categories. 
                            Implement breadcrumb navigation for better UX.
                        </p>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Brand Management:</h5>
                        <ul class="text-sm space-y-2">
                            <li>• Store brand logos and descriptions</li>
                            <li>• Track brand performance metrics</li>
                            <li>• Enable brand-based filtering</li>
                            <li>• Support for private label products</li>
                        </ul>
                        <div class="mt-3 p-3 bg-blue-50 rounded text-sm">
                            <strong>Tip:</strong> Use brands for supplier relationships and customer preferences analysis.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Inventory Management -->
        <section id="inventory-management" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-warehouse mr-3 text-blue-600"></i>
                Inventory Management Module
            </h2>

            <!-- Inventory Table -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-boxes mr-2"></i>
                    inventory
                </h3>
                <p class="text-gray-700 mb-4">Real-time stock tracking per branch with advanced inventory states</p>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Inventory States:</h5>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between bg-green-50 p-2 rounded">
                                <span><code>quantity_on_hand</code></span>
                                <span>Physical stock count</span>
                            </div>
                            <div class="flex justify-between bg-blue-50 p-2 rounded">
                                <span><code>quantity_available</code></span>
                                <span>Available for sale</span>
                            </div>
                            <div class="flex justify-between bg-yellow-50 p-2 rounded">
                                <span><code>quantity_reserved</code></span>
                                <span>Pending orders</span>
                            </div>
                            <div class="flex justify-between bg-purple-50 p-2 rounded">
                                <span><code>quantity_on_order</code></span>
                                <span>Incoming stock</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Business Rules:</h5>
                        <div class="code-block">
// Inventory calculation formula
available = on_hand - reserved

// Reorder alert trigger
if (available <= reorder_level) {
    triggerReorderAlert();
}

// Average cost calculation (FIFO)
average_cost = total_cost / quantity_received
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 p-4 rounded">
                    <h4 class="font-semibold text-orange-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Critical Implementation Notes:
                    </h4>
                    <ul class="text-sm space-y-1">
                        <li>• Use database transactions for all inventory updates</li>
                        <li>• Implement inventory locking during sales to prevent overselling</li>
                        <li>• Schedule daily reconciliation jobs for accuracy</li>
                        <li>• Create audit trail for all quantity changes</li>
                    </ul>
                </div>
            </div>

            <!-- Stock Movements -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    stock_movements
                </h3>
                <p class="text-gray-700 mb-4">Complete audit trail of all inventory transactions</p>

                <div class="grid md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-green-50 p-4 rounded">
                        <h5 class="font-semibold text-green-800 mb-2">Movement Types:</h5>
                        <ul class="text-sm space-y-1">
                            <li><span class="text-green-600">▲ IN:</span> Purchase, Return, Found</li>
                            <li><span class="text-red-600">▼ OUT:</span> Sale, Damage, Expired</li>
                            <li><span class="text-blue-600">↔ TRANSFER:</span> Branch to Branch</li>
                            <li><span class="text-yellow-600">⚡ ADJUSTMENT:</span> Count Correction</li>
                        </ul>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded">
                        <h5 class="font-semibold text-blue-800 mb-2">Reference Tracking:</h5>
                        <ul class="text-sm space-y-1">
                            <li><code>reference_type</code>: Source document</li>
                            <li><code>reference_id</code>: Document ID</li>
                            <li>Links to sales, purchases, adjustments</li>
                            <li>Complete traceability chain</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded">
                        <h5 class="font-semibold text-purple-800 mb-2">Costing Integration:</h5>
                        <ul class="text-sm space-y-1">
                            <li><code>unit_cost</code>: Cost per unit</li>
                            <li><code>total_cost</code>: Total value impact</li>
                            <li>FIFO/LIFO/Average costing</li>
                            <li>Profit margin calculations</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h4 class="font-semibold mb-3">Stock Movement Creation Example:</h4>
                    <div class="code-block">
// Record sale movement
StockMovement::create([
    'product_id' => $productId,
    'branch_id' => $branchId,
    'user_id' => auth()->id(),
    'type' => 'out',
    'reason' => 'sale',
    'quantity' => $saleQuantity,
    'balance_before' => $currentStock,
    'balance_after' => $currentStock - $saleQuantity,
    'unit_cost' => $averageCost,
    'total_cost' => $averageCost * $saleQuantity,
    'reference_type' => 'pos_transactions',
    'reference_id' => $transactionId,
    'occurred_at' => now()
]);

// Update inventory record
$inventory->decrement('quantity_on_hand', $saleQuantity);
$inventory->decrement('quantity_available', $saleQuantity);
                    </div>
                </div>
            </div>

            <!-- Stock Transfers -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-truck mr-2"></i>
                    stock_transfers & stock_transfer_items
                </h3>
                <p class="text-gray-700 mb-4">Inter-branch inventory transfers with approval workflow</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Transfer Workflow:</h5>
                        <div class="space-y-3">
                            <div class="flex items-center bg-gray-50 p-3 rounded">
                                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">1</div>
                                <div>
                                    <strong>Request</strong>
                                    <div class="text-sm text-gray-600">Branch manager creates transfer request</div>
                                </div>
                            </div>
                            <div class="flex items-center bg-gray-50 p-3 rounded">
                                <div class="w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">2</div>
                                <div>
                                    <strong>Approval</strong>
                                    <div class="text-sm text-gray-600">Source branch approves/rejects</div>
                                </div>
                            </div>
                            <div class="flex items-center bg-gray-50 p-3 rounded">
                                <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">3</div>
                                <div>
                                    <strong>Shipment</strong>
                                    <div class="text-sm text-gray-600">Goods dispatched with tracking</div>
                                </div>
                            </div>
                            <div class="flex items-center bg-gray-50 p-3 rounded">
                                <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3">4</div>
                                <div>
                                    <strong>Receipt</strong>
                                    <div class="text-sm text-gray-600">Destination confirms receipt</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Implementation Logic:</h5>
                        <div class="code-block">
// Transfer approval
public function approveTransfer($transferId) {
    DB::transaction(function() use ($transferId) {
        $transfer = StockTransfer::find($transferId);
        
        // Validate source stock
        foreach($transfer->items as $item) {
            $inventory = Inventory::where([
                'product_id' => $item->product_id,
                'branch_id' => $transfer->from_branch_id
            ])->first();
            
            if($inventory->quantity_available < $item->quantity_requested) {
                throw new InsufficientStockException();
            }
        }
        
        // Reserve stock and update status
        $transfer->update(['status' => 'approved']);
        $this->reserveStock($transfer);
    });
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sales & POS -->
        <section id="sales-pos" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-cash-register mr-3 text-blue-600"></i>
                Sales & POS Module
            </h2>

            <!-- POS Transactions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-receipt mr-2"></i>
                    pos_transactions & pos_transaction_items
                </h3>
                <p class="text-gray-700 mb-4">Point of Sale transactions optimized for African retail environments</p>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Transaction Features:</h5>
                        <ul class="text-sm space-y-2">
                            <li>• <strong>Offline Support:</strong> Store transactions locally, sync when online</li>
                            <li>• <strong>Multiple Payments:</strong> Cash + Card + Mobile Money</li>
                            <li>• <strong>Customer Integration:</strong> Link to customer for loyalty</li>
                            <li>• <strong>Returns & Exchanges:</strong> Full transaction reversal</li>
                            <li>• <strong>Discounts:</strong> Item-level and transaction-level</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">African Context Features:</h5>
                        <ul class="text-sm space-y-2">
                            <li>• <strong>Mobile Money:</strong> M-Pesa, Airtel Money integration</li>
                            <li>• <strong>Multi-Currency:</strong> Local currency + USD/EUR</li>
                            <li>• <strong>Low Connectivity:</strong> Offline-first design</li>
                            <li>• <strong>SMS Receipts:</strong> When email unavailable</li>
                            <li>• <strong>Loyalty Points:</strong> Customer retention program</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded mb-4">
                    <h4 class="font-semibold text-blue-800 mb-3">POS Transaction Flow:</h4>
                    <div class="code-block">
// Complete POS transaction with inventory update
public function processPOSTransaction($transactionData) {
    return DB::transaction(function() use ($transactionData) {
        // 1. Create transaction
        $transaction = POSTransaction::create([
            'transaction_number' => $this->generateTransactionNumber(),
            'branch_id' => $transactionData['branch_id'],
            'cashier_id' => auth()->id(),
            'customer_id' => $transactionData['customer_id'] ?? null,
            'subtotal' => $transactionData['subtotal'],
            'tax_amount' => $transactionData['tax_amount'],
            'total_amount' => $transactionData['total_amount'],
            'amount_paid' => $transactionData['amount_paid'],
            'change_amount' => $transactionData['change_amount'],
            'transaction_at' => now()
        ]);

        // 2. Add items and update inventory
        foreach($transactionData['items'] as $itemData) {
            $this->addTransactionItem($transaction, $itemData);
            $this->updateInventory($itemData);
        }

        // 3. Record payments
        $this->recordPayments($transaction, $transactionData['payments']);

        // 4. Update customer loyalty points
        if($transaction->customer_id) {
            $this->updateLoyaltyPoints($transaction);
        }

        return $transaction;
    });
}
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded">
                    <h4 class="font-semibold text-green-800 mb-2">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        Offline POS Implementation:
                    </h4>
                    <ul class="text-sm space-y-1">
                        <li>• Use IndexedDB/LocalStorage for offline transaction storage</li>
                        <li>• Sync transactions when connectivity restored</li>
                        <li>• Handle inventory conflicts during sync</li>
                        <li>• Provide clear offline/online status indicators</li>
                    </ul>
                </div>
            </div>

            <!-- Sales Orders -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-file-invoice mr-2"></i>
                    sales_orders & sales_order_items
                </h3>
                <p class="text-gray-700 mb-4">Advanced sales order management with quotations and invoicing</p>

                <div class="grid md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-blue-50 p-4 rounded">
                        <h5 class="font-semibold text-blue-800 mb-2">Order Types:</h5>
                        <ul class="text-sm space-y-1">
                            <li><strong>Quotation:</strong> Price estimate</li>
                            <li><strong>Order:</strong> Confirmed sale</li>
                            <li><strong>Invoice:</strong> Payment request</li>
                        </ul>
                    </div>
                    <div class="bg-green-50 p-4 rounded">
                        <h5 class="font-semibold text-green-800 mb-2">Status Flow:</h5>
                        <ul class="text-sm space-y-1">
                            <li>Draft → Pending</li>
                            <li>Confirmed → Processing</li>
                            <li>Shipped → Delivered</li>
                            <li>Cancelled/Refunded</li>
                        </ul>
                    </div>
                    <div class="bg-purple-50 p-4 rounded">
                        <h5 class="font-semibold text-purple-800 mb-2">Financial Tracking:</h5>
                        <ul class="text-sm space-y-1">
                            <li><code>total_amount</code>: Invoice total</li>
                            <li><code>paid_amount</code>: Payments received</li>
                            <li>Outstanding balance calculation</li>
                            <li>Payment term tracking</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-yellow-50 p-4 rounded">
                    <h4 class="font-semibold text-yellow-800 mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Business Intelligence Integration:
                    </h4>
                    <p class="text-sm">Track conversion rates from quotations to orders, customer payment patterns, and sales performance by product/category/branch for data-driven decisions.</p>
                </div>
            </div>

            <!-- Customer Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-users mr-2"></i>
                    customers & customer_groups
                </h3>
                <p class="text-gray-700 mb-4">Comprehensive customer relationship management</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Customer Segmentation:</h5>
                        <div class="space-y-2 text-sm">
                            <div class="bg-yellow-50 p-3 rounded border-l-4 border-yellow-400">
                                <strong>Individual Customers:</strong> Retail/walk-in customers
                            </div>
                            <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                                <strong>Business Customers:</strong> B2B clients with credit terms
                            </div>
                            <div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
                                <strong>VIP Customers:</strong> High-value customers with special pricing
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Loyalty Program:</h5>
                        <div class="code-block">
// Loyalty points calculation
public function calculateLoyaltyPoints($amount) {
    $pointsPerNaira = 0.01; // 1 point per ₦100
    return floor($amount * $pointsPerNaira);
}

// Redeem points (₦1 = 1 point)
public function redeemPoints($customerId, $points) {
    $customer = Customer::find($customerId);
    if($customer->loyalty_points >= $points) {
        $customer->decrement('loyalty_points', $points);
        return $points; // Value in Naira
    }
    return 0;
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Financial Management -->
        <section id="financial" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-chart-line mr-3 text-blue-600"></i>
                Financial Management Module
            </h2>

            <!-- Payments -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-credit-card mr-2"></i>
                    payments & payment_methods
                </h3>
                <p class="text-gray-700 mb-4">Multi-channel payment processing with African payment methods</p>

                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded">
                        <h5 class="font-semibold text-green-800 mb-2">
                            <i class="fas fa-money-bill mr-1"></i>
                            Cash Payments
                        </h5>
                        <ul class="text-sm space-y-1">
                            <li>• Point of sale cash handling</li>
                            <li>• Daily cash reconciliation</li>
                            <li>• Float management</li>
                            <li>• Cash drawer tracking</li>
                        </ul>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded">
                        <h5 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-mobile-alt mr-1"></i>
                            Mobile Money
                        </h5>
                        <ul class="text-sm space-y-1">
                            <li>• M-Pesa integration</li>
                            <li>• Airtel Money</li>
                            <li>• MTN MoMo</li>
                            <li>• Automated reconciliation</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 p-4 rounded">
                        <h5 class="font-semibold text-purple-800 mb-2">
                            <i class="fas fa-credit-card mr-1"></i>
                            Card Payments
                        </h5>
                        <ul class="text-sm space-y-1">
                            <li>• POS terminal integration</li>
                            <li>• Paystack/Flutterwave</li>
                            <li>• Online card processing</li>
                            <li>• Payment gateway fees</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-orange-50 p-4 rounded">
                    <h4 class="font-semibold text-orange-800 mb-3">Payment Method Configuration:</h4>
                    <div class="code-block">
// Mobile Money configuration example
{
  "type": "mobile_money",
  "provider": "mpesa",
  "api_config": {
    "consumer_key": "your_consumer_key",
    "consumer_secret": "your_consumer_secret",
    "shortcode": "174379",
    "passkey": "your_passkey",
    "callback_url": "https://yourdomain.com/api/mpesa/callback"
  },
  "fee_structure": {
    "percentage": 0.5,
    "minimum": 5.00,
    "maximum": 100.00
  }
}
                    </div>
                </div>
            </div>

            <!-- Accounting -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-calculator mr-2"></i>
                    accounts & journal_entries
                </h3>
                <p class="text-gray-700 mb-4">Double-entry bookkeeping system with chart of accounts</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Chart of Accounts Structure:</h5>
                        <div class="space-y-2 text-sm">
                            <div class="bg-green-50 p-2 rounded">
                                <strong>1000-1999: Assets</strong>
                                <ul class="ml-4 mt-1">
                                    <li>1100: Cash and Bank</li>
                                    <li>1200: Accounts Receivable</li>
                                    <li>1300: Inventory</li>
                                    <li>1400: Fixed Assets</li>
                                </ul>
                            </div>
                            <div class="bg-red-50 p-2 rounded">
                                <strong>2000-2999: Liabilities</strong>
                                <ul class="ml-4 mt-1">
                                    <li>2100: Accounts Payable</li>
                                    <li>2200: VAT Payable</li>
                                    <li>2300: Loans Payable</li>
                                </ul>
                            </div>
                            <div class="bg-blue-50 p-2 rounded">
                                <strong>4000-4999: Revenue</strong>
                                <ul class="ml-4 mt-1">
                                    <li>4100: Sales Revenue</li>
                                    <li>4200: Service Revenue</li>
                                </ul>
                            </div>
                            <div class="bg-orange-50 p-2 rounded">
                                <strong>5000-5999: Expenses</strong>
                                <ul class="ml-4 mt-1">
                                    <li>5100: Cost of Goods Sold</li>
                                    <li>5200: Operating Expenses</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Automatic Journal Entries:</h5>
                        <div class="code-block">
// Sale transaction journal entry
public function recordSaleJournalEntry($sale) {
    $journalEntry = JournalEntry::create([
        'entry_number' => $this->generateEntryNumber(),
        'entry_date' => $sale->transaction_at,
        'description' => "Sale #{$sale->transaction_number}",
        'reference_type' => 'pos_transactions',
        'reference_id' => $sale->id
    ]);

    // Debit: Cash/Bank (Asset increases)
    JournalEntryLine::create([
        'journal_entry_id' => $journalEntry->id,
        'account_id' => Account::where('code', '1100')->first()->id,
        'description' => 'Cash received from sale',
        'debit_amount' => $sale->total_amount
    ]);

    // Credit: Sales Revenue (Revenue increases)
    JournalEntryLine::create([
        'journal_entry_id' => $journalEntry->id,
        'account_id' => Account::where('code', '4100')->first()->id,
        'description' => 'Revenue from sale',
        'credit_amount' => $sale->subtotal
    ]);

    // Credit: VAT Payable (Liability increases)
    if($sale->tax_amount > 0) {
        JournalEntryLine::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => Account::where('code', '2200')->first()->id,
            'description' => 'VAT collected',
            'credit_amount' => $sale->tax_amount
        ]);
    }
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-percent mr-2"></i>
                    tax_rates
                </h3>
                <p class="text-gray-700 mb-4">Flexible tax management system for compliance</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Nigerian Tax Structure:</h5>
                        <div class="space-y-2 text-sm">
                            <div class="bg-blue-50 p-3 rounded">
                                <strong>VAT (Value Added Tax)</strong>
                                <div class="text-gray-600">7.5% on most goods and services</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded">
                                <strong>Withholding Tax</strong>
                                <div class="text-gray-600">Various rates (5%, 10%) on payments</div>
                            </div>
                            <div class="bg-yellow-50 p-3 rounded">
                                <strong>Import Duty</strong>
                                <div class="text-gray-600">Varies by product category</div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h5 class="font-semibold text-green-600 mb-3">Tax Calculation Logic:</h5>
                        <div class="code-block">
// Tax calculation for sale
public function calculateTax($subtotal, $taxRate) {
    if($taxRate->type === 'inclusive') {
        // Tax included in price
        $taxAmount = $subtotal * ($taxRate->rate / (100 + $taxRate->rate));
        $netAmount = $subtotal - $taxAmount;
    } else {
        // Tax added to price
        $taxAmount = $subtotal * ($taxRate->rate / 100);
        $netAmount = $subtotal;
    }
    
    return [
        'net_amount' => $netAmount,
        'tax_amount' => $taxAmount,
        'total_amount' => $subtotal + ($taxRate->type === 'exclusive' ? $taxAmount : 0)
    ];
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Table Relationships -->
        <section id="relationships" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-project-diagram mr-3 text-blue-600"></i>
                Database Relationships & Dependencies
            </h2>

            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Core Entity Relationships</h3>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Master-Detail Relationships:</h4>
                        <div class="space-y-3">
                            <div class="relationship-line">
                                <div class="font-semibold">companies</div>
                                <div class="ml-4">
                                    <div>→ branches (1:many)</div>
                                    <div>→ products (1:many)</div>
                                    <div>→ customers (1:many)</div>
                                    <div>→ suppliers (1:many)</div>
                                </div>
                            </div>
                            
                            <div class="relationship-line">
                                <div class="font-semibold">products</div>
                                <div class="ml-4">
                                    <div>→ product_variants (1:many)</div>
                                    <div>→ inventory (1:many via branch)</div>
                                    <div>→ stock_movements (1:many)</div>
                                </div>
                            </div>
                            
                            <div class="relationship-line">
                                <div class="font-semibold">pos_transactions</div>
                                <div class="ml-4">
                                    <div>→ pos_transaction_items (1:many)</div>
                                    <div>→ payments (1:many via allocation)</div>
                                    <div>→ stock_movements (1:many)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Key Foreign Keys:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="bg-blue-50 p-2 rounded">
                                <strong>company_id:</strong> Tenant isolation
                            </div>
                            <div class="bg-green-50 p-2 rounded">
                                <strong>branch_id:</strong> Location-specific data
                            </div>
                            <div class="bg-yellow-50 p-2 rounded">
                                <strong>user_id:</strong> Action tracking & ownership
                            </div>
                            <div class="bg-purple-50 p-2 rounded">
                                <strong>product_id/variant_id:</strong> Product references
                            </div>
                            <div class="bg-red-50 p-2 rounded">
                                <strong>customer_id/supplier_id:</strong> Party relationships
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">Critical Cascade Rules</h3>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-red-600 mb-3">CASCADE DELETE:</h4>
                        <ul class="text-sm space-y-1">
                            <li>• Company → All related data (branches, products, etc.)</li>
                            <li>• Transaction → Transaction items</li>
                            <li>• Journal Entry → Journal entry lines</li>
                            <li>• Stock Transfer → Transfer items</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-yellow-600 mb-3">NULL ON DELETE:</h4>
                        <ul class="text-sm space-y-1">
                            <li>• Customer deleted → Transactions remain (anonymized)</li>
                            <li>• Product deleted → Keep historical transaction data</li>
                            <li>• Supplier deleted → Purchase orders remain</li>
                            <li>• User deleted → Keep audit trail</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Business Logic -->
        <section id="business-logic" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-cog mr-3 text-blue-600"></i>
                Business Logic & Workflows
            </h2>

            <!-- Inventory Management Logic -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-warehouse mr-2"></i>
                    Inventory Management Workflows
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Stock Reservation Logic:</h4>
                        <div class="code-block">
// Reserve stock for pending orders
public function reserveStock($productId, $branchId, $quantity) {
    return DB::transaction(function() use ($productId, $branchId, $quantity) {
        $inventory = Inventory::where([
            'product_id' => $productId,
            'branch_id' => $branchId
        ])->lockForUpdate()->first();
        
        if($inventory->quantity_available < $quantity) {
            throw new InsufficientStockException();
        }
        
        $inventory->increment('quantity_reserved', $quantity);
        $inventory->decrement('quantity_available', $quantity);
        
        return $inventory;
    });
}

// Release reserved stock (order cancelled)
public function releaseReservation($productId, $branchId, $quantity) {
    $inventory = Inventory::where([
        'product_id' => $productId,
        'branch_id' => $branchId
    ])->first();
    
    $inventory->decrement('quantity_reserved', $quantity);
    $inventory->increment('quantity_available', $quantity);
}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Reorder Management:</h4>
                        <div class="code-block">
// Automated reorder alerts
public function checkReorderLevels() {
    $lowStockItems = Inventory::whereColumn('quantity_available', '<=', 'reorder_level')
        ->where('reorder_level', '>', 0)
        ->with(['product', 'branch'])
        ->get();
    
    foreach($lowStockItems as $item) {
        $this->createReorderAlert($item);
        
        // Auto-create purchase order if configured
        if($item->product->auto_reorder) {
            $this->createAutoPurchaseOrder($item);
        }
    }
}

// Calculate optimal reorder quantity
public function calculateReorderQuantity($productId, $branchId) {
    $salesHistory = $this->getAverageMonthlySales($productId, $branchId);
    $leadTime = $this->getSupplierLeadTime($productId);
    
    // EOQ formula consideration
    $reorderQuantity = ($salesHistory * $leadTime) + $safetyStock;
    
    return $reorderQuantity;
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Process Logic -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    Sales Process Automation
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">POS Transaction Processing:</h4>
                        <div class="code-block">
// Complete POS transaction with all side effects
public function completePOSTransaction($transactionData) {
    return DB::transaction(function() use ($transactionData) {
        // 1. Validate inventory availability
        foreach($transactionData['items'] as $item) {
            $this->validateStockAvailability($item);
        }
        
        // 2. Create transaction
        $transaction = $this->createPOSTransaction($transactionData);
        
        // 3. Process each item
        foreach($transactionData['items'] as $item) {
            // Create transaction item
            $this->createTransactionItem($transaction, $item);
            
            // Update inventory
            $this->updateInventoryOnSale($item);
            
            // Record stock movement
            $this->recordStockMovement($item, 'sale', $transaction);
        }
        
        // 4. Process payments
        $this->processPayments($transaction, $transactionData['payments']);
        
        // 5. Update customer loyalty
        if($transaction->customer_id) {
            $this->updateCustomerLoyalty($transaction);
        }
        
        // 6. Create accounting entries
        $this->createSaleAccountingEntries($transaction);
        
        // 7. Send receipt (SMS/Email)
        $this->sendReceipt($transaction);
        
        return $transaction;
    });
}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Return/Refund Processing:</h4>
                        <div class="code-block">
// Process return with inventory restoration
public function processReturn($originalTransactionId, $returnItems) {
    return DB::transaction(function() use ($originalTransactionId, $returnItems) {
        $originalTransaction = POSTransaction::find($originalTransactionId);
        
        // Create return transaction
        $returnTransaction = POSTransaction::create([
            'transaction_number' => $this->generateReturnNumber(),
            'type' => 'return',
            'branch_id' => $originalTransaction->branch_id,
            'customer_id' => $originalTransaction->customer_id,
            'cashier_id' => auth()->id(),
            'total_amount' => -$returnAmount, // Negative amount
            'transaction_at' => now()
        ]);
        
        foreach($returnItems as $item) {
            // Create return item
            $this->createReturnItem($returnTransaction, $item);
            
            // Restore inventory
            $this->restoreInventoryOnReturn($item);
            
            // Record stock movement
            $this->recordStockMovement($item, 'return', $returnTransaction);
        }
        
        // Process refund payment
        $this->processRefundPayment($returnTransaction);
        
        // Reverse accounting entries
        $this->createReturnAccountingEntries($returnTransaction);
        
        return $returnTransaction;
    });
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Workflows -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-calculator mr-2"></i>
                    Financial Management Workflows
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Payment Processing:</h4>
                        <div class="code-block">
// Multi-payment method processing
public function processMultiPayment($transactionId, $payments) {
    $transaction = POSTransaction::find($transactionId);
    $totalPaid = 0;
    
    foreach($payments as $paymentData) {
        $payment = Payment::create([
            'payment_number' => $this->generatePaymentNumber(),
            'company_id' => $transaction->company_id,
            'branch_id' => $transaction->branch_id,
            'payment_method_id' => $paymentData['method_id'],
            'customer_id' => $transaction->customer_id,
            'amount' => $paymentData['amount'],
            'type' => 'received',
            'payment_date' => now(),
            'user_id' => auth()->id()
        ]);
        
        // Link payment to transaction
        PaymentAllocation::create([
            'payment_id' => $payment->id,
            'allocatable_type' => 'pos_transactions',
            'allocatable_id' => $transaction->id,
            'allocated_amount' => $paymentData['amount']
        ]);
        
        // Handle payment method specific logic
        if($paymentData['method'] === 'mobile_money') {
            $this->processMobileMoneyPayment($payment, $paymentData);
        }
        
        $totalPaid += $paymentData['amount'];
    }
    
    // Calculate change
    $change = $totalPaid - $transaction->total_amount;
    $transaction->update([
        'amount_paid' => $totalPaid,
        'change_amount' => max(0, $change)
    ]);
    
    return $change;
}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">End of Day Reconciliation:</h4>
                        <div class="code-block">
// Daily cash reconciliation
public function performEndOfDayReconciliation($branchId, $cashierData) {
    $startOfDay = now()->startOfDay();
    $endOfDay = now()->endOfDay();
    
    // Calculate expected cash
    $cashTransactions = POSTransaction::where('branch_id', $branchId)
        ->whereBetween('transaction_at', [$startOfDay, $endOfDay])
        ->whereHas('payments', function($query) {
            $query->whereHas('paymentMethod', function($q) {
                $q->where('type', 'cash');
            });
        })->get();
    
    $expectedCash = $cashTransactions->sum('amount_paid');
    $actualCash = $cashierData['actual_cash_count'];
    $variance = $actualCash - $expectedCash;
    
    // Create reconciliation record
    CashReconciliation::create([
        'branch_id' => $branchId,
        'cashier_id' => auth()->id(),
        'date' => now()->toDateString(),
        'expected_amount' => $expectedCash,
        'actual_amount' => $actualCash,
        'variance' => $variance,
        'notes' => $cashierData['notes'],
        'status' => abs($variance) <= 10 ? 'balanced' : 'variance'
    ]);
    
    return [
        'expected' => $expectedCash,
        'actual' => $actualCash,
        'variance' => $variance,
        'status' => abs($variance) <= 10 ? 'balanced' : 'variance'
    ];
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- API Guidelines -->
        <section id="api-guidelines" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-code mr-3 text-blue-600"></i>
                API Implementation Guidelines
            </h2>

            <!-- RESTful API Design -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-network-wired mr-2"></i>
                    RESTful API Endpoints
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Core Resource Endpoints:</h4>
                        <div class="code-block">
// Product Management
GET /api/products                    // List products
POST /api/products                   // Create product
GET /api/products/{id}               // Get product details
PUT /api/products/{id}               // Update product
DELETE /api/products/{id}            // Delete product
GET /api/products/{id}/variants      // List product variants

// Inventory Management
GET /api/branches/{id}/inventory     // Branch inventory
POST /api/inventory/adjustments      // Create stock adjustment
POST /api/inventory/transfers        // Create stock transfer
GET /api/inventory/movements         // Stock movement history

// Sales & POS
POST /api/pos/transactions           // Create POS transaction
POST /api/pos/transactions/{id}/return // Process return
GET /api/sales/orders                // List sales orders
POST /api/sales/orders               // Create sales order

// Customer Management
GET /api/customers                   // List customers
POST /api/customers                  // Create customer
PUT /api/customers/{id}              // Update customer
GET /api/customers/{id}/transactions // Customer transaction history

// Payment Processing
POST /api/payments                   // Record payment
GET /api/payments/{id}               // Get payment details
POST /api/payments/mobile-money      // Mobile money payment
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">API Response Structure:</h4>
                        <div class="code-block">
// Success Response
{
  "success": true,
  "data": {
    "id": 123,
    "name": "Sample Product",
    "sku": "PROD-001",
    // ... other fields
  },
  "meta": {
    "timestamp": "2024-01-15T10:30:00Z",
    "version": "v1",
    "request_id": "req_abc123"
  }
}

// Error Response
{
  "success": false,
  "error": {
    "code": "INSUFFICIENT_STOCK",
    "message": "Not enough stock available",
    "details": {
      "requested": 10,
      "available": 5,
      "product_id": 123
    }
  },
  "meta": {
    "timestamp": "2024-01-15T10:30:00Z",
    "request_id": "req_abc123"
  }
}

// Paginated Response
{
  "success": true,
  "data": [...],
  "pagination": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8,
    "next_page_url": "/api/products?page=2",
    "prev_page_url": null
  }
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Authentication & Security -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Authentication & Security
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Laravel Sanctum Implementation:</h4>
                        <div class="code-block">
// Login endpoint
POST /api/auth/login
{
  "email": "user@example.com",
  "password": "password",
  "branch_id": 1 // Optional branch selection
}

// Response
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "roles": ["cashier", "sales_rep"],
      "permissions": ["pos.access", "sales.create"]
    },
    "token": "1|abc123...xyz789",
    "expires_at": "2024-02-15T10:30:00Z"
  }
}

// API calls with token
Authorization: Bearer 1|abc123...xyz789

// Logout
POST /api/auth/logout
Authorization: Bearer 1|abc123...xyz789
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Permission Middleware:</h4>
                        <div class="code-block">
// Route protection with permissions
Route::middleware(['auth:sanctum', 'permission:pos.access'])
    ->post('/api/pos/transactions', [POSController::class, 'store']);

Route::middleware(['auth:sanctum', 'permission:inventory.adjust'])
    ->post('/api/inventory/adjustments', [InventoryController::class, 'adjust']);

// Branch context middleware
Route::middleware(['auth:sanctum', 'branch.context'])
    ->group(function () {
        Route::get('/api/inventory', [InventoryController::class, 'index']);
        Route::get('/api/pos/transactions', [POSController::class, 'index']);
    });

// Rate limiting
Route::middleware(['throttle:60,1']) // 60 requests per minute
    ->group(function () {
        // Public API endpoints
    });

Route::middleware(['throttle:1000,1']) // 1000 requests per minute for authenticated users
    ->group(function () {
        // Authenticated API endpoints
    });
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offline Support -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-wifi mr-2"></i>
                    Offline Support & Sync
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Offline Transaction Storage:</h4>
                        <div class="code-block">
// Frontend (JavaScript/PWA)
class OfflinePOS {
    async createTransaction(transactionData) {
        if (navigator.onLine) {
            return await this.submitOnline(transactionData);
        } else {
            return await this.storeOffline(transactionData);
        }
    }
    
    async storeOffline(transactionData) {
        const transaction = {
            ...transactionData,
            id: this.generateOfflineId(),
            offline: true,
            created_at: new Date().toISOString()
        };
        
        await this.localDB.transactions.add(transaction);
        return transaction;
    }
    
    async syncWhenOnline() {
        const offlineTransactions = await this.localDB.transactions
            .where('offline').equals(true)
            .toArray();
            
        for (const transaction of offlineTransactions) {
            try {
                const response = await fetch('/api/pos/transactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.token}`
                    },
                    body: JSON.stringify(transaction)
                });
                
                if (response.ok) {
                    await this.localDB.transactions.delete(transaction.id);
                }
            } catch (error) {
                console.log('Sync failed for transaction:', transaction.id);
            }
        }
    }
}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Backend Sync Handling:</h4>
                        <div class="code-block">
// Offline transaction sync endpoint
POST /api/pos/transactions/sync
{
  "transactions": [
    {
      "offline_id": "offline_123",
      "transaction_number": "TXN-2024-001",
      "items": [...],
      "created_at": "2024-01-15T10:30:00Z",
      "offline": true
    }
  ]
}

// Sync controller method
public function syncOfflineTransactions(Request $request) {
    $results = [];
    
    foreach($request->transactions as $transactionData) {
        try {
            DB::beginTransaction();
            
            // Check for duplicates
            $existing = POSTransaction::where('transaction_number', 
                $transactionData['transaction_number'])->first();
                
            if ($existing) {
                $results[] = [
                    'offline_id' => $transactionData['offline_id'],
                    'status' => 'duplicate',
                    'server_id' => $existing->id
                ];
                continue;
            }
            
            // Process transaction normally
            $transaction = $this->processPOSTransaction($transactionData);
            
            DB::commit();
            
            $results[] = [
                'offline_id' => $transactionData['offline_id'],
                'status' => 'synced',
                'server_id' => $transaction->id
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            $results[] = [
                'offline_id' => $transactionData['offline_id'],
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    
    return response()->json(['results' => $results]);
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Best Practices -->
        <section id="best-practices" class="mb-12 print-break">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">
                <i class="fas fa-star mr-3 text-blue-600"></i>
                Implementation Best Practices
            </h2>

            <!-- Performance Optimization -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Performance Optimization
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Database Optimization:</h4>
                        <ul class="text-sm space-y-2">
                            <li class="bg-blue-50 p-2 rounded">
                                <strong>Indexing Strategy:</strong> Use compound indexes for common query patterns
                            </li>
                            <li class="bg-green-50 p-2 rounded">
                                <strong>Query Optimization:</strong> Eager load relationships to avoid N+1 queries
                            </li>
                            <li class="bg-yellow-50 p-2 rounded">
                                <strong>Pagination:</strong> Always paginate large datasets (products, transactions)
                            </li>
                            <li class="bg-purple-50 p-2 rounded">
                                <strong>Caching:</strong> Cache frequently accessed data (tax rates, exchange rates)
                            </li>
                        </ul>
                        
                        <div class="code-block mt-4">
// Efficient inventory query with proper indexing
$inventory = Inventory::with(['product', 'branch'])
    ->where('branch_id', $branchId)
    ->where('quantity_on_hand', '>', 0)
    ->orderBy('product_id')
    ->paginate(50);

// Use indexes for common queries
DB::statement('CREATE INDEX idx_inventory_branch_quantity 
    ON inventory (branch_id, quantity_on_hand, product_id)');

// Cache tax rates
$taxRates = Cache::remember('tax_rates_' . $companyId, 3600, function() use ($companyId) {
    return TaxRate::where('company_id', $companyId)
        ->where('is_active', true)
        ->get();
});
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Caching Strategies:</h4>
                        <ul class="text-sm space-y-2">
                            <li class="bg-red-50 p-2 rounded">
                                <strong>Redis Cache:</strong> Session data, frequently accessed settings
                            </li>
                            <li class="bg-orange-50 p-2 rounded">
                                <strong>Query Caching:</strong> Product catalogs, category trees
                            </li>
                            <li class="bg-teal-50 p-2 rounded">
                                <strong>API Response Caching:</strong> Public product data, exchange rates
                            </li>
                        </ul>
                        
                        <div class="code-block mt-4">
// Cache product catalog for branch
class ProductService {
    public function getBranchProducts($branchId) {
        $cacheKey = "products_branch_{$branchId}";
        
        return Cache::tags(['products', "branch_{$branchId}"])
            ->remember($cacheKey, 1800, function() use ($branchId) {
                return Product::with(['category', 'brand', 'variants'])
                    ->whereHas('inventory', function($query) use ($branchId) {
                        $query->where('branch_id', $branchId);
                    })
                    ->where('status', 'active')
                    ->get();
            });
    }
    
    public function clearProductCache($branchId = null) {
        if ($branchId) {
            Cache::tags("branch_{$branchId}")->flush();
        } else {
            Cache::tags('products')->flush();
        }
    }
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Handling -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Error Handling & Validation
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Custom Exceptions:</h4>
                        <div class="code-block">
// Custom business logic exceptions
class InsufficientStockException extends Exception {
    public function __construct($productName, $requested, $available) {
        $message = "Insufficient stock for {$productName}. Requested: {$requested}, Available: {$available}";
        parent::__construct($message);
    }
}

class InvalidPaymentAmountException extends Exception {
    public function __construct($expected, $received) {
        $message = "Payment amount mismatch. Expected: {$expected}, Received: {$received}";
        parent::__construct($message);
    }
}

// Global exception handler
public function render($request, Throwable $exception) {
    if ($exception instanceof InsufficientStockException) {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => 'INSUFFICIENT_STOCK',
                'message' => $exception->getMessage(),
                'type' => 'business_logic_error'
            ]
        ], 400);
    }
    
    return parent::render($request, $exception);
}
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Validation Rules:</h4>
                        <div class="code-block">
// Product validation
class CreateProductRequest extends FormRequest {
    public function rules() {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|gt:cost_price',
            'minimum_quantity' => 'required|integer|min:0',
            'track_quantity' => 'boolean'
        ];
    }
    
    public function messages() {
        return [
            'selling_price.gt' => 'Selling price must be greater than cost price',
            'sku.unique' => 'This SKU is already in use'
        ];
    }
}

// POS transaction validation
class POSTransactionRequest extends FormRequest {
    public function rules() {
        return [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payments' => 'required|array|min:1',
            'payments.*.method_id' => 'required|exists:payment_methods,id',
            'payments.*.amount' => 'required|numeric|min:0'
        ];
    }
}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Best Practices -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">
                    <i class="fas fa-lock mr-2"></i>
                    Security Implementation
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Data Protection:</h4>
                        <ul class="text-sm space-y-2">
                            <li class="bg-red-50 p-2 rounded">
                                <strong>Encryption:</strong> Encrypt sensitive data (payment info, personal details)
                            </li>
                            <li class="bg-orange-50 p-2 rounded">
                                <strong>Input Sanitization:</strong> Validate and sanitize all user inputs
                            </li>
                            <li class="bg-yellow-50 p-2 rounded">
                                <strong>HTTPS Only:</strong> Force SSL for all API endpoints
                            </li>
                            <li class="bg-green-50 p-2 rounded">
                                <strong>Rate Limiting:</strong> Prevent API abuse and brute force attacks
                            </li>
                        </ul>
                        
                        <div class="code-block mt-4">
// Encrypt sensitive customer data
class Customer extends Model {
    protected $fillable = [...];
    
    protected $encrypted = [
        'phone', 'address', 'tax_number'
    ];
    
    public function setPhoneAttribute($value) {
        $this->attributes['phone'] = encrypt($value);
    }
    
    public function getPhoneAttribute($value) {
        return decrypt($value);
    }
}

// API rate limiting
Route::middleware(['throttle:api'])
    ->prefix('api')
    ->group(function () {
        // API routes
    });

// Configure in config/sanctum.php
'middleware' => [
    'encrypt_cookies',
    'cookie_session',
    'throttle:api'
],
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-green-600 mb-3">Audit & Compliance:</h4>
                        <ul class="text-sm space-y-2">
                            <li class="bg-blue-50 p-2 rounded">
                                <strong>Audit Logging:</strong> Track all critical business operations
                            </li>
                            <li class="bg-purple-50 p-2 rounded">
                                <strong>Access Control:</strong> Implement role-based permissions
                            </li>
                            <li class="bg-teal-50 p-2 rounded">
                                <strong>Data Retention:</strong> Comply with local business regulations
                            </li>
                        </ul>
                        
                        <div class="code-block mt-4">
// Audit trait for models
trait Auditable {
    protected static function bootAuditable() {
        static::created(function($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'event' => 'created',
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'new_values' => $model->toArray()
            ]);
        });
        
        static::updated(function($model) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'event' => 'updated',
                'auditable_type' => get_class($model),
                'auditable_id' => $model->id,
                'old_values' => $model->getOriginal(),
                'new_values' => $model->getChanges()
            ]);
        });
    }
}

// Use on sensitive models
class POSTransaction extends Model {
    use Auditable;
    
    // Model implementation
}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Conclusion -->
        <section class="mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg p-8">
                <h2 class="text-3xl font-bold mb-4">
                    <i class="fas fa-rocket mr-3"></i>
                    Ready to Build Custosell?
                </h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-xl font-semibold mb-3">
                            <i class="fas fa-database mr-2"></i>
                            Database Setup
                        </h3>
                        <ul class="text-sm space-y-1 text-blue-100">
                            <li>• Run the comprehensive migration</li>
                            <li>• Set up proper indexes</li>
                            <li>• Configure database relationships</li>
                            <li>• Seed with sample data</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-3">
                            <i class="fas fa-code mr-2"></i>
                            API Development
                        </h3>
                        <ul class="text-sm space-y-1 text-blue-100">
                            <li>• Implement RESTful endpoints</li>
                            <li>• Add authentication with Sanctum</li>
                            <li>• Create validation rules</li>
                            <li>• Build offline sync capabilities</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="text-xl font-semibold mb-3">
                            <i class="fas fa-mobile-alt mr-2"></i>
                            Frontend Integration
                        </h3>
                        <ul class="text-sm space-y-1 text-blue-100">
                            <li>• Build Progressive Web App</li>
                            <li>• Implement offline-first design</li>
                            <li>• Add mobile payment integration</li>
                            <li>• Create responsive POS interface</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-white bg-opacity-20 rounded-lg">
                    <h3 class="font-semibold mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>
                        African Market Success Factors:
                    </h3>
                    <p class="text-sm">
                        Focus on offline capabilities, mobile money integration, multi-language support, 
                        and low-bandwidth optimization to succeed in African markets. Prioritize simplicity, 
                        reliability, and affordability in your implementation approach.
                    </p>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <h3 class="text-xl font-bold mb-2">Custosell ERP/POS System</h3>
            <p class="text-gray-300 mb-4">Comprehensive database schema documentation for backend developers</p>
            <div class="flex justify-center space-x-6 text-sm">
                <span><i class="fas fa-database mr-1"></i> 50+ Database Tables</span>
                <span><i class="fas fa-code mr-1"></i> RESTful API Ready</span>
                <span><i class="fas fa-mobile-alt mr-1"></i> Offline-First Design</span>
                <span><i class="fas fa-globe-africa mr-1"></i> African Market Optimized</span>
            </div>
            <div class="mt-4 text-xs text-gray-400">
                Generated on: <span id="current-date"></span>
            </div>
        </div>
    </footer>

    <script>
        // Set current date
        document.getElementById('current-date').textContent = new Date().toLocaleDateString();
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
