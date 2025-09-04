# COMPREHENSIVE API TESTING - PART 2 (Remaining Routes)
$adminToken = "4|osmuCsyLTq9bXcQJUXT3v4RWUtCpNNPNYoeCs26D2d1607ec"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

function Test-Endpoint {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [object]$Body = $null,
        [bool]$ExpectError = $false
    )
    
    Write-Host "=== TESTING $Name ===" -ForegroundColor Yellow
    try {
        if ($Body) {
            $bodyJson = $Body | ConvertTo-Json -Depth 3
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers -Body $bodyJson
        } else {
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        }
        Write-Host "✅ Success: $($response.StatusCode)" -ForegroundColor Green
        if ($response.Content.Length -lt 300) {
            Write-Host "Response: $($response.Content)" -ForegroundColor Gray
        } else {
            $responseObj = $response.Content | ConvertFrom-Json
            if ($responseObj.message) {
                Write-Host "Message: $($responseObj.message)" -ForegroundColor Gray
            }
            if ($responseObj.data -and $responseObj.data.Count) {
                Write-Host "Data Count: $($responseObj.data.Count)" -ForegroundColor Gray
            }
        }
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        if ($ExpectError) {
            Write-Host "⚠️  Expected Error: $statusCode" -ForegroundColor Orange
        } else {
            Write-Host "❌ Error: $statusCode" -ForegroundColor Red
            if ($_.Exception.Response) {
                $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
                $responseBody = $reader.ReadToEnd()
                if ($responseBody.Length -lt 200) {
                    Write-Host "Response: $responseBody" -ForegroundColor Red
                }
            }
        }
    }
    Write-Host ""
}

$timestamp = (Get-Date).ToString("yyyyMMddHHmmss")

Write-Host "🚀 CONTINUING COMPREHENSIVE API TEST - PART 2" -ForegroundColor Cyan
Write-Host "Timestamp: $timestamp" -ForegroundColor Cyan
Write-Host ""

# ===============================
# 4. BRAND ROUTES (6)
# ===============================
Write-Host "🏷️ BRAND ROUTES" -ForegroundColor Magenta

Test-Endpoint "BRAND - List" "GET" "http://127.0.0.1:8000/api/brands"
Test-Endpoint "BRAND - Active" "GET" "http://127.0.0.1:8000/api/brands/active"

Test-Endpoint "BRAND - Create" "POST" "http://127.0.0.1:8000/api/brands" @{
    "name" = "Test Brand $timestamp"
    "code" = "TBR$timestamp"
    "description" = "Test brand description"
    "is_active" = $true
}

Test-Endpoint "BRAND - Show" "GET" "http://127.0.0.1:8000/api/brands/1"
Test-Endpoint "BRAND - Update" "PUT" "http://127.0.0.1:8000/api/brands/1" @{
    "name" = "Updated Brand Name"
}

# ===============================
# 5. CATEGORY ROUTES (5)
# ===============================
Write-Host "📂 CATEGORY ROUTES" -ForegroundColor Magenta

Test-Endpoint "CATEGORY - List" "GET" "http://127.0.0.1:8000/api/categories"
Test-Endpoint "CATEGORY - Active" "GET" "http://127.0.0.1:8000/api/categories/active"

Test-Endpoint "CATEGORY - Create" "POST" "http://127.0.0.1:8000/api/categories" @{
    "name" = "Test Category $timestamp"
    "code" = "TCT$timestamp"
    "description" = "Test category description"
    "is_active" = $true
}

Test-Endpoint "CATEGORY - Show" "GET" "http://127.0.0.1:8000/api/categories/1"
Test-Endpoint "CATEGORY - Update" "PUT" "http://127.0.0.1:8000/api/categories/1" @{
    "name" = "Updated Category Name"
}

# ===============================
# 6. CUSTOMER ROUTES (5)
# ===============================
Write-Host "👥 CUSTOMER ROUTES" -ForegroundColor Magenta

Test-Endpoint "CUSTOMER - List" "GET" "http://127.0.0.1:8000/api/customers"
Test-Endpoint "CUSTOMER - Active" "GET" "http://127.0.0.1:8000/api/customers/active"

Test-Endpoint "CUSTOMER - Create" "POST" "http://127.0.0.1:8000/api/customers" @{
    "name" = "Test Customer $timestamp"
    "email" = "customer$timestamp@test.com"
    "phone" = "+1234567892"
    "address" = "789 Customer St"
    "city" = "Customer City"
    "state" = "Customer State"
    "country" = "Test Country"
    "is_active" = $true
}

Test-Endpoint "CUSTOMER - Show" "GET" "http://127.0.0.1:8000/api/customers/1"
Test-Endpoint "CUSTOMER - Update" "PUT" "http://127.0.0.1:8000/api/customers/1" @{
    "name" = "Updated Customer Name"
}

# ===============================
# 7. UNIT ROUTES (6)
# ===============================
Write-Host "📏 UNIT ROUTES" -ForegroundColor Magenta

Test-Endpoint "UNIT - List" "GET" "http://127.0.0.1:8000/api/units"
Test-Endpoint "UNIT - Active" "GET" "http://127.0.0.1:8000/api/units/active"

Test-Endpoint "UNIT - Create" "POST" "http://127.0.0.1:8000/api/units" @{
    "name" = "Test Unit $timestamp"
    "code" = "TU$timestamp"
    "symbol" = "tu$timestamp"
    "description" = "Test unit description"
    "is_active" = $true
}

Test-Endpoint "UNIT - Show" "GET" "http://127.0.0.1:8000/api/units/1"
Test-Endpoint "UNIT - Update" "PUT" "http://127.0.0.1:8000/api/units/1" @{
    "name" = "Updated Unit Name"
}

# ===============================
# 8. PRODUCT ROUTES (7)
# ===============================
Write-Host "📦 PRODUCT ROUTES" -ForegroundColor Magenta

Test-Endpoint "PRODUCT - List" "GET" "http://127.0.0.1:8000/api/products"
Test-Endpoint "PRODUCT - Active" "GET" "http://127.0.0.1:8000/api/products/active"

Test-Endpoint "PRODUCT - Create" "POST" "http://127.0.0.1:8000/api/products" @{
    "name" = "Test Product $timestamp"
    "code" = "TP$timestamp"
    "description" = "Test product description"
    "brand_id" = 1
    "category_id" = 1
    "unit_id" = 1
    "cost_price" = 10.50
    "selling_price" = 15.75
    "minimum_stock" = 5
    "is_active" = $true
}

Test-Endpoint "PRODUCT - Show" "GET" "http://127.0.0.1:8000/api/products/1"
Test-Endpoint "PRODUCT - Update" "PUT" "http://127.0.0.1:8000/api/products/1" @{
    "name" = "Updated Product Name"
}

Test-Endpoint "PRODUCT - Low Stock" "GET" "http://127.0.0.1:8000/api/products/low-stock"
Test-Endpoint "PRODUCT - Search" "GET" "http://127.0.0.1:8000/api/products/search?q=test"

# ===============================
# 9. PROJECT ROUTES (7)
# ===============================
Write-Host "🎯 PROJECT ROUTES" -ForegroundColor Magenta

Test-Endpoint "PROJECT - List" "GET" "http://127.0.0.1:8000/api/projects"
Test-Endpoint "PROJECT - Active" "GET" "http://127.0.0.1:8000/api/projects/active"

Test-Endpoint "PROJECT - Create" "POST" "http://127.0.0.1:8000/api/projects" @{
    "name" = "Test Project $timestamp"
    "code" = "PRJ$timestamp"
    "description" = "Test project description"
    "customer_id" = 1
    "branch_id" = 1
    "start_date" = "2025-01-01"
    "end_date" = "2025-12-31"
    "budget" = 5000.00
    "status" = "active"
    "priority" = "medium"
    "is_active" = $true
}

Test-Endpoint "PROJECT - Show" "GET" "http://127.0.0.1:8000/api/projects/1"
Test-Endpoint "PROJECT - Update" "PUT" "http://127.0.0.1:8000/api/projects/1" @{
    "name" = "Updated Project Name"
}

Test-Endpoint "PROJECT - Statistics" "GET" "http://127.0.0.1:8000/api/projects/statistics"
Test-Endpoint "PROJECT - By Status" "GET" "http://127.0.0.1:8000/api/projects/by-status/active"

# ===============================
# 10. TASK ROUTES (7)
# ===============================
Write-Host "✅ TASK ROUTES" -ForegroundColor Magenta

Test-Endpoint "TASK - List" "GET" "http://127.0.0.1:8000/api/tasks"
Test-Endpoint "TASK - Active" "GET" "http://127.0.0.1:8000/api/tasks/active"

Test-Endpoint "TASK - Create" "POST" "http://127.0.0.1:8000/api/tasks" @{
    "title" = "Test Task $timestamp"
    "description" = "Test task description"
    "project_id" = 1
    "assigned_to" = 1
    "start_date" = "2025-01-01"
    "due_date" = "2025-01-31"
    "priority" = "medium"
    "status" = "pending"
    "estimated_hours" = 40
    "is_active" = $true
}

Test-Endpoint "TASK - Show" "GET" "http://127.0.0.1:8000/api/tasks/1"
Test-Endpoint "TASK - Update" "PUT" "http://127.0.0.1:8000/api/tasks/1" @{
    "title" = "Updated Task Name"
}

Test-Endpoint "TASK - By Status" "GET" "http://127.0.0.1:8000/api/tasks/by-status/pending"
Test-Endpoint "TASK - By Project" "GET" "http://127.0.0.1:8000/api/tasks/by-project/1"

Write-Host "📊 PART 2 COMPLETE: Brands, Categories, Customers, Units, Products, Projects, Tasks tested!" -ForegroundColor Green
Write-Host "Ready for Part 3..." -ForegroundColor Cyan
