# Test Workspace Creation
$headers = @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
    "Authorization" = "Bearer 4|osmuCsyLTq9bXcQJUXT3v4RWUtCpNNPNYoeCs26D2d1607ec"
}

$body = @{
    "name" = "Test Workspace"
    "description" = "A test workspace for API testing"
    "branch_id" = 1
} | ConvertTo-Json

Write-Host "=== TESTING WORKSPACE CREATION ==="
try {
    $response = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/workspaces" -Method POST -Headers $headers -Body $body
    Write-Host "Success: $($response.StatusCode)"
    $responseObj = $response.Content | ConvertFrom-Json
    Write-Host "Workspace ID: $($responseObj.data.id)"
} catch {
    Write-Host "Error: $($_.Exception.Response.StatusCode)"
    if ($_.Exception.Response) {
        $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
        $responseBody = $reader.ReadToEnd()
        Write-Host "Response: $responseBody"
    }
}
Write-Host ""
