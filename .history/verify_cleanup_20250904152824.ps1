# Quick verification that API is still working after cleanup
$token = "12|fePLpL7ZQyH2Nqsg0VeBcxm1rHstEiYibhbmwuJ368977f4c"
$headers = @{ "Authorization" = "Bearer $token"; "Accept" = "application/json" }

Write-Host "Testing core endpoints..." -ForegroundColor Yellow

try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/products" -Headers $headers
    Write-Host "✅ Products: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Products failed" -ForegroundColor Red
}

try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/user/profile" -Headers $headers  
    Write-Host "✅ Profile: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Profile failed" -ForegroundColor Red
}

try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/projects/statistics" -Headers $headers
    Write-Host "✅ Statistics: $($response.StatusCode)" -ForegroundColor Green
} catch {
    Write-Host "❌ Statistics failed" -ForegroundColor Red
}

Write-Host "Verification complete!" -ForegroundColor Cyan
