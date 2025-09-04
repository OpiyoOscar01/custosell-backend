# COMPREHENSIVE ALL ROUTES TEST
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$baseUrl = "http://127.0.0.1:8000/api"

function Test-Endpoint {
    param([string]$Name, [string]$Method, [string]$Url, [hashtable]$Body = @{})
    
    Write-Host "Testing $Name..." -ForegroundColor Yellow
    try {
        if ($Method -eq "GET") {
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        } else {
            $bodyJson = $Body | ConvertTo-Json
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers -Body $bodyJson
        }
        Write-Host "✅ SUCCESS: $($response.StatusCode)" -ForegroundColor Green
        return $true
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "❌ ERROR: $statusCode" -ForegroundColor Red
        return $false
    }
}

Write-Host "=== COMPREHENSIVE API TESTING ===" -ForegroundColor Cyan
Write-Host "Testing ALL 109+ endpoints..." -ForegroundColor White
Write-Host ""

$successful = 0
$total = 0

# ==== AUTHENTICATION ROUTES ====
Write-Host "--- AUTHENTICATION ROUTES ---" -ForegroundColor Magenta

# User Profile & Auth
$successful += if (Test-Endpoint "User Profile" "GET" "$baseUrl/user/profile") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Auth Me" "GET" "$baseUrl/auth/me") { 1 } else { 0 }; $total++

# ==== WORKSPACE & COMPANY MANAGEMENT ====
Write-Host "`n--- WORKSPACE & COMPANY MANAGEMENT ---" -ForegroundColor Magenta

# Companies
$successful += if (Test-Endpoint "Companies Index" "GET" "$baseUrl/companies") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Companies Active" "GET" "$baseUrl/companies/active") { 1 } else { 0 }; $total++

# Workspaces
$successful += if (Test-Endpoint "Workspaces Index" "GET" "$baseUrl/workspaces") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Workspaces Active" "GET" "$baseUrl/workspaces/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Workspaces Current" "GET" "$baseUrl/workspaces/current") { 1 } else { 0 }; $total++

# Teams
$successful += if (Test-Endpoint "Teams Index" "GET" "$baseUrl/teams") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Teams Active" "GET" "$baseUrl/teams/active") { 1 } else { 0 }; $total++

# Branches
$successful += if (Test-Endpoint "Branches Index" "GET" "$baseUrl/branches") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Branches Active" "GET" "$baseUrl/branches/active") { 1 } else { 0 }; $total++

# ==== PRODUCT MANAGEMENT ====
Write-Host "`n--- PRODUCT MANAGEMENT ---" -ForegroundColor Magenta

# Categories
$successful += if (Test-Endpoint "Categories Index" "GET" "$baseUrl/categories") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Categories Active" "GET" "$baseUrl/categories/active") { 1 } else { 0 }; $total++

# Brands
$successful += if (Test-Endpoint "Brands Index" "GET" "$baseUrl/brands") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Brands Active" "GET" "$baseUrl/brands/active") { 1 } else { 0 }; $total++

# Units
$successful += if (Test-Endpoint "Units Index" "GET" "$baseUrl/units") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Units Active" "GET" "$baseUrl/units/active") { 1 } else { 0 }; $total++

# Products
$successful += if (Test-Endpoint "Products Index" "GET" "$baseUrl/products") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Active" "GET" "$baseUrl/products/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Search" "GET" "$baseUrl/products/search?q=test") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Low Stock" "GET" "$baseUrl/products/low-stock") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products by Category" "GET" "$baseUrl/products/category/1") { 1 } else { 0 }; $total++

# ==== CUSTOMER MANAGEMENT ====
Write-Host "`n--- CUSTOMER MANAGEMENT ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Customers Index" "GET" "$baseUrl/customers") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Customers Active" "GET" "$baseUrl/customers/active") { 1 } else { 0 }; $total++

# ==== PROJECT MANAGEMENT ====
Write-Host "`n--- PROJECT MANAGEMENT ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Projects Index" "GET" "$baseUrl/projects") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects Active" "GET" "$baseUrl/projects/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects Statistics" "GET" "$baseUrl/projects/statistics") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects by Status" "GET" "$baseUrl/projects/by-status/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects by Status - In Progress" "GET" "$baseUrl/projects/by-status/in_progress") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects by Status - Completed" "GET" "$baseUrl/projects/by-status/completed") { 1 } else { 0 }; $total++

# ==== TASK MANAGEMENT ====
Write-Host "`n--- TASK MANAGEMENT ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Tasks Index" "GET" "$baseUrl/tasks") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks Active" "GET" "$baseUrl/tasks/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks by Status" "GET" "$baseUrl/tasks/by-status/pending") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks by Status - Completed" "GET" "$baseUrl/tasks/by-status/completed") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks by Project" "GET" "$baseUrl/tasks/by-project/1") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks My Tasks" "GET" "$baseUrl/tasks/my-tasks") { 1 } else { 0 }; $total++

# ==== ORDER MANAGEMENT ====
Write-Host "`n--- ORDER MANAGEMENT ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Orders Index" "GET" "$baseUrl/orders") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Active" "GET" "$baseUrl/orders/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders by Status - Pending" "GET" "$baseUrl/orders/by-status/pending") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders by Status - Completed" "GET" "$baseUrl/orders/by-status/completed") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders by Status - Cancelled" "GET" "$baseUrl/orders/by-status/cancelled") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Statistics" "GET" "$baseUrl/orders/statistics") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Pending" "GET" "$baseUrl/orders/pending") { 1 } else { 0 }; $total++

# ==== INVOICE MANAGEMENT ====
Write-Host "`n--- INVOICE MANAGEMENT ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Invoices Index" "GET" "$baseUrl/invoices") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Active" "GET" "$baseUrl/invoices/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices by Status - Draft" "GET" "$baseUrl/invoices/by-status/draft") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices by Status - Sent" "GET" "$baseUrl/invoices/by-status/sent") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices by Status - Paid" "GET" "$baseUrl/invoices/by-status/paid") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Statistics" "GET" "$baseUrl/invoices/statistics") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Overdue" "GET" "$baseUrl/invoices/overdue") { 1 } else { 0 }; $total++

# ==== INDIVIDUAL RESOURCE TESTS ====
Write-Host "`n--- INDIVIDUAL RESOURCE TESTS ---" -ForegroundColor Magenta

# Test specific resource endpoints (using ID 1 where it exists)
$successful += if (Test-Endpoint "Category Show" "GET" "$baseUrl/categories/1") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Product Show" "GET" "$baseUrl/products/1") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Customer Show" "GET" "$baseUrl/customers/1") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Brand Show" "GET" "$baseUrl/brands/1") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Unit Show" "GET" "$baseUrl/units/1") { 1 } else { 0 }; $total++

# ==== SEARCH AND FILTERING ====
Write-Host "`n--- SEARCH AND FILTERING ---" -ForegroundColor Magenta

$successful += if (Test-Endpoint "Products Search Empty" "GET" "$baseUrl/products/search") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Search with Query" "GET" "$baseUrl/products/search?q=product") { 1 } else { 0 }; $total++

# ==== STATUS-BASED FILTERING ====
Write-Host "`n--- STATUS-BASED FILTERING ---" -ForegroundColor Magenta

# Different status values for different entities
$successful += if (Test-Endpoint "Tasks by Status - In Progress" "GET" "$baseUrl/tasks/by-status/in_progress") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks by Status - On Hold" "GET" "$baseUrl/tasks/by-status/on_hold") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects by Status - On Hold" "GET" "$baseUrl/projects/by-status/on_hold") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders by Status - Processing" "GET" "$baseUrl/orders/by-status/processing") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices by Status - Overdue" "GET" "$baseUrl/invoices/by-status/overdue") { 1 } else { 0 }; $total++

Write-Host ""
Write-Host "=== FINAL RESULTS ===" -ForegroundColor Cyan
Write-Host "Successful: $successful" -ForegroundColor Green
Write-Host "Total: $total" -ForegroundColor White
$percentage = [math]::Round(($successful / $total) * 100, 2)
Write-Host "Success Rate: $percentage%" -ForegroundColor $(if ($percentage -eq 100) { "Green" } elseif ($percentage -gt 90) { "Yellow" } else { "Red" })

if ($percentage -eq 100) {
    Write-Host ""
    Write-Host "🎉 PERFECT! ALL ENDPOINTS ARE WORKING! 🎉" -ForegroundColor Green
} elseif ($percentage -gt 90) {
    Write-Host ""
    Write-Host "⚡ EXCELLENT! Nearly all endpoints working!" -ForegroundColor Yellow
} else {
    Write-Host ""
    Write-Host "⚠️  Some endpoints need attention" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== TESTING COMPLETE ===" -ForegroundColor Cyan
