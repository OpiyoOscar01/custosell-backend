# QUICK IMPROVEMENT TEST
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
        return $true
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "ERROR: $statusCode" -ForegroundColor Red
        return $false
    }
}

Write-Host "TESTING RECENT FIXES" -ForegroundColor Cyan
Write-Host ""

$successful = 0
$total = 0

$successful += if (Test-Quick "Products Index" "$baseUrl/products") { 1 } else { 0 }; $total++
$successful += if (Test-Quick "Products Low Stock" "$baseUrl/products/low-stock") { 1 } else { 0 }; $total++
$successful += if (Test-Quick "Projects Active" "$baseUrl/projects/active") { 1 } else { 0 }; $total++

Write-Host ""
Write-Host "Quick test: $successful/$total working" -ForegroundColor $(if ($successful -eq $total) { "Green" } else { "Yellow" })
Write-Host ""
