$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
    "Authorization" = "Bearer 3|52wAd3tsbDUrFXA4XyvuF1zHiRRJzEfDIxU0reRh66cf720c"
}

try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/auth/me" -Method GET -Headers $headers
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
