# DEBUG PROFILE
$adminToken = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json" 
    "Authorization" = "Bearer $adminToken"
}

Write-Host "DEBUGGING PROFILE" -ForegroundColor Cyan
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/user/profile" -Method GET -Headers $headers
    Write-Host "SUCCESS: $($response.StatusCode)" -ForegroundColor Green
} catch {
    $statusCode = if ($_.Exception.Response) { $_.Exception.Response.StatusCode } else { "Unknown" }
    $errorResponse = if ($_.Exception.Response) {
        $stream = $_.Exception.Response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($stream)
        $reader.ReadToEnd()
    } else { 
        $_.Exception.Message 
    }
    
    Write-Host "ERROR: $statusCode" -ForegroundColor Red
    Write-Host "Error Response: $errorResponse" -ForegroundColor Yellow
}
