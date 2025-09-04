# NEW METHODS TEST
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$baseUrl = "http://127.0.0.1:8000/api"

function Test-Quick {
    param([string]$Name, [string]$Url)
    
    Write-Host "Testing $Name..." -ForegroundColor Yellow
    try {
        $response = Invoke-WebRequest -Uri $Url -Method GET -Headers $headers
        Write-Host "SUCCESS: $($response.StatusCode)" -ForegroundColor Green
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "ERROR: $statusCode" -ForegroundColor Red
    }
}

Write-Host "TESTING NEWLY ADDED METHODS" -ForegroundColor Cyan
Write-Host ""

# Test newly added methods
Test-Quick "Product Active" "$baseUrl/products/active"
Test-Quick "Product Search" "$baseUrl/products/search?q=test"
Test-Quick "Project by Status" "$baseUrl/projects/by-status/active"
Test-Quick "Task Active" "$baseUrl/tasks/active"
Test-Quick "Task by Status" "$baseUrl/tasks/by-status/pending"
Test-Quick "Order Active" "$baseUrl/orders/active"
Test-Quick "Order Statistics" "$baseUrl/orders/statistics"
Test-Quick "Invoice Active" "$baseUrl/invoices/active"
Test-Quick "Invoice Statistics" "$baseUrl/invoices/statistics"

Write-Host ""
Write-Host "NEW METHODS TEST COMPLETE" -ForegroundColor Green
