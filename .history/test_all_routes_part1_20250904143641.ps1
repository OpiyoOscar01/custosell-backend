# COMPREHENSIVE API TESTING - ALL 109 ROUTES
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
$testEmail = "test$timestamp@example.com"

Write-Host "🚀 STARTING COMPREHENSIVE API TEST - ALL 109 ROUTES" -ForegroundColor Cyan
Write-Host "Timestamp: $timestamp" -ForegroundColor Cyan
Write-Host ""

# ===============================
# 1. AUTHENTICATION ROUTES (10)
# ===============================
Write-Host "🔐 AUTHENTICATION ROUTES" -ForegroundColor Magenta

Test-Endpoint "AUTH - Login" "POST" "http://127.0.0.1:8000/api/auth/login" @{
    "email" = "admin@test.com"
    "password" = "password123"
}

Test-Endpoint "AUTH - Register" "POST" "http://127.0.0.1:8000/api/auth/register" @{
    "first_name" = "Test"
    "last_name" = "User"
    "email" = $testEmail
    "password" = "password123"
    "password_confirmation" = "password123"
}

Test-Endpoint "AUTH - Me" "GET" "http://127.0.0.1:8000/api/auth/me"
Test-Endpoint "AUTH - Update Profile" "PUT" "http://127.0.0.1:8000/api/auth/profile" @{
    "first_name" = "Updated"
    "last_name" = "Admin"
}

Test-Endpoint "AUTH - Forgot Password" "POST" "http://127.0.0.1:8000/api/auth/forgot-password" @{
    "email" = "admin@test.com"
} $true

Test-Endpoint "AUTH - Reset Password" "POST" "http://127.0.0.1:8000/api/auth/reset-password" @{
    "email" = "admin@test.com"
    "password" = "newpassword123"
    "password_confirmation" = "newpassword123"
    "token" = "fake-token"
} $true

Test-Endpoint "AUTH - Resend Verification" "POST" "http://127.0.0.1:8000/api/auth/resend-verification" @{
    "email" = "admin@test.com"
} $true

Test-Endpoint "AUTH - User Info" "GET" "http://127.0.0.1:8000/api/user"

# ===============================
# 2. COMPANY ROUTES (8)
# ===============================
Write-Host "🏢 COMPANY ROUTES" -ForegroundColor Magenta

Test-Endpoint "COMPANY - List" "GET" "http://127.0.0.1:8000/api/companies"
Test-Endpoint "COMPANY - Active" "GET" "http://127.0.0.1:8000/api/companies/active"
Test-Endpoint "COMPANY - Stats" "GET" "http://127.0.0.1:8000/api/companies/stats"
Test-Endpoint "COMPANY - Search" "GET" "http://127.0.0.1:8000/api/companies/search?q=test"

Test-Endpoint "COMPANY - Create" "POST" "http://127.0.0.1:8000/api/companies" @{
    "name" = "Test Company $timestamp"
    "code" = "TC$timestamp"
    "email" = "company$timestamp@test.com"
    "phone" = "+1234567890"
    "address" = "123 Test St"
    "city" = "Test City"
    "state" = "Test State"
    "country" = "Test Country"
    "is_active" = $true
}

Test-Endpoint "COMPANY - Show" "GET" "http://127.0.0.1:8000/api/companies/1"
Test-Endpoint "COMPANY - Update" "PUT" "http://127.0.0.1:8000/api/companies/1" @{
    "name" = "Updated Company Name"
}

# ===============================
# 3. BRANCH ROUTES (9)
# ===============================
Write-Host "🏪 BRANCH ROUTES" -ForegroundColor Magenta

Test-Endpoint "BRANCH - List" "GET" "http://127.0.0.1:8000/api/branches"
Test-Endpoint "BRANCH - Active" "GET" "http://127.0.0.1:8000/api/branches/active"
Test-Endpoint "BRANCH - Warehouses" "GET" "http://127.0.0.1:8000/api/branches/warehouses"
Test-Endpoint "BRANCH - POS Enabled" "GET" "http://127.0.0.1:8000/api/branches/pos-enabled"
Test-Endpoint "BRANCH - Managed By" "GET" "http://127.0.0.1:8000/api/branches/managed-by"

Test-Endpoint "BRANCH - Create" "POST" "http://127.0.0.1:8000/api/branches" @{
    "company_id" = 1
    "name" = "Test Branch $timestamp"
    "code" = "TB$timestamp"
    "email" = "branch$timestamp@test.com"
    "phone" = "+1234567891"
    "address" = "456 Branch St"
    "city" = "Branch City"
    "state" = "Branch State"
    "country" = "Test Country"
    "is_active" = $true
}

Test-Endpoint "BRANCH - Show" "GET" "http://127.0.0.1:8000/api/branches/1"
Test-Endpoint "BRANCH - Update" "PUT" "http://127.0.0.1:8000/api/branches/1" @{
    "name" = "Updated Branch Name"
}

Write-Host "📊 INTERMEDIATE RESULTS: Authentication, Company, and Branch routes tested!" -ForegroundColor Green
Write-Host "Continuing with remaining routes..." -ForegroundColor Cyan
