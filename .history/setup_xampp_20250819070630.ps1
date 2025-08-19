# This script needs to be run as Administrator
# Right-click on PowerShell and select "Run as Administrator"
# Then navigate to this directory and run: .\setup_xampp.ps1

# Check if running as admin
$currentPrincipal = New-Object Security.Principal.WindowsPrincipal([Security.Principal.WindowsIdentity]::GetCurrent())
$isAdmin = $currentPrincipal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "Please run this script as Administrator!" -ForegroundColor Red
    exit 1
}

# Check if XAMPP is installed
$xamppPaths = @("C:\xampp", "C:\xampps")
$xamppPath = $null

foreach ($path in $xamppPaths) {
    if (Test-Path "$path\apache\conf\extra\httpd-vhosts.conf") {
        $xamppPath = $path
        break
    }
}

if (-not $xamppPath) {
    Write-Host "XAMPP not found in common locations. Please install XAMPP or specify its location." -ForegroundColor Red
    exit 1
}

Write-Host "XAMPP found at: $xamppPath" -ForegroundColor Green

# Remove any existing custosell folder in htdocs if it exists
if (Test-Path "$xamppPath\htdocs\custosell") {
    Write-Host "Removing existing custosell folder in htdocs..." -ForegroundColor Yellow
    Remove-Item "$xamppPath\htdocs\custosell" -Recurse -Force
}

# Create symbolic link
Write-Host "Creating symbolic link to your Laravel public folder..." -ForegroundColor Yellow
New-Item -ItemType SymbolicLink -Path "$xamppPath\htdocs\custosell" -Target "C:\Users\Josemiles\Desktop\custosell-backend\public"

# Create .htaccess file in htdocs
$htaccessContent = @"
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle requests to custosell subdirectory
    RewriteCond %{REQUEST_URI} ^/custosell
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^custosell/(.*)$ custosell/index.php [L]
</IfModule>
"@

Write-Host "Creating .htaccess file in htdocs..." -ForegroundColor Yellow
$htaccessContent | Out-File -FilePath "$xamppPath\htdocs\.htaccess" -Encoding ASCII -Force

# Update Postman collection
$postmanCollection = Get-Content -Raw -Path "C:\Users\Josemiles\Desktop\custosell-backend\custosell_api.postman_collection.json" | ConvertFrom-Json
$postmanCollection.item[0].item | ForEach-Object {
    $urlObject = $_.request.url
    if ($urlObject.host -contains "custosell" -and $urlObject.host -contains "test") {
        $urlObject.raw = $urlObject.raw -replace "custosell.test", "localhost/custosell"
        $urlObject.host = @("localhost")
        $urlObject.path = @("custosell") + $urlObject.path
    }
}

Write-Host "Updating Postman collection..." -ForegroundColor Yellow
$postmanCollection | ConvertTo-Json -Depth 10 | Out-File -FilePath "C:\Users\Josemiles\Desktop\custosell-backend\custosell_api_xampp.postman_collection.json" -Encoding UTF8

Write-Host @"

Setup complete! Your Laravel API should now be accessible through XAMPP.

API Endpoints:
- http://localhost/custosell/api/auth/login
- http://localhost/custosell/api/auth/register
- http://localhost/custosell/api/auth/logout

An updated Postman collection has been created at:
C:\Users\Josemiles\Desktop\custosell-backend\custosell_api_xampp.postman_collection.json

Please restart Apache in the XAMPP Control Panel for changes to take effect.
"@ -ForegroundColor Green
