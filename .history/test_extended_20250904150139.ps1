# EXTENDED ENDPOINT TEST
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$baseUrl = "http://127.0.0.1:8000/api"

function Test-Endpoint {
    param([string]$Name, [string]$Url)
    
    Write-Host "Testing $Name..." -ForegroundColor Yellow
    try {
        $response = Invoke-WebRequest -Uri $Url -Method GET -Headers $headers
        Write-Host "SUCCESS: $($response.StatusCode)" -ForegroundColor Green
        return $true
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "ERROR: $statusCode" -ForegroundColor Red
        return $false
    }
}

Write-Host "EXTENDED ENDPOINT TESTING" -ForegroundColor Cyan
Write-Host ""

$successful = 0
$total = 0

# Authentication endpoints (known working)
$successful += if (Test-Endpoint "Profile" "$baseUrl/user/profile") { 1 } else { 0 }; $total++

# Category endpoints
$successful += if (Test-Endpoint "Categories Index" "$baseUrl/categories") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Categories Active" "$baseUrl/categories/active") { 1 } else { 0 }; $total++

# Customer endpoints  
$successful += if (Test-Endpoint "Customers Index" "$baseUrl/customers") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Customers Active" "$baseUrl/customers/active") { 1 } else { 0 }; $total++

# Product endpoints
$successful += if (Test-Endpoint "Products Index" "$baseUrl/products") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Active" "$baseUrl/products/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Search" "$baseUrl/products/search?q=test") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Products Low Stock" "$baseUrl/products/low-stock") { 1 } else { 0 }; $total++

# Project endpoints
$successful += if (Test-Endpoint "Projects Index" "$baseUrl/projects") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects Active" "$baseUrl/projects/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects by Status" "$baseUrl/projects/by-status/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Projects Statistics" "$baseUrl/projects/statistics") { 1 } else { 0 }; $total++

# Task endpoints
$successful += if (Test-Endpoint "Tasks Index" "$baseUrl/tasks") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks Active" "$baseUrl/tasks/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks by Status" "$baseUrl/tasks/by-status/pending") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Tasks My Tasks" "$baseUrl/tasks/my-tasks") { 1 } else { 0 }; $total++

# Order endpoints
$successful += if (Test-Endpoint "Orders Index" "$baseUrl/orders") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Active" "$baseUrl/orders/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders by Status" "$baseUrl/orders/by-status/pending") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Statistics" "$baseUrl/orders/statistics") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Orders Pending" "$baseUrl/orders/pending") { 1 } else { 0 }; $total++

# Invoice endpoints
$successful += if (Test-Endpoint "Invoices Index" "$baseUrl/invoices") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Active" "$baseUrl/invoices/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices by Status" "$baseUrl/invoices/by-status/draft") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Statistics" "$baseUrl/invoices/statistics") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Invoices Overdue" "$baseUrl/invoices/overdue") { 1 } else { 0 }; $total++

# Brand and Unit endpoints
$successful += if (Test-Endpoint "Brands Index" "$baseUrl/brands") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Brands Active" "$baseUrl/brands/active") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Units Index" "$baseUrl/units") { 1 } else { 0 }; $total++
$successful += if (Test-Endpoint "Units Active" "$baseUrl/units/active") { 1 } else { 0 }; $total++

Write-Host ""
Write-Host "RESULTS SUMMARY" -ForegroundColor Cyan
Write-Host "Successful: $successful" -ForegroundColor Green
Write-Host "Total: $total" -ForegroundColor White
$percentage = [math]::Round(($successful / $total) * 100, 2)
Write-Host "Success Rate: $percentage%" -ForegroundColor $(if ($percentage -gt 70) { "Green" } elseif ($percentage -gt 50) { "Yellow" } else { "Red" })
Write-Host ""
