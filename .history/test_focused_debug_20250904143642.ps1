# FOCUSED TEST - KEY ENDPOINTS ONLY
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$timestamp = (Get-Date).ToString("yyyyMMddHHmmss")
$baseUrl = "http://127.0.0.1:8000/api"

function Test-Focused {
    param(
        [string]$Name,
        [string]$Method,
        [string]$Url,
        [object]$Body = $null
    )
    
    Write-Host "=== Testing $Name ===" -ForegroundColor Yellow
    try {
        if ($Body) {
            $bodyJson = $Body | ConvertTo-Json -Depth 3
            Write-Host "Request Body: $bodyJson" -ForegroundColor Gray
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers -Body $bodyJson
        } else {
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        }
        Write-Host "SUCCESS: $($response.StatusCode)" -ForegroundColor Green
        $responseObj = $response.Content | ConvertFrom-Json
        if ($responseObj.message) {
            Write-Host "Message: $($responseObj.message)" -ForegroundColor Gray
        }
    } catch {
        $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
        Write-Host "ERROR: $statusCode" -ForegroundColor Red
        if ($_.Exception.Response) {
            $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
            $responseBody = $reader.ReadToEnd()
            Write-Host "Error Response: $responseBody" -ForegroundColor Red
        }
    }
    Write-Host ""
}

Write-Host "FOCUSED TEST - DEBUGGING KEY VALIDATION ISSUES" -ForegroundColor Cyan
Write-Host ""

# Test Category Active (was failing with 500 error)
Test-Focused "Category Active" "GET" "$baseUrl/categories/active"

# Test Category Create (was failing with 422 validation)
Test-Focused "Category Create" "POST" "$baseUrl/categories" @{
    "name" = "Test Category $timestamp"
    "code" = "TCT$timestamp"
    "description" = "Test category"
    "type" = "product"
    "is_active" = $true
}

# Test Customer Active (was failing with 500 error)
Test-Focused "Customer Active" "GET" "$baseUrl/customers/active"

# Test Customer Create (was failing with 422 validation)
Test-Focused "Customer Create" "POST" "$baseUrl/customers" @{
    "name" = "Test Customer $timestamp"
    "email" = "customer$timestamp@test.com"
    "phone" = "+1234567892"
    "type" = "business"
    "is_active" = $true
}

# Test Brand Active (was failing with 500 error)
Test-Focused "Brand Active" "GET" "$baseUrl/brands/active"

# Test Unit Active (was failing with 500 error) 
Test-Focused "Unit Active" "GET" "$baseUrl/units/active"

Write-Host "FOCUSED TEST COMPLETE" -ForegroundColor Green
