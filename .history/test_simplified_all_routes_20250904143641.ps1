# SIMPLIFIED COMPREHENSIVE API TEST - ALL 109 ROUTES
$adminToken = "4|osmuCsyLTq9bXcQJUXT3v4RWUtCpNNPNYoeCs26D2d1607ec"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$successCount = 0
$errorCount = 0
$timestamp = (Get-Date).ToString("yyyyMMddHHmmss")

function Test-Route {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [object]$Body = $null
    )
    
    try {
        if ($Body) {
            $bodyJson = $Body | ConvertTo-Json -Depth 3
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers -Body $bodyJson
        } else {
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        }
        Write-Host "SUCCESS: $Name ($($response.StatusCode))" -ForegroundColor Green
        $script:successCount++
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "ERROR: $Name ($statusCode)" -ForegroundColor Red
        $script:errorCount++
    }
}

Write-Host "COMPREHENSIVE API TEST - ALL 109 ROUTES" -ForegroundColor Cyan
Write-Host "Timestamp: $timestamp" -ForegroundColor Gray
Write-Host ""

# AUTHENTICATION ROUTES (10)
Write-Host "Testing Authentication Routes..." -ForegroundColor Yellow
Test-Route "Auth Login" "POST" "http://127.0.0.1:8000/api/auth/login" @{ "email" = "admin@test.com"; "password" = "password123" }
Test-Route "Auth Register" "POST" "http://127.0.0.1:8000/api/auth/register" @{ "first_name" = "Test"; "last_name" = "User"; "email" = "test$timestamp@example.com"; "password" = "password123"; "password_confirmation" = "password123" }
Test-Route "Auth Me" "GET" "http://127.0.0.1:8000/api/auth/me"
Test-Route "Auth Profile Update" "PUT" "http://127.0.0.1:8000/api/auth/profile" @{ "first_name" = "Updated"; "last_name" = "Admin" }
Test-Route "Auth User Info" "GET" "http://127.0.0.1:8000/api/user"
Test-Route "Auth Change Password" "POST" "http://127.0.0.1:8000/api/auth/change-password" @{ "current_password" = "password123"; "password" = "newpass123"; "password_confirmation" = "newpass123" }
Test-Route "Auth Forgot Password" "POST" "http://127.0.0.1:8000/api/auth/forgot-password" @{ "email" = "admin@test.com" }
Test-Route "Auth Reset Password" "POST" "http://127.0.0.1:8000/api/auth/reset-password" @{ "email" = "admin@test.com"; "password" = "newpass123"; "password_confirmation" = "newpass123"; "token" = "fake" }
Test-Route "Auth Resend Verification" "POST" "http://127.0.0.1:8000/api/auth/resend-verification" @{ "email" = "admin@test.com" }
Test-Route "Auth Logout" "POST" "http://127.0.0.1:8000/api/auth/logout" @{}

# COMPANY ROUTES (8)
Write-Host "Testing Company Routes..." -ForegroundColor Yellow
Test-Route "Company List" "GET" "http://127.0.0.1:8000/api/companies"
Test-Route "Company Active" "GET" "http://127.0.0.1:8000/api/companies/active"
Test-Route "Company Stats" "GET" "http://127.0.0.1:8000/api/companies/stats"
Test-Route "Company Search" "GET" "http://127.0.0.1:8000/api/companies/search?q=test"
Test-Route "Company Create" "POST" "http://127.0.0.1:8000/api/companies" @{ "name" = "Test Company $timestamp"; "code" = "TC$timestamp"; "email" = "company$timestamp@test.com"; "phone" = "+1234567890"; "address" = "123 Test St"; "city" = "Test City"; "state" = "Test State"; "country" = "Test Country"; "is_active" = $true }
Test-Route "Company Show" "GET" "http://127.0.0.1:8000/api/companies/1"
Test-Route "Company Update" "PUT" "http://127.0.0.1:8000/api/companies/1" @{ "name" = "Updated Company" }
Test-Route "Company Delete" "DELETE" "http://127.0.0.1:8000/api/companies/2"

# BRANCH ROUTES (9)
Write-Host "Testing Branch Routes..." -ForegroundColor Yellow
Test-Route "Branch List" "GET" "http://127.0.0.1:8000/api/branches"
Test-Route "Branch Active" "GET" "http://127.0.0.1:8000/api/branches/active"
Test-Route "Branch Warehouses" "GET" "http://127.0.0.1:8000/api/branches/warehouses"
Test-Route "Branch POS Enabled" "GET" "http://127.0.0.1:8000/api/branches/pos-enabled"
Test-Route "Branch Managed By" "GET" "http://127.0.0.1:8000/api/branches/managed-by"
Test-Route "Branch Create" "POST" "http://127.0.0.1:8000/api/branches" @{ "company_id" = 1; "name" = "Test Branch $timestamp"; "code" = "TB$timestamp"; "email" = "branch$timestamp@test.com"; "phone" = "+1234567891"; "address" = "456 Branch St"; "city" = "Branch City"; "state" = "Branch State"; "country" = "Test Country"; "is_active" = $true }
Test-Route "Branch Show" "GET" "http://127.0.0.1:8000/api/branches/1"
Test-Route "Branch Update" "PUT" "http://127.0.0.1:8000/api/branches/1" @{ "name" = "Updated Branch" }
Test-Route "Branch Delete" "DELETE" "http://127.0.0.1:8000/api/branches/2"

# BRAND ROUTES (6)
Write-Host "Testing Brand Routes..." -ForegroundColor Yellow
Test-Route "Brand List" "GET" "http://127.0.0.1:8000/api/brands"
Test-Route "Brand Active" "GET" "http://127.0.0.1:8000/api/brands/active"
Test-Route "Brand Create" "POST" "http://127.0.0.1:8000/api/brands" @{ "company_id" = 1; "name" = "Test Brand $timestamp"; "code" = "TBR$timestamp"; "description" = "Test brand"; "is_active" = $true }
Test-Route "Brand Show" "GET" "http://127.0.0.1:8000/api/brands/1"
Test-Route "Brand Update" "PUT" "http://127.0.0.1:8000/api/brands/1" @{ "name" = "Updated Brand" }
Test-Route "Brand Delete" "DELETE" "http://127.0.0.1:8000/api/brands/2"

# CATEGORY ROUTES (5)
Write-Host "Testing Category Routes..." -ForegroundColor Yellow
Test-Route "Category List" "GET" "http://127.0.0.1:8000/api/categories"
Test-Route "Category Active" "GET" "http://127.0.0.1:8000/api/categories/active"
Test-Route "Category Create" "POST" "http://127.0.0.1:8000/api/categories" @{ "company_id" = 1; "name" = "Test Category $timestamp"; "code" = "TCT$timestamp"; "description" = "Test category"; "is_active" = $true }
Test-Route "Category Show" "GET" "http://127.0.0.1:8000/api/categories/1"
Test-Route "Category Update" "PUT" "http://127.0.0.1:8000/api/categories/1" @{ "name" = "Updated Category" }

# CUSTOMER ROUTES (5)
Write-Host "Testing Customer Routes..." -ForegroundColor Yellow
Test-Route "Customer List" "GET" "http://127.0.0.1:8000/api/customers"
Test-Route "Customer Active" "GET" "http://127.0.0.1:8000/api/customers/active"
Test-Route "Customer Create" "POST" "http://127.0.0.1:8000/api/customers" @{ "company_id" = 1; "name" = "Test Customer $timestamp"; "email" = "customer$timestamp@test.com"; "phone" = "+1234567892"; "address" = "789 Customer St"; "city" = "Customer City"; "state" = "Customer State"; "country" = "Test Country"; "is_active" = $true }
Test-Route "Customer Show" "GET" "http://127.0.0.1:8000/api/customers/1"
Test-Route "Customer Update" "PUT" "http://127.0.0.1:8000/api/customers/1" @{ "name" = "Updated Customer" }

# UNIT ROUTES (6)
Write-Host "Testing Unit Routes..." -ForegroundColor Yellow
Test-Route "Unit List" "GET" "http://127.0.0.1:8000/api/units"
Test-Route "Unit Active" "GET" "http://127.0.0.1:8000/api/units/active"
Test-Route "Unit Create" "POST" "http://127.0.0.1:8000/api/units" @{ "company_id" = 1; "name" = "Test Unit $timestamp"; "code" = "TU$timestamp"; "symbol" = "tu$timestamp"; "description" = "Test unit"; "is_active" = $true }
Test-Route "Unit Show" "GET" "http://127.0.0.1:8000/api/units/1"
Test-Route "Unit Update" "PUT" "http://127.0.0.1:8000/api/units/1" @{ "name" = "Updated Unit" }
Test-Route "Unit Delete" "DELETE" "http://127.0.0.1:8000/api/units/2"

# PRODUCT ROUTES (7)
Write-Host "Testing Product Routes..." -ForegroundColor Yellow
Test-Route "Product List" "GET" "http://127.0.0.1:8000/api/products"
Test-Route "Product Active" "GET" "http://127.0.0.1:8000/api/products/active"
Test-Route "Product Create" "POST" "http://127.0.0.1:8000/api/products" @{ "name" = "Test Product $timestamp"; "code" = "TP$timestamp"; "description" = "Test product"; "brand_id" = 1; "category_id" = 1; "unit_id" = 1; "cost_price" = 10.50; "selling_price" = 15.75; "minimum_stock" = 5; "is_active" = $true }
Test-Route "Product Show" "GET" "http://127.0.0.1:8000/api/products/1"
Test-Route "Product Update" "PUT" "http://127.0.0.1:8000/api/products/1" @{ "name" = "Updated Product" }
Test-Route "Product Low Stock" "GET" "http://127.0.0.1:8000/api/products/low-stock"
Test-Route "Product Search" "GET" "http://127.0.0.1:8000/api/products/search?q=test"
Test-Route "Product Delete" "DELETE" "http://127.0.0.1:8000/api/products/2"

# PROJECT ROUTES (7)
Write-Host "Testing Project Routes..." -ForegroundColor Yellow
Test-Route "Project List" "GET" "http://127.0.0.1:8000/api/projects"
Test-Route "Project Active" "GET" "http://127.0.0.1:8000/api/projects/active"
Test-Route "Project Create" "POST" "http://127.0.0.1:8000/api/projects" @{ "name" = "Test Project $timestamp"; "code" = "PRJ$timestamp"; "description" = "Test project"; "customer_id" = 1; "branch_id" = 1; "start_date" = "2025-01-01"; "end_date" = "2025-12-31"; "budget" = 5000.00; "status" = "active"; "priority" = "medium"; "is_active" = $true }
Test-Route "Project Show" "GET" "http://127.0.0.1:8000/api/projects/1"
Test-Route "Project Update" "PUT" "http://127.0.0.1:8000/api/projects/1" @{ "name" = "Updated Project" }
Test-Route "Project Statistics" "GET" "http://127.0.0.1:8000/api/projects/statistics"
Test-Route "Project By Status" "GET" "http://127.0.0.1:8000/api/projects/by-status/active"
Test-Route "Project Delete" "DELETE" "http://127.0.0.1:8000/api/projects/2"

# TASK ROUTES (7)
Write-Host "Testing Task Routes..." -ForegroundColor Yellow
Test-Route "Task List" "GET" "http://127.0.0.1:8000/api/tasks"
Test-Route "Task Active" "GET" "http://127.0.0.1:8000/api/tasks/active"
Test-Route "Task Create" "POST" "http://127.0.0.1:8000/api/tasks" @{ "title" = "Test Task $timestamp"; "description" = "Test task"; "project_id" = 1; "assigned_to" = 1; "start_date" = "2025-01-01"; "due_date" = "2025-01-31"; "priority" = "medium"; "status" = "pending"; "estimated_hours" = 40; "is_active" = $true }
Test-Route "Task Show" "GET" "http://127.0.0.1:8000/api/tasks/1"
Test-Route "Task Update" "PUT" "http://127.0.0.1:8000/api/tasks/1" @{ "title" = "Updated Task" }
Test-Route "Task By Status" "GET" "http://127.0.0.1:8000/api/tasks/by-status/pending"
Test-Route "Task By Project" "GET" "http://127.0.0.1:8000/api/tasks/by-project/1"
Test-Route "Task Delete" "DELETE" "http://127.0.0.1:8000/api/tasks/2"

# ORDER ROUTES (7)
Write-Host "Testing Order Routes..." -ForegroundColor Yellow
Test-Route "Order List" "GET" "http://127.0.0.1:8000/api/orders"
Test-Route "Order Active" "GET" "http://127.0.0.1:8000/api/orders/active"
Test-Route "Order Create" "POST" "http://127.0.0.1:8000/api/orders" @{ "order_number" = "ORD$timestamp"; "customer_id" = 1; "branch_id" = 1; "order_date" = "2025-01-18"; "status" = "pending"; "total_amount" = 100.00; "tax_amount" = 10.00; "discount_amount" = 5.00; "notes" = "Test order"; "items" = @(@{ "product_id" = 1; "quantity" = 2; "unit_price" = 15.75; "total_price" = 31.50 }) }
Test-Route "Order Show" "GET" "http://127.0.0.1:8000/api/orders/1"
Test-Route "Order Update" "PUT" "http://127.0.0.1:8000/api/orders/1" @{ "status" = "confirmed" }
Test-Route "Order By Status" "GET" "http://127.0.0.1:8000/api/orders/by-status/pending"
Test-Route "Order Statistics" "GET" "http://127.0.0.1:8000/api/orders/statistics"
Test-Route "Order Delete" "DELETE" "http://127.0.0.1:8000/api/orders/2"

# INVOICE ROUTES (7)
Write-Host "Testing Invoice Routes..." -ForegroundColor Yellow
Test-Route "Invoice List" "GET" "http://127.0.0.1:8000/api/invoices"
Test-Route "Invoice Active" "GET" "http://127.0.0.1:8000/api/invoices/active"
Test-Route "Invoice Create" "POST" "http://127.0.0.1:8000/api/invoices" @{ "invoice_number" = "INV$timestamp"; "customer_id" = 1; "branch_id" = 1; "invoice_date" = "2025-01-18"; "due_date" = "2025-02-18"; "status" = "pending"; "subtotal" = 90.00; "tax_amount" = 9.00; "discount_amount" = 4.50; "total_amount" = 94.50; "notes" = "Test invoice" }
Test-Route "Invoice Show" "GET" "http://127.0.0.1:8000/api/invoices/1"
Test-Route "Invoice Update" "PUT" "http://127.0.0.1:8000/api/invoices/1" @{ "status" = "sent" }
Test-Route "Invoice By Status" "GET" "http://127.0.0.1:8000/api/invoices/by-status/pending"
Test-Route "Invoice Statistics" "GET" "http://127.0.0.1:8000/api/invoices/statistics"
Test-Route "Invoice Delete" "DELETE" "http://127.0.0.1:8000/api/invoices/2"

# TEAM ROUTES (10)
Write-Host "Testing Team Routes..." -ForegroundColor Yellow
Test-Route "Team List" "GET" "http://127.0.0.1:8000/api/teams"
Test-Route "Team Create" "POST" "http://127.0.0.1:8000/api/teams" @{ "name" = "Test Team $timestamp"; "description" = "Test team"; "is_active" = $true }
Test-Route "Team Show" "GET" "http://127.0.0.1:8000/api/teams/1"
Test-Route "Team Update" "PUT" "http://127.0.0.1:8000/api/teams/1" @{ "name" = "Updated Team" }
Test-Route "Team Members" "GET" "http://127.0.0.1:8000/api/teams/1/members"
Test-Route "Team Add Member" "POST" "http://127.0.0.1:8000/api/teams/1/members" @{ "user_id" = 1; "role" = "member" }
Test-Route "Team Update Member" "PUT" "http://127.0.0.1:8000/api/teams/1/members/1" @{ "role" = "admin" }
Test-Route "Team Remove Member" "DELETE" "http://127.0.0.1:8000/api/teams/1/members/1"
Test-Route "Team Leave" "POST" "http://127.0.0.1:8000/api/teams/1/leave" @{}
Test-Route "Team Delete" "DELETE" "http://127.0.0.1:8000/api/teams/2"

# WORKSPACE ROUTES (9)
Write-Host "Testing Workspace Routes..." -ForegroundColor Yellow
Test-Route "Workspace List" "GET" "http://127.0.0.1:8000/api/workspaces"
Test-Route "Workspace Create" "POST" "http://127.0.0.1:8000/api/workspaces" @{ "name" = "Test Workspace $timestamp"; "description" = "Test workspace"; "is_active" = $true }
Test-Route "Workspace Show" "GET" "http://127.0.0.1:8000/api/workspaces/1"
Test-Route "Workspace Update" "PUT" "http://127.0.0.1:8000/api/workspaces/1" @{ "name" = "Updated Workspace" }
Test-Route "Workspace Members" "GET" "http://127.0.0.1:8000/api/workspaces/1/members"
Test-Route "Workspace Add Member" "POST" "http://127.0.0.1:8000/api/workspaces/1/members" @{ "user_id" = 1; "role" = "member" }
Test-Route "Workspace Update Member" "PUT" "http://127.0.0.1:8000/api/workspaces/1/members/1" @{ "role" = "admin" }
Test-Route "Workspace Remove Member" "DELETE" "http://127.0.0.1:8000/api/workspaces/1/members/1"
Test-Route "Workspace Leave" "POST" "http://127.0.0.1:8000/api/workspaces/1/leave" @{}

Write-Host ""
Write-Host "COMPREHENSIVE TEST RESULTS" -ForegroundColor Cyan
Write-Host "============================" -ForegroundColor Cyan
Write-Host "Total Routes Tested: 109" -ForegroundColor White
Write-Host "Successful: $successCount" -ForegroundColor Green
Write-Host "Errors: $errorCount" -ForegroundColor Red
Write-Host "Success Rate: $([Math]::Round(($successCount / 109) * 100, 2))%" -ForegroundColor Yellow
Write-Host ""
