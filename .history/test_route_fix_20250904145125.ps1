# ROUTE FIX TEST
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

Write-Host "TESTING ROUTE FIXES" -ForegroundColor Cyan
Write-Host ""

Test-Quick "Category Active" "$baseUrl/categories/active"
Test-Quick "Brand Active" "$baseUrl/brands/active"
Test-Quick "Unit Active" "$baseUrl/units/active"

Write-Host ""
Write-Host "ROUTE FIX TEST COMPLETE" -ForegroundColor Green
