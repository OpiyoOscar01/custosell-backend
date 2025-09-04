# Debug the failing endpoints
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

$baseUrl = "http://127.0.0.1:8000/api"

function Debug-Endpoint {
    param([string]$Name, [string]$Url)
    
    Write-Host "=== DEBUGGING $Name ===" -ForegroundColor Yellow
    try {
        $response = Invoke-WebRequest -Uri $Url -Headers $headers -ErrorAction Stop
        Write-Host "✅ SUCCESS: $($response.StatusCode)" -ForegroundColor Green
        $content = $response.Content | ConvertFrom-Json
        Write-Host "Response: $($content.message)" -ForegroundColor Cyan
    } catch {
        Write-Host "❌ ERROR: $($_.Exception.Response.StatusCode)" -ForegroundColor Red
        Write-Host "Error Details: $($_.Exception.Message)" -ForegroundColor Red
        if ($_.Exception.Response) {
            try {
                $errorResponse = $_.Exception.Response.GetResponseStream()
                $reader = New-Object System.IO.StreamReader($errorResponse)
                $errorBody = $reader.ReadToEnd()
                Write-Host "Error Body: $errorBody" -ForegroundColor Red
            } catch {
                Write-Host "Could not read error body" -ForegroundColor Red
            }
        }
    }
    Write-Host ""
}

Debug-Endpoint "Workspaces Active" "$baseUrl/workspaces/active"
Debug-Endpoint "Teams Active" "$baseUrl/teams/active"
