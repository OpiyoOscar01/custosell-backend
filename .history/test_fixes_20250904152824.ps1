# Test the 4 failing endpoints
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$baseUrl = "http://127.0.0.1:8000/api"

function Test-Endpoint {
    param([string]$Name, [string]$Method, [string]$Url)
    
    Write-Host "Testing $Name..." -ForegroundColor Yellow
    try {
        $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        Write-Host "✅ SUCCESS: $($response.StatusCode)" -ForegroundColor Green
        return $true
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "❌ ERROR: $statusCode - $($_.Exception.Message)" -ForegroundColor Red
        return $false
    }
}

Write-Host "=== TESTING PREVIOUSLY FAILING ENDPOINTS ===" -ForegroundColor Cyan

$successful = 0
$total = 4

$successful += if (Test-Endpoint "Workspaces Active" "GET" "$baseUrl/workspaces/active") { 1 } else { 0 }
$successful += if (Test-Endpoint "Workspaces Current" "GET" "$baseUrl/workspaces/current") { 1 } else { 0 }
$successful += if (Test-Endpoint "Teams Active" "GET" "$baseUrl/teams/active") { 1 } else { 0 }
$successful += if (Test-Endpoint "Products Search Empty" "GET" "$baseUrl/products/search") { 1 } else { 0 }

Write-Host ""
Write-Host "=== RESULTS ===" -ForegroundColor Cyan
Write-Host "Fixed: $successful/$total" -ForegroundColor $(if ($successful -eq $total) { "Green" } else { "Red" })

if ($successful -eq $total) {
    Write-Host "🎉 ALL PREVIOUSLY FAILING ENDPOINTS ARE NOW FIXED! 🎉" -ForegroundColor Green
}
