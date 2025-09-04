# COMPREHENSIVE API TESTING - PART 3 (Final Routes)
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

Write-Host "🚀 FINAL COMPREHENSIVE API TEST - PART 3" -ForegroundColor Cyan
Write-Host "Timestamp: $timestamp" -ForegroundColor Cyan
Write-Host ""

# ===============================
# 11. ORDER ROUTES (7)
# ===============================
Write-Host "🛒 ORDER ROUTES" -ForegroundColor Magenta

Test-Endpoint "ORDER - List" "GET" "http://127.0.0.1:8000/api/orders"
Test-Endpoint "ORDER - Active" "GET" "http://127.0.0.1:8000/api/orders/active"

Test-Endpoint "ORDER - Create" "POST" "http://127.0.0.1:8000/api/orders" @{
    "order_number" = "ORD$timestamp"
    "customer_id" = 1
    "branch_id" = 1
    "order_date" = "2025-01-18"
    "status" = "pending"
    "total_amount" = 100.00
    "tax_amount" = 10.00
    "discount_amount" = 5.00
    "notes" = "Test order"
    "items" = @(
        @{
            "product_id" = 1
            "quantity" = 2
            "unit_price" = 15.75
            "total_price" = 31.50
        }
    )
}

Test-Endpoint "ORDER - Show" "GET" "http://127.0.0.1:8000/api/orders/1"
Test-Endpoint "ORDER - Update" "PUT" "http://127.0.0.1:8000/api/orders/1" @{
    "status" = "confirmed"
}

Test-Endpoint "ORDER - By Status" "GET" "http://127.0.0.1:8000/api/orders/by-status/pending"
Test-Endpoint "ORDER - Statistics" "GET" "http://127.0.0.1:8000/api/orders/statistics"

# ===============================
# 12. INVOICE ROUTES (7)
# ===============================
Write-Host "💰 INVOICE ROUTES" -ForegroundColor Magenta

Test-Endpoint "INVOICE - List" "GET" "http://127.0.0.1:8000/api/invoices"
Test-Endpoint "INVOICE - Active" "GET" "http://127.0.0.1:8000/api/invoices/active"

Test-Endpoint "INVOICE - Create" "POST" "http://127.0.0.1:8000/api/invoices" @{
    "invoice_number" = "INV$timestamp"
    "customer_id" = 1
    "branch_id" = 1
    "invoice_date" = "2025-01-18"
    "due_date" = "2025-02-18"
    "status" = "pending"
    "subtotal" = 90.00
    "tax_amount" = 9.00
    "discount_amount" = 4.50
    "total_amount" = 94.50
    "notes" = "Test invoice"
}

Test-Endpoint "INVOICE - Show" "GET" "http://127.0.0.1:8000/api/invoices/1"
Test-Endpoint "INVOICE - Update" "PUT" "http://127.0.0.1:8000/api/invoices/1" @{
    "status" = "sent"
}

Test-Endpoint "INVOICE - By Status" "GET" "http://127.0.0.1:8000/api/invoices/by-status/pending"
Test-Endpoint "INVOICE - Statistics" "GET" "http://127.0.0.1:8000/api/invoices/statistics"

# ===============================
# 13. TEAM ROUTES (10)
# ===============================
Write-Host "👥 TEAM ROUTES" -ForegroundColor Magenta

Test-Endpoint "TEAM - List" "GET" "http://127.0.0.1:8000/api/teams"

Test-Endpoint "TEAM - Create" "POST" "http://127.0.0.1:8000/api/teams" @{
    "name" = "Test Team $timestamp"
    "description" = "Test team description"
    "is_active" = $true
}

Test-Endpoint "TEAM - Show" "GET" "http://127.0.0.1:8000/api/teams/1"
Test-Endpoint "TEAM - Update" "PUT" "http://127.0.0.1:8000/api/teams/1" @{
    "name" = "Updated Team Name"
}

Test-Endpoint "TEAM - Members" "GET" "http://127.0.0.1:8000/api/teams/1/members"

Test-Endpoint "TEAM - Add Member" "POST" "http://127.0.0.1:8000/api/teams/1/members" @{
    "user_id" = 1
    "role" = "member"
}

Test-Endpoint "TEAM - Update Member" "PUT" "http://127.0.0.1:8000/api/teams/1/members/1" @{
    "role" = "admin"
}

Test-Endpoint "TEAM - Remove Member" "DELETE" "http://127.0.0.1:8000/api/teams/1/members/1" $null $true

Test-Endpoint "TEAM - Leave Team" "POST" "http://127.0.0.1:8000/api/teams/1/leave" @{}

# ===============================
# 14. WORKSPACE ROUTES (9)
# ===============================
Write-Host "🏢 WORKSPACE ROUTES" -ForegroundColor Magenta

Test-Endpoint "WORKSPACE - List" "GET" "http://127.0.0.1:8000/api/workspaces"

Test-Endpoint "WORKSPACE - Create" "POST" "http://127.0.0.1:8000/api/workspaces" @{
    "name" = "Test Workspace $timestamp"
    "description" = "Test workspace description"
    "is_active" = $true
}

Test-Endpoint "WORKSPACE - Show" "GET" "http://127.0.0.1:8000/api/workspaces/1"
Test-Endpoint "WORKSPACE - Update" "PUT" "http://127.0.0.1:8000/api/workspaces/1" @{
    "name" = "Updated Workspace Name"
}

Test-Endpoint "WORKSPACE - Members" "GET" "http://127.0.0.1:8000/api/workspaces/1/members"

Test-Endpoint "WORKSPACE - Add Member" "POST" "http://127.0.0.1:8000/api/workspaces/1/members" @{
    "user_id" = 1
    "role" = "member"
}

Test-Endpoint "WORKSPACE - Update Member" "PUT" "http://127.0.0.1:8000/api/workspaces/1/members/1" @{
    "role" = "admin"
}

Test-Endpoint "WORKSPACE - Remove Member" "DELETE" "http://127.0.0.1:8000/api/workspaces/1/members/1" $null $true

Test-Endpoint "WORKSPACE - Leave" "POST" "http://127.0.0.1:8000/api/workspaces/1/leave" @{}

# ===============================
# 15. ADDITIONAL ROUTES
# ===============================
Write-Host "🔍 ADDITIONAL ROUTES" -ForegroundColor Magenta

# Company Specialized Routes
Test-Endpoint "COMPANY - Delete" "DELETE" "http://127.0.0.1:8000/api/companies/2" $null $true

# Branch Specialized Routes  
Test-Endpoint "BRANCH - Delete" "DELETE" "http://127.0.0.1:8000/api/branches/2" $null $true

# Brand Specialized Routes
Test-Endpoint "BRAND - Delete" "DELETE" "http://127.0.0.1:8000/api/brands/2" $null $true

# Category Specialized Routes
Test-Endpoint "CATEGORY - Delete" "DELETE" "http://127.0.0.1:8000/api/categories/2" $null $true

# Customer Specialized Routes
Test-Endpoint "CUSTOMER - Delete" "DELETE" "http://127.0.0.1:8000/api/customers/2" $null $true

# Unit Specialized Routes
Test-Endpoint "UNIT - Delete" "DELETE" "http://127.0.0.1:8000/api/units/2" $null $true

# Product Specialized Routes
Test-Endpoint "PRODUCT - Delete" "DELETE" "http://127.0.0.1:8000/api/products/2" $null $true

# Project Specialized Routes
Test-Endpoint "PROJECT - Delete" "DELETE" "http://127.0.0.1:8000/api/projects/2" $null $true

# Task Specialized Routes
Test-Endpoint "TASK - Delete" "DELETE" "http://127.0.0.1:8000/api/tasks/2" $null $true

# Order Specialized Routes
Test-Endpoint "ORDER - Delete" "DELETE" "http://127.0.0.1:8000/api/orders/2" $null $true

# Invoice Specialized Routes
Test-Endpoint "INVOICE - Delete" "DELETE" "http://127.0.0.1:8000/api/invoices/2" $null $true

# Team Specialized Routes
Test-Endpoint "TEAM - Delete" "DELETE" "http://127.0.0.1:8000/api/teams/2" $null $true

# Workspace Specialized Routes
Test-Endpoint "WORKSPACE - Delete" "DELETE" "http://127.0.0.1:8000/api/workspaces/2" $null $true

# Auth Specialized Routes
Test-Endpoint "AUTH - Change Password" "POST" "http://127.0.0.1:8000/api/auth/change-password" @{
    "current_password" = "password123"
    "password" = "newpassword123"
    "password_confirmation" = "newpassword123"
}

Test-Endpoint "AUTH - Logout" "POST" "http://127.0.0.1:8000/api/auth/logout" @{}

Write-Host "🎉 ALL 109 ROUTES TESTED!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "COMPREHENSIVE TEST COMPLETE" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
