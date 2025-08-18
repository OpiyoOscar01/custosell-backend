<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custosell ERP & POS System - Software Design Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <style>
        @page {
            margin: 0.5in;
            size: A4;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .section-divider {
            border-top: 2px solid #e5e7eb;
            margin: 2rem 0;
        }
        .code-block {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 1rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .table-container {
            overflow-x: visible;
        }
        .module-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .tech-stack-item {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .stakeholder-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-8">
    <div class="container mx-auto px-6">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-2">
                <i class="fas fa-store mr-3"></i>
                Custosell ERP & POS System
            </h1>
            <h2 class="text-2xl font-light mb-4">Software Design Document</h2>
            <div class="flex justify-center items-center space-x-6 text-sm mb-6">
                <div><i class="fas fa-calendar-alt mr-2"></i>Version 1.0</div>
                <div><i class="fas fa-user mr-2"></i>Enterprise Solution</div>
                <div><i class="fas fa-code mr-2"></i>PHP Laravel Framework</div>
            </div>

            <!-- 🔗 Button to View Database Document -->
            <a href="{{route('database-doc')}}" target="_blank"
               class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full shadow-lg hover:bg-blue-100 transition duration-300">
                <i class="fas fa-database mr-2"></i> View Database Document
            </a>
        </div>
    </div>
</div>


    <div class="container mx-auto px-6 py-8 max-w-6xl">
        <!-- Executive Summary -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                Executive Summary
            </h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-700 mb-4">
                        Custosell is a comprehensive Enterprise Resource Planning (ERP) and Point of Sale (POS) system designed to streamline business operations for retail, wholesale, manufacturing, and service businesses. Built on the robust Laravel PHP framework, it provides end-to-end business management capabilities.
                    </p>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Key Benefits</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Unified business management platform</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Real-time inventory tracking</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Multi-branch operations support</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Comprehensive financial management</li>
                        </ul>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-green-400 to-blue-500 p-4 rounded-lg text-white">
                        <h4 class="font-bold mb-2"><i class="fas fa-users mr-2"></i>Target Users</h4>
                        <div class="text-sm">
                            <div>• Small to Large Businesses</div>
                            <div>• Multi-location Retailers</div>
                            <div>• Wholesale Distributors</div>
                            <div>• Manufacturing Companies</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-400 to-pink-500 p-4 rounded-lg text-white">
                        <h4 class="font-bold mb-2"><i class="fas fa-cogs mr-2"></i>Core Technologies</h4>
                        <div class="text-sm">
                            <div>• PHP 8.1+ & Laravel Framework</div>
                            <div>• MySQL Database</div>
                            <div>• RESTful API Architecture</div>
                            <div>• Responsive Web Interface</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Architecture Overview -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-sitemap text-purple-600 mr-3"></i>
                System Architecture Overview
            </h2>
            
            <div class="grid lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3"><i class="fas fa-layer-group mr-2"></i>Multi-Tier Architecture</h3>
                    <div class="space-y-2 text-sm">
                        <div><strong>Presentation Layer:</strong> Web UI, Mobile Responsive</div>
                        <div><strong>Business Logic:</strong> Laravel Controllers & Services</div>
                        <div><strong>Data Access:</strong> Eloquent ORM</div>
                        <div><strong>Database:</strong> MySQL with Optimized Indexes</div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-teal-600 p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3"><i class="fas fa-shield-alt mr-2"></i>Security Features</h3>
                    <div class="space-y-2 text-sm">
                        <div>• Role-Based Access Control (RBAC)</div>
                        <div>• JWT Authentication</div>
                        <div>• Data Encryption</div>
                        <div>• Audit Trail & Activity Logging</div>
                        <div>• SQL Injection Prevention</div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-br from-orange-500 to-red-600 p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3"><i class="fas fa-tachometer-alt mr-2"></i>Performance</h3>
                    <div class="space-y-2 text-sm">
                        <div>• Database Query Optimization</div>
                        <div>• Redis Caching Layer</div>
                        <div>• Background Job Processing</div>
                        <div>• Compound Database Indexes</div>
                        <div>• API Rate Limiting</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-bold mb-4 text-gray-800">System Flow Diagram</h3>
                <div class="flex flex-wrap justify-center items-center space-x-4 text-sm">
                    <div class="bg-blue-100 px-4 py-2 rounded-lg border-2 border-blue-300">
                        <i class="fas fa-user mr-2 text-blue-600"></i>User Interface
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="bg-purple-100 px-4 py-2 rounded-lg border-2 border-purple-300">
                        <i class="fas fa-server mr-2 text-purple-600"></i>API Gateway
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="bg-green-100 px-4 py-2 rounded-lg border-2 border-green-300">
                        <i class="fas fa-cogs mr-2 text-green-600"></i>Business Logic
                    </div>
                    <i class="fas fa-arrow-right text-gray-400"></i>
                    <div class="bg-yellow-100 px-4 py-2 rounded-lg border-2 border-yellow-300">
                        <i class="fas fa-database mr-2 text-yellow-600"></i>Database
                    </div>
                </div>
            </div>
        </section>

        <!-- Core System Modules -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-puzzle-piece text-indigo-600 mr-3"></i>
                Core System Modules
            </h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- User Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-users mr-2"></i>User Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Multi-company support</li>
                        <li>• Role-based permissions</li>
                        <li>• Employee profiles</li>
                        <li>• Authentication system</li>
                        <li>• Branch assignments</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: users, companies, branches, roles, user_roles
                    </div>
                </div>

                <!-- Product Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-box mr-2"></i>Product Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Product catalog</li>
                        <li>• Categories & brands</li>
                        <li>• Product variants</li>
                        <li>• Pricing management</li>
                        <li>• Attribute system</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: products, categories, brands, units, product_variants
                    </div>
                </div>

                <!-- Inventory Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-warehouse mr-2"></i>Inventory Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Real-time stock tracking</li>
                        <li>• Stock movements</li>
                        <li>• Inter-branch transfers</li>
                        <li>• Stock adjustments</li>
                        <li>• Reorder alerts</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: inventory, stock_movements, stock_transfers
                    </div>
                </div>

                <!-- Sales Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-chart-line mr-2"></i>Sales Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Sales orders & quotes</li>
                        <li>• Point of Sale (POS)</li>
                        <li>• Customer management</li>
                        <li>• Invoice generation</li>
                        <li>• Sales analytics</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: sales_orders, pos_transactions, customers
                    </div>
                </div>

                <!-- Purchase Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-shopping-cart mr-2"></i>Purchase Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Purchase orders</li>
                        <li>• Supplier management</li>
                        <li>• Goods received notes</li>
                        <li>• Vendor payments</li>
                        <li>• Cost tracking</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: purchase_orders, suppliers, goods_received_notes
                    </div>
                </div>

                <!-- Financial Management -->
                <div class="module-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-calculator mr-2"></i>Financial Management
                    </h3>
                    <ul class="text-sm space-y-1">
                        <li>• Chart of accounts</li>
                        <li>• Journal entries</li>
                        <li>• Payment processing</li>
                        <li>• Tax management</li>
                        <li>• Financial reports</li>
                    </ul>
                    <div class="mt-4 text-xs opacity-80">
                        Tables: accounts, journal_entries, payments, tax_rates
                    </div>
                </div>
            </div>
        </section>

        <!-- Database Schema Overview -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-database text-green-600 mr-3"></i>
                Database Schema Overview
            </h2>
            
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Core Entities</h3>
                    <div class="space-y-3">
                        <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                            <h4 class="font-semibold text-blue-800">Users & Authentication</h4>
                            <p class="text-sm text-blue-700 mt-1">Multi-tenant user management with role-based access control across companies and branches.</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                            <h4 class="font-semibold text-green-800">Product Catalog</h4>
                            <p class="text-sm text-green-700 mt-1">Hierarchical product structure with categories, variants, and configurable attributes.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                            <h4 class="font-semibold text-purple-800">Inventory System</h4>
                            <p class="text-sm text-purple-700 mt-1">Real-time stock tracking with movement history and multi-location support.</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Key Statistics</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-500 to-purple-600 text-white rounded-lg">
                            <div class="text-2xl font-bold">50+</div>
                            <div class="text-sm">Database Tables</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-500 to-teal-600 text-white rounded-lg">
                            <div class="text-2xl font-bold">200+</div>
                            <div class="text-sm">Database Fields</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-lg">
                            <div class="text-2xl font-bold">100+</div>
                            <div class="text-sm">Database Indexes</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-pink-500 to-rose-600 text-white rounded-lg">
                            <div class="text-2xl font-bold">Multi</div>
                            <div class="text-sm">Tenant Support</div>
                        </div>
                    </div>
                    
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold mb-2">Database Features</h4>
                        <ul class="text-sm space-y-1">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Foreign key constraints</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Composite indexes for performance</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Full-text search capability</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>JSON field support</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Soft delete functionality</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <h3 class="text-xl font-bold mb-4 text-gray-700">Key Database Tables</h3>
                <table class="w-full text-sm border-collapse border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">Module</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Primary Tables</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Key Features</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Core System</td>
                            <td class="border border-gray-300 px-4 py-2">users, companies, branches, roles</td>
                            <td class="border border-gray-300 px-4 py-2">Multi-tenant, RBAC, Branch management</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Products</td>
                            <td class="border border-gray-300 px-4 py-2">products, categories, brands, product_variants</td>
                            <td class="border border-gray-300 px-4 py-2">Hierarchical categories, Product variants, Attributes</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Inventory</td>
                            <td class="border border-gray-300 px-4 py-2">inventory, stock_movements, stock_transfers</td>
                            <td class="border border-gray-300 px-4 py-2">Real-time tracking, Movement history, Transfers</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Sales</td>
                            <td class="border border-gray-300 px-4 py-2">sales_orders, pos_transactions, customers</td>
                            <td class="border border-gray-300 px-4 py-2">Order management, POS integration, Customer CRM</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Purchasing</td>
                            <td class="border border-gray-300 px-4 py-2">purchase_orders, suppliers, goods_received_notes</td>
                            <td class="border border-gray-300 px-4 py-2">PO workflow, Supplier management, GRN tracking</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">Financial</td>
                            <td class="border border-gray-300 px-4 py-2">accounts, journal_entries, payments</td>
                            <td class="border border-gray-300 px-4 py-2">Chart of accounts, Double-entry, Payment tracking</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Technical Specifications -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-code text-red-600 mr-3"></i>
                Technical Specifications
            </h2>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Backend Technology Stack</h3>
                    <div class="space-y-3">
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fab fa-php mr-2"></i>PHP 8.1+</div>
                            <div class="text-sm opacity-90">Core programming language</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-layer-group mr-2"></i>Laravel Framework</div>
                            <div class="text-sm opacity-90">MVC architecture, Eloquent ORM</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-database mr-2"></i>MySQL 8.0+</div>
                            <div class="text-sm opacity-90">Primary database with JSON support</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-memory mr-2"></i>Redis</div>
                            <div class="text-sm opacity-90">Caching and session management</div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Frontend & Integration</h3>
                    <div class="space-y-3">
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fab fa-html5 mr-2"></i>HTML5/CSS3/JS</div>
                            <div class="text-sm opacity-90">Modern web standards</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-mobile-alt mr-2"></i>Responsive Design</div>
                            <div class="text-sm opacity-90">Mobile-first approach</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-plug mr-2"></i>RESTful APIs</div>
                            <div class="text-sm opacity-90">JSON-based communication</div>
                        </div>
                        <div class="tech-stack-item p-3 rounded-lg text-white">
                            <div class="font-semibold"><i class="fas fa-lock mr-2"></i>JWT Authentication</div>
                            <div class="text-sm opacity-90">Secure token-based auth</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-gray-100 to-gray-200 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Performance Optimizations</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-blue-700 mb-2">
                            <i class="fas fa-rocket mr-2"></i>Database
                        </h4>
                        <ul class="text-sm space-y-1">
                            <li>• Compound indexes</li>
                            <li>• Query optimization</li>
                            <li>• Full-text search</li>
                            <li>• Connection pooling</li>
                        </ul>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-green-700 mb-2">
                            <i class="fas fa-tachometer-alt mr-2"></i>Application
                        </h4>
                        <ul class="text-sm space-y-1">
                            <li>• Redis caching</li>
                            <li>• Lazy loading</li>
                            <li>• Background jobs</li>
                            <li>• API rate limiting</li>
                        </ul>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="font-semibold text-purple-700 mb-2">
                            <i class="fas fa-shield-alt mr-2"></i>Security
                        </h4>
                        <ul class="text-sm space-y-1">
                            <li>• SQL injection prevention</li>
                            <li>• CSRF protection</li>
                            <li>• XSS filtering</li>
                            <li>• Data encryption</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stakeholder Information -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-users-cog text-teal-600 mr-3"></i>
                Stakeholder Information
            </h2>
            
            <div class="grid md:grid-cols-3 gap-6">
                <div class="stakeholder-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-user-tie mr-2"></i>Business Managers
                    </h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Primary Interests:</strong></p>
                        <ul class="space-y-1 ml-4">
                            <li>• Business process automation</li>
                            <li>• Real-time reporting & analytics</li>
                            <li>• Cost reduction & efficiency</li>
                            <li>• Multi-location management</li>
                            <li>• Customer relationship management</li>
                        </ul>
                    </div>
                </div>

                <div class="stakeholder-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-code mr-2"></i>Developers
                    </h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>Technical Focus:</strong></p>
                        <ul class="space-y-1 ml-4">
                            <li>• Laravel framework architecture</li>
                            <li>• Database schema & relationships</li>
                            <li>• API endpoints & integration</li>
                            <li>• Security implementations</li>
                            <li>• Performance optimization</li>
                        </ul>
                    </div>
                </div>

                <div class="stakeholder-card p-6 rounded-lg text-white">
                    <h3 class="text-xl font-bold mb-3">
                        <i class="fas fa-users mr-2"></i>End Users
                    </h3>
                    <div class="space-y-2 text-sm">
                        <p><strong>User Experience:</strong></p>
                        <ul class="space-y-1 ml-4">
                            <li>• Intuitive user interface</li>
                            <li>• Mobile-responsive design</li>
                            <li>• Fast POS operations</li>
                            <li>• Easy inventory management</li>
                            <li>• Quick report generation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Implementation Roadmap -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-road text-orange-600 mr-3"></i>
                Implementation Roadmap
            </h2>
            
            <div class="space-y-6">
                <div class="grid md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-lg text-white text-center">
                        <div class="text-2xl font-bold mb-2">Phase 1</div>
                        <div class="text-sm">Core System Setup</div>
                        <div class="text-xs mt-2 opacity-80">Months 1-2</div>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-lg text-white text-center">
                        <div class="text-2xl font-bold mb-2">Phase 2</div>
                        <div class="text-sm">Inventory & POS</div>
                        <div class="text-xs mt-2 opacity-80">Months 3-4</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-4 rounded-lg text-white text-center">
                        <div class="text-2xl font-bold mb-2">Phase 3</div>
                        <div class="text-sm">Financial Integration</div>
                        <div class="text-xs mt-2 opacity-80">Months 5-6</div>
                    </div>
                    <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-lg text-white text-center">
                        <div class="text-2xl font-bold mb-2">Phase 4</div>
                        <div class="text-sm">Advanced Features</div>
                        <div class="text-xs mt-2 opacity-80">Months 7-8</div>
                    </div>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Deployment Considerations</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Server Requirements</h4>
                            <ul class="text-sm space-y-1">
                                <li><i class="fas fa-server text-blue-500 mr-2"></i>PHP 8.1+ with required extensions</li>
                                <li><i class="fas fa-database text-green-500 mr-2"></i>MySQL 8.0+ with InnoDB engine</li>
                                <li><i class="fas fa-memory text-purple-500 mr-2"></i>Redis server for caching</li>
                                <li><i class="fas fa-hdd text-orange-500 mr-2"></i>SSD storage recommended</li>
                                <li><i class="fas fa-shield-alt text-red-500 mr-2"></i>SSL certificate for HTTPS</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-700 mb-2">Migration Strategy</h4>
                            <ul class="text-sm space-y-1">
                                <li><i class="fas fa-check text-green-500 mr-2"></i>Data backup and validation</li>
                                <li><i class="fas fa-check text-green-500 mr-2"></i>Parallel system testing</li>
                                <li><i class="fas fa-check text-green-500 mr-2"></i>User training programs</li>
                                <li><i class="fas fa-check text-green-500 mr-2"></i>Phased rollout approach</li>
                                <li><i class="fas fa-check text-green-500 mr-2"></i>24/7 support during transition</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Key Business Benefits -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-trophy text-yellow-600 mr-3"></i>
                Key Business Benefits
            </h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-lg">
                    <i class="fas fa-chart-line text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Increased Efficiency</h3>
                    <p class="text-sm opacity-90">Automate manual processes and reduce operational overhead by up to 40%</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-lg">
                    <i class="fas fa-eye text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Real-time Visibility</h3>
                    <p class="text-sm opacity-90">Complete visibility into inventory, sales, and financial data across all locations</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-purple-400 to-purple-600 text-white rounded-lg">
                    <i class="fas fa-shield-alt text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Enhanced Security</h3>
                    <p class="text-sm opacity-90">Role-based access control and comprehensive audit trails for compliance</p>
                </div>
                
                <div class="text-center p-6 bg-gradient-to-br from-orange-400 to-orange-600 text-white rounded-lg">
                    <i class="fas fa-expand-arrows-alt text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Scalability</h3>
                    <p class="text-sm opacity-90">Easily scale from single location to multi-branch operations seamlessly</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <section class="bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-lg p-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold mb-4">
                    <i class="fas fa-handshake mr-2"></i>
                    Ready to Transform Your Business?
                </h2>
                <p class="text-gray-300 mb-4">
                    Custosell ERP & POS System provides the comprehensive solution your business needs to streamline operations, increase efficiency, and drive growth.
                </p>
                <div class="flex justify-center items-center space-x-8 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-phone mr-2 text-green-400"></i>
                        <span>24/7 Support</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-cloud mr-2 text-blue-400"></i>
                        <span>Cloud & On-Premise</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt mr-2 text-purple-400"></i>
                        <span>Mobile Responsive</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-lock mr-2 text-red-400"></i>
                        <span>Enterprise Security</span>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
