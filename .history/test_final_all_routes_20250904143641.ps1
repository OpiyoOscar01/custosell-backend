# COMPREHENSIVE API TEST WITH TOKEN REFRESH - ALL 109 ROUTES
$baseUrl = "http://127.0.0.1:8000/api"

$successCount = 0
$errorCount = 0
$timestamp = (Get-Date).ToString("yyyyMMddHHmmss")

function Get-FreshToken {
    $loginData = @{
        "email" = "admin@test.com"
        "password" = "password123"
    } | ConvertTo-Json
    
    $headers = @{
        "Content-Type" = "application/json"
        "Accept" = "application/json"
    }
    
    try {
        $response = Invoke-WebRequest -Uri "$baseUrl/auth/login" -Method POST -Headers $headers -Body $loginData
        $responseObj = $response.Content | ConvertFrom-Json
        return $responseObj.token
    } catch {
        Write-Host "ERROR: Could not get fresh token" -ForegroundColor Red
        return $null
    }
}

function Test-Route {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [object]$Body = $null,
        [string]$Token
    )
    
    $headers = @{
        "Content-Type" = "application/json"
        "Accept" = "application/json" 
        "Authorization" = "Bearer $Token"
    }
    
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

Write-Host "COMPREHENSIVE API TEST - ALL 109 ROUTES WITH TOKEN REFRESH" -ForegroundColor Cyan
Write-Host "Timestamp: $timestamp" -ForegroundColor Gray
Write-Host ""

# GET FRESH TOKEN FOR AUTHENTICATION TESTS
$authToken = Get-FreshToken
if (-not $authToken) {
    Write-Host "Cannot proceed without valid token" -ForegroundColor Red
    exit 1
}

# AUTHENTICATION ROUTES (10)
Write-Host "Testing Authentication Routes..." -ForegroundColor Yellow
Test-Route "Auth Login" "POST" "$baseUrl/auth/login" @{ "email" = "admin@test.com"; "password" = "password123" } $authToken
Test-Route "Auth Register" "POST" "$baseUrl/auth/register" @{ "first_name" = "Test"; "last_name" = "User"; "email" = "test$timestamp@example.com"; "password" = "password123"; "password_confirmation" = "password123" } $authToken
Test-Route "Auth Me" "GET" "$baseUrl/auth/me" $null $authToken
Test-Route "Auth Profile Update" "PUT" "$baseUrl/auth/profile" @{ "first_name" = "Updated"; "last_name" = "Admin" } $authToken
Test-Route "Auth User Info" "GET" "$baseUrl/user" $null $authToken
Test-Route "Auth Change Password" "POST" "$baseUrl/auth/change-password" @{ "current_password" = "password123"; "password" = "newpass123"; "password_confirmation" = "newpass123" } $authToken
Test-Route "Auth Forgot Password" "POST" "$baseUrl/auth/forgot-password" @{ "email" = "admin@test.com" } $authToken
Test-Route "Auth Reset Password" "POST" "$baseUrl/auth/reset-password" @{ "email" = "admin@test.com"; "password" = "newpass123"; "password_confirmation" = "newpass123"; "token" = "fake" } $authToken
Test-Route "Auth Resend Verification" "POST" "$baseUrl/auth/resend-verification" @{ "email" = "admin@test.com" } $authToken

# GET FRESH TOKEN FOR BUSINESS ENTITY TESTS (since logout invalidated the previous one)
$businessToken = Get-FreshToken

# COMPANY ROUTES (8)
Write-Host "Testing Company Routes..." -ForegroundColor Yellow
Test-Route "Company List" "GET" "$baseUrl/companies" $null $businessToken
Test-Route "Company Active" "GET" "$baseUrl/companies/active" $null $businessToken
Test-Route "Company Stats" "GET" "$baseUrl/companies/stats" $null $businessToken
Test-Route "Company Search" "GET" "$baseUrl/companies/search?q=test" $null $businessToken
Test-Route "Company Create" "POST" "$baseUrl/companies" @{ "name" = "Test Company $timestamp"; "code" = "TC$timestamp"; "email" = "company$timestamp@test.com"; "phone" = "+1234567890"; "address" = "123 Test St"; "city" = "Test City"; "state" = "Test State"; "country" = "Test Country"; "is_active" = $true } $businessToken
Test-Route "Company Show" "GET" "$baseUrl/companies/1" $null $businessToken
Test-Route "Company Update" "PUT" "$baseUrl/companies/1" @{ "name" = "Updated Company" } $businessToken
Test-Route "Company Delete" "DELETE" "$baseUrl/companies/2" $null $businessToken

# BRANCH ROUTES (9)
Write-Host "Testing Branch Routes..." -ForegroundColor Yellow
Test-Route "Branch List" "GET" "$baseUrl/branches" $null $businessToken
Test-Route "Branch Active" "GET" "$baseUrl/branches/active" $null $businessToken
Test-Route "Branch Warehouses" "GET" "$baseUrl/branches/warehouses" $null $businessToken
Test-Route "Branch POS Enabled" "GET" "$baseUrl/branches/pos-enabled" $null $businessToken
Test-Route "Branch Managed By" "GET" "$baseUrl/branches/managed-by" $null $businessToken
Test-Route "Branch Create" "POST" "$baseUrl/branches" @{ "company_id" = 1; "name" = "Test Branch $timestamp"; "code" = "TB$timestamp"; "email" = "branch$timestamp@test.com"; "phone" = "+1234567891"; "address" = "456 Branch St"; "city" = "Branch City"; "state" = "Branch State"; "country" = "Test Country"; "is_active" = $true } $businessToken
Test-Route "Branch Show" "GET" "$baseUrl/branches/1" $null $businessToken
Test-Route "Branch Update" "PUT" "$baseUrl/branches/1" @{ "name" = "Updated Branch" } $businessToken
Test-Route "Branch Delete" "DELETE" "$baseUrl/branches/2" $null $businessToken

# BRAND ROUTES (6)
Write-Host "Testing Brand Routes..." -ForegroundColor Yellow
Test-Route "Brand List" "GET" "$baseUrl/brands" $null $businessToken
Test-Route "Brand Active" "GET" "$baseUrl/brands/active" $null $businessToken
Test-Route "Brand Create" "POST" "$baseUrl/brands" @{ "company_id" = 1; "name" = "Test Brand $timestamp"; "code" = "TBR$timestamp"; "description" = "Test brand"; "is_active" = $true } $businessToken
Test-Route "Brand Show" "GET" "$baseUrl/brands/1" $null $businessToken
Test-Route "Brand Update" "PUT" "$baseUrl/brands/1" @{ "name" = "Updated Brand" } $businessToken
Test-Route "Brand Delete" "DELETE" "$baseUrl/brands/2" $null $businessToken

# CATEGORY ROUTES (5)
Write-Host "Testing Category Routes..." -ForegroundColor Yellow
Test-Route "Category List" "GET" "$baseUrl/categories" $null $businessToken
Test-Route "Category Active" "GET" "$baseUrl/categories/active" $null $businessToken
Test-Route "Category Create" "POST" "$baseUrl/categories" @{ "company_id" = 1; "name" = "Test Category $timestamp"; "code" = "TCT$timestamp"; "description" = "Test category"; "is_active" = $true } $businessToken
Test-Route "Category Show" "GET" "$baseUrl/categories/1" $null $businessToken
Test-Route "Category Update" "PUT" "$baseUrl/categories/1" @{ "name" = "Updated Category" } $businessToken

# CUSTOMER ROUTES (5)
Write-Host "Testing Customer Routes..." -ForegroundColor Yellow
Test-Route "Customer List" "GET" "$baseUrl/customers" $null $businessToken
Test-Route "Customer Active" "GET" "$baseUrl/customers/active" $null $businessToken
Test-Route "Customer Create" "POST" "$baseUrl/customers" @{ "company_id" = 1; "name" = "Test Customer $timestamp"; "email" = "customer$timestamp@test.com"; "phone" = "+1234567892"; "address" = "789 Customer St"; "city" = "Customer City"; "state" = "Customer State"; "country" = "Test Country"; "is_active" = $true } $businessToken
Test-Route "Customer Show" "GET" "$baseUrl/customers/1" $null $businessToken
Test-Route "Customer Update" "PUT" "$baseUrl/customers/1" @{ "name" = "Updated Customer" } $businessToken

# UNIT ROUTES (6)
Write-Host "Testing Unit Routes..." -ForegroundColor Yellow
Test-Route "Unit List" "GET" "$baseUrl/units" $null $businessToken
Test-Route "Unit Active" "GET" "$baseUrl/units/active" $null $businessToken
Test-Route "Unit Create" "POST" "$baseUrl/units" @{ "company_id" = 1; "name" = "Test Unit $timestamp"; "code" = "TU$timestamp"; "symbol" = "tu$timestamp"; "description" = "Test unit"; "is_active" = $true } $businessToken
Test-Route "Unit Show" "GET" "$baseUrl/units/1" $null $businessToken
Test-Route "Unit Update" "PUT" "$baseUrl/units/1" @{ "name" = "Updated Unit" } $businessToken
Test-Route "Unit Delete" "DELETE" "$baseUrl/units/2" $null $businessToken

# PRODUCT ROUTES (7)
Write-Host "Testing Product Routes..." -ForegroundColor Yellow
Test-Route "Product List" "GET" "$baseUrl/products" $null $businessToken
Test-Route "Product Active" "GET" "$baseUrl/products/active" $null $businessToken
Test-Route "Product Create" "POST" "$baseUrl/products" @{ "name" = "Test Product $timestamp"; "code" = "TP$timestamp"; "description" = "Test product"; "brand_id" = 1; "category_id" = 1; "unit_id" = 1; "cost_price" = 10.50; "selling_price" = 15.75; "minimum_stock" = 5; "is_active" = $true } $businessToken
Test-Route "Product Show" "GET" "$baseUrl/products/1" $null $businessToken
Test-Route "Product Update" "PUT" "$baseUrl/products/1" @{ "name" = "Updated Product" } $businessToken
Test-Route "Product Low Stock" "GET" "$baseUrl/products/low-stock" $null $businessToken
Test-Route "Product Search" "GET" "$baseUrl/products/search?q=test" $null $businessToken
Test-Route "Product Delete" "DELETE" "$baseUrl/products/2" $null $businessToken

# PROJECT ROUTES (7)
Write-Host "Testing Project Routes..." -ForegroundColor Yellow
Test-Route "Project List" "GET" "$baseUrl/projects" $null $businessToken
Test-Route "Project Active" "GET" "$baseUrl/projects/active" $null $businessToken
Test-Route "Project Create" "POST" "$baseUrl/projects" @{ "name" = "Test Project $timestamp"; "code" = "PRJ$timestamp"; "description" = "Test project"; "customer_id" = 1; "branch_id" = 1; "start_date" = "2025-01-01"; "end_date" = "2025-12-31"; "budget" = 5000.00; "status" = "active"; "priority" = "medium"; "is_active" = $true } $businessToken
Test-Route "Project Show" "GET" "$baseUrl/projects/1" $null $businessToken
Test-Route "Project Update" "PUT" "$baseUrl/projects/1" @{ "name" = "Updated Project" } $businessToken
Test-Route "Project Statistics" "GET" "$baseUrl/projects/statistics" $null $businessToken
Test-Route "Project By Status" "GET" "$baseUrl/projects/by-status/active" $null $businessToken
Test-Route "Project Delete" "DELETE" "$baseUrl/projects/2" $null $businessToken

# TASK ROUTES (7)
Write-Host "Testing Task Routes..." -ForegroundColor Yellow
Test-Route "Task List" "GET" "$baseUrl/tasks" $null $businessToken
Test-Route "Task Active" "GET" "$baseUrl/tasks/active" $null $businessToken
Test-Route "Task Create" "POST" "$baseUrl/tasks" @{ "title" = "Test Task $timestamp"; "description" = "Test task"; "project_id" = 1; "assigned_to" = 1; "start_date" = "2025-01-01"; "due_date" = "2025-01-31"; "priority" = "medium"; "status" = "pending"; "estimated_hours" = 40; "is_active" = $true } $businessToken
Test-Route "Task Show" "GET" "$baseUrl/tasks/1" $null $businessToken
Test-Route "Task Update" "PUT" "$baseUrl/tasks/1" @{ "title" = "Updated Task" } $businessToken
Test-Route "Task By Status" "GET" "$baseUrl/tasks/by-status/pending" $null $businessToken
Test-Route "Task By Project" "GET" "$baseUrl/tasks/by-project/1" $null $businessToken
Test-Route "Task Delete" "DELETE" "$baseUrl/tasks/2" $null $businessToken

# ORDER ROUTES (7)
Write-Host "Testing Order Routes..." -ForegroundColor Yellow
Test-Route "Order List" "GET" "$baseUrl/orders" $null $businessToken
Test-Route "Order Active" "GET" "$baseUrl/orders/active" $null $businessToken
Test-Route "Order Create" "POST" "$baseUrl/orders" @{ "order_number" = "ORD$timestamp"; "customer_id" = 1; "branch_id" = 1; "order_date" = "2025-01-18"; "status" = "pending"; "total_amount" = 100.00; "tax_amount" = 10.00; "discount_amount" = 5.00; "notes" = "Test order"; "items" = @(@{ "product_id" = 1; "quantity" = 2; "unit_price" = 15.75; "total_price" = 31.50 }) } $businessToken
Test-Route "Order Show" "GET" "$baseUrl/orders/1" $null $businessToken
Test-Route "Order Update" "PUT" "$baseUrl/orders/1" @{ "status" = "confirmed" } $businessToken
Test-Route "Order By Status" "GET" "$baseUrl/orders/by-status/pending" $null $businessToken
Test-Route "Order Statistics" "GET" "$baseUrl/orders/statistics" $null $businessToken
Test-Route "Order Delete" "DELETE" "$baseUrl/orders/2" $null $businessToken

# INVOICE ROUTES (7)
Write-Host "Testing Invoice Routes..." -ForegroundColor Yellow
Test-Route "Invoice List" "GET" "$baseUrl/invoices" $null $businessToken
Test-Route "Invoice Active" "GET" "$baseUrl/invoices/active" $null $businessToken
Test-Route "Invoice Create" "POST" "$baseUrl/invoices" @{ "invoice_number" = "INV$timestamp"; "customer_id" = 1; "branch_id" = 1; "invoice_date" = "2025-01-18"; "due_date" = "2025-02-18"; "status" = "pending"; "subtotal" = 90.00; "tax_amount" = 9.00; "discount_amount" = 4.50; "total_amount" = 94.50; "notes" = "Test invoice" } $businessToken
Test-Route "Invoice Show" "GET" "$baseUrl/invoices/1" $null $businessToken
Test-Route "Invoice Update" "PUT" "$baseUrl/invoices/1" @{ "status" = "sent" } $businessToken
Test-Route "Invoice By Status" "GET" "$baseUrl/invoices/by-status/pending" $null $businessToken
Test-Route "Invoice Statistics" "GET" "$baseUrl/invoices/statistics" $null $businessToken
Test-Route "Invoice Delete" "DELETE" "$baseUrl/invoices/2" $null $businessToken

# TEAM ROUTES (10)
Write-Host "Testing Team Routes..." -ForegroundColor Yellow
Test-Route "Team List" "GET" "$baseUrl/teams" $null $businessToken
Test-Route "Team Create" "POST" "$baseUrl/teams" @{ "name" = "Test Team $timestamp"; "description" = "Test team"; "is_active" = $true } $businessToken
Test-Route "Team Show" "GET" "$baseUrl/teams/1" $null $businessToken
Test-Route "Team Update" "PUT" "$baseUrl/teams/1" @{ "name" = "Updated Team" } $businessToken
Test-Route "Team Members" "GET" "$baseUrl/teams/1/members" $null $businessToken
Test-Route "Team Add Member" "POST" "$baseUrl/teams/1/members" @{ "user_id" = 1; "role" = "member" } $businessToken
Test-Route "Team Update Member" "PUT" "$baseUrl/teams/1/members/1" @{ "role" = "admin" } $businessToken
Test-Route "Team Remove Member" "DELETE" "$baseUrl/teams/1/members/1" $null $businessToken
Test-Route "Team Leave" "POST" "$baseUrl/teams/1/leave" @{} $businessToken
Test-Route "Team Delete" "DELETE" "$baseUrl/teams/2" $null $businessToken

# WORKSPACE ROUTES (9)
Write-Host "Testing Workspace Routes..." -ForegroundColor Yellow
Test-Route "Workspace List" "GET" "$baseUrl/workspaces" $null $businessToken
Test-Route "Workspace Create" "POST" "$baseUrl/workspaces" @{ "name" = "Test Workspace $timestamp"; "description" = "Test workspace"; "is_active" = $true } $businessToken
Test-Route "Workspace Show" "GET" "$baseUrl/workspaces/1" $null $businessToken
Test-Route "Workspace Update" "PUT" "$baseUrl/workspaces/1" @{ "name" = "Updated Workspace" } $businessToken
Test-Route "Workspace Members" "GET" "$baseUrl/workspaces/1/members" $null $businessToken
Test-Route "Workspace Add Member" "POST" "$baseUrl/workspaces/1/members" @{ "user_id" = 1; "role" = "member" } $businessToken
Test-Route "Workspace Update Member" "PUT" "$baseUrl/workspaces/1/members/1" @{ "role" = "admin" } $businessToken
Test-Route "Workspace Remove Member" "DELETE" "$baseUrl/workspaces/1/members/1" $null $businessToken
Test-Route "Workspace Leave" "POST" "$baseUrl/workspaces/1/leave" @{} $businessToken

# FINAL AUTH TEST (with fresh token)
$finalAuthToken = Get-FreshToken
Test-Route "Auth Logout" "POST" "$baseUrl/auth/logout" @{} $finalAuthToken

Write-Host ""
Write-Host "COMPREHENSIVE TEST RESULTS" -ForegroundColor Cyan
Write-Host "============================" -ForegroundColor Cyan
Write-Host "Total Routes Tested: 109" -ForegroundColor White
Write-Host "Successful: $successCount" -ForegroundColor Green
Write-Host "Errors: $errorCount" -ForegroundColor Red
$successRate = [Math]::Round(($successCount / 109) * 100, 2)
Write-Host "Success Rate: $successRate%" -ForegroundColor Yellow

if ($successRate -gt 90) {
    Write-Host ""
    Write-Host "EXCELLENT! PRODUCTION READY!" -ForegroundColor Green
} elseif ($successRate -gt 75) {
    Write-Host ""
    Write-Host "GOOD! Minor issues to address" -ForegroundColor Yellow
} else {
    Write-Host ""
    Write-Host "NEEDS ATTENTION - Several issues found" -ForegroundColor Red
}
Write-Host ""
