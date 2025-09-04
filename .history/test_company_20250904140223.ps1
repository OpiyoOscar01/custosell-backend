$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
    "Authorization" = "Bearer 3|52wAd3tsbDUrFXA4XyvuF1zHiRRJzEfDIxU0reRh66cf720c"
}

$body = @{
    "name" = "Test Company Inc"
    "code" = "TESTCO"
    "email" = "info@testcompany.com"
    "phone" = "+1234567890"
    "address" = "123 Test Street"
    "city" = "Test City"
    "state" = "Test State"
    "country" = "Test Country"
    "website" = "https://testcompany.com"
    "industry" = "Technology"
    "is_active" = $true
} | ConvertTo-Json

try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/companies" -Method POST -Headers $headers -Body $body
    Write-Host "Success: $($response.StatusCode)"
    Write-Host $response.Content
} catch {
    Write-Host "Error: $($_.Exception.Response.StatusCode)"
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response: $responseBody"
    }
}
