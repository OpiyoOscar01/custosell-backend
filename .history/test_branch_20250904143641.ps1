# Test Branch Creation
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
    "Authorization" = "Bearer 4|osmuCsyLTq9bXcQJUXT3v4RWUtCpNNPNYoeCs26D2d1607ec"
}

$body = @{
    "name" = "Test Branch"
    "company_id" = 1
    "code" = "TB001"
    "email" = "branch@testcompany.com"
    "phone" = "+1234567891"
    "address" = "456 Branch Street"
    "city" = "Branch City"
    "state" = "Branch State"
    "country" = "Test Country"
    "is_active" = $true
} | ConvertTo-Json

Write-Host "=== TESTING BRANCH CREATION ==="
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/branches" -Method POST -Headers $headers -Body $body
    Write-Host "Success: $($response.StatusCode)"
    $responseObj = $response.Content | ConvertFrom-Json
    Write-Host "Branch ID: $($responseObj.data.id)"
    Write-Host "Response: $($response.Content)"
} catch {
    Write-Host "Error: $($_.Exception.Response.StatusCode)"
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response: $responseBody"
    }
}
Write-Host ""
