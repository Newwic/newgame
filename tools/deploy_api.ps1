# deploy_api.ps1
# Copies the project's api/ files to C:\xampp\htdocs\api and restarts Apache (service name may vary).
# Run PowerShell as Administrator.

param(
    [string]$Source = "D:\67319090012\newgame-main\api",
    [string]$Dest = "C:\xampp\htdocs\api",
    [string]$ApacheServiceName = 'Apache2.4'
)

Write-Host "Deploying API from $Source to $Dest"
if (-not (Test-Path $Source)) {
    Write-Error "Source path not found: $Source"
    exit 1
}

# Ensure destination exists
if (-not (Test-Path $Dest)) {
    Write-Host "Destination doesn't exist, creating: $Dest"
    New-Item -ItemType Directory -Path $Dest -Force | Out-Null
}

# Stop Apache service if exists
$svc = Get-Service -Name $ApacheServiceName -ErrorAction SilentlyContinue
if ($svc) {
    if ($svc.Status -eq 'Running') {
        Write-Host "Stopping Apache service '$ApacheServiceName'..."
        Stop-Service -Name $ApacheServiceName -Force -ErrorAction SilentlyContinue
    Start-Sleep -Seconds 1
    }
} else {
    Write-Host "Warning: Apache service '$ApacheServiceName' not found. You may need to restart Apache via XAMPP control panel."
}

Write-Host "Copying files..."
Copy-Item -Path (Join-Path $Source '*') -Destination $Dest -Recurse -Force

# Start Apache
if ($svc) {
    Write-Host "Starting Apache service '$ApacheServiceName'..."
    Start-Service -Name $ApacheServiceName -ErrorAction SilentlyContinue
}

Write-Host "Done. You can test endpoints like http://localhost/api/get_reviews.php"
