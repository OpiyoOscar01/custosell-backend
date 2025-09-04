# Comprehensive API Route Testing
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
        [object]$Body = $null
    )
    
    Write-Host "=== TESTING $Name ==="
    try {
        if ($Body) {
            $bodyJson = $Body | ConvertTo-Json
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers -Body $bodyJson
        } else {
            $response = Invoke-WebRequest -Uri $Url -Method $Method -Headers $headers
        }
        Write-Host "✅ Success: $($response.StatusCode)"
        if ($response.Content.Length -lt 500) {
            Write-Host "Response: $($response.Content)"
        } else {
            $responseObj = $response.Content | ConvertFrom-Json
            if ($responseObj.data) {
                Write-Host "Data received: $($responseObj.data.GetType().Name)"
            }
            Write-Host "Message: $($responseObj.message)"
        }
    } catch {
        Write-Host "❌ Error: $($_.Exception.Response.StatusCode)"
        if ($_.Exception.Response) {
            $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
            $responseBody = $reader.ReadToEnd()
            Write-Host "Response: $responseBody"
        }
    }
    Write-Host ""
}

# Test Category Creation
Test-Endpoint "CATEGORY CREATION" "POST" "http://127.0.0.1:8000/api/categories" @{
    "name" = "Electronics"
    "description" = "Electronic products and devices"
    "type" = "product"
    "workspace_id" = 1
}

# Test Customer Creation  
Test-Endpoint "CUSTOMER CREATION" "POST" "http://127.0.0.1:8000/api/customers" @{
    "name" = "John Customer"
    "email" = "john.customer@example.com"
    "phone" = "+1234567892"
    "type" = "individual"
    "workspace_id" = 1
}

# Test Product Creation
Test-Endpoint "PRODUCT CREATION" "POST" "http://127.0.0.1:8000/api/products" @{
    "name" = "Test Product"
    "description" = "A test product"
    "sku" = "PROD001"
    "category_id" = 1
    "unit_id" = 1
    "cost_price" = 50.00
    "price" = 99.99
    "workspace_id" = 1
}

# Test Project Creation
Test-Endpoint "PROJECT CREATION" "POST" "http://127.0.0.1:8000/api/projects" @{
    "name" = "Test Project"
    "description" = "A test project"
    "code" = "PROJ001"
    "workspace_id" = 1
    "manager_id" = 6
    "customer_id" = 1
    "priority" = "medium"
    "start_date" = "2025-09-05"
    "status" = "active"
}

# Test GET endpoints
Test-Endpoint "GET COMPANIES" "GET" "http://127.0.0.1:8000/api/companies"
Test-Endpoint "GET BRANCHES" "GET" "http://127.0.0.1:8000/api/branches" 
Test-Endpoint "GET WORKSPACES" "GET" "http://127.0.0.1:8000/api/workspaces"
Test-Endpoint "GET CATEGORIES" "GET" "http://127.0.0.1:8000/api/categories"
Test-Endpoint "GET CUSTOMERS" "GET" "http://127.0.0.1:8000/api/customers?workspace_id=1"
Test-Endpoint "GET PRODUCTS" "GET" "http://127.0.0.1:8000/api/products?workspace_id=1"
Test-Endpoint "GET PROJECTS" "GET" "http://127.0.0.1:8000/api/projects?workspace_id=1"

Write-Host "🎉 API ROUTE TESTING COMPLETED!"
